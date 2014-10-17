<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employee extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->library('render');
		$this->load->model('employee_model');
		$this->load->library('access');
		$this->access->set_module('employee.employee');
	}
	
	function index(){
		
		$this->render->add_view('app/employee/list');
		$this->render->build('Data Pegawai');
		$this->render->show('Pegawai');
	}
	
	function table_controller(){
		$data = $this->employee_model->list_controller();
		send_json($data);
	}
	
	function form($id = 0){
		$data = array();
		if($id==0){
			$data['row_id']					= '';
			$data['employee_nip']			= format_code('employees','employee_nip','E',7);
			$data['employee_name']			= '';
			$data['employee_birth']	= '';
			$data['employee_gender']		= '';
			$data['employee_position_id']	= '';
			$data['employee_ktp']	= '';
			$data['employee_address']	= '';
			$data['employee_phone']	= '';
			$data['employee_email']	= '';
			$data['employee_bank_number']	= '';
			$data['employee_bank_name']	= '';
			$data['employee_bank_beneficiary']	= '';
			$data['stand_id']	= '';
			$data['employee_pic']	= '';
		}else{
			$result = $this->employee_model->read_id($id);
			if($result){
				$data = $result;
				$data['row_id'] = $id;
				$data['employee_birth'] = date('d/m/Y', strtotime($data['employee_birth']));
			}
		}
		$this->render->add_js('ajaxfileupload');	
		$this->load->helper('form');
		$this->render->add_form('app/employee/form', $data);
		$this->render->build('Pegawai');
		$this->render->show('Pegawai');
		//$this->access->generate_log_view($id);
	}
	
	function form_action($is_delete = 0){
		
		$id = $this->input->post('row_id');
			
		if($is_delete){
			$is_proses_error = $this->employee_model->delete($id);
			send_json_action($is_proses_error, "Data telah dihapus");
		}
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('i_nip','NIP', 'trim|required');
		$this->form_validation->set_rules('i_name','Nama', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('i_birth','Tanggal Lahir', 'trim|required|valid_date|sql_date');
		$this->form_validation->set_rules('i_gender','Jenis Kelamin', 'trim|required');
		$this->form_validation->set_rules('i_position_id','Jabatan', 'trim|required');
		$this->form_validation->set_rules('i_stand_id','Cabang', 'trim|required');
		$this->form_validation->set_rules('i_ktp','KTP', 'trim|required');
		$this->form_validation->set_rules('i_phone','No Telepon', 'trim|required');
		$this->form_validation->set_rules('i_email','Email', 'trim|required');
		$this->form_validation->set_rules('i_address','Alamat', 'trim|required');
		$this->form_validation->set_rules('i_bank_number','Rekening Bank', 'trim|required');
		$this->form_validation->set_rules('i_bank_name','Nama Bank', 'trim|required');
		$this->form_validation->set_rules('i_bank_beneficiary','Atas Nama', 'trim|required');
		
		if($this->form_validation->run() == FALSE) send_json_validate();
		
		$data['employee_nip'] 				= $this->input->post('i_nip');
		$data['employee_name'] 				= $this->input->post('i_name');
		$data['employee_birth'] 			= $this->input->post('i_birth');
		$data['employee_gender'] 			= $this->input->post('i_gender');
		$data['employee_position_id'] 		= $this->input->post('i_position_id');
		$data['employee_ktp'] 				= $this->input->post('i_ktp');
		$data['employee_address'] 			= $this->input->post('i_address');
		$data['employee_phone'] 			= $this->input->post('i_phone');
		$data['employee_email'] 			= $this->input->post('i_email');
		$data['employee_bank_number'] 		= $this->input->post('i_bank_number');
		$data['employee_bank_name'] 		= $this->input->post('i_bank_name');
		$data['employee_bank_beneficiary'] 		= $this->input->post('i_bank_beneficiary');
		$data['stand_id'] 					= $this->input->post('i_stand_id');
		$data['employee_pic'] 							= $this->input->post('i_photo');
		$old_pic 										= $this->input->post('i_oldphoto');


		
		if(empty($id)){
			$data['employee_active_status'] 					= 1;
			$data['employee_nip']			= format_code('employees','employee_nip','E',7);
			$error = $this->employee_model->create($data);
			if($error)if($data['employee_pic'])rename($this->config->item('upload_tmp').$data['employee_pic'], $this->config->item('upload_storage')."img_employee/".$data['employee_pic']);	
			send_json_action($error, "Data telah ditambah", "Data gagal ditambah");
		}else{
			if($data['employee_pic'] != $old_pic) rename($this->config->item('upload_tmp').$data['employee_pic'], $this->config->item('upload_storage')."img_employee/".$data['employee_pic']);
			$error = $this->employee_model->update($id, $data);
			send_json_action($error, "Data telah direvisi", "Data gagal direvisi");
		}
		
	}
	
	function do_upload()
	{		
		//$this->load->library('blob');
		//$blob = $this->blob->send('fileToUpload', BLOB_ALLOW_IMAGES, 1);
		$config['upload_path'] = 'tmp/';
		$config['allowed_types'] = 'gif|jpg|png';
		//$config['max_size']	= '1000';
		//$config['max_width']  = '1024';
		//$config['max_height']  = '768';
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload('fileToUpload'))
		{
			$output = array('error' => strip_tags($this->upload->display_errors()));
			debug($output);
			//$output = array('error' => print_r($error,1), 'msg'=>'test');
			send_json($output);
			//$this->load->view('upload_form', $error);
		}	
		else
		{
			$data = $this->upload->data();
			$output = array('error' => '', 'value' => $data['file_name']);
			send_json($output);
			//$this->load->view('upload_success', $data);
		}
	}
	
}
