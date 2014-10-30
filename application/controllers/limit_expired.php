<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Limit_expired extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->library('render');
		$this->load->model('limit_expired_model');
		$this->load->library('access');
		$this->access->set_module('master.limit_expired');
	}
	
	function index(){
		
		$this->render->add_view('app/limit_expired/list');
		$this->render->build('Produk Akan Expired');
		$this->render->show('Produk Akan Expired');
	}
	
	function table_controller(){
		$data = $this->limit_expired_model->list_controller();
		send_json($data);
	}
	
	
}
