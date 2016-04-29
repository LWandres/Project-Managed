<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class meetings extends CI_Model {

	public function index(){
		$this->load->view('welcome_message');
	}

	public function new_meeting($meetinginfo){
		//inserts a new meeting
		$query="INSERT INTO meetings(name,projectname,date,start,end,objective,goals,agenda,status,recurringseries_id,template,recur,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,NOW(),NOW())";
		$values= array($meetinginfo['meeting'],$meetinginfo['project'],$meetinginfo['meetingdate'],$meetinginfo['start'],$meetinginfo['end'],$meetinginfo['objectives'],$meetinginfo['goals'],$meetinginfo['agenda'],'Active','0',$meetinginfo['template'],'No');
		$this->db->query($query,$values);
		//gets meeting id and assigns user as the owner of the meeting
		$id= $this->db->query("SELECT MAX(id) FROM meetings")->row_array();
		$query="INSERT INTO users_has_meetings(users_id,meetings_id,owner) VALUES (?,?,?)";
		$values=array($meetinginfo['owner'],$id['MAX(id)'],$meetinginfo['owner']);
		return $this->db->query($query,$values);
	}

	public function get_meetid(){
		return $this->db->query("SELECT MAX(id) FROM meetings")->row_array();
	}

	public function get_agenda($id){
		return $this->db->query("SELECT * from meetings WHERE id=?",$id)->row_array();
	}

	public function new_meeting_recur($meetinginfo){
		//creates a new recurring series id.
		$query="INSERT INTO recurringseries(recurname,created_at,updated_at) VALUES(?,NOW(),NOW())";
		$values=array($meetinginfo['project']);
		$this->db->query($query,$values);
		//gets the recurringseries id
		$id= $this->db->query("SELECT MAX(id) FROM recurringseries")->row_array();
		$query="INSERT INTO meetings(recurringseries_id,recur,name,projectname,date,start,end,objective,goals,participants,agenda,status,template,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,NOW(),NOW())";
		$values= array($id['MAX(id)'],$meetinginfo['Recur'],$meetinginfo['meeting'],$meetinginfo['project'],$meetinginfo['meetingdate'],$meetinginfo['start'],$meetinginfo['end'],$meetinginfo['objectives'],$meetinginfo['goals'],$meetinginfo['participants'],$meetinginfo['agenda'],'Active',$meetinginfo['template']);
		//inserts user ownership for the meeting
		$id= $this->db->query("SELECT MAX(id) FROM meetings")->row_array();
		$query="INSERT INTO users_has_meetings (users_id,meetings_id) VALUES (?,?)";
		$values=array($meetinginfo['owner'],$id['MAX(id)']);
		return $this->db->query($query,$values);
	}

	public function new_meeting_recurexist($meetinginfo){
		$query="SELECT id FROM recurringseries WHERE recurname= ?";
		$values=array($meetinginfo['Recur2']);
		$id= $this->db->query($query,$values)->row_array();
		$query="INSERT INTO meetings(recurringseries_id,recur,name,projectname,date,start,end,objective,goals,participants,agenda,status,template,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,? NOW(),NOW())";
		$values= array($id,$meetinginfo['Recur'],$meetinginfo['meeting'],$meetinginfo['project'],$meetinginfo['meetingdate'],$meetinginfo['start'],$meetinginfo['end'],$meetinginfo['objectives'],$meetinginfo['goals'],$meetinginfo['participants'],$meetinginfo['agenda'],'Active','0',$meetinginfo['template']);
		return $this->db->query($query,$values);
		//inserts user ownership for the meeting
		$id= $this->db->query("SELECT MAX(id) FROM meetings")->row_array();
		$query="INSERT INTO users_has_meetings (users_id,meetings_id) VALUES (?,?)";
		$values=array($meetinginfo['owner'],$id['MAX(id)']);
		return $this->db->query($query,$values);
	}

	public function insertfollowups($owners,$followup,$dues,$statuses,$id){
		for($i=0;$i<count($owners);$i++){
			$query="INSERT INTO followups (owner,followup,duedate,status,meetings_id,created_at,updated_at) VALUES (?,?,?,?,?,NOW(),NOW())";
			$values=array($owners[$i],$followup[$i],$dues[$i],$statuses[$i],$id);
			$this->db->query($query,$values);
		}
	}

	public function get_participants($id){
		return $this->db->query("SELECT * from users_has_meetings
														 LEFT JOIN users on users.id=users_has_meetings.users_id
														 WHERE meetings_id=?",$id)->result_array();
	}

	public function meetingroll($value,$id){
			$query="SELECT * from users_has_meetings
						  WHERE users_id=? AND meetings_id=?";
			$values=array($value,$id);
			$rollinfo= $this->db->query($query,$values)->result_array();
			foreach($rollinfo as $roll){
				$query="UPDATE users_has_meetings SET present ='Yes'
								WHERE has_meetings_id=? AND users_id=?";
				$values=array($roll['has_meetings_id'],$roll['users_id']);
				$this->db->query($query,$values);
			}
		return;
	}

	public function archivemeeting($id){
		return $this->db->query("UPDATE meetings SET status='Archived'
														WHERE meetings.id=?",$id);
	}

	public function activemeeting($id){
		return $this->db->query("UPDATE meetings SET status='Active'
														WHERE meetings.id=?",$id);
	}

	public function updatenotes($id,$notes){
		$query= ("UPDATE meetings
							SET objective=?, goals=?, notes=?
							WHERE meetings.id=?");
		$values= array($notes['objectives'],$notes['goals'],$notes['agenda'],$id);
		return $this->db->query($query,$values);
	}

	public function get_followups($id){
		return $this->db->query("SELECT * from followups WHERE meetings_id=$id")->result_array();
	}

	public function update_present($id,$attendees){
		for($i=0;$i<count($attendees);$i++){
			$query= "SELECT * from users_has_meetings WHERE meetings_id=? AND users_id=?";
			$values=array($id,$attendees[$i]);
			$tableid= $this->db->query($query,$values)->row_array();
			$query="UPDATE users_has_meetings SET users_has_meetings.present='Yes' WHERE has_meetings_id=?";
			$values=array('Yes',$tableid['has_meetings_id']);
			$this->db->query($query,$values);
		}
	}

	public function completefollow($id){
		return $this->db->query("UPDATE followups SET status='Yes' WHERE followups.id=?",$id);
	}

	public function incompletefollow($id){
		return $this->db->query("UPDATE followups SET status='No' WHERE followups.id=?",$id);
	}

	public function getrecurid($id){
		return $this->db->query("Select recurringseries_id FROM meetings WHERE meetings.id=?",$id)->row_array();
	}

	public function allrecur($id,$recurid){
		return $this->db->query("Select * FROM meetings WHERE recurringseries_id=?",$recurid)->result_array();
	}

}
