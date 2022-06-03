<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


// CHECK IF THE USER HAS ALREADY BEEN LOGGED IN ? WHEN USER TRIES TO ACCESS THE LOGIN PAGE EVEN THEY HAVE ALREADY LOGGED IN
if (!function_exists('_is_logged_in')){
	function _is_logged_in(){
		$user_info = get_value_from_session_cookie(USER_INFO);
	    if (isset($user_info) && !empty($user_info)){
	        return true;
	    }
	    return false;
	}
}

  /* 
	 * It will retrive session value
	 */
if (!function_exists('get_value_from_session_cookie')){
	 function get_value_from_session_cookie($name,$set_cookie=false){
	 	$CI = & get_instance();
		$return=NULL;
		if($CI->session->userdata($name)!==NULL){
			$return=$CI->session->userdata($name);
		}else{
			if(!is_null(get_cookie($name)) && false){
				$CI->session->set_userdata($name,get_cookie($name));
				$return=get_cookie($name);
			}else{
				$CI->session->set_userdata($name,0);
				$return=0;			
			}
		}
		return $return;	
	}
}		
	/* 
	 * It will set session value
	 */
if (!function_exists('set_value_in_session_cookie')){
	 function set_value_in_session_cookie($name,$value,$set_cookie=false,$set_in_config=true){
	 	$CI = & get_instance();
		$CI->session->set_userdata($name,$value);	
		if($set_cookie){
			set_cookie($name,$value,time()+31556926);
		}
		if($set_in_config){
			$CI->config->set_item($name,$value);
		}
	}
}
	
	/* 
	 * It will unset session 
	 */
if (!function_exists('delete_session_cookie')){
	 function delete_session_cookie($name,$set_in_config=true){
	 	$CI = & get_instance();
		$CI->session->unset_userdata($name);	
		delete_cookie($name);
		if($set_in_config)
		{
			$CI->config->set_item($name,false);
		}
	}
}

/**
 * ABOVE ARE SYNERGYTECHPORTAL RELATED FUNCTIONS
 */

/**
 * following function will read the given server config data.
 */
if (!function_exists('read_server_config')){	
	function read_server_config(){
		$json = file_get_contents(config_item('server_config_url'));
		$json_to_array = json_decode($json,true);
		return $json_to_array;
	}
}

/**
 * following function return last-modified header of given file
 */
if (!function_exists('get_last_modified_date')){
	function get_last_modified_date($file_path) {
	    $h = get_headers($file_path, 1);
	    if(!empty($h)){
		    if (stristr($h[0], '200')) {
		        foreach($h as $k=>$v) {
		            if(strtolower(trim($k))=="last-modified") return strtotime($v);
		        }
		    }
	    }else{return false;}
	}	
}


/**
 * Display post data filled
 * $var = variable to check
 * return = value to be displayed
 */
if (!function_exists('fill_data')){
	function fill_data($var) {
		if(!empty($var)) {
			return htmlentities(trim($var));
		} else {
			return "";
		}
	}
}



/**
 * following function will convert date to specific timezone
 * 
 */
if (!function_exists('get_converted_date')){
	function get_converted_date($timestamp,$target_tz,$dst=false) {
		$timezone_identifier_arr=array(
		'-12:00'=>'UM12',
		'-11:00'=>'UM11',
		'-10:00'=>'UM10',
		'-09:30'=>'UM95',
		'-09:00'=>'UM9',
		'-08:00'=>'UM8',
		'-07:00'=>'UM7',
		'-06:00'=>'UM6',
		'-05:00'=>'UM5',
		'-04:30'=>'UM45',
		'-04:00'=>'UM4',
		'-03:30'=>'UM35',
		'-03:00'=>'UM3',
		'-02:00'=>'UM2',
		'-01:00'=>'UM1',
		'+00:00'=>'UTC',
		'+01:00'=>'UP1',
		'+02:00'=>'UP2',
		'+03:00'=>'UP3',
		'+03:30'=>'UP35',
		'+04:00'=>'UP4',
		'+04:30'=>'UP45',
		'+05:00'=>'UP5',
		'+05:30'=>'UP55',
		'+06:00'=>'UP6',
		'+07:00'=>'UP7',
		'+08:00'=>'UP8',
		'+09:00'=>'UP9',
		'+09:30'=>'UP95',						
		'+10:00'=>'UP10',
		'+11:00'=>'UP11',
		'+12:00'=>'UP12'			
		);	
			
		$tz=(isset($timezone_identifier_arr[$target_tz]))?$timezone_identifier_arr[$target_tz]:'UP55';
		$gmt = local_to_gmt($timestamp);
		return gmt_to_local($gmt,$tz,$dst);
	}
}

