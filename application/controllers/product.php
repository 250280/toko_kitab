<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->library('render');
		$this->load->model('product_model');
		$this->load->library('access');
		$this->access->set_module('master.product');
	}
	
	function index(){
		
		$this->render->add_view('app/product/list');
		$this->render->build('Data Produk');
		$this->render->show('Produk');
	}
	
	function table_controller(){
		$data = $this->product_model->list_controller();
		send_json($data);
	}
	
	function form($id = 0){
		$data = array();
		if($id==0){
			$data['row_id']					= '';
			$data['product_code']			= format_code('products','product_code','P',7);
			$data['product_name']			= '';
			$data['product_category_id']	= '';
			$data['product_type_id']		= '';
			$data['product_description']	= '';
			$data['product_purchase_price']	= '';
			$data['product_min_reorder']	= '';
			$data['product_point']	= '';
			//$data['product_expired']	= '';
		}else{
			$result = $this->product_model->read_id($id);
			if($result){
				$data = $result;
				$data['row_id'] = $id;
				//$data['product_expired'] = date('d/m/Y', strtotime($data['product_expired']));
			}
		}

		$this->load->helper('form');
		$this->render->add_form('app/product/form', $data);
		$this->render->build('Produk');
		$this->render->show('Produk');
		//$this->access->generate_log_view($id);
	}
	
	function form_action($is_delete = 0){
		
		$id = $this->input->post('row_id');
			
		if($is_delete){
			$is_proses_error = $this->product_model->delete($id);
			send_json_action($is_proses_error, "Data telah dihapus");
		}
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('i_code','Kode', 'trim|required');
		$this->form_validation->set_rules('i_name','Nama', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('i_category_id','Kategori Produk', 'trim|required');
		//$this->form_validation->set_rules('i_type_id','Tipe Produk', 'trim|required');
		$this->form_validation->set_rules('i_purchase_price','Harga Beli', 'trim|required|numeric');
		$this->form_validation->set_rules('i_min_reorder','Minimal Reorder', 'trim|required|numeric');
		$this->form_validation->set_rules('i_point','Poin', 'trim|numeric');
		//$this->form_validation->set_rules('i_expired','Expired', 'trim|valid_date|sql_date');
		
		if($this->form_validation->run() == FALSE) send_json_validate();
		
		$data['product_code'] 				= $this->input->post('i_code');
		$data['product_name'] 				= $this->input->post('i_name');
		$data['product_category_id'] 		= $this->input->post('i_category_id');
		$data['product_type_id'] 			= $this->input->post('i_type_id');
		$data['product_description'] 		= $this->input->post('i_description');
		$data['product_purchase_price'] 	= $this->input->post('i_purchase_price');
		$data['product_min_reorder'] 		= $this->input->post('i_min_reorder');
		$data['product_point'] 				= $this->input->post('i_point');
		//$data['product_expired'] 			= $this->input->post('i_expired');
		
		if(empty($id)){
			$data['product_status'] 					= 1;
			$data['product_code']			= format_code('products','product_code','P',7);
			$error = $this->product_model->create($data);
			send_json_action($error, "Data telah ditambah", "Data gagal ditambah");
		}else{
			$error = $this->product_model->update($id, $data);
			send_json_action($error, "Data telah direvisi", "Data gagal direvisi");
		}
		
	}
	
	
	
}
