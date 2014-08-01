<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event_model extends CI_Model {
 
	 public function __construct()
	 {
	  parent::__construct();
	 }
	 
	 public function getEvents($company) {
		$company = rawurlencode($company);
		$sql = $this->db->query("SELECT * FROM events WHERE company='$company'");
		return $sql->result();
		
	 }
	 
	 public function getResumes($event_id) {
		$sql = $this->db->query("SELECT a.resume_file, b.name
								FROM event_activities a, users b, resumes c
								WHERE event_id='$event_id'
								AND c.resume_file = a.resume_file
								AND c.user_id = b.unique_id");
		return $sql->result();
	 }
	 
	 public function getComments($resume_file, $event_id) {
		$result = mysql_query("SELECT comments FROM event_activities WHERE resume_file='$resume_file' AND event_id='$event_id'");
		return mysql_fetch_array($result);
	 }
 }
 
 ?>