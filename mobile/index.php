<?php
/**
 * File to handle all API requests
 * Accepts GET and POST
 *
 * Each request will be identified by TAG
 * Response will be JSON data
 
  /**
 * check for POST request
 */
if (isset($_REQUEST['tag']) && $_REQUEST['tag'] != '') {
    // get tag
    $tag = $_REQUEST['tag'];
 
    // include db handler
    require_once 'include/db_functions.php';
    $db = new DB_Functions();
 
    // response Array
    $response = array("tag" => $tag, "success" => 0, "error" => 0);
 
    // check for tag type
    if ($tag == 'login') {
        // Request type is check Login
        $email = $_REQUEST['email'];
        $password = $_REQUEST['password'];
		$type = $_REQUEST['type'];
		
        // check for user
        $user = $db->getUserByEmailAndPassword($email, $password, $type);
        if ($user != false) {
            // user found
            // echo json with success = 1
            $response["success"] = 1;
            $response["uid"] = $user["unique_id"];
            $response["user"]["name"] = $user["name"];
			if($type == 'recruiter') {
				$response["user"]["company"] = $user["company"];
			}
            $response["user"]["email"] = $user["email"];
            $response["user"]["created_at"] = $user["created_at"];
            $response["user"]["updated_at"] = $user["updated_at"];
            echo json_encode($response);
        } else {
            // user not found
            // echo json with error = 1
            $response["error"] = 1;
            $response["error_msg"] = "Incorrect email or password!";
            echo json_encode($response);
        }
    } else if ($tag == 'register') {
        // Request type is Register new user
        $name = $_REQUEST['name'];
        $email = $_REQUEST['email'];
        $password = $_REQUEST['password'];
 
        // check if user is already existed
        if ($db->isUserExisted($email)) {
            // user is already existed - error response
            $response["error"] = 2;
            $response["error_msg"] = "User already existed";
            echo json_encode($response);
        } else {
            // store user
            $user = $db->storeUser($name, $email, $password);
            if ($user) {
                // user stored successfully
                $response["success"] = 1;
                $response["uid"] = $user["unique_id"];
                $response["user"]["name"] = $user["name"];
                $response["user"]["email"] = $user["email"];
                $response["user"]["created_at"] = $user["created_at"];
                $response["user"]["updated_at"] = $user["updated_at"];
                echo json_encode($response);
            } else {
                // user failed to store
                $response["error"] = 1;
                $response["error_msg"] = "Error occured in Registartion";
                echo json_encode($response);
            }
        }
    } 
	else if($tag == 'list_resume') {
		//user id
		$uid = $_REQUEST['uid'];
		
		//get list of resumes for this user
		$resumes = $db->getResumeList($uid);
		
		if ($resumes != false) {
            // list of resumes found
			$response["success"] = 1;
            $response["uid"] = $uid;
				
			while($row = mysql_fetch_array($resumes)) {
				$rows[] = $row;
			}
			$counter = 0;
			foreach($rows as $row){ 
				$response["resume.$counter"]["resume_name"] = $row["resume_name"];
				$response["resume.$counter"]["resume_file"] = $row["resume_file"];
				$counter++;
			}
			
			echo json_encode($response);
		}
		else {
			//no resumes
			$response["error"] = 1;
            $response["error_msg"] = "You have no resumes on file";
            echo json_encode($response);
		}
	}
	else if($tag == 'input_event') {
		//company name
		$company_name = $_REQUEST['company'];
		$event_name = $_REQUEST['event'];
		
		//check if this event/company combo has been already inputted, if so just retreieve the uid, if not create one, then return
		$event_id = $db->getEventID($company_name, $event_name);
		
		if($event_id == false) {
			$event_id = $db->storeEvent($company_name, $event_name);
		}
		
		$response["success"] = 1;
        $response["event_id"] = $event_id;
		echo json_encode($response);
	}
	else if($tag == 'fetch_resume') {
		//check to make sure the captured 
		$resume_file = $_REQUEST['resume_file'];
		if($db->checkResume($resume_file)) {
			$url = "http://www.ryevents.com/resumes/".$resume_file;
			$response["success"] = 1;
			$response["url"] = $url;
			echo json_encode($response);
		}
		else {
			$response["error"] = 1;
            $response["error_msg"] = "Resume not in database";
            echo json_encode($response);
		}
	}
	else if($tag == 'accept_resume') {
		//accept resume and catalogue it
		$resume_file = $_REQUEST['resume_file'];
		$event_id = $_REQUEST['event_id'];
		$comments = $_REQUEST['comments'];
		$uid = $_REQUEST['uid']; //recruiter's uid
		
		//check if it was accepted already
		if($db->checkAcceptance($resume_file, $event_id)) {
			$response["success"] = 1;
			$response["message"] = "Resume has already been accepted";
			echo json_encode($response);
		}
		else {
			$accept = $db->acceptResume($resume_file, $event_id, $comments, $uid);
			if($accept) {
				//successful
				$response["success"] = 1;
				$response["message"] = "Resume has been accepted";
				echo json_encode($response);
			}
			else {
                // resume failed to store
                $response["error"] = 1;
                $response["error_msg"] = "Error occured in accepting resume, please try again";
                echo json_encode($response);
            }
		}
	}
	else if($tag == 'get_total_acceptance') {
		//only need event id
		$event_id = $_REQUEST['event_id'];
		
		$total = $db->getTotalAccepted($event_id);
		if($total != false) {
			$response["success"] = 1;
			while($row = mysql_fetch_array($total)) {
				$rows[] = $row;
			}
			$counter = 0;
			foreach($rows as $row){ 
				$response["user.$counter"]["name"] = $row["name"];
				$response["user.$counter"]["url"] = "http://www.ryevents.com/resumes/".$row["resume_file"];
				$response["user.$counter"]["resume_file"] = $row["resume_file"];
				$spacedComments = str_replace("-", " ", $row["comments"]);
				$response["user.$counter"]["comments"] = urldecode($spacedComments);
				$counter++;
			}
			$response['total'] = $counter;
			echo json_encode($response);
		}
		else {
			$response["error"] = 1;
			$response["message"] = "No resumes have been collected so far";
			echo json_encode($response);
		}
	}
	else if($tag == 'make_changes') {
		//needs resume, event id, user id
		$event_id = $_REQUEST['event_id'];
		$resume_file = $_REQUEST['resume_file'];
		$uid = $_REQUEST['uid']; //recruiter's uid
		$comments = $_REQUEST['comments'];
		
		//check if it was accepted already
		if($db->checkAcceptance($resume_file, $event_id)) {
			//make the update query
			$updated = $db->updateAcceptance($resume_file, $event_id, $comments, $uid);
			
			if($updated) {
				$response["success"] = 1;
				$response["message"] = "Resume has been updated";
				echo json_encode($response);
			}
			else {
				$response["error"] = 1;
                $response["error_msg"] = "Error occured in updating resume, please try again";
                echo json_encode($response);
			}
		}
		else {
			//resume was never accepted, error
            $response["error"] = 1;
            $response["error_msg"] = "Error occured in updating resume, please try again";
            echo json_encode($response);
		}
		
	}
	else if($tag == 'delete') {
		//just need event and resume
		$event_id = $_REQUEST['event_id'];
		$resume_file = $_REQUEST['resume_file'];
		
		$deleted = $db->removeFile($event_id, $resume_file);
		if($deleted) {
			$response["success"] = 1;
			$response["message"] = "Resume has been removed";
			echo json_encode($response);
		}
		else {
			//resume cannot be deleted
            $response["error"] = 1;
            $response["error_msg"] = "Error occured in deleting resume, please try again";
            echo json_encode($response);
		}
	}
	else if($tag == 'end_event') {
		$event_id = $_REQUEST['event_id'];
		$uid = $_REQUEST['uid']; //recruiter's uid
		
		$end = $db->endEvent($event_id, $uid);
		if($end) {
			//successful
			$response["success"] = 1;
			$response["message"] = "Event has ended";
			echo json_encode($response);
		}
		else {
			// resume failed to store
			$response["error"] = 1;
			$response["error_msg"] = "Error occured in ending event, please try again";
			echo json_encode($response);
		}
		
	}
	else {
        echo "Invalid Request";
    }
} else {
    echo "Access Denied";
}
?>