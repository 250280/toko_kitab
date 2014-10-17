<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Salesman extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->library('render');
		$this->load->model('salesman_model');
		$this->load->library('access');
		$this->access->set_module('master.salesman');
	}
	
	function index(){
		
		$this->render->add_view('app/salesman/list');
		$this->render->build('Data Salesman');
		$this->render->show('Salesman');
	}
	
	function table_controller(){
		$data = $this->salesman_model->list_controller();
		send_json($data);
	}
	
	function form($id = 0){
		$data = array();
		if($id==0){
			$data['row_id']				= '';
			$data['salesman_code']			= format_code('salesmans','salesman_code','SM',7);
			
			$data['salesman_name']			= '';
			$data['salesman_description']	= '';
			$data['salesman_phone']	= '';
			$data['salesman_email']	= '';
			$data['salesman_address']		= '';
		
		}else{
			$result = $this->salesman_model->read_id($id);
			if($result){
				$data = $result;
				$data['row_id'] = $id;
			}
		}

		$this->load->helper('form');
		$this->render->add_form('app/salesman/form', $data);
		$this->render->build('salesman');
		
		if($id){
			$this->render->add_view('app/salesman/transient_list');
			$this->render->build('Pelanggan');
		}
		
		$this->render->show('salesman');
		//$this->access->generate_log_view($id);
	}
	
	function form_action($is_delete = 0){
		
		$id = $this->input->post('row_id');
			
		if($is_delete){
			$is_proses_error = $this->salesman_model->delete($id);
			send_json_action($is_proses_error, "Data telah dihapus");
		}
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('i_code','Kode', 'trim|required');
		$this->form_validation->set_rules('i_name','Nama', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('i_phone','Telepon', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('i_email','Email', 'trim|required');
		$this->form_validation->set_rules('i_address','Alamat', 'trim|required');
		
		if($this->form_validation->run() == FALSE) send_json_validate();
		
		$data['salesman_code'] 					= $this->input->post('i_code');
		$data['salesman_name'] 					= $this->input->post('i_name');
		$data['salesman_description'] 			= $this->input->post('i_description');
		$data['salesman_phone'] 					= $this->input->post('i_phone');
		$data['salesman_email'] 					= $this->input->post('i_email');
		$data['salesman_address'] 				= $this->input->post('i_address');
		
		if(empty($id)){
			$data['salesman_status'] 					= 1;
			
			$data['salesman_code']			= format_code('salesmans','salesman_code','SM',7);
			
			$error = $this->salesman_model->create($data);
			send_json_action($error, "Data telah ditambah", "Data gagal ditambah");
		}else{
			$error = $this->salesman_model->update($id, $data);
			send_json_action($error, "Data telah direvisi", "Data gagal direvisi");
		}
		
	}
	
	function load_class_grade()
	{
		$id 	= $this->input->post('id');
		
		$query = $this->salesman_model->load_class_grade($id);
		$data = array();
		
		foreach($query->result_array() as $row)
		{
			$data['class_grade_name'] = $row['class_grade_name'];
		}
		send_json_message('Tingkat kelas', $data);
	}
	
	function load_class_type()
	{
		$id 	= $this->input->post('id');
		
		$query = $this->salesman_model->load_class_type($id);
		$data = array();
		
		foreach($query->result_array() as $row)
		{
			$data['class_type_name'] = $row['class_type_name'];
		}
		send_json_message('Jenis kelas', $data);
	}
	
	function load_class_number()
	{
		$id 	= $this->input->post('id');
		
		$query = $this->salesman_model->load_class_number($id);
		$data = array();
		
		foreach($query->result_array() as $row)
		{
			$data['class_number_name'] = $row['class_number_name'];
		}
		send_json_message('Abjad kelas', $data);
	}
	
	function detail_list_loader($id=0)
	{
		if($id == 0)send_json(make_datatables_list(null)); 
				
		$data = $this->salesman_model->detail_list_loader($id);
		$sort_id = 0;
		foreach($data as $key => $value) 
		{	
		
		$data[$key] = array(
				form_transient_pair('transient_number', $value['customer_number']),
				form_transient_pair('transient_name', $value['customer_name']),
				form_transient_pair('transient_email', $value['customer_email']),
				form_transient_pair('transient_phone', $value['customer_phone']),
				form_transient_pair('transient_address', $value['customer_address'])
		);

		}		
		send_json(make_datatables_list($data)); 
	}
	function detail_form($transaction_id = 0) // jika id tidak diisi maka dianggap create, else dianggap edit
	{		
		$this->load->library('render');
		$index = $this->input->post('transient_index');
		if (strlen(trim($index)) == 0) {
					
			// TRANSIENT CREATE - isi form dengan nilai default / kosong
			$data['index']			= '';
			$data['transaction_id'] 				= $transaction_id;
			$data['product_id']	= '';	
			$data['product_name'] = '';			
			$data['transaction_detail_qty'] 	= '';
			$data['transaction_detail_price'] 	= '';
			$data['transaction_detail_purchase_price'] = '';
			$data['transaction_detail_total_price'] = '';
			$data['transaction_detail_description'] = '';
		} else {
			
			$data['index']			= $index;
			$data['transaction_id'] 				= $transaction_id;
			$data['product_id']	= array_shift($this->input->post('transient_product_id'));
			$data['product_code']	= array_shift($this->input->post('transient_product_code'));
			$data['product_stock_id']	= array_shift($this->input->post('transient_product_stock_id'));
			$data['product_name'] = array_shift($this->input->post('transient_product_name'));
			$data['transaction_detail_qty'] 	= array_shift($this->input->post('transient_transaction_detail_qty'));
			$data['transaction_detail_price'] = array_shift($this->input->post('transient_transaction_detail_price'));
			$data['transaction_detail_qty'] = array_shift($this->input->post('transient_transaction_detail_qty'));
			$data['transaction_detail_total_price'] = array_shift($this->input->post('transient_transaction_detail_total_price'));
			
		}		
		$this->render->add_form('app/normal_sales_transaction/transient_form', $data);
		$this->render->show_buffer();
	}
	
}
