<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class main extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('users');
		$this->load->model('displays');
		$this->load->model('meetings');
	}

//Meeting functions
	public function viewagenda(){
		$this->load->view('agenda');
	}

//updates follow ups
	public function updatefollows($id){
		$followups = $this->input->post();
		$owner = $followups['owner'];
		$followup = $followups['follow'];
		$dues = $followups['due'];
		$statuses = $followups['status'];
		$this->meetings->insertfollowups($owner,$followup,$dues,$statuses,$id);
		//reload the agenda with updated information
		$agenda = $this->meetings->get_agenda($id);
  		$attendees = $this->meetings->get_participants($id);
 		$followups = $this->meetings->get_followups($id);
 		$followups1 = "This meeting does not have any followups";
  		$data=array(
  			'agenda'=>$agenda,
 			'attendees'=>$attendees,
 			'attendees'=>$attendees,
 			'followups1'=> $followups1,
 			'followups'=>$followups
 		);
		$this->load->view('meeting',$data);
	}

//completes follow ups
	public function completefollow($id){
		$this->meetings->completefollow($id);
		redirect('/display/loaddashboard');
	}

//updates follow up status to incomplete
	public function incompletefollow($id){
		$this->meetings->incompletefollow($id);
		redirect('/display/loaddashboard');
	}

//updates meeting attendance
	public function attendance($id){
		$attendance= $this->input->post();

		for($i = 0;$i < count($attendance['attendee'][$i]);$i++){
			$this->meetings->meetingroll($attendance['attendee'][$i],$id);
		}

		$agenda = $this->meetings->get_agenda($id);
		$attendees = $this->meetings->get_participants($id);
		$data = array(
			'agenda'=>$agenda,
			'attendees'=>$attendees);
		$this->load->view('agenda',$data);
	}

//creates a new meeting with recurring logic built in- REFACTOR in future- this is too long and can be split out into functions for each edgecase
	public function new_meeting(){
		$meetinginfo=$this->input->post();
			if($meetinginfo['Recur']== 'No'){
				$this->meetings->new_meeting($meetinginfo);
			}
			else if($meetinginfo['Recur']== 'Yes'){
				if($meetinginfo['Recur2']== 'New'){
					$this->meetings->new_meeting_recur($meetinginfo);
				}
				if($meetinginfo['Recur2']!= 'New'){
					$this->meetings->new_meeting_recurexist($meetinginfo);
				}
			}
			$ids = $this->meetings->get_meetid();
			$id = $ids['MAX(id)'];

			//prepare user information for import to the phpmailer & agendas
			//this if check formats users/emails for the copy/paste scenario from gmail
			if (!empty($meetinginfo['participants'])){
				$splituser = explode(",", $meetinginfo['participants']);
				foreach($splituser as $user){
					$splitemail = explode(" <",$user);
					$fullname = trim($splitemail[0]," ");
					$fullsplit = explode(" ",$fullname);
					$first = $fullsplit[0];
					$last = $fullsplit[1];

					//if there's only a single user that was submitted, handle the user differently
					if (count($splitemail) > 1){
						$email1 = trim($splitemail[1],",");
						$email = trim($email1,">");
					}else{
						$email1 = trim($last," ");
						$email2 = explode("<",$email1);
						$email = trim($email2[1],">");
					}
					$exists = $this->users->check_email_exists($email);
					//if user doesn't exist yet in DB, create user
					if(empty($exists)){
						$this->users->create_new_participant($first,$last,$email);
						$newuser = $this->users->get_newuser_id();
						$this->users->update_meeting_users($newuser['MAX(id)'],$id);;
					}
					//otherwise update meeting users
					else if(!empty($exists)){
						$this->users->update_meeting_users($exists['id'],$id);
					}
				}
			} else{ //handles the non-gmail case with field input
				if(isset($meetinginfo['first'])){
					foreach ($meetinginfo['first'] as $key => $user){
						$email = $meetinginfo['email'][$key];
						$exists = $this->users->check_email_exists($email);
						if(empty($exists)){
							$this->users->create_new_participant($meetinginfo['first'][$key],$meetinginfo['last'][$key],$email);
							$newuser = $this->users->get_newuser_id();
							$this->users->update_meeting_users($newuser['MAX(id)'],$id);;
						}
						else if(!empty($exists)){
							$this->users->update_meeting_users($exists['id'],$id);
						}
					}
				}
			}
			//prepare view for new meeting agenda
			$agenda = $this->meetings->get_agenda($id);
			$attendees = $this->meetings->get_participants($id);

			$data=array(
				'agenda'=>$agenda,
				'attendees'=> $attendees);
			$this->load->view('agenda',$data);
		}//end new meeting creation function

//gets meeting info for the recurring tab
	public function recurring($id){
		$recurid = $this->meetings->getrecurid($id);
		$allrecurs = $this->meetings->allrecur($recurid);
		$is_logged = $this->session->userdata['id'];
		$first = $this->session->userdata('first');
		$owned = $this->displays->showactiveowned($is_logged); //refactor with single get users_has_meetings query.
		$attend = $this->displays->showactiveattend($is_logged);//refactor with single get users_has_meetings query.
		$archived = $this->displays->showarchived($is_logged);//refactor with single get users_has_meetings query.
		$userfollowups = $this->displays->showfollowups($is_logged); //refactor into one query for all followups handle complete/incomplete in logic
		$completefollows = $this->displays->showcompleted($is_logged);//refactor into one query for all followups handle complete/incomplete in logic
		$data = array(
			'recurrings'=>$allrecurs,
			'activeowned'=> $owned,
			'activeattend'=>$attend,
			'archived'=>$archived,
			'userfollowups'=>$userfollowups,
			'completefollows'=>	$completefollows,
			'userinfo'=>$first,
			'userid'=>$is_logged);
		$this->load->view('recurring',$data);
	}

//updates full meeting notes
	public function updatenotes($id){
		$notes=$this->input->post();
		if (isset ($notes['attendee'])){
			$attendees=$notes['attendee'];
			$this->meetings->update_present($id,$attendees);
			$this->meetings->updatenotes($id,$notes);
		}else{
			$this->meetings->updatenotes($id,$notes);
		}
		//reload the agenda with updated information
		$agenda = $this->meetings->get_agenda($id);
  		$attendees = $this->meetings->get_participants($id);
 		$followups = $this->meetings->get_followups($id);
 		$followups1 = "This meeting does not have any followups";
  		$data = array(
  			'agenda'=>$agenda,
 			'attendees'=>$attendees,
 			'attendees'=>$attendees,
 			'followups1'=> $followups1,
 			'followups'=>$followups
 		);
		$this->load->view('meeting',$data);
	}

}
