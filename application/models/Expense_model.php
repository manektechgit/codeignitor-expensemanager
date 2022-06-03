<?php
class Expense_model extends CI_Model {
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
	public function get_expenses(){
		// return array instead of jason like we do for form submission. form submissions are jquery ajax submit , so have to return json data.
		// listing page do not require json data
		$response = array();
		
		$user_info = get_value_from_session_cookie(USER_INFO);
		$sess_role_id = $user_info[SESS_ROLE_ID];
		$sess_user_id = $user_info[SESS_USER_ID];
		
		// CONDITION
		$condition = " e.status!='".STATUS_DELETE."'";

		// get category name associated with income/expense
		$condition.=" AND c.id=e.fk_category_id";
		
		// order by
		$condition.=" ORDER BY e.expense_date DESC";
		
		$list = $this->common->get_fields("expense e, categories c","e.*,c.name as category",$condition);
		
		if(!empty($list)){
			$response = $list;
		}
		
		return $response;
	}
	
	/**
	 * ADD/UPDATE
	 */
	public function add_update_expense($params){
		
		$response = array();
		$response['success']=0;
		$response['message']="Something went wrong. Please try again";
		
		$user_info = get_value_from_session_cookie(USER_INFO);
		$sess_role_id = $user_info[SESS_ROLE_ID];
		$sess_user_id = $user_info[SESS_USER_ID];
		
		$allow_save = true;
		
		// $data_add_update will be used for customers, as because the email address is editable. and there are no passwords.
		$data_add_update = array();
		$data_add_update['fk_category_id'] = $params->fk_category_id;
		$data_add_update['title'] = $params->title;
		$data_add_update['amount'] = $params->amount;
		$data_add_update['expense_date'] = $params->expense_date;
		$data_add_update['description'] = $params->description;
		
		if($allow_save){
			// check if the id is not empty, then update the existing record, else save new
			if(isset($params->id) && !empty($params->id)){
				// update
				$this->db->where('id', $params->id);
				if($this->db->update('expense',$data_add_update)){
					$response['success']=1;
					$response['message']="Expense updated successfully";
				}
			}else{
				if($this->db->insert('expense',$data_add_update)){
					$response['success']=1;
					$response['message']="Expense added successfully";
				}
			}
		}

		return json_encode($response,true);
	}
}