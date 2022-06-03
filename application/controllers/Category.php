<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {
	/**
	 * Constructor 
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('category_model');
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
		$this->response['page'] = "category";
		
		$this->response['page_heading'] = "Categories";
		// COMMON VARIABLES END
		
		// get list of customers
		$this->response['data']=$this->category_model->get_categories();
		
		$this->load->view('common/header',$this->response);
		$this->load->view('category/list',$this->response);
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
		$this->response['page'] = "category";
		
		$redirect_page = "category";
		$this->response['redirect_page'] = $redirect_page;
		
		$this->response['page_heading'] = "Add Category";
		// COMMON VARIABLES END
		
		$this->load->view('common/header',$this->response);
		$this->load->view('category/add',$this->response);
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
		$this->response['page'] = "category";
		
		$redirect_page = "category";
		$this->response['redirect_page'] = $redirect_page;
		
		$this->response['page_heading'] = "Edit Category";
		// COMMON VARIABLES END
		
		// now get all the details of the user
		$details = $this->common->get_fields("categories","*","id='".$id."'");
		$details = $details[0];
		$this->response['data']=$details;
		
		$this->load->view('common/header',$this->response);
		$this->load->view('category/add',$this->response);
		$this->load->view('common/footer',$this->response);
	}
	
	/**
	 * SAVE 
	 */
	public function save_category(){
		$this->response=array();
		
		// now update the profile
		$this->params=new stdClass();
		$this->params->id = $this->input->post('id',true);
		$this->params->name = $this->input->post('name',true);
		
		$this->response=$this->category_model->add_update_category($this->params);
		
		echo $this->response;
	}
}
