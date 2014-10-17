<?php 

class Beginning_balance extends CI_Controller 
{	
	function __construct()
	{
		parent::__construct();	
		$this->load->model('beginning_balance_model');
		$this->load->library('render');
		
		// set kode module ini .. misal usr
		$this->access->set_module('accounting.beginning_balance');
		// default access adalah User
		$this->access->user_page();		
	}
	function index()
	{
		$data_is_exist = $this->beginning_balance_model->data_is_exist();
		
		if($data_is_exist){
			$data['akses'] = 1;
		}else{
			$data['akses'] = 0;
		}
		$this->render->add_view('app/beginning_balance/list', $data);
		$this->render->build('Saldo Awal');
		
		$this->render->show('Saldo Awal');
	}
	function table_controller()
	{
		$data = $this->beginning_balance_model->list_controller();
		send_json($data); 
	}
	
	function form($id = 0)
	{
		$data = array();
		if ($id == 0) {
			$data['row_id'] = '';
			$data['period_id'] = '';
			$data['beginning_balance_desc'] = '';
			$data_list['total_debit'] ='';	
			$data_list['total_kredit'] = '';	
		} else {
			$result = $this->beginning_balance_model->read_id($id);
			
			if ($result) // cek dulu apakah data ditemukan 
			{
				$data = $result;
				$data['row_id'] = $id;	
				$data_list['total_debit'] = $result['beginning_balance_debit'];	
				$data_list['total_kredit'] = $result['beginning_balance_credit'];	
				
			}
		}	
		$this->load->model('global_model');
		$this->load->helper('form');
		
		
		
		$this->render->add_form('app/beginning_balance/form', $data);
		$this->render->build('Entry Saldo Awal');
		$this->render->add_view('app/beginning_balance/transient_list', $data_list);
		$this->render->build('Data Item Saldo Awal');
		if($id){
		//$this->access->generate_log_view($id);
		}
		$this->render->show('Saldo Awal');
	}
	function form_action($is_delete = 0) // jika 0, berarti insert atau update, bila 1 berarti delete
	{
		$this->load->library('form_validation');
		
		// bila operasinya DELETE -----------------------------------------------------------------------------------------		
		if($is_delete)
		{
			$this->load->model('beginning_balance_model');
			$id = $this->input->post('row_id');
			$is_process_error = $this->beginning_balance_model->delete($id);
			send_json_action($is_process_error, "Data telah dihapus", "Data gagal dihapus");
		}
		
		// bila bukan delete, berarti create atau update ------------------------------------------------------------------
	
		// definisikan kriteria data
		$this->form_validation->set_rules('i_period_id','Periode','trim|required|integer');
	
		// cek data berdasarkan kriteria
		if ($this->form_validation->run() == FALSE) send_json_validate(); 
		
		$id = $this->input->post('row_id');
		$data['period_id'] 					= $this->input->post('i_period_id');
		$data['beginning_balance_desc'] 	= $this->input->post('i_beginning_balance_desc');
		
		$list_market_id	= $this->input->post('transient_market_id');
		$list_coa_id	= $this->input->post('transient_coa_name');
		$list_balance_date		= $this->input->post('transient_balance_date');
		$list_period_id		= $this->input->post('transient_period');
		$list_debit	 	= $this->input->post('transient_coa_debit');
		$list_kredit	= $this->input->post('transient_coa_kredit');
		
		if(!$list_market_id){ send_json_error("Simpan gagal. Data item saldo belum diisi"); }
		
		$total_debit = 0;
		$total_kredit = 0;
		
		$items = array();
		if($list_market_id){
		foreach($list_market_id as $key => $value)
		{
			$items[] = array(				
				'market_id'  => $list_market_id[$key],
				'coa_id'  => $list_coa_id[$key],
				'balance_date'  => $list_balance_date[$key],
				'period_id'  => $list_period_id[$key],
				'balance_debit'  => $list_debit[$key],
				'balance_kredit'  => $list_kredit[$key]
			);
			 $total_debit += $list_debit[$key];
			 $total_kredit += $list_kredit[$key];
		}
		}
		
		$data['beginning_balance_debit'] = $total_debit;
		$data['beginning_balance_credit'] = $total_kredit;
		
		if($total_debit != $total_kredit){
			send_json_error("Simpan gagal. Jumlah Debit dan Kredit harus sama");
		}else{
		
			if(empty($id)) // jika tidak ada id maka create
			{ 
				$error = $this->beginning_balance_model->create($data, $items);
				send_json_action($error, "Data telah ditambah", "Data gagal ditambah");
			}
			else // id disebutkan, lakukan proses UPDATE
			{
				$error = $this->beginning_balance_model->update($id, $data, $items);
				send_json_action($error, "Data telah direvisi", "Data gagal direvisi");
			}		
		}
	}
	function detail_list_loader($period_id=0)
	{
		if($period_id == 0)send_json(make_datatables_list(null)); 
				
		$data = $this->beginning_balance_model->detail_list_loader($period_id);
		$sort_id = 0;
		foreach($data as $key => $value) 
		{	
		
		$periode = $value['period_month']."/".$value['period_year'];
		
		$data[$key] = array(
				//form_transient_pair('transient_balance_id', $value['balance_id']),
				form_transient_pair('transient_period', $periode, $value['period_id']),
				form_transient_pair('transient_market_id', $value['market_name'], $value['market_id']),
				form_transient_pair('transient_coa_hierarchy', $value['coa_mode'], $value['coa_id']),
				form_transient_pair('transient_coa_name', $value['coa_name'], $value['coa_id']),
				form_transient_pair('transient_coa_debit', tool_money_format($value['balance_debit']), $value['balance_debit']),
				form_transient_pair('transient_coa_kredit', tool_money_format($value['balance_kredit']), $value['balance_kredit'],
				array(
					 'transient_balance_date' => $value['balance_date'],
				)
				
				),
				
				
			);
		}		
		send_json(make_datatables_list($data)); 
	}
	function detail_form($balance_id = 0) // jika id tidak diisi maka dianggap create, else dianggap edit
	{		
		$this->load->library('render');
		$index = $this->input->post('transient_index');
		if (strlen(trim($index)) == 0) {
					
			// TRANSIENT CREATE - isi form dengan nilai default / kosong
			$data['index']			= '';
			$data['balance_id'] 				= $balance_id;
			$data['market_id']	= '';	
			$data['coa_id'] = '';			
			$data['period_id'] 	= '';
			$data['balance_date'] = '';
			$data['balance_debit'] = '';
			$data['balance_kredit'] = '';
		} else {
			$data['index']				= $index;
			$data['balance_id'] 				= $balance_id;
			$data['market_id']	= array_shift($this->input->post('transient_market_id'));
			$data['coa_id'] = array_shift($this->input->post('transient_coa_name'));
			$data['period_id'] 	= array_shift($this->input->post('transient_period'));
			
			
			$d = explode("/", array_shift($this->input->post('transient_balance_date')));
			if(strlen($d[0]) == 4){
				$date = $d[2]."/".$d[1]."/".$d[0];
			}else{
				$date = array_shift($this->input->post('transient_balance_date'));
			}
			$data['balance_date'] = $date;
			
			$data['balance_debit'] = array_shift($this->input->post('transient_coa_debit'));
			$data['balance_kredit'] = array_shift($this->input->post('transient_coa_kredit'));
		}		
		$this->render->add_form('app/beginning_balance/transient_form', $data);
		$this->render->show_buffer();
	}
	function detail_form_action()
	{		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('i_market', 'Unit Pendidikan', 'trim|required|integer');
		$this->form_validation->set_rules('i_coa', 'Akun', 'trim|required|integer');
		$this->form_validation->set_rules('i_period_id2', 'Periode', 'trim|required|integer');
		$this->form_validation->set_rules('i_balance_date', 'Tanggal Entry', 'trim|valid_date|sql_date|required');
		$this->form_validation->set_rules('i_debit', 'Coa debit', 'trim|required|numeric');
		$this->form_validation->set_rules('i_kredit', 'Coa Kredit', 'trim|required|numeric');
		$index = $this->input->post('i_index');		
		// cek data berdasarkan kriteria
		if ($this->form_validation->run() == FALSE) send_json_validate(); 
		
		$this->load->model('global_model');	
		
		$balance_id 	= '';
		$market 		= $this->input->post('i_market');
		$coa 			= $this->input->post('i_coa');
		$period 		= $this->input->post('i_period_id2');
		$debit 			= $this->input->post('i_debit');
		$kredit 		= $this->input->post('i_kredit');
		$date			= $this->input->post('i_balance_date');
		
		$period_name 	= $this->beginning_balance_model->get_period_name($period);
		$market_name 	= $this->beginning_balance_model->get_market_name($market);
		$coa_name	 	= $this->beginning_balance_model->get_coa_name($coa);
		$coa_hierarchy	= $this->beginning_balance_model->get_coa_hierarchy($coa);
		
		
		$data = array(
				//form_transient_pair('transient_balance_id', $balance_id),
				form_transient_pair('transient_period', $period_name, $period),
				form_transient_pair('transient_market_id', $market_name, $market),
				form_transient_pair('transient_coa_hierarchy', $coa_hierarchy, $coa),
				form_transient_pair('transient_coa_name', $coa_name, $coa),
				form_transient_pair('transient_coa_debit', tool_money_format($debit), $debit),
				form_transient_pair('transient_coa_kredit', tool_money_format($kredit) , $kredit,
				array(
					 'transient_balance_date' => $date,
				)
				)
		);
		 
		send_json_transient($index, $data);
	}
}
