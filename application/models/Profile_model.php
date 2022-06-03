<?php
class Profile_model extends CI_Model {
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
	public function get_users($params){
		// return array instead of jason like we do for form submission. form submissions are jquery ajax submit , so have to return json data.
		// listing page do not require json data
		$response = array();
		
		// get list of all users who has the fk_role_id matching with param
		
		$condition = " fk_role_id='".$params->fk_role_id."' AND status!='".STATUS_DELETE."'";
		
		// order by
		$condition.=" ORDER BY first_name,last_name";
		$list = $this->common->get_fields("users","*",$condition);
		
		if(!empty($list)){
			$response = $list;
		}
		
		return $response;
	}
	
	/**
	 * ADD/UPDATE PROFILE
	 */
	public function add_update_profile($params){
		//$this->load->model('Sendmail_model', 'sendmail');
		
		$response = array();
		$response['success']=0;
		$response['message']="Something went wrong. Please try again";
		
		$send_new_profile_add_email = false;
		
		if(isset($params->id) && !empty($params->id)){
			// update profile
			// we will not update the password when edit profile. it will have separate page
			$data_update = array();
			$data_update['first_name'] = $params->first_name;
			$data_update['last_name'] = $params->last_name;
			// we will not update the email as well
			$data_update['email'] = $params->email;
			$data_update['phone'] = $params->phone;
			
			$this->db->where('id', $params->id);
			if($this->db->update('users',$data_update)){
				$response['success']=1;
				$response['message']="Profile updated successfully";
			}
			
		}else{
			// add profile
			$data_add = array();
			$data_add['fk_role_id'] = $params->fk_role_id;
			$data_add['first_name'] = $params->first_name;
			$data_add['last_name'] = $params->last_name;
			$data_add['email'] = $params->email;
			$data_add['phone'] = $params->phone;
			$data_add['password'] = encrypt_simple($params->new_password);
			
			if($params->new_password!=$params->verify_password){
				$response['message']="Verify Password do not match.";
			}else{
				
				// also check if the email entered is already existing, then don't allow to add the same email again.
				$id = $this->common->get_field("users","id","email='".$params->email."' AND status!='".STATUS_DELETE."'");
				if(!empty($id)){
					// throw an error
					$response['message']="Email already exists!";
				}else{
					// add a new profile
					if($this->db->insert('users',$data_add)){
						$send_new_profile_add_email = true;
						
						$response['success']=1;
						$response['message']="Profile added successfully";
					}
				}
			}
		}
		
		// send email when any new profile is created
		/*if($send_new_profile_add_email){
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
	 * UPDATE PASSWORD
	 */
	public function update_password($params){
		$response = array();
		$response['success']=0;
		$response['message']="Something went wrong. Please try again";
		
		$user_id = $params->id;
		
		// FIRST LETS CHECK IF THE CURRENT PASSWORD MATCHES WITH THE CURRENT PASSWORD FROM DB
		$new_password = encrypt_simple($params->new_password);
		$verify_password = encrypt_simple($params->verify_password);
		
		if($new_password!=$verify_password){
			$response['message']="Verify Password do not match.";
		}else{
			// everything is okay. change the password
			$data_update['password'] = $new_password;
			
			$this->db->where('id', $user_id);
			if($this->db->update('users',$data_update)){
				$response['success']=1;
				$response['message']="Password changed successfully";
			}
		}
		
		return json_encode($response,true);
	}
	
	/**
	 * Get list of companies
	 */
	public function get_company($params){
		
		// return array instead of jason like we do for form submission. form submissions are jquery ajax submit , so have to return json data.
		// listing page do not require json data
		$response = array();
		
		// get list of all users who has the fk_role_id matching with param
		$condition = " u.id=cm.fk_accountmanager_id AND cm.status!='".STATUS_DELETE."' AND u.status!='".STATUS_DELETE."'";
		
		if($params->fk_role_id == ROLE_ACCOUNT_MANAGER){
			// also check condition for if this account manager is set as proxy to another am as well or not.
			$today = date("Y-m-d");
			$arrProxyForCompanyIds = $this->common->get_list("proxy","from_user_id","fk_company_id","to_user_id='".$params->fk_user_id."' AND till_date>='".$today."' AND status!='".STATUS_DELETE."'");
			if(!empty($arrProxyForCompanyIds)){
				$condition.=" AND (cm.fk_accountmanager_id='".$params->fk_user_id."' OR cm.id IN (".implode(",", $arrProxyForCompanyIds).") )";
			}else{
				// if its account manager, then show only their associated customers
				$condition.=" AND cm.fk_accountmanager_id='".$params->fk_user_id."'";
			}
		}
		
		$condition .= " ORDER BY cm.created DESC";
		$query = "SELECT cm.*,u.first_name,u.last_name FROM company_master cm, users u WHERE".$condition;
		$list = $this->db->query($query)->result_array();
		
		if(!empty($list)){
			$response = $list;
		}
		
		return $response;
	}
	
	/**
	 * ADD/UPDATE COMPANY
	 */
	public function add_update_company($params){
		//$this->load->model('Sendmail_model', 'sendmail');
		
		$response = array();
		$response['success']=0;
		$response['message']="Something went wrong. Please try again";
		
		if(isset($params->id) && !empty($params->id)){
			// update profile
			
			// check if the company name changed or account manager changed.
			$existing_company_name = $this->common->get_field("company_master","name","id='".$params->id."'");
			$existing_account_manager_id = $this->common->get_field("company_master","fk_accountmanager_id","id='".$params->id."'");
			
			// if company name is changed and account manager is same, then send change of company email
			if($params->name!=$existing_company_name && $params->fk_accountmanager_id==$existing_account_manager_id){
				// send email to account manager for change of company name
				$data_email['old_company_name'] = $existing_company_name;
				$data_email['new_company_name'] = $params->name;
				$data_email['fk_accountmanager_id'] = $params->fk_accountmanager_id;
				
				// send email
				$this->sendmail->send_mail_company_name_changed($data_email);
				
			}else if($params->fk_accountmanager_id==$existing_account_manager_id){
				// else if account manager changed, then send new customer assigned mail
				$data_email['name'] = $params->name;
				$data_email['fk_accountmanager_id'] = $params->fk_accountmanager_id;
				
				// send email to account manager of new assignment
				$this->sendmail->send_mail_company_assigned($data_email);
			}
			
			// we will not update the password when edit profile. it will have separate page
			$data_update = array();
			$data_update['name'] = $params->name;
			$data_update['fk_accountmanager_id'] = $params->fk_accountmanager_id;
			
			$this->db->where('id', $params->id);
			if($this->db->update('company_master',$data_update)){
				$response['success']=1;
				$response['message']="Company updated successfully";
			}
			
		}else{
			// add profile
			$data_add = array();
			$data_add['name'] = $params->name;
			$data_add['fk_accountmanager_id'] = $params->fk_accountmanager_id;
			
			// also check if the email entered is already existing, then don't allow to add the same email again.
			$id = $this->common->get_field("company_master","id","name='".$params->name."' AND status!='".STATUS_DELETE."'");
			if(!empty($id)){
				// throw an error
				$response['message']="Company already exists!";
			}else{
				// add a new profile
				if($this->db->insert('company_master',$data_add)){
					$response['success']=1;
					$response['message']="Company added successfully";
					
					
					// notify account manager for assigning a new company
					$this->sendmail->send_mail_company_assigned($data_add);
				}
			}
		}
		return json_encode($response,true);
	}
	
	/**
	 * Get Profile Report Data.
	 * following function will get profile report data for the current year.
	 */
	public function get_profile_report_data($filters){
		$arrstatus = array("A"=>"Active","I"=>"Inactive","D"=>"Deleted");	// 0 active is for customer
		$arrmonths = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May","06"=>"June","07"=>"July","08"=>"August","09"=>"September",10=>"October",11=>"November",12=>"December");
		
		// now get all the details of the user
		$data = array();
		$overalltotal = 0;
		$arrProfiles = array();
    	$percentage_profile = array();
    	$tmp_arrX = array();
    	
    	$data['active_inactive_users'] = (isset($filters['active_inactive_users']))?$filters['active_inactive_users']:"-1";	// -1 means all/ otherwise A/I/D
    	$data['start_date'] = (isset($filters['start_date']))?$filters['start_date']:"01/01/".date("Y");//(isset($_POST['start_date']))?$_POST['start_date']:date("m")."/01/".date("Y");
    	$data['end_date'] = (isset($filters['end_date']))?$filters['end_date']:"12/31/".date("Y");//(isset($_POST['end_date']))?$_POST['end_date']:date("m/d/Y",strtotime('last day of this month', time()));
    	
		// get count of total user profiles group by status
    	$condition = "created BETWEEN '".date("Y-m-d H:i:s",strtotime($data['start_date']))."' AND '".date("Y-m-d",strtotime($data['end_date']))." 11:59:59'";
		if(isset($data['active_inactive_users']) && $data['active_inactive_users']!="-1"){
    		$condition .= " AND status='".$data['active_inactive_users']."'";
    	}
    	
    	// get records
    	$this->db->select("COUNT(id) AS total,status,MONTH(`created`) AS month");
		$this->db->where($condition);
		$this->db->group_by("status,MONTH(`created`)");
		$query = $this->db->get("users");
		
		$profiles = $query->result();
		
		foreach ($profiles as $each_profile){
    		$tmpmonth = (strlen($each_profile->month)==1)?"0".$each_profile->month:$each_profile->month;
    		$arrProfiles[$tmpmonth][$arrstatus[$each_profile->status]] = intval($arrProfiles[$tmpmonth][$arrstatus[$each_profile->status]])+intval($each_profile->total);
    		$percentage_profile[$arrstatus[$each_profile->status]] = intval($percentage_profile[$arrstatus[$each_profile->status]])+intval($each_profile->total);
    		
    		$overalltotal = intval($overalltotal)+intval($each_profile->total);
    	}
		
    	$data['arrProfiles'] = $arrProfiles;
    	
    	$final_data = array();
    	
    	$final_data["months"] = $arrmonths;
    	foreach ($arrmonths as $eachmonthnumber=>$eachmonthname){
    		$final_data[$arrstatus[STATUS_ACTIVE]][$eachmonthnumber] = isset($arrProfiles[$eachmonthnumber][$arrstatus[STATUS_ACTIVE]])?$arrProfiles[$eachmonthnumber][$arrstatus[STATUS_ACTIVE]]:"0";
    		$final_data[$arrstatus[STATUS_INACTIVE]][$eachmonthnumber] = isset($arrProfiles[$eachmonthnumber][$arrstatus[STATUS_INACTIVE]])?$arrProfiles[$eachmonthnumber][$arrstatus[STATUS_INACTIVE]]:"0";
    		$final_data[$arrstatus[STATUS_DELETE]][$eachmonthnumber] = isset($arrProfiles[$eachmonthnumber][$arrstatus[STATUS_DELETE]])?$arrProfiles[$eachmonthnumber][$arrstatus[STATUS_DELETE]]:"0";
    	}
    	
		if($data['active_inactive_users']=="-1"){
    		$data['percentage']['active'] = (is_float(($percentage_profile[$arrstatus[STATUS_ACTIVE]]*100)/$overalltotal))?number_format(($percentage_profile[$arrstatus[STATUS_ACTIVE]]*100)/$overalltotal,2)."%":($percentage_profile[$arrstatus[STATUS_ACTIVE]]*100)/$overalltotal."%";
	    	$data['percentage']['inactive'] = (is_float(($percentage_profile[$arrstatus[STATUS_INACTIVE]]*100)/$overalltotal))?number_format(($percentage_profile[$arrstatus[STATUS_INACTIVE]]*100)/$overalltotal,2)."%":($percentage_profile[$arrstatus[STATUS_INACTIVE]]*100)/$overalltotal."%";
	    	$data['percentage']['deleted'] = (is_float(($percentage_profile[$arrstatus[STATUS_DELETE]]*100)/$overalltotal))?number_format(($percentage_profile[$arrstatus[STATUS_DELETE]]*100)/$overalltotal,2)."%":($percentage_profile[$arrstatus[STATUS_DELETE]]*100)/$overalltotal."%";
    	}else{
    		if($data['active_inactive_users']==STATUS_ACTIVE){
    			$data['percentage']['active'] = (is_float(($percentage_profile[$arrstatus[STATUS_ACTIVE]]*100)/$overalltotal))?number_format(($percentage_profile[$arrstatus[STATUS_ACTIVE]]*100)/$overalltotal,2)."%":($percentage_profile[$arrstatus[STATUS_ACTIVE]]*100)/$overalltotal."%";
    		}else if($data['active_inactive_users']==STATUS_INACTIVE){
    			$data['percentage']['inactive'] = (is_float(($percentage_profile[$arrstatus[STATUS_INACTIVE]]*100)/$overalltotal))?number_format(($percentage_profile[$arrstatus[STATUS_INACTIVE]]*100)/$overalltotal,2)."%":($percentage_profile[$arrstatus[STATUS_INACTIVE]]*100)/$overalltotal."%";
    		}else if($data['active_inactive_users']==STATUS_DELETE){
    			$data['percentage']['deleted'] = (is_float(($percentage_profile[$arrstatus[STATUS_DELETE]]*100)/$overalltotal))?number_format(($percentage_profile[$arrstatus[STATUS_DELETE]]*100)/$overalltotal,2)."%":($percentage_profile[$arrstatus[STATUS_DELETE]]*100)/$overalltotal."%";
    		}
    	}
    	
    	//echo "<pre>"; print_r($final_data); exit;
    	
		return $final_data;
	}
	
}