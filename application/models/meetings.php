<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class meetings extends CI_Model {

	public function index(){
		$this->load->view('welcome_message');
	}

	//create a new meeting from dashboard
	public function new_meeting($meetinginfo){
		//inserts a new meeting
		$query = "INSERT INTO meetings(name,projectname,date,start,end,objective,goals,agenda,status,recurringseries_id,recur,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,NOW(),NOW())";
		$values = array($meetinginfo['meeting'],$meetinginfo['project'],$meetinginfo['meetingdate'],$meetinginfo['start'],$meetinginfo['end'],$meetinginfo['objectives'],$meetinginfo['goals'],$meetinginfo['agenda'],'Active','0','No');
		$this->db->query($query,$values);
		//gets meeting id and assigns user as the owner of the meeting
		$id = $this->db->query("SELECT MAX(id) FROM meetings")->row_array();
		$query ="INSERT INTO users_has_meetings(users_id,meetings_id,owner) VALUES (?,?,?)";
		$values = array($meetinginfo['owner'],$id['MAX(id)'],$meetinginfo['owner']);
		return $this->db->query($query,$values);
	}

	//get most recently created meeting
	public function get_meetid(){
		return $this->db->query("SELECT MAX(id) FROM meetings")->row_array();
	}

	//get agenda for specific meeting
	public function get_agenda($id){
		return $this->db->query("SELECT * from meetings WHERE id=?",$id)->row_array();
	}

	//create a recurring record for a meeting
	public function new_meeting_recur($meetinginfo){
		//creates a new recurring series id
		$query = "INSERT INTO recurringseries(recurname,created_at,updated_at) VALUES(?,NOW(),NOW())";
		$values = array($meetinginfo['project']);
		$this->db->query($query,$values);
		//gets the recurringseries id
		$id = $this->db->query("SELECT MAX(id) FROM recurringseries")->row_array();
		$query ="INSERT INTO meetings(recurringseries_id,recur,name,projectname,date,start,end,objective,goals,agenda,status,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,NOW(),NOW())";
		$values = array($id['MAX(id)'],$meetinginfo['Recur'],$meetinginfo['meeting'],$meetinginfo['project'],$meetinginfo['meetingdate'],$meetinginfo['start'],$meetinginfo['end'],$meetinginfo['objectives'],$meetinginfo['goals'],$meetinginfo['agenda'],'Active');
		$this->db->query($query,$values);
		// inserts user ownership for the meeting
		$id = $this->db->query("SELECT MAX(id) FROM meetings")->row_array();
		$query = "INSERT INTO users_has_meetings (users_id,meetings_id,owner) VALUES (?,?,?)";
		$values = array($meetinginfo['owner'],$id['MAX(id)'],$meetinginfo['owner']);
		return $this->db->query($query,$values);
	}

	//create a new meeting, and attach it to an existing recurring series
	public function new_meeting_recurexist($meetinginfo){
		$query = "SELECT id FROM recurringseries WHERE recurname= ?";
		$values = array($meetinginfo['Recur2']);
		$arrayid = $this->db->query($query,$values)->row_array();
 		$id = $arrayid['id'];
		//create a new meeting
		$query = "INSERT INTO meetings(recurringseries_id,recur,name,projectname,date,start,end,objective,goals,agenda,status,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,NOW(),NOW())";
		$values = array($id,$meetinginfo['Recur'],$meetinginfo['meeting'],$meetinginfo['project'],$meetinginfo['meetingdate'],$meetinginfo['start'],$meetinginfo['end'],$meetinginfo['objectives'],$meetinginfo['goals'],$meetinginfo['agenda'],'Active');
		$this->db->query($query,$values);
		// inserts user ownership for the meeting
		$id = $this->db->query("SELECT MAX(id) FROM meetings")->row_array();
		$query = "INSERT INTO users_has_meetings (users_id,meetings_id,owner) VALUES (?,?,?)";
		$values = array($meetinginfo['owner'],$id['MAX(id)'],$meetinginfo['owner']);
		return $this->db->query($query,$values);
	}

	//get all participants for a meeting
	public function get_participants($id){
		return $this->db->query("SELECT * from users_has_meetings LEFT JOIN users on users.id=users_has_meetings.users_id
								WHERE meetings_id=?",$id)->result_array();
	}

	//function to update attending particpants in agenda
	public function meetingroll($value,$id){
		$query = "SELECT * from users_has_meetings WHERE users_id=? AND meetings_id=?";
		$values = array($value,$id);
		$rollinfo = $this->db->query($query,$values)->result_array();

		foreach($rollinfo as $roll){
			$query="UPDATE users_has_meetings SET present ='Yes'
			WHERE has_meetings_id=? AND users_id=?";
			$values=array($roll['has_meetings_id'],$roll['users_id']);
			$this->db->query($query,$values);
		}
		return;
	}

	//archive meeting from dashboard
	public function archivemeeting($id){
		return $this->db->query("UPDATE meetings SET status='Archived' WHERE meetings.id=?",$id);
	}

	//reactivate meeting from dashboard
	public function activemeeting($id){
		return $this->db->query("UPDATE meetings SET status='Active' WHERE meetings.id=?",$id);
	}

	//save/update meeting notes
	public function updatenotes($id,$notes){
		$query= ("UPDATE meetings SET name= ?, date=?, start=?, end=?,objective=?, goals=?, agenda=?,notes=? WHERE meetings.id=?");
		$values= array($notes['meeting'],$notes['meetingdate'],$notes['start'],$notes['end'],$notes['objectives'],$notes['goals'],$notes['agenda'],$notes['agenda'],$id);
		return $this->db->query($query,$values);
	}

	//update present attendees
	public function update_present($id,$attendees){
		if(!empty($attendees)){
			for($i=0;$i<count($attendees);$i++){
				$query = "SELECT * from users_has_meetings WHERE meetings_id=? AND users_id=?";
				$values = array($id,$attendees[$i]);
				$tableid = $this->db->query($query,$values)->row_array();
				$query = "UPDATE users_has_meetings SET users_has_meetings.present='Yes' WHERE has_meetings_id=?";
				$values =  array('Yes',$tableid['has_meetings_id']);
				$this->db->query($query,$values);
			}
		}
	}

//followups
	public function insertfollowups($owners,$followup,$dues,$statuses,$id){
		for($i=0;$i<count($owners);$i++){
			$query="INSERT INTO followups (owner,followup,duedate,status,meetings_id,created_at,updated_at) VALUES (?,?,?,?,?,NOW(),NOW())";
			$values=array($owners[$i],$followup[$i],$dues[$i],$statuses[$i],$id);
			$this->db->query($query,$values);
		}
	}

	//get follow ups for display
	public function get_followups($id){
		return $this->db->query("SELECT users.first, users.last,followups.owner,followups.followup,followups.duedate,followups.status AS followstatus, followups.meetings_id from followups LEFT JOIN users on owner = users.id WHERE meetings_id = $id")->result_array();
	}
	//get follow ups for particular meeting for emails
	public function get_emailfollowups($id){
		return $this->db->query("SELECT followups.id,owner,followup,duedate, meetings_id,followups.status AS followstatus from followups
								LEFT JOIN users on followups.owner = users.id
								WHERE followups.meetings_id=?",$id)->result_array();
	}
	//complete a follow up in DB
	public function completefollow($id){
		return $this->db->query("UPDATE followups SET status='Yes' WHERE followups.id=?",$id);
	}
	//mark a follow up as incomplete in DB
	public function incompletefollow($id){
		return $this->db->query("UPDATE followups SET status='No' WHERE followups.id=?",$id);
	}

//recurring functionality
	//get recurring meeting id
	public function getrecurid($id){
		return $this->db->query("Select recurringseries_id FROM meetings WHERE meetings.id=?",$id)->row_array();
	}
	//get all information for that recurring meeting
	public function allrecur($recurid){
		return $this->db->query("Select * FROM meetings WHERE recurringseries_id=?",$recurid)->result_array();
	}
	//show recurring meetings for a particular user
	public function showrecurring($id){
		return $this->db->query("Select * FROM meetings LEFT JOIN users_has_meetings on meetings_id = meetings.id
								WHERE users_has_meetings.users_id = ? AND recur = 'Yes'
								GROUP by meetings.id",$id)->result_array();
	}

}
