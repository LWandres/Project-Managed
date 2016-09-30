<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class admin extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('users');
		$this->load->model('displays');
		$this->load->model('meetings');
	}

	//edit link on dashboard
	public function edit($id){
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

	//get single meeting information
	public function meetinginfo($id){
		$agenda = $this->meetings->get_agenda($id);
		$attendees = $this->meetings->get_participants($id);
		$followups = $this->meetings->get_followups($id);
		$followups1 = "This meeting does not have any followups";
		$data=array(
			'agenda'=>$agenda,
			'attendees'=>$attendees,
			'followups1'=> $followups1,
			'followups'=>$followups
		);
		$this->load->view('agenda',$data);
	}

	// Archive meeting with id from dashboard
	public function archive($id){
		$this->meetings->archivemeeting($id);
		redirect('/display/loaddashboard');
	}

	//Get all meetings that a user is active for.
	public function active($id){
		$this->meetings->activemeeting($id);
		redirect('/display/loaddashboard');
	}

	//View notes hyperlink on dashboard
	public function viewnotes($id){
		$notes= $this->meetings->get_agenda($id);
		$attendees= $this->meetings->get_participants($id);
		$followups= $this->meetings->get_followups($id);
		$followups1= "This meeting does not have any followups";
		$data=array(
			'agenda'=>$notes,
			'attendees'=>$attendees,
			'followups1'=> $followups1,
			'followups'=>$followups);
		$this->load->view('meetingnotes',$data);
	}

	//notes for emails
	public function viewnotesemail($id){
		$notes = $this->meetings->get_agenda($id);
		$attendees = $this->meetings->get_participants($id);
		$followups = $this->meetings->get_followups($id);
		$followups1 = "This meeting does not have any followups";
		$data = array(
			'agenda'=>$notes,
			'attendees'=>$attendees,
			'followups1'=> $followups1,
			'followups'=>$followups);
		$this->load->view('meetingnotesportal',$data);
	}
}//closes controller
