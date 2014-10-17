<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Realtime_price extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->library('render');
		$this->load->model('realtime_price_model');
		$this->load->library('access');
		$this->access->set_module('stock.realtime_price');
	}
	
	function index(){
		
		$this->render->add_view('app/realtime_price/list');
		$this->render->build('Data Realtime harga');
		$this->render->show('Realtime harga');
	}
	
	function table_controller(){
		$data = $this->realtime_price_model->list_controller();
		send_json($data);
	}
	
	function form($id = 0){
		$data = array();
		if($id==0){
			$data['row_id']					= '';
			$data['product_id']			= '';
			$data['stand_id']			= '';
			$data['product_stock_qty']	= '';
			$data['product_stock_description']		= '';
			$data['user_price']	= '';
			$data['freeline_price']	= '';
			$data['counter_price']	= '';
			$data['online_price'] = '';
			$data['another_price'] = '';
			
		}else{
			$result = $this->realtime_price_model->read_id($id);
			if($result){
				$data = $result;
				$data['row_id'] = $id;
			}
		}

		$this->load->helper('form');
		$this->render->add_form('app/realtime_price/form', $data);
		$this->render->build('Realtime Harga');
		$this->render->show('Realtime Harga');
		//$this->access->generate_log_view($id);
	}
	
	function form_action($is_delete = 0){
		
		$id = $this->input->post('row_id');
			
		if($is_delete){
			$is_proses_error = $this->realtime_price_model->delete($id);
			send_json_action($is_proses_error, "Data telah dihapus");
		}
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('i_product_id','Produk', 'trim|required');
		$this->form_validation->set_rules('i_stand_id','Cabang', 'trim|required');
		$this->form_validation->set_rules('i_qty','Qty', 'trim|required|numeric');
		$this->form_validation->set_rules('i_user_price','Harga User', 'trim|required|numeric');
		$this->form_validation->set_rules('i_another_price','Harga Distributor', 'trim|required|numeric');
		$this->form_validation->set_rules('i_freeline_price','Harga Freeline', 'trim|required|numeric');
		$this->form_validation->set_rules('i_counter_price','Harga Counter', 'trim|required|numeric');
		$this->form_validation->set_rules('i_online_price','Harga Online', 'trim|required|numeric');
		
		if($this->form_validation->run() == FALSE) send_json_validate();
		
		$data['product_id'] 				= $this->input->post('i_product_id');
		$data['stand_id'] 					= $this->input->post('i_stand_id');
		$data['product_stock_qty'] 			= $this->input->post('i_qty');
		$data['product_stock_description'] 	= $this->input->post('i_description');
		$data['user_price'] 				= $this->input->post('i_user_price');
		$data['freeline_price'] 			= $this->input->post('i_freeline_price');
		$data['counter_price'] 				= $this->input->post('i_counter_price');
		$data['online_price'] 				= $this->input->post('i_online_price');
		$data['another_price'] 				= $this->input->post('i_another_price');
		
		
		
		if(empty($id)){
			
			$check_data = $this->realtime_price_model->check_data($data['product_id'], $data['stand_id']);
		
			if($check_data){
				send_json_error("Simpan gagal. Data sudah ada");
			}
			
			$error = $this->realtime_price_model->create($data);
			send_json_action($error, "Data telah ditambah", "Data gagal ditambah");
		}else{
			$error = $this->realtime_price_model->update($id, $data);
			send_json_action($error, "Data telah direvisi", "Data gagal direvisi");
		}
		
	}
	
	
	
}
