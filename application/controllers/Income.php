<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Income extends CI_Controller {
	/**
	 * Constructor 
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('income_model');
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
		$this->response['page'] = "income";
		
		$this->response['page_heading'] = "Income";
		// COMMON VARIABLES END
		
		// get list of customers
		$this->response['data']=$this->income_model->get_incomes();

		// categories list
		$this->response['categories'] = get_categories();

		// get total income
		$this->response['total_income'] = get_total_income();
		
		$this->load->view('common/header',$this->response);
		$this->load->view('income/list',$this->response);
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
		$this->response['page'] = "income";
		
		$redirect_page = "income";
		$this->response['redirect_page'] = $redirect_page;
		
		$this->response['page_heading'] = "Add Income";
		// COMMON VARIABLES END

		// categories for dropdown
		$categories = $this->common->get_fields("categories","id,name","status!='".STATUS_DELETE."'");
		$this->response['categories'] = $categories;
		
		$this->load->view('common/header',$this->response);
		$this->load->view('income/add',$this->response);
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
		$this->response['page'] = "income";
		
		$redirect_page = "income";
		$this->response['redirect_page'] = $redirect_page;
		
		$this->response['page_heading'] = "Edit Income";
		// COMMON VARIABLES END
		
		// now get all the details of the record
		$details = $this->common->get_fields("income","*","id='".$id."'");
		$details = $details[0];
		$this->response['data']=$details;

		// categories for dropdown
		$categories = $this->common->get_fields("categories","id,name","status!='".STATUS_DELETE."'");
		$this->response['categories'] = $categories;
		
		$this->load->view('common/header',$this->response);
		$this->load->view('income/add',$this->response);
		$this->load->view('common/footer',$this->response);
	}
	
	/**
	 * SAVE 
	 */
	public function save_income(){
		$this->response=array();
		
		// now update the profile
		$this->params=new stdClass();
		$this->params->id = $this->input->post('id',true);
		$this->params->fk_category_id = $this->input->post('fk_category_id',true);
		$this->params->title = $this->input->post('title',true);
		$this->params->amount = $this->input->post('amount',true);
		$this->params->income_date = $this->input->post('income_date',true);
		$this->params->description = $this->input->post('description',true);
		
		$this->response=$this->income_model->add_update_income($this->params);
		
		echo $this->response;
	}
}
