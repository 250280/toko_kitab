<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Limit_stock extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->library('render');
		$this->load->model('limit_stock_model');
		$this->load->library('access');
		$this->access->set_module('master.limit_stock');
	}
	
	function index(){
		
		$this->render->add_view('app/limit_stock/list');
		$this->render->build('Stok Menipis');
		$this->render->show('Stok Menipis');
	}
	
	function table_controller(){
		$data = $this->limit_stock_model->list_controller();
		send_json($data);
	}
	
	
}
