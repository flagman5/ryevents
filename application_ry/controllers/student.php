<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student extends CI_Controller {
	
	public function index()
	{
		//$this->load->view('welcome_message');
		
		$user_id= $this->session->userdata('user_id');
		$this->load->view('header');
		if(!$user_id) {
			//user is not logged in, show login page
			$this->load->view('login_student');
		}
		else {
			//user is logged in, redirect to home page
			redirect('student/home');
		}
		$this->load->view('footer');
	}
	
	public function register() {
	
		  // field name, error message, validation rules
		  $this->form_validation->set_rules('email_address', 'Your Email', 'callback_studentCheck');
		  
		  $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
		  $this->form_validation->set_rules('email_address', 'Your Email', 'trim|required|valid_email');
		  $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
		  $this->form_validation->set_rules('con_password', 'Password Confirmation', 'trim|required|matches[password]');

		  if($this->form_validation->run() == FALSE)
		  {
			$this->load->view('header');
			$this->load->view('student_registration');
			$this->load->view('footer');
		  }
		  else
		  {	
			$name = $this->input->post('name');
			$email = $this->input->post('email_address');
			$password = $this->input->post('password');
			
			$this->load->model('user_model');
			$this->user_model->add_user('student', $name, '', $email, $password);
			
			$data['msg'] = "Thank you for registering, please login";
			$this->load->view('header');
			$this->load->view('login_student', $data);
			$this->load->view('footer');
		  }
	}
	
	public function login() {
		
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		
		$this->load->model('user_model');
		$rows = $this->user_model->login($email, $password, 'student');

		if($rows) {
			$newdata = array(
							  'user_id'  => $rows['uid'],
							  'student_id' => $rows['unique_id'],
							  'user_name'  => $rows['name'],
							  'user_email'    => $rows['email'],
							  'logged_in'  => TRUE,
							);
	   
			$this->session->set_userdata($newdata);
			redirect('student/home');
			
		}
		else {
			$data['msg'] = "Unable to login, please try again";
			$this->load->view('header');
			$this->load->view('login_student', $data);
			$this->load->view('footer');
		}
		
	}
	
	public function logout()
	 {
	  $newdata = array(
	  'user_id'   =>'',
	  'student_id' => '',
	  'user_name'  =>'',
	  'user_email'     => '',
	  'logged_in' => FALSE,
	  );
	  $this->session->unset_userdata($newdata);
	  $this->session->sess_destroy();
	  $this->index();
	 }
	 /*
	 *	home page for student
	 */
	 public function home() {
		$student_id = $this->session->userdata('student_id');
		if(!$student_id) {
			redirect('student');
		}
		$this->load->view('header');
			
		$this->load->model('resume_model');
		$resumes = $this->resume_model->getResumesOnFile($student_id);
		$data['resumes'] = $resumes;
		
		$upload_msg = $this->session->userdata('upload_msg');
		$data['upload_msg'] = $upload_msg;
		$error_msg = $this->session->userdata('error_msg');
		$data['error_msg'] = $error_msg;
		$this->load->view('student_home', $data);
		$this->load->view('footer');
		
		$this->session->unset_userdata('upload_msg');
		$this->session->unset_userdata('error_msg');
	 }
	 
	 public function display() {

		$data['student'] = 1;
		
		$resume_file = $this->uri->segment(3);
		$data['file'] = $resume_file;
		
		$this->load->view('header');
		$this->load->view('display_resume', $data);
		$this->load->view('footer');

	 }
	
	public function upload() {
		$student_id = $this->session->userdata('student_id');
		if(!$student_id) {
			redirect('student');
		}
		$this->form_validation->set_rules('resume_name', 'Resume Name', 'trim|required|xss_clean');
		
		if($this->form_validation->run() == FALSE)
		  {
			$this->home();
		  }
		  else
		  {	
			$this->do_upload();
		  }
	}
	
	public function do_upload() {
		$student_id = $this->session->userdata('student_id');
		//database query to construct name 
		$this->load->model('resume_model');
		$num_resume = $this->resume_model->getNumResume($student_id);
		$num_resume++;
		
		//create the filename
		$fileName = $student_id."_".$num_resume.".pdf";
		$config['file_name'] = $fileName;
		$config['upload_path'] = './resumes/';
		$config['allowed_types'] = 'pdf';
		$config['max_size']	= '2048';
		
		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload())
		{
			$msg = array('error' => $this->upload->display_errors());
			$this->session->set_userdata("error_msg", $msg);
			redirect('student/home');
		}
		else
		{
			//insert into database
			$resume_name = $this->input->post('resume_name');
			$this->resume_model->addResume($student_id, $resume_name, $fileName);
			
			$data = array('upload_data' => $this->upload->data());
			$msg = "Successfully uploaded resume";
			$this->session->set_userdata("upload_msg", $msg);
			redirect('student/home');
		}
	}
	
	public function delete() {
		$resume_file = $this->uri->segment(3);
	
		$this->load->model('resume_model');
		$this->resume_model->deleteResume($resume_file);
		unlink(FCPATH . 'resumes/'.$resume_file);
		
		$this->home();
	}
}
?>
