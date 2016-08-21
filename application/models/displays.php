<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Displays extends CI_Model {

	public function __construct(){
		parent::__construct();
	}

 	public function showactiveowned($is_logged){
		return $this->db->query("SELECT * from meetings LEFT JOIN users_has_meetings ON meetings.id= users_has_meetings.meetings_id
								WHERE users_has_meetings.owner=".$is_logged." AND meetings.status='Active'")->result_array();
	}

	public function showactiveattend($is_logged){
		return $this->db->query("SELECT * from meetings LEFT JOIN users_has_meetings ON meetings.id= users_has_meetings.meetings_id
								WHERE users_has_meetings.users_id=".$is_logged." AND (users_has_meetings.owner IS NULL OR users_has_meetings.owner!=".$is_logged.") AND meetings.status='Active'")->result_array();
	}

	public function showarchived($is_logged){
		return $this->db->query("SELECT * from meetings LEFT JOIN users_has_meetings ON id= users_has_meetings.meetings_id
								WHERE users_has_meetings.users_id=".$is_logged." AND meetings.status='Archived'")->result_array();
	}

	public function showfollowups($is_logged){
		return $this->db->query("SELECT followups.id AS follow_id,followups.owner,duedate,meetings.id,followups.status,meetings.name,meetings.projectname,followups.followup from followups
								LEFT JOIN meetings on followups.meetings_id=meetings.id WHERE owner=".$is_logged." AND followups.status != 'Yes'")->result_array();
	}

	public function showcompleted($is_logged){
		return $this->db->query("SELECT followups.id AS follow_id,followups.owner,duedate,meetings.id,followups.status,meetings.name,meetings.projectname,followups.followup from followups
								LEFT JOIN meetings on followups.meetings_id=meetings.id	WHERE owner=".$is_logged." AND followups.status = 'Yes'")->result_array();
	}

}
