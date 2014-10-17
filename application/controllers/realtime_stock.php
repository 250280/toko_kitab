<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Realtime_stock extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->library('render');
		$this->load->model('realtime_stock_model');
		$this->load->library('access');
		$this->access->set_module('stock.realtime_stock');
	}
	
	function index(){
		
		$list_stand = $this->realtime_stock_model->get_list_stand();
		$data_product = $this->realtime_stock_model->get_data_product();
		
		
		$this->render->add_view('app/realtime_stock/list', array('list' => $list_stand, 'data_product' => $data_product));
		$this->render->build('Data Realtime Stok');
		$this->render->show('Realtime Stok');
	}
	
	function table_controller(){
		$data = $this->realtime_stock_model->list_controller();
		send_json($data);
	}
	
	function form($id = 0){
		$data = array();
		
			$result = $this->realtime_stock_model->read_id($id);
			if($result){
				$data = $result;
				$data['row_id'] = $id;
			}
		

		$this->load->helper('form');
		$this->render->add_form('app/realtime_stock/form', $data);
		$this->render->build('Kartu Gudang');
		
		$this->render->add_view('app/realtime_stock/transient_list');
		$this->render->build('Riwayat Stok');
		
		$this->render->show('Kartu Gudang');
		//$this->access->generate_log_view($id);
	}
	
	function form_action($is_delete = 0){
		
		$id = $this->input->post('row_id');
			
		if($is_delete){
			$is_proses_error = $this->realtime_stock_model->delete($id);
			send_json_action($is_proses_error, "Data telah dihapus");
		}
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('i_code','Kode', 'trim|required');
		$this->form_validation->set_rules('i_name','Nama', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('i_category_id','Kategori Produk', 'trim|required');
		$this->form_validation->set_rules('i_type_id','Tipe Produk', 'trim|required');
		$this->form_validation->set_rules('i_purchase_price','Harga Beli', 'trim|required|numeric');
		$this->form_validation->set_rules('i_min_reorder','Minimal Reorder', 'trim|required|numeric');
		
		if($this->form_validation->run() == FALSE) send_json_validate();
		
		$data['realtime_stock_code'] 				= $this->input->post('i_code');
		$data['realtime_stock_name'] 				= $this->input->post('i_name');
		$data['realtime_stock_category_id'] 		= $this->input->post('i_category_id');
		$data['realtime_stock_type_id'] 			= $this->input->post('i_type_id');
		$data['realtime_stock_description'] 		= $this->input->post('i_description');
		$data['realtime_stock_purchase_price'] 	= $this->input->post('i_purchase_price');
		$data['realtime_stock_min_reorder'] 			= $this->input->post('i_min_reorder');
		
		if(empty($id)){
			$data['realtime_stock_status'] 					= 1;
			$data['realtime_stock_code']			= format_code('realtime_stocks','realtime_stock_code','P',7);
			$error = $this->realtime_stock_model->create($data);
			send_json_action($error, "Data telah ditambah", "Data gagal ditambah");
		}else{
			$error = $this->realtime_stock_model->update($id, $data);
			send_json_action($error, "Data telah direvisi", "Data gagal direvisi");
		}
		
	}
	
	function detail_list_loader($id=0)
	{
		if($id == 0)send_json(make_datatables_list(null)); 
				
		$data = $this->realtime_stock_model->detail_list_loader($id);
		$sort_id = 0;
		foreach($data as $key => $value) 
		{	
		
		$data[$key] = array(
				form_transient_pair('transient_date', $value['transaction_date']),
				form_transient_pair('transient_debet', $value['debet']),
				form_transient_pair('transient_kredit', $value['kredit']),
				form_transient_pair('transient_saldo', '')		
		);

		}		
		send_json(make_datatables_list($data)); 
	}
	
	
	
}
