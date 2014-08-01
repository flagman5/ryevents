<?php
 
class DB_Functions {
 
    private $db;
 
    //put your code here
    // constructor
    function __construct() {
        require_once 'db_connect.php';
        // connecting to database
        $this->db = new DB_Connect();
        $this->db->connect();
    }
 
    // destructor
    function __destruct() {
 
    }
 
    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($name, $email, $password) {
        $uuid = uniqid('', true);
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt
        $result = mysql_query("INSERT INTO recruiters(unique_id, name, email, encrypted_password, salt, created_at) VALUES('$uuid', '$name', '$email', '$encrypted_password', '$salt', NOW())");
        // check for successful store
        if ($result) {
            // get user details
            $uid = mysql_insert_id(); // last inserted id
            $result = mysql_query("SELECT * FROM recruiters WHERE uid = $uid");
            // return user details
            return mysql_fetch_array($result);
        } else {
            return false;
        }
    }
 
    /**
     * Get user by email and password
     */
    public function getUserByEmailAndPassword($email, $password, $type) {
		if($type == 'student') {
			$result = mysql_query("SELECT * FROM users WHERE email = '$email'") or die(mysql_error());
		}
		else if($type == 'recruiter') {
			$result = mysql_query("SELECT * FROM recruiters WHERE email = '$email'") or die(mysql_error());
		}
        // check for result
        $no_of_rows = mysql_num_rows($result);
		
        if ($no_of_rows > 0) {
            $result = mysql_fetch_array($result);
            $salt = $result['salt'];
            $encrypted_password = $result['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $password);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                return $result;
            }
        } else {
            // user not found
            return false;
        }
    }
 
    /**
     * Check user is existed or not
     */
    public function isUserExisted($email) {
        $result = mysql_query("SELECT email from users WHERE email = '$email'");
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            // user existed
            return true;
        } else {
            // user not existed
            return false;
        }
    }
 
    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password) {
 
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }
 
    /**
     * Decrypting password
     * @param salt, password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {
 
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
 
        return $hash;
    }
	
	/* get list of resumes
	*/
	public function getResumeList($uid) {
		$result = mysql_query("SELECT * FROM resumes WHERE user_id='$uid'");
		$no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            return $result;
        } else {
            // user not existed
            return false;
        }
	}
	
	/* get event_id
	*/
	public function getEventID($company_name, $event_name) {
		$result = mysql_query("SELECT event_id from events WHERE company = '$company_name' AND event='$event_name'");
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
			$result = mysql_fetch_array($result);
            // event existed
            return $result['event_id'];
        } else {
            // event not existed
            return false;
        }
	}
	
	/* store an event
	*/
	public function storeEvent($company_name, $event_name) {
		$uuid = uniqid('', true);
		$status = 1;
		$result = mysql_query("INSERT INTO events(event_id, event, company, status, created_at) VALUES('$uuid', '$event_name', '$company_name', '$status', NOW())");
        // check for successful store
        if ($result) {
            // get event details
            $uid = mysql_insert_id(); // last inserted id
            $result = mysql_query("SELECT event_id FROM events WHERE uid = $uid");
            // return event details
			$result = mysql_fetch_array($result);
            return $result['event_id'];
        } else {
            return false;
        }
	}
	/*check if resume exists
	*/
	public function checkResume($resume_file) {
		$result = mysql_query("SELECT * FROM resumes WHERE resume_file='$resume_file'");
		$no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            return true;
        } else {
            // resume does not exist
            return false;
        }
	}
	
	/*check if resume has been accepted
	*/
	public function checkAcceptance($resume_file, $event_id) {
		$result = mysql_query("SELECT * FROM event_activities WHERE resume_file='$resume_file' AND event_id='$event_id'");
		$no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            return true;
        } else {
            // resume not accepted
            return false;
        }
	}
	
	/*do the work to accept a resume into event_activities
	*/
	public function acceptResume($resume_file, $event_id, $comments, $uid) {
		$result = mysql_query("INSERT INTO event_activities(event_id, resume_file, recruiter_id, comments, created_at) VALUES('$event_id', '$resume_file', '$uid', '$comments', NOW())");
        // check for successful store
        if ($result) {
            // get event details
            $uid = mysql_insert_id(); // last inserted id
            $result = mysql_query("SELECT event_activities FROM events WHERE uid = $uid");
            // return event details
            return true;
        } else {
            return false;
        }
	}
	
	/* get total resumes accepted
	*/
	public function getTotalAccepted($event_id) {
		$result = mysql_query("SELECT c.name, b.resume_file, a.comments
							   FROM event_activities a, resumes b, users c
							   WHERE a.event_id='$event_id'
							   AND a.resume_file = b.resume_file
							   AND b.user_id = c.unique_id");
		$no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            return $result;
        } else {
            // resume not accepted
            return false;
        }
	}
	
	/* update resumes
	*/
	public function updateAcceptance($resume_file, $event_id, $comments, $uid) {
		
		$result = mysql_query("UPDATE event_activities SET recruiter_id='$uid', comments='$comments' WHERE event_id = '$event_id' AND resume_file='$resume_file'");
		if(mysql_affected_rows() == 1) {
			return true;
		}
		else {
			return false;
		}
		
	}
	
	/* delete collected resume
	*/ 
	public function removeFile($event_id, $resume_file) {
		
		$result = mysql_query("DELETE FROM event_activities WHERE event_id = '$event_id' AND resume_file='$resume_file'");
		
		return $result;
	}
	
	/*end the event
	*/
	public function endEvent($event_id, $uid) {
		$result = mysql_query("UPDATE events SET status=0 WHERE event_id='$event_id'");
		return true;
	}
}
 
?>