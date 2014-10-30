<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->library('render');
		//$this->load->model('customer_model');
		$this->load->library('access');
		$this->access->set_module('master.dashboard');
	}
	
	function index(){
		
		
		$this->load->library('render');
		
		
		$this->load->model('dashboard_model');
		$data_top_product = $this->dashboard_model->get_data_top_product();
		$data_top_customer = $this->dashboard_model->get_data_top_customer();
		$data_top_salesman = $this->dashboard_model->get_data_top_salesman();
		$data_limit_stock = $this->dashboard_model->get_data_limit_stock();
		$data_limit_expired = $this->dashboard_model->get_data_limit_expired();
		
		$get_period = $this->dashboard_model->get_period();
		
		//omset harian
		$get_pendapatan_utama = $this->dashboard_model->get_pendapatan_utama($get_period[0]);
		$get_pendapatan_utama = ($get_pendapatan_utama) ? $get_pendapatan_utama : 0;
		$list_flow_transaction = $this->dashboard_model->get_list_nama_pendapatan_edit($get_period[0]);
		
		//laba kotor
		$get_laba_utama = $this->dashboard_model->get_laba_utama($get_period[0]);
		$get_laba_utama = ($get_laba_utama) ? $get_laba_utama : 0;
		$list_profit = $this->dashboard_model->get_list_profit($get_period[0]);
		
		//dashboard
		$col1 = $this->dashboard_model->get_col1();
		$col2_utama = $this->dashboard_model->get_col2_utama();
		$col2_lain = $this->dashboard_model->get_col2_lain();
		$col2 = $col2_utama + $col2_lain;
		$col3_utama = $this->dashboard_model->get_col3_utama();
		$col3_lain = $this->dashboard_model->get_col3_lain();
		$col3 = $col3_utama + $col3_lain;
		$col4_name = $this->dashboard_model->get_col4_name();
		$col4_name = ($col4_name) ? $col4_name : "Tidak ada agenda";
		$col4_jumlah = $this->dashboard_model->get_col4_jumlah();
		$col4_jumlah = ($col4_jumlah) ? $col4_jumlah : "-";
				
		$this->render->add_view_dashboard(
				array(
					'content1' => 'app/dashboard/top_product',
					'content2' => 'app/dashboard/top_customer',
					'content3' => 'app/dashboard/limit_stock',
					'content4' => 'app/dashboard/flow_transaction',
					'content5' => 'app/dashboard/profit',
					'content6' => 'app/dashboard/limit_expired',
				)
				, array(
					'list_top_product' => $data_top_product, 
					'list_top_customer' => $data_top_customer,
					'list_top_salesman' => $data_top_salesman,
					'list_limit_stock' => $data_limit_stock,
					'list_limit_expired' => $data_limit_expired,
					'list_flow_transaction' => $list_flow_transaction,
					'active_period_id' => $get_period[0],
					'active_period_name' => $get_period[1],
					'pendapatan_utama' => $get_pendapatan_utama,
					'list_profit' => $list_profit,
					'laba_utama' => $get_laba_utama,
					'col1' => $col1,
					'col2' => $col2,
					'col3' => $col3,
					'col4_name' => $col4_name,
					'col4_jumlah' => $col4_jumlah
					));
		
		$data_title = array('Top 10 Produk', 'Top 10 Pelanggan', 'Stok Menipis');
		$this->render->build_dashboard($data_title, 'win_dashboard');

		$this->render->show('dashboard', 'dashboard');


	}
	
	function table_controller(){
		$data = $this->customer_model->list_controller();
		send_json($data);
	}
	
	function form($id = 0){
		$data = array();
		if($id==0){
			$data['row_id']				= '';
			$data['customer_number']			= format_code('customers','customer_number','C',7);
			
			$data['customer_name']			= '';
			$data['customer_ktp_number']	= '';
			$data['customer_description']	= '';
			$data['customer_phone']	= '';
			$data['customer_email']	= '';
			$data['customer_address']		= '';
			$data['salesman_id']							= '';
		
		}else{
			$result = $this->customer_model->read_id($id);
			if($result){
				$data = $result;
				$data['row_id'] = $id;
			}
		}

		$this->load->helper('form');
		$this->render->add_form('app/customer/form', $data);
		$this->render->build('pelanggan');
		$this->render->show('pelanggan');
		//$this->access->generate_log_view($id);
	}
	
	function form_action($is_delete = 0){
		
		$id = $this->input->post('row_id');
			
		if($is_delete){
			$is_proses_error = $this->customer_model->delete($id);
			send_json_action($is_proses_error, "Data telah dihapus");
		}
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('i_number','Kode', 'trim|required');
		$this->form_validation->set_rules('i_name','Nama', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('i_ktp_number','Nomor KTP/SIM', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('i_phone','Telepon', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('i_email','Email', 'trim|required');
		$this->form_validation->set_rules('i_address','Alamat', 'trim|required');
		
		if($this->form_validation->run() == FALSE) send_json_validate();
		
		$data['customer_number'] 					= $this->input->post('i_number');
		$data['customer_name'] 					= $this->input->post('i_name');
		$data['customer_ktp_number'] 					= $this->input->post('i_ktp_number');
		$data['customer_description'] 			= $this->input->post('i_description');
		$data['customer_phone'] 					= $this->input->post('i_phone');
		$data['customer_email'] 					= $this->input->post('i_email');
		$data['customer_address'] 				= $this->input->post('i_address');
		$data['salesman_id'] 				= ($this->input->post('i_salesman_id')) ? $this->input->post('i_salesman_id') : null;
		
		if(empty($id)){	
			$data['customer_number']			= format_code('customers','customer_number','C',7);
			
			$error = $this->customer_model->create($data);
			send_json_action($error, "Data telah ditambah", "Data gagal ditambah");
		}else{
			$error = $this->customer_model->update($id, $data);
			send_json_action($error, "Data telah direvisi", "Data gagal direvisi");
		}
		
	}
	
	function load_class_grade()
	{
		$id 	= $this->input->post('id');
		
		$query = $this->customer_model->load_class_grade($id);
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
		
		$query = $this->customer_model->load_class_type($id);
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
		
		$query = $this->customer_model->load_class_number($id);
		$data = array();
		
		foreach($query->result_array() as $row)
		{
			$data['class_number_name'] = $row['class_number_name'];
		}
		send_json_message('Abjad kelas', $data);
	}
	
}