if (!function_exists('curl_request')){
     function curl_request($request,$json_array=false){
    	// prepare curl request
		$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $request->api_url);
    	if(!empty($request->headers)){
    		curl_setopt($ch, CURLOPT_HTTPHEADER, $request->headers);
    		curl_setopt($ch, CURLOPT_HEADER, TRUE);	    	
    	}
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);	    
		if($request->method == "DEL"){
		  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DEL");
		  curl_setopt($ch, CURLOPT_POSTFIELDS, $request->rpc);
		}else if ($request->method == "GET"){
		  $url.=(!empty($request->rpc))?"?".$request->rpc:"";
		}else if ($request->method == "POST"){
		  curl_setopt($ch, CURLOPT_POST, TRUE);
		  if($json_array){
		  	 curl_setopt($ch, CURLOPT_POSTFIELDS, $request->rpc);
		  }else{
		   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request->rpc,true));
		  }
		}else if ($request->method == "PUT"){
		  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		  curl_setopt($ch, CURLOPT_POSTFIELDS, $request->rpc);
		}
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);				
		// fire request	
	    $response = curl_exec($ch);
	   /* $ee       = curl_getinfo($ch);
	    $ee       = curl_error($ch);
	    echo '<pre>';print_r($ee);
	    print_r($response);exit;*/
	    curl_close($ch);
	    if(isset($request->return_headers)){
	    	$response=str_replace("HTTP/1.1 100 Continue\r\n","",$response);
	    	list($headers, $res) = explode("\r\n\r\n",trim($response),2);	    	
	    	$response=NULL;	    	
			$headers = explode("\n", $headers);
			$response['response']=$res;
			$response['headers']=$headers;
	    }
	    return $response;
    }
}

	 /**
     * Sets text color according to app color in header(When we don't have event color)
     * @param string color_code
     **/

if (!function_exists('isTooLightYIQ')){
   function isTooLightYIQ($hex) {
   		// color in hex, without any prefixing #!
   		$hex = str_replace("#","",$hex);
   		
	    $r = hexdec(substr($hex,0,2));
	    $g = hexdec(substr($hex,2,2));
	    $b = hexdec(substr($hex,4,2));
	
	    if($r + $g + $b > 382){
	    	//Light color, use dark font
	        return true;
	    }else{ 
	    	//dark color, use bright font
	        return false;
	    }
	} 
}

	/* This function will return string with limited text */
if (!function_exists('show_limited_text')){
	 function show_limited_text($text="", $length=LIMITED_TEXT_LENGTH) {
        $return_string = $text;

        if(!empty($text) && !empty($length)) {
            if(strlen($text) > $length) {
                $return_string = substr($text, 0, $length)."...";
            }
        }

        return $return_string;
    }
}

	 /* 
	 *  Returns sorted multidimentional array
	 */
if (!function_exists('array_orderby')){
	function array_orderby()
	{
	    $args = func_get_args();
	    $data = array_shift($args);
	    foreach ($args as $n => $field) {
	        if (is_string($field)) {
	            $tmp = array();
	            foreach ($data as $key => $row)
	                $tmp[$key] = $row[$field];
	            $args[$n] = $tmp;
	            }
	    }
	    $args[] = &$data;
	    call_user_func_array('array_multisort', $args);
	    return array_pop($args);
	}
}

