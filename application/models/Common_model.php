<?php
class Common_model extends CI_Model {
	private $request;
	private $response;
	public function __construct(){
		$this->request=new stdClass();	
	}
	
	/**
	 * MAIN AUTHENTICATION FOR BACKEND USERS
	 */
	public function check_page_authenticated(){
		if ($this->input->is_ajax_request()) {return;}// if it is ajax request then skip auth
		
		/*================================================================================================*/
		
		if(!empty($this->input->get('printsession', false))){
			echo '<pre>';print_r($this->session->userdata());exit;
		}
		
		//$curPage=$this->getCurPage();
		$current_class=	$this->router->fetch_class();
		$current_method=$this->router->fetch_method();
		if(in_array($current_class, array('logout','dynamiccss')))
		{// allow this pages at any cost
			return;
		}
		
		// following condition will check if the user has logged in or not, and if not , then redirected to login page, and the default hook will ignore this condition for login page, otherwise it will be infinite
		$method = $this->router->fetch_method();
		if(!_is_logged_in() && $method!="login" && $method!="login_submit" && $method!="logout"){
			redirect('/user/login');
		}
	}
	
	/**
	 * following function will return event_settings table
	 * @param	array/string      $condition				Not Required				  
	 */
	function get_field($table_name,$field_name,$condition=array(),$lang_id=0,$pk_key="") {
		$this->db->select($field_name);
		$this->db->from($table_name);
		if(is_array($condition)){
			if(!empty($pk_key)){
				$condition[$pk_key]=$condition["fk_".$pk_key];
				unset($condition['fk_language_id']);
				unset($condition["fk_".$pk_key]);
			}
			$this->db->where($condition);
		}else{
			//CodeIgniter will not try to protect your field or table names
			$this->db->where($condition,NULL,FALSE);
		}
		$resultset=$this->db->get();
		
		return ($resultset->num_rows()>0)?$resultset->row()->$field_name:false;
	}
	
	/*
	 * following function will return all fields
	 * @param	array/string      $condition				Not Required		
	 */
	function get_fields($table_name,$field_name,$condition=array()) {
		$this->db->select($field_name);
		$this->db->from($table_name);
		if(is_array($condition)){
			$this->db->where($condition);
		}else{
			// CodeIgniter will not try to protect your field or table names
			$this->db->where($condition,NULL,FALSE);
		}
		$resultset=$this->db->get();
		// echo $this->db->last_query(); exit;
		return ($resultset->num_rows()>0)?$resultset->result_array():false;
	}
/*
	 * following function will return all fields
	 * @param	array/string      $condition				Not Required		
	 */
	function get_list($table_name,$field_name1,$field_name2,$condition=array()) {
		$this->db->select($field_name1.','.$field_name2);
		$this->db->from($table_name);
		if(is_array($condition)){
			$this->db->where($condition);
		}else{
			// CodeIgniter will not try to protect your field or table names
			$this->db->where($condition,NULL,FALSE);
		}
		$resultset=$this->db->get();
		$return_array = array();
		if($resultset->num_rows()>0){
			$result=$resultset->result_array();
			foreach($result as $key=>$value){
			  $return_array[$value[$field_name1]] = trim($value[$field_name2]);  
			}
		}
		return $return_array;
	}
	
	function my_json_encode($arr)
	{
	       //convmap since 0x80 char codes so it takes all multibyte codes (above ASCII 127). So such characters are being "hidden" from normal json_encoding
	       array_walk_recursive($arr, function (&$item, $key) { if (is_string($item)) $item = mb_encode_numericentity($item, array (0x80, 0xffff, 0, 0xffff), 'UTF-8'); });
	       return mb_decode_numericentity(json_encode($arr), array (0x80, 0xffff, 0, 0xffff), 'UTF-8');
	} 
	
