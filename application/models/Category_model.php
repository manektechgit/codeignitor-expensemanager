<?php
class Category_model extends CI_Model {
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
	public function get_categories(){
		// return array instead of jason like we do for form submission. form submissions are jquery ajax submit , so have to return json data.
		// listing page do not require json data
		$response = array();
		
		$user_info = get_value_from_session_cookie(USER_INFO);
		$sess_role_id = $user_info[SESS_ROLE_ID];
		$sess_user_id = $user_info[SESS_USER_ID];
		
		// CONDITION
		$condition = " c.status!='".STATUS_DELETE."'";
		
		// order by
		$condition.=" ORDER BY name";
		
		$list = $this->common->get_fields("categories c","c.*",$condition);
		
		if(!empty($list)){
			$response = $list;
		}
		
		return $response;
	}
	
	/**
	 * ADD/UPDATE
	 */
	public function add_update_category($params){
		//$this->load->model('Sendmail_model', 'sendmail');
		
		$response = array();
		$response['success']=0;
		$response['message']="Something went wrong. Please try again";
		
		$user_info = get_value_from_session_cookie(USER_INFO);
		$sess_role_id = $user_info[SESS_ROLE_ID];
		$sess_user_id = $user_info[SESS_USER_ID];
		
		$allow_save = true;
		
		// $data_add_update will be used for customers, as because the email address is editable. and there are no passwords.
		$data_add_update = array();
		$data_add_update['name'] = $params->name;
		
		// Category Check. if category exists, then don't allow to add new
		$condition_check = "name='".$params->email."' AND status!='".STATUS_DELETE."'";
		if(isset($params->id) && !empty($params->id)){
			// check for other category for the same name
			$condition_check.=" AND id!='".$params->id."'";
		}
		$chk_record = $this->common->get_field("categories","id",$condition_check);
		if(!empty($chk_record)){
			
			$allow_save = false;
			
			// throw an error
			$response['message']="Category already exists!";
		}
		
		if($allow_save){
			// check if the id is not empty, then update the existing record, else save new
			if(isset($params->id) && !empty($params->id)){
				// update
				$this->db->where('id', $params->id);
				if($this->db->update('categories',$data_add_update)){
					$response['success']=1;
					$response['message']="Category updated successfully";
				}
			}else{
				if($this->db->insert('categories',$data_add_update)){
					$response['success']=1;
					$response['message']="Category added successfully";
				}
			}
		}

		return json_encode($response,true);
	}
}