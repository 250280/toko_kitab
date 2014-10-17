<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Top_product extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->library('render');
		$this->load->model('top_product_model');
		$this->load->library('access');
		$this->access->set_module('report.top_product');
	}
	
	function index(){
		
		$data_product = $this->top_product_model->get_data_product();
		
		
		$this->render->add_view('app/top_product/list', array('data_product' => $data_product));
		$this->render->build('Top 10 Produk');
		$this->render->show('Top 10 Produk');
	}
	
}
