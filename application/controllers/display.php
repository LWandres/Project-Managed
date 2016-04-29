<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class display extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('users');
		$this->load->model('displays');
		$this->load->model('meetings');
	}

	public function loaddashboard(){
		$is_logged= $this->session->userdata['id'];
		$first= $this->session->userdata('first');
		$owned= $this->displays->showactiveowned($is_logged);
		$attend= $this->displays->showactiveattend($is_logged);
		$archived= $this->displays->showarchived($is_logged);
		$userfollowups= $this->displays->showfollowups($is_logged);
		$completefollows= $this->displays->showcompleted($is_logged);
		$data=array(
			'activeowned'=> $owned,
			'activeattend'=>$attend,
			'archived'=>$archived,
			'userfollowups'=>$userfollowups,
			'completefollows'=>	$completefollows,
			'userinfo'=>$first,
			'userid'=>$is_logged);
			$this->load->view('dashboard', $data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
