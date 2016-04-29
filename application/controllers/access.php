<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class access extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('users');
		$this->load->model('displays');
		$this->load->model('meetings');
	}

	public function index(){
		$this->load->view('welcome');
	}

	public function regpage(){
		$this->load->view('login');
	}

	public function login(){
		$is_logged = $this->users->login($this->input->post());
    if($is_logged){
			$newdata = array(
								 'id'=> $is_logged['id'],
								 'first'=> $is_logged['first'],
								 'last'=> $is_logged['last'],
								 'email'=> $is_logged['email']);
			$sessioninfo= $this->session->set_userdata($newdata);
      redirect('/display/loaddashboard');
    } else {
      $this->session->set_flashdata('log_errors', "<p class='errors'>Invalid login credentials</p>");
      redirect('/access/regpage');
    }
	}
	public function register(){
		$is_valid = $this->users->register($this->input->post());
		if($is_valid[0] == 'valid'){
			$user= $this->users->get_user_by_id($is_valid[1]);
			$this->session->set_userdata('id', $user['id']);
			$this->session->set_userdata('first', $user['first']);
			$this->session->set_userdata('last', $user['last']);
			$this->session->set_userdata('email', $user['email']);

			redirect('/display/loaddashboard');
		} else {
			// set session error messages
			$this->session->set_flashdata('reg_errors', $is_valid);
			// redirect to index
			redirect('/access/regpage');
			// show session error messages
		}
	}

	public function profile(){
		$this->load->view('profile');
	}

	public function updateprofile($id){

		$this->users->updateprofile($id);
		redirect('/display/loaddashboard');
	}

	public function dashboard(){
		$this->load->view('dashboard');
	}

	public function logout(){
		$this->session->unset_userdata('id');
		$this->session->unset_userdata('first');
		$this->session->unset_userdata('last');
		$this->session->unset_userdata('email');
		redirect('/');
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
