<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Recruiter extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		//$this->load->view('welcome_message');
		
		$company= $this->session->userdata('company');
		$this->load->view('header');
		if(!$company) {
			//user is not logged in, show login page
			$this->load->view('login_recruiter');
		}
		else {
			//user is logged in, redirect to home page
			redirect('recruiter/home');
		}
		$this->load->view('footer');
	}
	
	public function register() {
	
		  // field name, error message, validation rules
		  $this->form_validation->set_rules('email_address', 'Your Email', 'callback_recruiterCheck');
		  
		  $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
		  $this->form_validation->set_rules('company_name', 'Company Name', 'trim|required|xss_clean');
		  $this->form_validation->set_rules('email_address', 'Your Email', 'trim|required|valid_email');
		  $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
		  $this->form_validation->set_rules('con_password', 'Password Confirmation', 'trim|required|matches[password]');

		  if($this->form_validation->run() == FALSE)
		  {
			$this->load->view('header');
			$this->load->view('recruiter_registration');
			$this->load->view('footer');
		  }
		  else
		  {	
			$name = $this->input->post('name');
			$company = $this->input->post('company_name');
			$email = $this->input->post('email_address');
			$password = $this->input->post('password');
			
			$this->load->model('user_model');
			$this->user_model->add_user('recruiter', $name, $company, $email, $password);
			
			$data['msg'] = "Thank you for registering, please login";
			$this->load->view('header');
			$this->load->view('login_recruiter', $data);
			$this->load->view('footer');
		  }
	}
	
	public function login() {
		
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		
		$this->load->model('user_model');
		$rows = $this->user_model->login($email, $password, 'recruiter');

		if($rows) {
			$newdata = array(
							  'user_id'  => $rows['uid'],
							  'user_name'  => $rows['name'],
							  'company' => $rows['company'],
							  'user_email'    => $rows['email'],
							  'hr' => $rows['hr'],
							  'logged_in'  => TRUE,
							);
	   
			$this->session->set_userdata($newdata);
			redirect('recruiter/home');
			
		}
		else {
			$data['msg'] = "Unable to login, please try again";
			$this->load->view('header');
			$this->load->view('login_recruiter', $data);
			$this->load->view('footer');
		}
		
	}
	
	public function logout()
	 {
	  $newdata = array(
	  'user_id'   =>'',
	  'user_name'  =>'',
	  'user_email'     => '',
	  'logged_in' => FALSE,
	  );
	  $this->session->unset_userdata($newdata);
	  $this->session->sess_destroy();
	  $this->index();
	 }
	 
	 public function home() {
		$company= $this->session->userdata('company');
			
		$this->load->view('header');
			
		$this->load->model('event_model');
		$events = $this->event_model->getEvents($company);
		$data['company'] = $company;
		$data['events'] = $events;
			
		$this->load->view('recruiter_home', $data);
		$this->load->view('footer');
	 }
	 
	 public function getResumes() {
		$user_id = $this->session->userdata('user_id');
		
		$event_id = $this->uri->segment(3);
		$this->session->set_userdata('event_id', $event_id);
		
		$this->load->model('event_model');
		$resumes = $this->event_model->getResumes($event_id);
		$checkRated = array();
		$counter = 0;
		foreach($resumes as $resume) {
			$this->load->model('resume_model');
			$rated = $this->resume_model->checkIfRated($user_id, $resume->resume_file);
			if($rated) {
				$checkRated[$counter] = $resume->resume_file;
				$counter++;
			}
		}
		$data['resumes'] = $resumes;
		$data['checkRated'] = $checkRated;
		
		$this->load->view('header');
		$this->load->view('resume_review', $data);
		$this->load->view('footer');
	 }
	 
	 public function display() {
		$resume_file = $this->uri->segment(3);
		if($resume_file == 'stats') { $this->display_stats();}
		else {
		
			$user_id = $this->session->userdata('user_id');
			$event_id = $this->session->userdata('event_id');
			$this->load->model('event_model');
			$comments = $this->event_model->getComments($resume_file, $event_id);
			
			$data['file'] = $resume_file;
			$data['user_id'] = $user_id;
			$data['comments'] = rawurldecode($comments['comments']);
			
			//check if the guy already rated
			$this->load->model('resume_model');
			$rated = $this->resume_model->checkIfRated($user_id, $resume_file);
			$data['rated'] = $rated;
			
			$this->load->view('header');
			$this->load->view('display_resume', $data);
			$this->load->view('footer');
		}
	 }
	 
	 public function evaluate() {
		$evaluator_id = $this->input->post('evaluator');
		$resume_file = $this->input->post('resume_file');
		$rating = $this->input->post('rating');
		
		$this->load->model('resume_model');
		$success = $this->resume_model->evaluate($evaluator_id, $resume_file, $rating);
		
		$event_id = $this->session->userdata('event_id');
		redirect('recruiter/getResumes/'.$event_id);
		
	 }
	 
	 public function display_stats() {
		$event_id = $this->session->userdata('event_id');
		
		$this->load->model('resume_model');
		$resumes = $this->resume_model->getStats($event_id);
		
		$data['resumes'] = $resumes;
		
		$this->load->view('header');
		$this->load->view('resume_review_stats', $data);
		$this->load->view('footer');
		
	 }
}

?>