	/**
	 * UPLOAD FILE SINGLE
	 */
	function upload_file_single($file,$module,$createThumb=0,$output=0){
		$tF = $_SERVER['DOCUMENT_ROOT'].SITE_ROOT.'uploads/'.$module.'/';

		if($file['error'] == UPLOAD_ERR_OK) {
			$name = $file["name"];
			$curTimeStamp = str_replace('.','',microtime(date('Y-m-d H:i:s')));
			$imginfo = pathinfo($name);
			
			// keep the timestamp name for profile image only, otherwise keep the original name. as we are using the dropzone js for uploading files
			$newName = $curTimeStamp.".".$imginfo['extension'];//($module=='profile_image')?$curTimeStamp.".".$imginfo['extension']:$name;

			if (!is_dir($tF)) {
				mkdir($tF, 0777,true);
			}
			
			// resizing image to 1000 width/height
			list($width,$height,$fileType)=getimagesize($file["tmp_name"]);
			if($width>1000 || $height>1000){
				$imgSrc;
				if($fileType==1){
					$imgSrc = imagecreatefromgif($file["tmp_name"]);
				}else if($fileType==2){
					$imgSrc = ImageCreateFromjpeg($file["tmp_name"]);
				}else if($fileType==3){
					$imgSrc = imagecreatefrompng($file["tmp_name"]);
				}
				$new_img_size=$this->calculateDimensions($width,$height,1000,1000);
				$resampled = imagecreatetruecolor($new_img_size['width'], $new_img_size['height']);
				imagecopyresampled($resampled, $imgSrc, 0, 0, 0, 0,$new_img_size['width'],  $new_img_size['height'], $width, $height);
				imagejpeg($resampled, $tF.$newName, 100);
			}else{
				move_uploaded_file($file["tmp_name"], $tF.$newName);
			}

			if(!empty($createThumb)){
				$this->generateThumb($tF.'Thumbs/', $tF.$newName, 300, $tF.'Thumbs/'.$newName);
			}
			if($output)
				echo $newName;
			else
				return $newName;

		}
	}
	