/** this will convert php date format to ISO type format*/
if (!function_exists('convertFormat')){
	function convertFormat($format,$lang_id){
		$formatConvertArray=		 array(
		 'D'=>'%a',
		 'l'=>'%A',
		 'd'=>'%d',
		 'j'=>'%e',
		 'M'=>'%b',
		 'F'=>'%B',
		 'm'=>'%m',
		 'n'=>'%l',
		 'y'=>'%y',
		 'Y'=>'%Y',
		 'H'=>'%H',
		 'h'=>'%I',
		 'g'=>'%l',
		 'G'=>'%H',
		 'i'=>'%M',
		 's'=>'%S',
		 'A'=>'%p',
		 'a'=>'%P',
		 ' '=>' ',
		 );
		 //some language not provides am/pm so replace it manually
		if(in_array($lang_id, array(3,4,5,6,12))){$formatConvertArray['a']='#P#';$formatConvertArray['A']='#p#';	}
		$returnformat='';
		$format=str_split($format);		
		foreach ($format as $char){
			if(isset($formatConvertArray[$char])){
				$returnformat.=$formatConvertArray[$char];
			}else{
				$returnformat.=$char;
			}
		}
	return $returnformat;
	}
}

if (!function_exists('format_lang_date')){
	function format_lang_date($return_format, $date, $lang_id=NULL)
	{
		if(empty($lang_id)){
			$event_id	=	get_value_from_session_cookie(EVENT_ID);
			if(!empty($event_id)){
				$lang_id	=	get_value_from_session_cookie($event_id."_".LANGUAGE_ID);
			}
			if(empty($lang_id)){$lang_id=1;}
		}		
		//https://www.ibm.com/support/knowledgecenter/en/SSGSG7_6.3.4/com.ibm.itsm.srv.install.doc/r_nls_tables.html
		$_lang_codes_patterns=array(	
					1=>"en_US.utf8",//works
					2=>"zh_CN.utf8",//works
					3=>"es_ES.utf8",//ampm not working
					4=>"de_DE.utf8",//ampm not working
					5=>"fr_FR.utf8",//ampm not working
					6=>"pt_PT.utf8",//ampm not working
					7=>"ar_AE.utf8",//works
					8=>"hi_IN",//works
					9=>"gu_IN",//works
					10=>"zh_TW.utf8",//works
					11=>"tr_TR.utf8",//works
					12=>"ru_RU.utf8"//ampm not working				
					);
					
		if(intval($lang_id)>1){
			$return_format=convertFormat($return_format,$lang_id);
			$language_code_format = $_lang_codes_patterns[$lang_id];
			$language_code_format=($language_code_format);
			setlocale(LC_ALL , $language_code_format);
			
			//return $return_format;
			$newdate= strftime($return_format,strtotime($date));
			if(in_array($lang_id, array(3,4,5,6,12))){//if am/pm string not work then concat it.
				if( (strpos($return_format,'#p#'))!=false  ) {
					$am=((strtotime($date)%86400) < 43200 ? lang('lbl_AM') : lang('lbl_PM'));
					$newdate=str_replace('#p#', $am, $newdate);
				}
				if( (strpos($return_format,'#P#'))!=false  ) {
					$am=((strtotime($date)%86400) < 43200 ? lang('lbl_am') : lang('lbl_pm'));
					$newdate=str_replace("#P#", $am, $newdate);
				}
			}
			return $newdate;
		}else{
			return date($return_format,strtotime($date));
		}					
	}
}

if (!function_exists('check_feature_exists')){
	function check_feature_exists($feature_id){
		$event_id	=	get_value_from_session_cookie(EVENT_ID);
		if(!empty($event_id)){
			$feature_ids	=	get_value_from_session_cookie($event_id."_".FEATURE_LIST);
			if(!empty($feature_ids)){
				if(!is_array($feature_ids)){$feature_ids=json_decode($feature_ids);		}
				if(in_array($feature_id, $feature_ids)){return true;}
			}		
		}
		return false;
	}
}

	 /**
     * Sets graphics related variables in session
     **/
