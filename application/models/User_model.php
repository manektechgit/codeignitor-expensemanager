<?php
class User_model extends CI_Model {
	private $request;
	private $response;
	public function __construct(){
		$this->request=new stdClass();
			
	}
	
	/**
	 * LOGIN
	 */
	public function login_submit($params){
		$response = array();
		$response['success']=0;
		$response['message']="Invalid email/password";
		
		// START LOGIN
		$encrypted_password = encrypt_simple($params->password);
		$condition = "email='".$params->email."' AND password='".$encrypted_password."' AND status!='".STATUS_DELETE."'";
		$result = $this->common->get_fields("users","id,fk_role_id,first_name,last_name,email,phone,profile_image,created,modified,status",$condition);
		
		if(!empty($result)){
			if($result[0]['status']==STATUS_INACTIVE){
				$response['message']="Your account is not Active";
			}else if($result[0]['status']==STATUS_ACTIVE){
				$response['success']=1;
			
				$user_info[SESS_USER_ID]=$result[0]['id'];
				$user_info[SESS_ROLE_ID]=$result[0]['fk_role_id'];
				$user_info[SESS_FIRST_NAME]=$result[0]['first_name'];
				$user_info[SESS_LAST_NAME]=$result[0]['last_name'];
				$user_info[SESS_EMAIL]=$result[0]['email'];
				$user_info[SESS_PHONE]=$result[0]['phone'];
				$user_info[SESS_PROFILE_IMAGE]=$result[0]['profile_image'];
				
				set_value_in_session_cookie(USER_INFO, $user_info);
			}
			
		}
		
		return json_encode($response,true);
	}
	
	/**
	 * UPDATE PROFILE
	 */
	public function update_profile($params){
		$response = array();
		$response['success']=0;
		$response['message']="Something went wrong. Please try again";
		
		// get logged in user info and later will update the value if first name and email and phone and profile image is changed
		$user_info = get_value_from_session_cookie(USER_INFO);
		
		$user_id = $user_info[SESS_USER_ID];
		
		$data_update['first_name'] = $params->first_name;
		$data_update['last_name'] = $params->last_name;
		$data_update['email'] = $params->email;
		$data_update['phone'] = $params->phone;
		
		$this->db->where('id', $user_id);
		if($this->db->update('users',$data_update)){
			$response['success']=1;
			$response['message']="Profile updated successfully";
			
			// also update the session value
			$user_info[SESS_FIRST_NAME]=$data_update['first_name'];
			$user_info[SESS_LAST_NAME]=$data_update['last_name'];
			$user_info[SESS_PHONE]=$data_update['phone'];
			
			set_value_in_session_cookie(USER_INFO, $user_info);
		}
		
		return json_encode($response,true);
	}
	
	/**
	 * UPDATE PASSWORD
	 */
	public function update_password($params){
		$response = array();
		$response['success']=0;
		$response['message']="Something went wrong. Please try again";
		
		// get logged in user info and later will update the value if first name and email and phone and profile image is changed
		$user_info = get_value_from_session_cookie(USER_INFO);
		
		$user_id = $user_info[SESS_USER_ID];
		
		// FIRST LETS CHECK IF THE CURRENT PASSWORD MATCHES WITH THE CURRENT PASSWORD FROM DB
		$current_password = encrypt_simple($params->current_password);
		$new_password = encrypt_simple($params->new_password);
		$verify_password = encrypt_simple($params->verify_password);
		
		$current_password_from_db = $this->common->get_field("users","password","id='".$user_id."'");
		if($current_password!=$current_password_from_db){
			$response['message']="Current Password do not match.";
		}else if($new_password!=$verify_password){
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
}