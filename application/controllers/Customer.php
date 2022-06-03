<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {
	/**
	 * Constructor 
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('customer_model');
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
		
		/**
		 * SET COMMON VARIABLES
		 * @var unknown_type
		 */
		$this->response=array();
		$this->response['page'] = "customer";
		
		$this->response['page_heading'] = "Customers";
		// COMMON VARIABLES END
		
		// get list of customers
		$this->response['data']=$this->customer_model->get_customers();
		
		// account manager list
		$this->response['account_managers'] = get_account_managers();
		
		$this->load->view('common/header',$this->response);
		$this->load->view('customer/list',$this->response);
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
		$this->response['page'] = "customer";
		
		$redirect_page = "customer";
		$this->response['redirect_page'] = $redirect_page;
		
		$this->response['page_heading'] = "Add Customer";
		// COMMON VARIABLES END
		
		// account managers for dropdown
		$account_managers = $this->common->get_fields("users","id,CONCAT(first_name,' ',last_name) AS name","status!='".STATUS_DELETE."' AND fk_role_id!='".ROLE_ADMIN."'");
		$this->response['account_managers'] = $account_managers;
		
		// industry
		$industries = $this->common->get_fields("flagindustry","id,name","status!='".STATUS_DELETE."' ORDER BY name");
		$this->response['industries'] = $industries;
		
		$this->load->view('common/header',$this->response);
		$this->load->view('customer/add',$this->response);
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
		$this->response['page'] = "customer";
		
		$redirect_page = "customer";
		$this->response['redirect_page'] = $redirect_page;
		
		$this->response['page_heading'] = "Edit Customer";
		// COMMON VARIABLES END
		
		// now get all the details of the user
		$details = $this->common->get_fields("customers","*","id='".$id."'");
		$details = $details[0];
		$this->response['data']=$details;
		
		// account managers for dropdown
		$account_managers = $this->common->get_fields("users","id,CONCAT(first_name,' ',last_name) AS name","status!='".STATUS_DELETE."' AND fk_role_id!='".ROLE_ADMIN."'");
		$this->response['account_managers'] = $account_managers;
		
		// industry
		$industries = $this->common->get_fields("flagindustry","id,name","status!='".STATUS_DELETE."' ORDER BY name");
		$this->response['industries'] = $industries;
		
		$this->load->view('common/header',$this->response);
		$this->load->view('customer/add',$this->response);
		$this->load->view('common/footer',$this->response);
	}
	
	/**
	 * SAVE CUSTOMER
	 */
	public function save_customer(){
		$this->response=array();
		
		// now update the profile
		$this->params=new stdClass();
		$this->params->id = $this->input->post('id',true);
		$this->params->name = $this->input->post('customer_name',true);
		$this->params->contact_name = $this->input->post('contact_name',true);
		$this->params->fk_industry_id = $this->input->post('fk_industry_id',true);
		$this->params->fk_accountmanager_id = $this->input->post('fk_accountmanager_id',true);
		$this->params->email = $this->input->post('email',true);
		$this->params->phone = $this->input->post('phone',true);
		$this->params->address_line_1 = $this->input->post('address_line_1',true);
		$this->params->address_line_2 = $this->input->post('address_line_2',true);
		$this->params->city = $this->input->post('city',true);
		$this->params->state = $this->input->post('state',true);
		$this->params->country = $this->input->post('country',true);
		$this->params->followup_date = $this->input->post('followup_date',true);
		
		$this->response=$this->customer_model->add_update_customer($this->params);
		
		echo $this->response;
	}
	
	/**
	 * COMMENTS
	 */
	public function comments(){
		// first of all get the passed id in get
		$customer_id = decrypt_simple($this->input->get("id"));
		
		/**
		 * SET COMMON VARIABLES
		 * @var unknown_type
		 */
		$this->response=array();
		$this->response['page'] = "customer";
		$this->response['back_page'] = "customer";
		$this->response['redirect_page'] = "customer/comments?id=".encrypt_simple($customer_id);
		$this->response['customer_id'] = $customer_id;
		
		// customer details
		$details = $this->common->get_fields("customers","*","id='".$customer_id."'");
		$details = $details[0];
		$this->response['page_heading'] = "Comments for: ".$details['name'];
		
		$user_info = get_value_from_session_cookie(USER_INFO);
		// COMMON VARIABLES END
		
		$this->params=new stdClass();
		$this->params->fk_role_id = $user_info[SESS_ROLE_ID];
		$this->params->fk_user_id = $user_info[SESS_USER_ID];
		$this->params->fk_row_id = $customer_id;
		$this->params->module = "C";	// C=Customer module comments
		$this->params->files_for = "C";	// C=Comments
		
		// get comments for the task. client can see those comments which are set show_to_customer = 1 or the one that client has posted 
		$this->response['comments']=$this->common->get_comments($this->params);
		
		$this->load->view('common/header',$this->response);
		$this->load->view('customer/comments',$this->response);
		$this->load->view('common/footer',$this->response);
	}
	
	/**
	 * SAVE COMMENT
	 */
	public function save_comment(){
		/*echo "<pre>";
		print_r($_POST);
		print_r($_FILES);
		exit;*/
		
		$comment_file = "";
		$file_name = "";
		if(isset($_FILES['comment_file'])){
			// upload the file and save the new name
			$comment_file = $this->common->upload_file_single($_FILES['comment_file'],"comments");
			$file_name = $_FILES['comment_file']['name'];
		}
		
		$user_info = get_value_from_session_cookie(USER_INFO);
		$fk_user_id = $user_info[SESS_USER_ID];
		
		$this->response=array();
		
		// now update the profile
		$this->params=new stdClass();
		$this->params->fk_row_id = $this->input->post('customer_id',true);
		$this->params->fk_user_id = $fk_user_id;
		$this->params->module = "C";		// C=Customer Comment
		$this->params->comments = $this->input->post('comments',true);
		
		$this->params->file_name = $file_name;
		$this->params->comment_file = $comment_file;
		
		$this->response=$this->customer_model->add_comment($this->params);
		
		echo $this->response;
		
	}
	
	/**
	 * EXPORT TO EXCEL
	 */
	public function export(){
		// first of all get a list of customers
		$list = $this->customer_model->get_customers_for_export();
		
		if(!empty($list)){
			
			$filename = 'curbee_customers.csv';
			
			header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename='.$filename);
			
			$fp = fopen('php://output', 'wb');
			
			// put headings
			$data = array("CUSTOMER NAME", "CONTACT NAME", "EMAIL", "PHONE", "ADDRESS LINE 1", "ADDRESS LINE 2", "CITY", "STATE", "COUNTRY", "FOLLOWUP DATE", "ACCOUNT MANAGER", "INDUSTRY");
			fputcsv($fp, $data);
			
			// put data
			foreach ($list as $each){
				fputcsv($fp, $each);
				//$tmp = array();
				//$tmp[] = implode(", ", $each);
				
				//$data = array_merge($data,$tmp);
			}
			
			/*foreach ( $data as $line ) {
			    $val = explode(",", $line);
			    fputcsv($fp, $val);
			}*/
			fclose($fp);
			
		}else{
			echo "No data available for export";
			exit;
		}
	}
	
	public function update_followup_date(){
		$this->response=array();
		
		// now update the profile
		$this->params=new stdClass();
		$this->params->id = $this->input->post('customer_id',true);
		$this->params->followup_date = $this->input->post('followup_date',true);
		
		$this->response=$this->customer_model->update_followup_date($this->params);
		echo $this->response;
	}
}
