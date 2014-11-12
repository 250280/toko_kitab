<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report_laba_rugi extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->library('render');
		$this->load->model('report_laba_rugi_model');
		$this->load->library('access');
		$this->access->set_module('report.laba_rugi');
	}
	
	function index(){
		
		$data = array();
		
			$data['row_id']				= '';
			$data['period_id']			= '';

		$this->load->helper('form');
		$this->render->add_form('app/report_laba_rugi/form', $data);
		$this->render->build('Laporan Laba Rugi');
		$this->render->show('Laporan Laba Rugi');
		//$this->access->generate_log_view($id);
	}
	
	
	function action_report(){
		
		$id = $this->input->post('i_period_id');
			
	
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('i_period_id','Periode', 'trim|required');
		
		if($this->form_validation->run() == FALSE) send_json_validate();
		
				
			$error = true;
			send_json_action($error, "Cetak berhasil", "Data gagal ditambah", $id);
	}
	
	function create_report($id = 0){
		
		$this->load->model('global_model');
	   
	  	$period = $this->report_neraca_model->get_period($id);
		
		$data['period_id'] = $id;
		$data['period_name'] = $period;
		
		$data_coa4 = $this->report_neraca_model->get_data_coa(4);
		$data_coa6 = $this->report_neraca_model->get_data_coa(6);
		 
	   	$this->global_model->create_report_laba_rugi('report_laba_rugi', 'report/report_laba_rugi.php', $data, $data_coa4,$data_coa6, 'header_normal.php');
		
	}
	
	
	
}
