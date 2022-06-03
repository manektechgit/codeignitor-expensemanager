<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	/**
	 * Constructor 
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('user_model');
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/login
	 *	- or -
	 * 		http://example.com/index.php/login/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index(){
		// this will do later. first lets create login for the users
		/**
		 * SET COMMON VARIABLES
		 * @var unknown_type
		 */
		$this->response=array();
		$this->response['page'] = "dashboard";
		// COMMON VARIABLES END

		// get total income, expense and balance
		$this->response['total_income'] = get_total_income();
		$this->response['total_expense'] = get_total_expense();
		$this->response['balance'] = intval($this->response['total_income']) - intval($this->response['total_expense']);

		// get expense by categories
		$expense_by_category_chart = get_expense_by_category_chart();
		$this->response['expense_by_category_chart'] = $expense_by_category_chart;

		$this->load->view('common/header',$this->response);
		$this->load->view('user/dashboard',$this->response);
		$this->load->view('common/footer',$this->response);
	}
	
	 /**
	 * Allowed to user to log in
	 */
	public function login(){
		// just load the 
		if (_is_logged_in()){
            redirect('user/index');
        }else{
        	$this->load->view('common/header_login');
        	$this->load->view('user/login');
        	$this->load->view('common/footer_login');
        }
	}
	
	/**
	 * LOGIN SUBMIT
	 */
	public function login_submit(){
		$this->response=array();
		
		$this->params=new stdClass();
		$this->params->email= $this->input->post('email',true);
		$this->params->password= $this->input->post('password',true);
		
		$this->response=$this->user_model->login_submit($this->params);
		
		echo $this->response;
	}
	
	/**
	 * LOGOUT
	 */
	public function logout(){
		delete_session_cookie(USER_INFO);
		
		redirect(base_url());
	}
	
	/**
	 * VIEW PROFILE
	 */
	public function profile(){
		$this->response=array();
		$this->response['page'] = "profile";
		
		$user_info = get_value_from_session_cookie(USER_INFO);
		
		// get user details
		$user_details = $this->common->get_fields("users","id,fk_role_id,first_name,last_name,email,password,phone,profile_image","id='".$user_info[SESS_USER_ID]."'");
		$user_details = $user_details[0];
		
		$this->response['data']=$user_details;
		
		$this->load->view('common/header',$this->response);
		$this->load->view('user/profile',$this->response);
		$this->load->view('common/footer',$this->response);
	}
	
	/**
	 * UPDATE PROFILE
	 */
	public function update_profile(){
		$this->response=array();
		
		// now update the profile
		$this->params=new stdClass();
		$this->params->first_name = $this->input->post('first_name',true);
		$this->params->last_name = $this->input->post('last_name',true);
		$this->params->email = $this->input->post('email',true);
		$this->params->phone = $this->input->post('phone',true);
		
		$this->response=$this->user_model->update_profile($this->params);
		
		echo $this->response;
	}
	
	/**
	 * CHANGE PASSWORD PAGE VIEW
	 */
	public function changepassword(){
		$this->response=array();
		$this->response['page'] = "changepassword";
		
		$this->load->view('common/header',$this->response);
		$this->load->view('user/changepassword',$this->response);
		$this->load->view('common/footer',$this->response);
	}
	
	/**
	 * UPDATE PASSWORD
	 */
	public function update_password(){
		$this->response=array();
		
		// now update the profile
		$this->params=new stdClass();
		$this->params->current_password = $this->input->post('current_password',true);
		$this->params->new_password = $this->input->post('new_password',true);
		$this->params->verify_password = $this->input->post('verify_password',true);
		
		$this->response=$this->user_model->update_password($this->params);
		
		echo $this->response;
	}
	
}
