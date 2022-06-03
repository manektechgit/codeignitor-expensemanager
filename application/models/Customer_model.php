<?php
class Customer_model extends CI_Model {
	private $request;
	private $response;
	public function __construct(){
		$this->request=new stdClass();
	}
	
	/**
	 * LOGIN
	 * @param
	 * fk_role_id
	 */
	public function get_customers(){
		// return array instead of jason like we do for form submission. form submissions are jquery ajax submit , so have to return json data.
		// listing page do not require json data
		$response = array();
		
		$user_info = get_value_from_session_cookie(USER_INFO);
		$sess_role_id = $user_info[SESS_ROLE_ID];
		$sess_user_id = $user_info[SESS_USER_ID];
		
		// CONDITION
		$condition = " c.status!='".STATUS_DELETE."'";
		
		// get account manager name associated with customer
		$condition.=" AND u.id=c.fk_accountmanager_id";
		
		if($sess_role_id==ROLE_ACCOUNT_MANAGER){
			// only get associated customers
			$condition.=" AND c.fk_accountmanager_id='".$sess_user_id."'";
		}
		
		// order by
		$condition.=" ORDER BY name";
		
		$list = $this->common->get_fields("customers c, users u","c.*,CONCAT(u.first_name,' ',u.last_name) as accountmanager_name",$condition);
		
		if(!empty($list)){
			$response = $list;
		}
		
		return $response;
	}
	
	/**
	 * get customers for export
	 * Enter description here ...
	 */
	public function get_customers_for_export(){
		// return array instead of jason like we do for form submission. form submissions are jquery ajax submit , so have to return json data.
		// listing page do not require json data
		$response = array();
		
		$user_info = get_value_from_session_cookie(USER_INFO);
		$sess_role_id = $user_info[SESS_ROLE_ID];
		$sess_user_id = $user_info[SESS_USER_ID];
		
		// CONDITION
		$condition = " c.status!='".STATUS_DELETE."'";
		
		// get account manager name associated with customer
		$condition.=" AND u.id=c.fk_accountmanager_id";
		$condition.=" AND i.id=c.fk_industry_id";
		
		
		if($sess_role_id==ROLE_ACCOUNT_MANAGER){
			// only get associated customers
			$condition.=" AND c.fk_accountmanager_id='".$sess_user_id."'";
		}
		
		// order by
		$condition.=" ORDER BY name";
		
		$list = $this->common->get_fields("customers c, users u, flagindustry i","c.name,c.contact_name,c.email,c.phone,c.address_line_1,c.address_line_2,c.city,c.state,c.country,c.followup_date,CONCAT(u.first_name,' ',u.last_name) as accountmanager_name,i.name AS industry",$condition);
		
		if(!empty($list)){
			$response = $list;
		}
		
		return $response;
	}
	
	/**
	 * ADD/UPDATE PROFILE
	 */
	public function add_update_customer($params){
		//$this->load->model('Sendmail_model', 'sendmail');
		
		$response = array();
		$response['success']=0;
		$response['message']="Something went wrong. Please try again";
		
		$user_info = get_value_from_session_cookie(USER_INFO);
		$sess_role_id = $user_info[SESS_ROLE_ID];
		$sess_user_id = $user_info[SESS_USER_ID];
		
		$send_new_customer_add_email = false;
		$allow_save = true;
		
		// $data_add_update will be used for customers, as because the email address is editable. and there are no passwords.
		$data_add_update = array();
		$data_add_update['name'] = $params->name;
		$data_add_update['contact_name'] = $params->contact_name;
		$data_add_update['fk_industry_id'] = $params->fk_industry_id;
		$data_add_update['fk_accountmanager_id'] = $params->fk_accountmanager_id;
		$data_add_update['email'] = $params->email;
		$data_add_update['phone'] = $params->phone;
		$data_add_update['address_line_1'] = $params->address_line_1;
		$data_add_update['address_line_2'] = $params->address_line_2;
		$data_add_update['city'] = $params->city;
		$data_add_update['state'] = $params->state;
		$data_add_update['country'] = $params->country;
		$data_add_update['followup_date'] = $params->followup_date;
		
		// EMAIL Check. if email exists, then don't allow to add new
		$condition_check_email = "email='".$params->email."' AND status!='".STATUS_DELETE."'";
		if(isset($params->id) && !empty($params->id)){
			// check for other customers for the email
			$condition_check_email.=" AND id!='".$params->id."'";
		}
		$chk_email = $this->common->get_field("customers","id",$condition_check_email);
		if(!empty($chk_email)){
			
			$allow_save = false;
			
			// throw an error
			$response['message']="Email already exists!";
		}
		
		// check customer name
		$condition_check_name = "name='".$params->name."' AND status!='".STATUS_DELETE."'";
		if(isset($params->id) && !empty($params->id)){
			// check for other customers for the email
			$condition_check_name.=" AND id!='".$params->id."'";
		}
		$chk_name = $this->common->get_field("customers","id",$condition_check_name);
		if(!empty($chk_name)){
			
			$allow_save = false;
			
			// throw an error
			$response['message']="Customer name already exists!";
		}
		
		if($allow_save){
			// check if the id is not empty, then update the existing record, else save new
			if(isset($params->id) && !empty($params->id)){
				// update
				$this->db->where('id', $params->id);
				if($this->db->update('customers',$data_add_update)){
					$response['success']=1;
					$response['message']="Customer updated successfully";
				}
			}else{
				$data_add_update['fk_user_id'] = $sess_user_id;
				
				if($this->db->insert('customers',$data_add_update)){
					$send_new_customer_add_email = true;
					
					$response['success']=1;
					$response['message']="Customer added successfully";
				}
			}
		}
		// send email when any new profile is created
		/*if($send_new_customer_add_email){
			// send email to the added profile to intimate them for using the portal
			$arrDetails['name'] = $params->first_name;
			$arrDetails['portal_url'] = base_url();
			$arrDetails['profile_password'] = $params->new_password;
			$arrDetails['profile_email'] = $params->email;
			
			$this->sendmail->send_mail_profile_created($arrDetails);
		}*/
		return json_encode($response,true);
	}
	
	/**
	 * ADD COMMENT
	 */
	public function add_comment($params){
		//$this->load->model('Sendmail_model', 'sendmail');
		
		$response = array();
		$response['success']=0;
		$response['message']="Something went wrong. Please try again";
		
		$data_add = array();
		$data_add['fk_row_id'] = $params->fk_row_id;
		$data_add['fk_user_id'] = $params->fk_user_id;
		$data_add['module'] = $params->module;
		$data_add['comments'] = $params->comments;
				
		if($this->db->insert('comments',$data_add)){
			
			$comment_id = $this->db->insert_id();
			
			if(isset($params->comment_file) && !empty($params->comment_file)){
				// add entry in all files
				$data_add_file = array();
				$data_add_file['fk_user_id']=$params->fk_user_id;
				$data_add_file['module']="C";		// C=Comment Files
				$data_add_file['fk_row_id'] = $comment_id;
				$data_add_file['file_name']=$params->file_name;
				$data_add_file['file']=$params->comment_file;
				$this->db->insert('all_files',$data_add_file);
			}
			
			$response['success']=1;
			$response['message']="Comment added successfully";
		}
		
		return json_encode($response,true);
	}
	
	public function update_followup_date($params){
		$data_update['followup_date'] = $params->followup_date;
		$this->db->where('id', $params->id);
		$this->db->update('customers',$data_update);
		
		$response['success']=1;
		$response['message']="Follow-up Date updated successfully";
		
		return json_encode($response,true);
	}
}