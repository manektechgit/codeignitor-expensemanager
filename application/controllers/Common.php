<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends CI_Controller {
	/**
	 * Constructor 
	 */
	public function __construct(){
		parent::__construct();
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
	 * UPDATE STATUS (ONE FUNCTION TO UPDATE STATUS OF ALL RECORDS ACROSS THE SYSTEM
	 * this will be an ajax call
	 */
	public function update_status(){
		$this->response=array();
		
		// now update the profile
		$this->params=new stdClass();
		$this->params->table_name = $this->input->post('table_name',true);
		$this->params->record_id = $this->input->post('record_id',true);
		$this->params->set_status = $this->input->post('set_status',true);
		
		$this->response=$this->common_model->update_status($this->params);
		
		echo $this->response;
	}
	
	/**
	 * The following function is for uploading multiple files.
	 */
	public function upload_files($module){
		// the files will be added to this variable $_FILES['file'];
		$response_array = array();
		if(isset($_FILES) && !empty($_FILES)){
			$file_name = $this->common_model->upload_file_single($_FILES["file"], $module);
			
			$response_array['new_file_name'] = $file_name;
			
			echo json_encode($response_array,true);
			exit;
		}
	}
}
