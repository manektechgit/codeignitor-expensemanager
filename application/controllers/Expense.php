<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expense extends CI_Controller {
	/**
	 * Constructor 
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('expense_model');
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
		$this->response['page'] = "expense";
		
		$this->response['page_heading'] = "Expense";
		// COMMON VARIABLES END
		
		// get list of customers
		$this->response['data']=$this->expense_model->get_expenses();

		// categories list
		$this->response['categories'] = get_categories();

		// get total expense
		$this->response['total_expense'] = get_total_expense();
		
		$this->load->view('common/header',$this->response);
		$this->load->view('expense/list',$this->response);
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
		$this->response['page'] = "expense";
		
		$redirect_page = "expense";
		$this->response['redirect_page'] = $redirect_page;
		
		$this->response['page_heading'] = "Add Expense";
		// COMMON VARIABLES END

		// categories for dropdown
		$categories = $this->common->get_fields("categories","id,name","status!='".STATUS_DELETE."'");
		$this->response['categories'] = $categories;
		
		$this->load->view('common/header',$this->response);
		$this->load->view('expense/add',$this->response);
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
		$this->response['page'] = "expense";
		
		$redirect_page = "expense";
		$this->response['redirect_page'] = $redirect_page;
		
		$this->response['page_heading'] = "Edit Expense";
		// COMMON VARIABLES END
		
		// now get all the details of the record
		$details = $this->common->get_fields("expense","*","id='".$id."'");
		$details = $details[0];
		$this->response['data']=$details;

		// categories for dropdown
		$categories = $this->common->get_fields("categories","id,name","status!='".STATUS_DELETE."'");
		$this->response['categories'] = $categories;
		
		$this->load->view('common/header',$this->response);
		$this->load->view('expense/add',$this->response);
		$this->load->view('common/footer',$this->response);
	}
	
	/**
	 * SAVE 
	 */
	public function save_expense(){
		$this->response=array();
		
		// now update the profile
		$this->params=new stdClass();
		$this->params->id = $this->input->post('id',true);
		$this->params->fk_category_id = $this->input->post('fk_category_id',true);
		$this->params->title = $this->input->post('title',true);
		$this->params->amount = $this->input->post('amount',true);
		$this->params->expense_date = $this->input->post('expense_date',true);
		$this->params->description = $this->input->post('description',true);
		
		$this->response=$this->expense_model->add_update_expense($this->params);
		
		echo $this->response;
	}
}
