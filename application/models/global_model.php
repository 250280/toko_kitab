<?php

class Global_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function get_unit()
	{
		$this->db->select('*');
		$this->db->order_by('unit_id','ASC');
		$query = $this->db->get('units');	

		$data = array();
		foreach($query->result_array() as $row)
		{
			$data[$row['unit_id']] = $row['unit_name'];
		}
		return $data;
	}
	function get_transaction_payment_method()
	{
		$this->db->select('*');
		$this->db->order_by('transaction_payment_method_id','ASC');
		$query = $this->db->get('transaction_payment_methods');	

		$data = array();
		foreach($query->result_array() as $row)
		{
			$data[$row['transaction_payment_method_id']] = $row['transaction_payment_method_name'];
		}
		return $data;
	}
	
	function get_active_period()
	{
		$sql = "select period_id, period_name from periods where period_closed = 1";
		$query = $this->db->query($sql);
		//query();
		$result = null ; 
		foreach($query->result_array() as $row)	$result = format_html($row);
		return array($result['period_id'], $result['period_name']);
		
	}
	
	
	function get_coa($id)
	{
		$query = $this->db->get_where('coas', array('coa_id' => $id), 1);
		$data = array();
		if ($query->num_rows() > 0)
		{
			$data = $query->row_array();
			return $data;
		}
		return $data;
	}
	
	function get_market_value($id)
	{
		$this->db->select('market_id,market_code,market_name', 1);		
		$this->db->from('markets');
		$this->db->where('market_id', $id);
		
		$query = $this->db->get();
		return $query->row_array();
	}	
	
	
	function create_report($title, $content, $data = '', $data_detail = '', $header){
		
	    $this->load->library('html2pdf');
	    $this->html2pdf->folder('report_new/');
	    
	    //Set the filename to save/download as
	    $this->html2pdf->filename($title.'.pdf');
	    
	    //Set the paper defaults
	    $this->html2pdf->paper( 'A5', 'landscape');
	    
	   	

	    $mydata = $this->load->view($header,$data,TRUE) ;
	    $mydata .= $this->load->view($content, array('data' => $data, 'data_detail' => $data_detail) ,TRUE) ;
	    $mydata .= $this->load->view('footer.php',$data,TRUE) ;
	    //Load html view
	    $this->html2pdf->html($mydata);
	    
	    if($this->html2pdf->create('save')) {
	    	header('Content-type: application/pdf');
			readfile('report_new/'.$title.'.pdf');
	    }
	}
	
	function create_report_neraca($title, $content, $data = '', $data_coa1, $data_coa2, $data_coa3, $header){
		
	    $this->load->library('html2pdf');
	    $this->html2pdf->folder('report_new/');
	    
	    //Set the filename to save/download as
	    $this->html2pdf->filename($title.'.pdf');
	    
	    //Set the paper defaults
	    $this->html2pdf->paper( 'A4', 'portrait');
	    
	   	

	    $mydata = $this->load->view($header,$data,TRUE) ;
	    $mydata .= $this->load->view($content, array(
			'data' => $data, 
			'data_coa1' => $data_coa1,
			'data_coa2' => $data_coa2,
			'data_coa3' => $data_coa3,
			
		) ,TRUE) ;
	    $mydata .= $this->load->view('footer.php',$data,TRUE) ;
	    //Load html view
	    $this->html2pdf->html($mydata);
	    
	    if($this->html2pdf->create('save')) {
	    	header('Content-type: application/pdf');
			readfile('report_new/'.$title.'.pdf');
	    }
	}
}

# -- end file -- #
