<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class users extends CI_Model {

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$this->load->view('welcome_message');
	}

	public function register($post){
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
		$this->form_validation->set_rules('firstname', 'First Name', 'trim|required');
		$this->form_validation->set_rules('lastname', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
	    	$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[3]|matches[confirmpassword]');
		$this->form_validation->set_rules('confirmpassword', 'Password Confirmation', 'trim|required|min_length[3]');
	    	if($this->form_validation->run()){
			$query = "INSERT INTO users (first,last,email,password,status,created_at,updated_at) VALUES (?,?,?,?,?,NOW(),NOW())";
			$values = array($post['firstname'], $post['lastname'], $post['email'],md5($post['password']),"Active");
	      // if query runs correctly
	      	if($this->db->query($query, $values)){
	        	$id = $this->db->insert_id();
			$success = array('valid', $id);
	        	return $success;
	      } else {
	        return false;
	      }
	    } else {
	      return array(validation_errors());
	    }
  	}

	public function login($post){
		$check_user = "SELECT * FROM users WHERE users.email = ?";
		$user = $this->db->query($check_user, array($post['email']))->row_array();
		if($user){
			if(md5($post['password']) == $user['password']){
				return $user;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public function get_user_by_id($id){
		return $this->db->query("SELECT id,first,last,email,orgname,password FROM users WHERE users.id=?",$id)->row_array();
	}

	public function check_email_exists($email){
		return $this->db->query("SELECT * from users WHERE email=?",$email)->row_array();
	}
	public function get_newuser_id(){
		return $this->db->query("SELECT MAX(id) FROM users")->row_array();
	}

	public function updateprofile($id,$newprofile){
		$query = "UPDATE users SET first = ?,last =?,email =?,orgname=? WHERE users.id=?";
		$values = array($newprofile['first'],$newprofile['last'],$newprofile['email'],$newprofile['orgname'],$id);
		return $this->db->query($query,$values);
	}

	public function create_new_participant($first,$last,$email){
		$query = "INSERT INTO users (first,last,email,status,created_at,updated_at) VALUES (?,?,?,?,NOW(),NOW())";
		$values = array($first,$last,$email,'Pending');
		return $this->db->query($query,$values);
	}

	public function update_meeting_users($exists,$id){
		$query = "INSERT INTO users_has_meetings(users_id,meetings_id) VALUES(?,?)";
		$values= array($exists,$id);
		return $this->db->query($query,$values);
	}

	public function sendemails($id){
		return $this->db->query("SELECT email FROM users_has_meetings LEFT JOIN users on users.id=users_has_meetings.users_id
					Where users_has_meetings.meetings_id=?",$id)->result_array();
	}

}
