<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {
	/**
	 * Constructor 
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('profile_model');
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
		$this->response=array();
	    
		redirect('user');
	}
	
	/**
	 * ACCOUNT MANAGERS
	 */
	public function accountmanager(){
		
		/**
		 * SET COMMON VARIABLES
		 * @var unknown_type
		 */
		$this->response=array();
		$this->response['page'] = "accountmanager";
		
		$role = ROLE_ACCOUNT_MANAGER;
		$this->response['fk_role_id'] = $role;
		
		global $arrRoleNames;
		$this->response['page_heading'] = $arrRoleNames[$role];
		// COMMON VARIABLES END
		
		
		$this->params=new stdClass();
		$this->params->fk_role_id = $role;
		
		// get_users will be one function providing data for Account Managers, Admins, Super Admins, Tech and Client Profiles
		$this->response['data']=$this->profile_model->get_users($this->params);
		
		$this->load->view('common/header',$this->response);
		$this->load->view('profile/list',$this->response);
		$this->load->view('common/footer',$this->response);
	}
	
	/**
	 * ADD PAGE
	 */
	public function add(){
		/**
		 * SET COMMON VARIABLES
		 * @var unknown_type
		 */
		$this->response=array();
		$this->response['page'] = "accountmanager";
		
		$redirect_page = "profile/accountmanager";
		$this->response['redirect_page'] = $redirect_page;
		
		$role = ROLE_ACCOUNT_MANAGER;
		
		global $arrRoleNames;
		$this->response['page_heading'] = "Add ".$arrRoleNames[$role];
		// COMMON VARIABLES END
		
		$this->load->view('common/header',$this->response);
		$this->load->view('profile/add',$this->response);
		$this->load->view('common/footer',$this->response);
	}
	
	/**
	 * EDIT PAGE
	 */
	public function edit(){
		
		// first of all get the passed role id in get
		$id = decrypt_simple($this->input->get("id"));
		
		/**
		 * SET COMMON VARIABLES
		 * @var unknown_type
		 */
		$this->response=array();
		$this->response['page'] = "accountmanager";
		
		$redirect_page = "profile/accountmanager";
		$this->response['redirect_page'] = $redirect_page;
		
		$role = ROLE_ACCOUNT_MANAGER;
		$this->response['fk_role_id'] = $role;
		
		global $arrRoleNames;
		$this->response['page_heading'] = "Edit ".$arrRoleNames[$role];
		// COMMON VARIABLES END
		
		// now get all the details of the user
		$user_details = $this->common->get_fields("users","id,fk_role_id,first_name,last_name,email,password,phone","id='".$id."'");
		$user_details = $user_details[0];
		$this->response['data']=$user_details;
		
		$this->load->view('common/header',$this->response);
		$this->load->view('profile/add',$this->response);
		$this->load->view('common/footer',$this->response);
	}
	
	/**
	 * SAVE PROFILE
	 */
	public function save_profile(){
		$this->response=array();
		
		// now update the profile
		$this->params=new stdClass();
		$this->params->id = $this->input->post('id',true);
		$this->params->fk_role_id = ROLE_ACCOUNT_MANAGER;
		$this->params->first_name = $this->input->post('first_name',true);
		$this->params->last_name = $this->input->post('last_name',true);
		$this->params->email = $this->input->post('email',true);
		$this->params->phone = $this->input->post('phone',true);
		$this->params->new_password = $this->input->post('new_password',true);
		$this->params->verify_password = $this->input->post('verify_password',true);
		
		$this->response=$this->profile_model->add_update_profile($this->params);
		
		echo $this->response;
	}
	
	/**
	 * CHANGE PASSWORD
	 */
	public function changepassword(){
		// first of all get the passed role id in get
		$id = decrypt_simple($this->input->get("id"));
		
		/**
		 * SET COMMON VARIABLES
		 * @var unknown_type
		 */
		$this->response=array();
		$this->response['page'] = "accountmanager";
		
		$redirect_page = "profile/accountmanager";
		$this->response['redirect_page'] = $redirect_page;
		
		$role = ROLE_ACCOUNT_MANAGER;
		$this->response['fk_role_id'] = $role;
		// COMMON VARIABLES END
		
		// now get all the details of the user
		$user_details = $this->common->get_fields("users","id,fk_role_id,first_name,last_name,email,password,phone","id='".$id."'");
		$user_details = $user_details[0];
		$this->response['data']=$user_details;
		
		$this->response['page_heading'] = "Set new password for - ".$user_details['first_name']." ".$user_details['last_name'];
		
		$this->load->view('common/header',$this->response);
		$this->load->view('profile/changepassword',$this->response);
		$this->load->view('common/footer',$this->response);
	}
	
	public function update_password(){
		$this->response=array();
		
		// now update the profile
		$this->params=new stdClass();
		$this->params->id = $this->input->post('id',true);
		$this->params->new_password = $this->input->post('new_password',true);
		$this->params->verify_password = $this->input->post('verify_password',true);
		
		$this->response=$this->profile_model->update_password($this->params);
		
		echo $this->response;
	}
	
	/**
	 * COMPANY
	 */
	public function company(){
		
		/**
		 * SET COMMON VARIABLES
		 * @var unknown_type
		 */
		$this->response=array();
		$this->response['parent_page'] = "profile";
		$this->response['child_page'] = "company";
		
		global $arrRoleNames;
		$this->response['page_heading'] = "Company";
		// COMMON VARIABLES END
		
		$user_info = get_value_from_session_cookie(USER_INFO);
		
		$this->params=new stdClass();
		$this->params->fk_role_id = $user_info[SESS_ROLE_ID];
		$this->params->fk_user_id = $user_info[SESS_USER_ID];
		
		// get_users will be one function providing data for Account Managers, Admins, Super Admins, Tech and Client Profiles
		$this->response['data']=$this->profile_model->get_company($this->params);
		
		$this->load->view('common/header',$this->response);
		$this->load->view('profile/list_company',$this->response);
		$this->load->view('common/footer',$this->response);
	}
	
	/**
	 * ADD COMPANY
	 */
	public function addcompany(){
		
		/**
		 * SET COMMON VARIABLES
		 * @var unknown_type
		 */
		$this->response=array();
		$this->response['parent_page'] = "profile";
		
		$child_page = "company";
		$redirect_page = "profile/company";
		
		$this->response['child_page'] = $child_page;
		$this->response['redirect_page'] = $redirect_page;
		
		$this->response['page_heading'] = "Add Company";
		// COMMON VARIABLES END
		
		$user_info = get_value_from_session_cookie(USER_INFO);
		$this->response['data']['fk_accountmanager_id'] = $user_info[SESS_USER_ID];	// set in data variable so that it comes as a selected value
		
		// get list of account managers to select
		$account_managers = $this->common->get_fields("users","id,first_name,last_name","fk_role_id='".ROLE_ACCOUNT_MANAGER."' AND status!='".STATUS_DELETE."'");
		$this->response['account_managers'] = $account_managers;
		
		$this->load->view('common/header',$this->response);
		$this->load->view('profile/add_company',$this->response);
		$this->load->view('common/footer',$this->response);
	}
	
	/**
	 * Edit Company
	 */
	public function editcompany(){
		
		// first of all get the passed id in get
		$id = decrypt_simple($this->input->get("id"));
		/**
		 * SET COMMON VARIABLES
		 * @var unknown_type
		 */
		$this->response=array();
		$this->response['parent_page'] = "profile";
		$this->response['child_page'] = "company";
		$this->response['redirect_page'] = "profile/company";
		
		$this->response['page_heading'] = "Edit Company";
		// COMMON VARIABLES END
		
		// now get all the details of the user
		$details = $this->common->get_fields("company_master","*","id='".$id."'");
		$details = $details[0];
		$this->response['data']=$details;
		
		// get list of account managers to select
		$account_managers = $this->common->get_fields("users","id,first_name,last_name","fk_role_id='".ROLE_ACCOUNT_MANAGER."' AND status!='".STATUS_DELETE."'");
		$this->response['account_managers'] = $account_managers;
		
		$this->load->view('common/header',$this->response);
		$this->load->view('profile/add_company',$this->response);
		$this->load->view('common/footer',$this->response);
	}
	
	/**
	 * SAVE COMPANY
	 */
	public function save_company(){
		$this->response=array();
		
		// now update the profile
		$this->params=new stdClass();
		$this->params->id = $this->input->post('id',true);
		$this->params->name = $this->input->post('name',true);
		$this->params->fk_accountmanager_id = $this->input->post('fk_accountmanager_id',true);
		
		$this->response=$this->profile_model->add_update_company($this->params);
		
		echo $this->response;
	}
}
