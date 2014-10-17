<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Top_customer extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->library('render');
		$this->load->model('top_customer_model');
		$this->load->library('access');
		$this->access->set_module('report.top_customer');
	}
	
	function index(){
		
		$data_customer = $this->top_customer_model->get_data_customer();
		
		
		$this->render->add_view('app/top_customer/list', array('data_customer' => $data_customer));
		$this->render->build('Top 10 Pelanggan');
		$this->render->show('Top 10 Pelanggan');
	}
	
	
	
}
