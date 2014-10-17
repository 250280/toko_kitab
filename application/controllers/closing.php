<?php

class Closing extends CI_Controller 
{
	
	var $id ; 
	
	function __construct()
	{
		
		parent::__construct();
		$this->load->library('render');
		$this->access->set_module('accounting.closing');
		$this->access->user_page();
		$this->load->model('global_model');
		$this->load->model('closing_model');
		
	}// end of function
	
	function index()
	{
		$data = array();
		
		$period_new = $this->closing_model->get_period_new();
		$data['period_id'] = $period_new;
		$data['close_mode'] = 0;
		
		//$data['period'] = $this->global_model->get_period();	
		$this->load->helper('form');
		$this->render->add_form('app/closing/view',$data);
		$this->render->build('Tutup Buku');
		$this->render->show('Tutup Buku');
		
	}// end of function 
	
	function form_closing()
	{
		$data = array();
		//$data['periode_id'] = 0;
		$data['close_mode'] = 1;
			
		$this->render->add_form('app/closing_fix/view',$data);
		$this->render->build('Tutup Buku Permanen');
		$this->render->show('Tutup Buku Permanen');
		
	}// end of function 
		
	function action()
	{
		
		
		$period_id		= $this->input->post('i_period');
		$next_period	= $this->input->post('n_period');
		$close	 		= $this->input->post('i_closed');
		$desc	 		= $this->input->post('i_desc');
		$week 			= $this->closing_model->get_week(date("Y-m-d H:m:s"));
		
		//send_json_error($period_id." - ".$next_period);
		
		if($next_period == $period_id) send_json_error('Periode closing tidak boleh sama dengan periode selanjutnya');
		if($next_period < $period_id) send_json_error('Periode selanjutnya tidak boleh lebih kecil dari periode closing');
		
		//$is_closed = $this->closing_model->is_closed($period_id);		
		//if ($is_closed) send_json_error('Transaksi untuk periode tersebut sudah ditutup');
				
		$this->db->trans_start();
		$this->closing_model->execute($period_id, $next_period, $close, $desc, $week);
		$this->db->trans_complete();
		
		send_json_action($this->db->trans_status(), "Proses tutup buku telah berhasil", "Proses tutup buku gagal");	
	}	
}
// END Closing Class

/* End of file closing.php */
/* Location: ./application/controllers/closing.php */