if (!function_exists('set_graphic_settings_ifnotset')){
	 function set_graphic_settings_ifnotset($event_id,$forceset=false){
	 	$CI = & get_instance();
		if((empty(get_value_from_session_cookie($event_id."_".BAR_GRADIENT_TOP)) || ($forceset))){
			$CI->common_model->get_event_graphic_settings($event_id,'',true);
		}
	}
}

if (!function_exists('time_elapsed_string')){
    function time_elapsed_string($ptime)
    {
        
        // Past time as MySQL DATETIME value
        //$ptime = strtotime($ptime);
        
        // Current time as MySQL DATETIME value
        $csqltime = date('Y-m-d H:i:s');
        
        // Current time as Unix timestamp
        $ctime = strtotime($csqltime);
        
        // Elapsed time
        $etime = $ctime - $ptime;
        
        // If no elapsed time, return 0
        if ($etime < 1){
            return '0 seconds';
        }
        
        $a = array( 365 * 24 * 60 * 60  =>  'yr',
            30 * 24 * 60 * 60  =>  'mnth',
            24 * 60 * 60  =>  'day',
            60 * 60  =>  'hr',
            60  =>  'min',
            1  =>  'sec'
        );
        
        $a_plural = array( 'yr'   => 'yrs',
            'mnth'  => 'mnths',
            'day'    => 'days',
            'hr'   => 'hrs',
            'min' => 'mins',
            'sec' => 'secs'
        );
        $estring = '';
        foreach ($a as $secs => $str){
            // Divide elapsed time by seconds
            $d = $etime / $secs;
            if ($d >= 1){
                // Round to the next lowest integer
                $r = floor($d);
                // Calculate time to remove from elapsed time
                $rtime = $r * $secs;
                // Recalculate and store elapsed time for next loop
                if(($etime - $rtime)  < 0){
                    $etime -= ($r - 1) * $secs;
                }
                else{
                    $etime -= $rtime;
                }
                // Create string to return
                $estring = $estring . $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ';
            }
        }
        
        $arrTimeAgo = explode(" ", $estring);
        //print_r($arrTimeAgo);
        if (strpos($estring, 'yrs') !== false || strpos($estring, 'yr') !== false){
            $estring = $arrTimeAgo[0];
            if($estring==1){
                $estring = $estring." Year Ago";
            }else{
                $estring = $estring." Years Ago";
            }
        }else if (strpos($estring, 'mnths') !== false || strpos($estring, 'mnth') !== false){
            $estring = $arrTimeAgo[0];
            if($estring==1){
                $estring = $estring." Month Ago";
            }else{
                $estring = $estring." Months Ago";
            }
        }else if (strpos($estring, 'days') !== false || strpos($estring, 'day') !== false){
            $estring = $arrTimeAgo[0];
            if($estring==1){
                $estring = $estring." Day Ago";
            }else{
                $estring = $estring." Days Ago";
            }
        }else if (strpos($estring, 'hr') !== false || strpos($estring, 'hrs') !== false){
            $estring = $arrTimeAgo[0];
            if($estring==1){
                $estring = $estring." Hr Ago";
            }else{
                $estring = $estring." Hrs Ago";
            }
        }else if (strpos($estring, 'mins') !== false || strpos($estring, 'min') !== false){
            $estring = $arrTimeAgo[0];
            if($estring==1){
                $estring = $estring." Min Ago";
            }else{
                $estring = $estring." Mins Ago";
            }
        }else if (strpos($estring, 'secs') !== false || strpos($estring, 'sec') !== false){
            $estring = $arrTimeAgo[0];
            if($estring==1){
                $estring = $estring." Sec Ago";
            }else{
                $estring = $estring." Secs Ago";
            }
        }
        
        return $estring;
    }
}

