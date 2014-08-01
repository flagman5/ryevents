<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resume_model extends CI_Model {
	
	public function __construct()
	 {
	  parent::__construct();
	 }
	 
	 public function evaluate($evaluator_id, $resume_file, $rating) {
		
		$result = mysql_query("INSERT INTO 
								evaluations(evaluator, resume_file, rating, created_at) VALUES('$evaluator_id', '$resume_file', '$rating', NOW())") or die(mysql_error());
		if($result)
		{
			return TRUE;
		}
		else {
			return false;
		}

	 }
	 
	 public function checkIfRated($evaluator_id, $resume_file) {
		
		$result = mysql_query("SELECT * FROM evaluations WHERE evaluator='$evaluator_id' AND resume_file='$resume_file'") or die(mysql_error());
		$no_of_rows = mysql_num_rows($result);
		
		if($no_of_rows  > 0) {
			return "1";
		}
		else {
			return "0";
		}
		
	 }
	 
	 public function getStats($event_id) {
		
		//complicated query to join all tables
		$sql = $this->db->query("SELECT SUM(d.rating) as total, b.name
								FROM event_activities a, users b, resumes c, evaluations d
								WHERE event_id='$event_id'
								AND c.resume_file = a.resume_file
								AND c.user_id = b.unique_id
								AND c.resume_file = d.resume_file");
		return $sql->result();
	 }
	
	 public function getResumesOnFile($student_id) {
		
		$sql = $this->db->query("SELECT * FROM resumes WHERE user_id='$student_id'") or die(mysql_error());
		return $sql->result();
	 }
	 
	 public function getNumResume($student_id) {
		$result = mysql_query("SELECT * FROM resumes WHERE user_id='$student_id'") or die(mysql_error());
		$no_of_rows = mysql_num_rows($result);
		return $no_of_rows;
	 }
	 
	 public function addResume($student_id, $resume_name, $fileName) {
		
		$result = mysql_query("INSERT INTO 
								resumes(user_id, resume_name, resume_file, created_at) VALUES('$student_id', '$resume_name', '$fileName', NOW())") or die(mysql_error());
		if($result)
		{
			return TRUE;
		}
		else {
			return false;
		}
		
	 }
	 
	 public function deleteResume($fileName) {
		$result = mysql_query("DELETE FROM resumes WHERE resume_file='$fileName'") or die(mysql_error());
		if($result)
		{
			return TRUE;
		}
		else {
			return false;
		}
	 }	
 }
 ?>