	/**
	 * GENERATE THUMB
	 */
	function generateThumb($target_folder,$imgName, $scale, $fileName) {

		if (!is_dir($target_folder)) {
			mkdir($target_folder, 0777,true);
		}

		$pathinfo = pathinfo($imgName);
		$fileType = $pathinfo['extension'];
		$fileType = strtolower($fileType);


		$imageData = getimagesize($imgName);
		$mimeType = image_type_to_mime_type($imageData[2]);
		$fileType = $mimeType;

		switch ($fileType) {
			case "image/jpeg":
			case "image/jpg":
				$imgSrc = ImageCreateFromjpeg($imgName);
				break;
			case "image/gif":
				$imgSrc = imagecreatefromgif($imgName);
				break;
			case "image/png":
				$imgSrc = imagecreatefrompng($imgName);
				break;
		}

		$width = imagesx($imgSrc);
		$height = imagesy($imgSrc);
		/*        $ratioX = 100 * $width / $scale;
		 $ratioY = 100 * $height / $scale;*/

		$ratioX =$scale/$width;
		$ratioY =$scale/$height;

		$ratio = min($ratioX,$ratioY);
		// Calculate resampling
		$newHeight = $height * $ratio;
		$newWidth = $width * $ratio;

		// Calculate cropping (division by zero)
		$cropX = ($newWidth - $scale != 0) ? ($newWidth - $scale) / 2 : 0;
		$cropY = ($newHeight - $scale != 0) ? ($newHeight - $scale) / 2 : 0;

		// Setup Resample & Crop buffers
		$resampled = imagecreatetruecolor($newWidth, $newHeight);
		$cropped = imagecreatetruecolor($scale, $scale);
		switch ($fileType) {
			case "image/jpeg":
			case "image/jpg":
				break;
			case "image/gif":
				break;
			case "image/png":
				imagealphablending( $resampled, false );
				imagesavealpha( $resampled, true );
				break;
		}
		/*echo $newHeight.','.$newWidth.','.$height.','.$width;
		 exit();*/
		// Resample
		imagecopyresampled($resampled, $imgSrc, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

		/*        imagecopyresized($resampled, $imgSrc, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);*/
		// Crop
		// imagecopy($cropped, $resampled, 0, 0, $cropX, $cropY, $newWidth, $newHeight);

		// Save the cropped image
		switch ($fileType) {
			case "image/jpeg":
			case "image/jpg":
				imagejpeg($resampled, $fileName, 100);
				break;
			case "image/gif":
				imagegif($resampled, $fileName, 80);
				break;
			case "image/png":
				imagepng($resampled, $fileName, 9);
				break;
		}
	}
	
	/*
	 * Calculate Dimenstions
	 *
	 */
	public function calculateDimensions($width,$height,$maxwidth,$maxheight){

		if($width != $height)
		{
			if($width > $height)
			{
				$t_width = $maxwidth;
				$t_height = (($t_width * $height)/$width);
				//fix height
				if($t_height > $maxheight)
				{
					$t_height = $maxheight;
					$t_width = (($width * $t_height)/$height);
				}
			}
			else
			{
				$t_height = $maxheight;
				$t_width = (($width * $t_height)/$height);
				//fix width
				if($t_width > $maxwidth)
				{
					$t_width = $maxwidth;
					$t_height = (($t_width * $height)/$width);
				}
			}
		}
		else
		$t_width = $t_height = min($maxheight,$maxwidth);

		return array('height'=>(int)$t_height,'width'=>(int)$t_width);
	}
	
	/**
	 * common function to update status active/inactive
	 */
	public function update_status($params){
		$response = array();
		$response['success']=0;
		$response['message']="Something went wrong. Please try again";
		
		$table_name = $params->table_name;
		$set_status = $params->set_status;
		$record_id = $params->record_id;
		
		// if table is `users`, then check if the user is account manager or tech, then check for if they are asosciated with anything or not.
		$allow_update_status = true;
		if($table_name=='users' && $set_status==STATUS_DELETE){
			// get role of the user
			$user_role = $this->get_field($table_name, "fk_role_id", "id='".$record_id."'");
			
			if($user_role==ROLE_ACCOUNT_MANAGER){
				// check his/her association
				$current_date = date("Y-m-d");
				$count_clients = $this->get_field("customers", "count(id)", "fk_accountmanager_id='".$record_id."' AND status!='".STATUS_DELETE."'");
				
				if(!empty($count_clients)){
					$allow_update_status = false;
					$response['success']=0;
					$response['message']="Please remove all associations first, then delete the record";
				}
			}
		}
		
		if($allow_update_status){
			$data_update['status'] = $set_status;
			
			$this->db->where('id', $record_id);
			if($this->db->update($table_name,$data_update)){
				
				$message = "";
				if($set_status==STATUS_ACTIVE){
					$message = "Activated successfully";
				}else if($set_status==STATUS_INACTIVE){
					$message = "Inactivated successfully";
				}else{
					$message = "Deleted successfully";
				}
				
				$response['success']=1;
				$response['message']=$message;
			}
		}
		
		return json_encode($response,true);
	}
	
	/**
	 * Get followup reminder
	 */
	public function get_followup_reminder(){
		$response = array();
		
		$user_info = get_value_from_session_cookie(USER_INFO);
		$sess_user_id = $user_info[SESS_USER_ID];
		$sess_role_id = $user_info[SESS_ROLE_ID];
		
		$current_date = date("Y-m-d");
		
		// CONDITION
		$condition = " c.status!='".STATUS_DELETE."' AND followup_date='".$current_date."'";
		
		// get account manager name associated with customer
		$condition.=" AND u.id=c.fk_accountmanager_id";
		
		if($sess_role_id==ROLE_ACCOUNT_MANAGER){
			// only get associated customers
			$condition.=" AND c.fk_accountmanager_id='".$sess_user_id."'";
		}
		
		// order by
		$condition.=" ORDER BY name";
		
		$list = $this->get_fields("customers c, users u","c.*,CONCAT(u.first_name,' ',u.last_name) as accountmanager_name",$condition);
		
		if(!empty($list)){
			$response = $list;
		}
		
		return $response;
	}
	
	/**
	 * Get Comments common function for Customer Comments
	 */
	public function get_comments($params){
		$response = array();
		
		// get comments and creator name and profile picture
		$condition = "c.fk_row_id=".$params->fk_row_id." AND c.module='".$params->module."' AND c.status!='".STATUS_DELETE."' AND u.status!='".STATUS_DELETE."'";
		
		// now get comments, comment files and name of the user
		// selcet fields
		$this->db->select("c.*,u.first_name,u.last_name,u.profile_image");
		
		// from table
		$this->db->from('comments c');
		
		// join conditions
		$this->db->join('users u','u.id=c.fk_user_id','left');
		
		// query WHERE condition
		$this->db->where($condition);
		
		// order by
		$this->db->order_by('c.created','DESC');
		
		// execute query and get result
		$list = $this->db->get()->result_array();
		
		if(!empty($list)){

			// now loop through each list object and get associated files
			foreach ($list as $key=>$details){
				$file_condition = "module='".$params->files_for."' AND status!='".STATUS_DELETE."' AND fk_row_id='".$details['id']."'";
				
				$this->db->select("file_name,file");
				$this->db->from('all_files');
				$this->db->where($file_condition);
				$this->db->order_by('created','DESC');
				$list[$key]['files'] = $this->db->get()->result_array();
			}
			
			$response = $list;
		}
		return $response;
	}
	
	/**
	 * Save Comments
	 */
	public function save_comments($params){
		$response = array();
		$response['success']=0;
		$response['message']="Something went wrong. Please try again";
		
		$uploaded_files = $params->uploaded_files;
		
		// add comment
		$data_add = array();
		$data_add['fk_row_id'] = $params->fk_row_id;
		$data_add['fk_user_id'] = $params->fk_user_id;
		$data_add['type'] = $params->type;
		$data_add['comments'] = $params->comments;
		$data_add['show_to_customer'] = $params->show_to_customer;
		
		// add a new task
		if($this->db->insert('comments',$data_add)){
			
			// after we save this record, then we have to update its taskid
			$comment_id = $this->db->insert_id();
			
			$response['success']=1;
			$response['message']="Comment added successfully";
		}
		
		// now save images / files for comment
		if(!empty($comment_id)){
			// now loop through all newly added files and save them.
			if(!empty($uploaded_files)){
				foreach ($uploaded_files as $eachFile){
					$data_add = array();
					$data_add['fk_user_id'] = $params->fk_user_id;
					$data_add['module'] = $params->module;
					$data_add['fk_row_id'] = $comment_id;
					$data_add['file_name'] = $eachFile['original_name'];
					$data_add['file'] = $eachFile['file_name']; 
					$this->db->insert('all_files',$data_add);
				}
			}
		}
		
		return json_encode($response,true);
	}
	
	public function get_account_managers(){
		
		$response = array();
		
		$user_info = get_value_from_session_cookie(USER_INFO);
		$sess_user_id = $user_info[SESS_USER_ID];
		$sess_role_id = $user_info[SESS_ROLE_ID];
		
		// CONDITION
		$condition = " fk_role_id='".ROLE_ACCOUNT_MANAGER."'";
		
		// order by
		$condition.=" ORDER BY first_name,last_name";
		
		$list = $this->get_fields("users","id,first_name,last_name",$condition);
		
		if(!empty($list)){
			$response = $list;
		}
		
		return $response;
	}
	
	public function get_categories(){
		
		$response = array();
		
		$user_info = get_value_from_session_cookie(USER_INFO);
		$sess_user_id = $user_info[SESS_USER_ID];
		$sess_role_id = $user_info[SESS_ROLE_ID];
		
		// CONDITION
		$condition = " status!='".STATUS_DELETE."' ";
		
		// order by
		$condition.=" ORDER BY name";
		
		$list = $this->get_fields("categories","id,name",$condition);
		
		if(!empty($list)){
			$response = $list;
		}
		
		return $response;
	}
	
	public function get_total_income(){
		
		$response = array();
		
		$user_info = get_value_from_session_cookie(USER_INFO);
		$sess_user_id = $user_info[SESS_USER_ID];
		$sess_role_id = $user_info[SESS_ROLE_ID];
		
		// CONDITION
		$condition = " status!='".STATUS_DELETE."' ";
		
		$this->db->select("SUM(amount) as total_income");
		
		// from table
		$this->db->from('income');
		
		// query WHERE condition
		$this->db->where($condition);
		
		// execute query and get result
		$list = $this->db->get()->result_array();
		
		if(!empty($list)){
			$response = $list[0]['total_income'];
		}
		
		return $response;
	}
	
	public function get_total_expense(){
		
		$response = array();
		
		$user_info = get_value_from_session_cookie(USER_INFO);
		$sess_user_id = $user_info[SESS_USER_ID];
		$sess_role_id = $user_info[SESS_ROLE_ID];
		
		// CONDITION
		$condition = " status!='".STATUS_DELETE."' ";
		
		$this->db->select("SUM(amount) as total_expense");
		
		// from table
		$this->db->from('expense');
		
		// query WHERE condition
		$this->db->where($condition);
		
		// execute query and get result
		$list = $this->db->get()->result_array();
		
		if(!empty($list)){
			$response = $list[0]['total_expense'];
		}
		
		return $response;
	}
	
	public function get_expense_by_category_chart(){
		
		$response = array();
		
		$user_info = get_value_from_session_cookie(USER_INFO);
		$sess_user_id = $user_info[SESS_USER_ID];
		$sess_role_id = $user_info[SESS_ROLE_ID];
		
		$query = "SELECT SUM(e.amount) AS amount,c.name AS category_name FROM expense e,categories c WHERE c.id=e.fk_category_id AND e.status!='".STATUS_DELETE."' GROUP BY e.fk_category_id";
		$query_run = $this->db->query($query);
		$result = $query_run->result_array();
		
		$array_response = array();
		$labels = array();
		$data = array();
		$backgroundColor = array();
		$borderColor = array();

		foreach($result as $eachrecord){
			$labels[] = '"'.$eachrecord['category_name'].'"';
			$data[] = $eachrecord['amount'];
			$backgroundColor[] = '"rgba(255, 159, 64, 0.2)"';
			$borderColor[] = '"rgba(255, 159, 64, 1)"';

		}

		$array_response['labels'] = implode(",",$labels);
		$array_response['data'] = implode(",",$data);
		$array_response['backgroundColor'] = implode(",",$backgroundColor);
		$array_response['borderColor'] = implode(",",$borderColor);
		
		return $array_response;
	}
}
