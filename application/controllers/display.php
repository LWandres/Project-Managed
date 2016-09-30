<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class display extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('users');
		$this->load->model('displays');
		$this->load->model('meetings');
	}

//Main function to populate all dashboard data. Future - refactor with caching data
	public function loaddashboard(){
		$is_logged = $this->session->userdata['id'];//checks if use is logged in
		$first = $this->session->userdata('first');
		$owned = $this->displays->showactiveowned($is_logged); //get all active meetings user owns -*  refactor with get all meetings/deal with differences in logic
		$attend = $this->displays->showactiveattend($is_logged);//gets all active meetings user is a participant in but does not own -* refactor with get all meetings/deal with differences in logic
		$archived = $this->displays->showarchived($is_logged);//gets all archived meetings user owns * refactor with get all meetings/ deal with differences in logic
		$userfollowups = $this->displays->showfollowups($is_logged);//gets all incomplete follow ups user owns- * for potential refactor with get all followups/deal with differences in logic
		$completefollows = $this->displays->showcompleted($is_logged);//gets all completed follow ups user owns- * for potential refactor with get all followups/deal with differences in logic
		$recurring = $this->meetings->showrecurring($is_logged); //gets all active meetings user owns * for potential refactor with get all meetings/deal with differences in logic
		$data = array(
			'activeowned'=> $owned,
			'activeattend'=>$attend,
			'archived'=>$archived,
			'userfollowups'=>$userfollowups,
			'completefollows'=>	$completefollows,
			'userinfo'=>$first,
			'recurring'=>$recurring,
			'userid'=>$is_logged);
			$this->load->view('dashboard', $data);
	}
}