function time_elapsed_chat($ptime)
{
    
    // Past time as MySQL DATETIME value
    //$ptime = strtotime($ptime);
    
    // Current time as MySQL DATETIME value
    $csqltime = date('Y-m-d H:i:s');
    
    // Current time as Unix timestamp
    $ctime = strtotime($csqltime);
    
    // Elapsed time
    $etime = $ctime - $ptime;
    if( $etime>(60*60*24*2) ){
        return date('d M',$ptime);
    }else{
        return time_elapsed_string($ptime);
    }
}

function meterToMiles($meters) {
    return $meters*0.000621371192;
}

/* cal distance*/
function geo_distance($lat1, $lon1, $lat2, $lon2, $unit) {
    
    
    $lat1= (float)$lat1;
    $lat2= (float)$lat2;
    $lon1= (float)$lon1;
    $lon2= (float)$lon2;
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);
    
    if ($unit == "K") {
        return ($miles * 1.609344);
    } else if ($unit == "N") {
        return ($miles * 0.8684);
    } else {
        return sprintf ("%.2f", $miles);;
    }
}

if (!function_exists('is_admin')){
    function is_admin(){
    	$user_info = get_value_from_session_cookie(USER_INFO);
    	if($user_info[SESS_ROLE_ID]==ROLE_ADMIN){
    		return true;
    	}else{
			return false;
    	}
    }
}

if (!function_exists('is_account_manager')){
    function is_account_manager(){
    	$user_info = get_value_from_session_cookie(USER_INFO);
    	if($user_info[SESS_ROLE_ID]==ROLE_ACCOUNT_MANAGER){
    		return true;
    	}else{
			return false;
    	}
    }
}

if (!function_exists('encrypt_simple')){
    function encrypt_simple($str){
    	return base64_encode($str);
    }
}

if (!function_exists('decrypt_simple')){
    function decrypt_simple($str){
    	return base64_decode($str);
    }
}

if (!function_exists('setParamInContent')){
	function setParamInContent($content,$param_name,$param_value){
		return str_replace($param_name,$param_value,$content);
	}
}

if (!function_exists('format_time')){
	function format_time($t,$f=':'){
		//return ($t< 0 ? '-' : '') . sprintf("%02d%s%02d%s%02d", floor(abs($t)/3600), $f, (abs($t)/60)%60, $f, abs($t)%60);
		
		$seconds = abs($t)%60;
		$minutes = (abs($t)/60)%60;
	  
		if($seconds>30){
			$minutes = $minutes+1;
		}
		return sprintf("%02d",$minutes);//($t< 0 ? '-' : '') . sprintf("%02d%s%02d%s%02d", floor(abs($t)/3600), $f, (abs($t)/60)%60, $f, abs($t)%60);
	}
}

if (!function_exists('get_followup_reminder')){
    function get_followup_reminder(){
    	
    	$CI = & get_instance();
		$followup_reminder = $CI->common_model->get_followup_reminder();
		
    	return $followup_reminder;
    }
}

if (!function_exists('get_account_managers')){
    function get_account_managers(){
    	
    	$CI = & get_instance();
		$account_managers = $CI->common_model->get_account_managers();
		
    	return $account_managers;
    }
}
if (!function_exists('get_categories')){
    function get_categories(){
    	
    	$CI = & get_instance();
		$categories = $CI->common_model->get_categories();
		
    	return $categories;
    }
}

if (!function_exists('get_total_income')){
    function get_total_income(){
    	
    	$CI = & get_instance();
		$total_income = $CI->common_model->get_total_income();
		
    	return $total_income;
    }
}

if (!function_exists('get_total_expense')){
    function get_total_expense(){
    	
    	$CI = & get_instance();
		$total_expense = $CI->common_model->get_total_expense();
		
    	return $total_expense;
    }
}

if (!function_exists('get_expense_by_category_chart')){
    function get_expense_by_category_chart(){
    	
    	$CI = & get_instance();
		$expense_by_category_chart = $CI->common_model->get_expense_by_category_chart();
		
    	return $expense_by_category_chart;
    }
}