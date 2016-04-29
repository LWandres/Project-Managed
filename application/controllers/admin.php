<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class admin extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('users');
		$this->load->model('displays');
		$this->load->model('meetings');
	}
	public function edit($id){
		$agenda= $this->meetings->get_agenda($id);
		$attendees= $this->meetings->get_participants($id);
		$data=array(
			'agenda'=>$agenda,
			'attendees'=>$attendees);
		$this->load->view('meeting',$data);
	}

	public function meetinginfo($id){
		$agenda= $this->meetings->get_agenda($id);
		$attendees= $this->meetings->get_participants($id);
		$data=array(
			'agenda'=>$agenda,
			'attendees'=>$attendees);
		$this->load->view('agenda',$data);
	}

	public function archive($id){
		$this->meetings->archivemeeting($id);
		redirect('/display/loaddashboard');
	}

	public function active($id){
		$this->meetings->activemeeting($id);
		redirect('/display/loaddashboard');
	}

	public function viewnotes($id){
		$notes= $this->meetings->get_agenda($id);
		$attendees= $this->meetings->get_participants($id);
		$followups= $this->meetings->get_followups($id);
		if(empty($followups)){
			$followups1= "This meeting does not have any followups";
		}
		$data=array(
			'agenda'=>$notes,
			'attendees'=>$attendees,
			'followups1'=> $followups1,
			'followups'=>$followups);
		$this->load->view('meetingnotes',$data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
