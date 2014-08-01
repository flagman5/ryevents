<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {
 
	 public function __construct()
	 {
	  parent::__construct();
	 }
 
 /**
     * Storing new user
     * returns user details
     */
    public function add_user($type, $name, $company, $email, $password) {
		
        $uuid = uniqid('', true);
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt
		if($type == 'recruiter') {
			$result = mysql_query("INSERT INTO recruiters(unique_id, name, company, email, hr, encrypted_password, salt, created_at) VALUES('$uuid', '$name', '$company', '$email', 0, '$encrypted_password', '$salt', NOW())");
        }
		else {
			//students
			$result = mysql_query("INSERT INTO users(unique_id, name, email, encrypted_password, salt, created_at) VALUES('$uuid', '$name', '$email', '$encrypted_password', '$salt', NOW())");

		}
		// check for successful store
        if ($result) {
            // get user details
            $uid = mysql_insert_id(); // last inserted id
			if($type == 'recruiter') {
				$result = mysql_query("SELECT * FROM recruiters WHERE uid = $uid");
			}
			else {
				$result = mysql_query("SELECT * FROM users WHERE uid = $uid");
			}
            // return user details
            return mysql_fetch_array($result);
        } else {
            return false;
        }
    }
 
    /**
     * Get user by email and password
     */
    public function login($email, $password, $type) {
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
	public function recruiterCheck($email) {
		$result = mysql_query("SELECT email from recruiter WHERE email = '$email'");
		$no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            // user existed
			$this->form_validation->set_message('recruiterCheck', 'The email is already registered');
            return FALSE;
        } else {
            // user not existed
            return TRUE;
        }
	}
	
   public function studentCheck($email) {
		$result = mysql_query("SELECT email from users WHERE email = '$email'");
		$no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            // user existed
			$this->form_validation->set_message('studentCheck', 'The email is already registered');
            return FALSE;
        } else {
            // user not existed
            return TRUE;
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
	
 
}
?>