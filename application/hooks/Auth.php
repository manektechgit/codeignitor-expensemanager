<?php
class Auth extends CI_Hooks{
	private $CI;
	private $last_config_sync_time;
	private $recent_config_sync_time;
	private $server_configs;
	public function __construct(){ 
		$this->CI =&get_instance();
		$this->CI->load->library(array('session','user_agent'));
		$this->CI->load->model('common_model');
	}
	
	public function index(){
		// setting up browser UDID
		$this->set_browser_udid();
		
		// setting session variables will be used in application
		//$this->set_session_vars();
		
		//check for page access authencated
		$this->CI->common->check_page_authenticated();
	}
    
	public function set_browser_udid(){
		if(is_null(get_cookie(BROWSER_UDID_SETTING_NAME))){
			$browser_udid=random_string('alnum',40);
			define('BROWSER_UDID',$browser_udid);
			set_cookie(BROWSER_UDID_SETTING_NAME,$browser_udid,time()+31556926);						
		}else{
			define('BROWSER_UDID',get_cookie(BROWSER_UDID_SETTING_NAME));
		}		
	}
}