<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vendor extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->library('render');
		$this->load->model('vendor_model');
		$this->load->library('access');
		$this->access->set_module('master.vendor');
	}
	
	function index(){
		
		$this->render->add_view('app/vendor/list');
		$this->render->build('Data Vendor');
		$this->render->show('Vendor');
	}
	
	function table_controller(){
		$data = $this->vendor_model->list_controller();
		send_json($data);
	}
	
	function form($id = 0){
		$data = array();
		if($id==0){
			$data['row_id']				= '';
			$data['vendor_code']			= format_code('vendors','vendor_code','V',7);
			
			$data['vendor_name']			= '';
			$data['vendor_description']	= '';
			$data['vendor_phone']	= '';
			$data['vendor_email']	= '';
			$data['vendor_address']		= '';
		
		}else{
			$result = $this->vendor_model->read_id($id);
			if($result){
				$data = $result;
				$data['row_id'] = $id;
			}
		}

		$this->load->helper('form');
		$this->render->add_form('app/vendor/form', $data);
		$this->render->build('Vendor');
		$this->render->show('Vendor');
		//$this->access->generate_log_view($id);
	}
	
	function form_action($is_delete = 0){
		
		$id = $this->input->post('row_id');
			
		if($is_delete){
			$is_proses_error = $this->vendor_model->delete($id);
			send_json_action($is_proses_error, "Data telah dihapus");
		}
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('i_code','Kode', 'trim|required');
		$this->form_validation->set_rules('i_name','Nama', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('i_phone','Telepon', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('i_email','Email', 'trim|required');
		$this->form_validation->set_rules('i_address','Alamat', 'trim|required');
		
		if($this->form_validation->run() == FALSE) send_json_validate();
		
		$data['vendor_code'] 					= $this->input->post('i_code');
		$data['vendor_name'] 					= $this->input->post('i_name');
		$data['vendor_description'] 			= $this->input->post('i_description');
		$data['vendor_phone'] 					= $this->input->post('i_phone');
		$data['vendor_email'] 					= $this->input->post('i_email');
		$data['vendor_address'] 				= $this->input->post('i_address');
		
		if(empty($id)){
			$data['vendor_status'] 					= 1;
			
			$data['vendor_code']			= format_code('vendors','vendor_code','V',7);
			$error = $this->vendor_model->create($data);
			send_json_action($error, "Data telah ditambah", "Data gagal ditambah");
		}else{
			$error = $this->vendor_model->update($id, $data);
			send_json_action($error, "Data telah direvisi", "Data gagal direvisi");
		}
		
	}
	
	function load_class_grade()
	{
		$id 	= $this->input->post('id');
		
		$query = $this->vendor_model->load_class_grade($id);
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
		
		$query = $this->vendor_model->load_class_type($id);
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
		
		$query = $this->vendor_model->load_class_number($id);
		$data = array();
		
		foreach($query->result_array() as $row)
		{
			$data['class_number_name'] = $row['class_number_name'];
		}
		send_json_message('Abjad kelas', $data);
	}
	
}
