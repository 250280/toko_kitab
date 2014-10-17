<?php 

class Flow_transaction extends CI_Controller 
{	
	function __construct()
	{
		parent::__construct();	
		$this->load->model('flow_transaction_model');
		$this->load->library('render');
		
		// set kode module ini .. misal usr
		$this->access->set_module('accounting.flow_transaction');
		// default access adalah User
		$this->access->user_page();		
	}
	function index()
	{
		
		$this->render->add_view('app/flow_transaction/list');
		$this->render->build('Transaksi Harian');
		
		$this->render->show('Transaksi Harian');
	}
	function table_controller()
	{
		$data = $this->flow_transaction_model->list_controller();
		send_json($data); 
	}
	
	function form($id = 0)
	{
		$data = array();
		if ($id == 0) {
			$data['row_id'] = '';
			$data['period_id'] = '';
			$data['flow_transaction_desc'] = '';
			$data_list['total_debit'] ='';	
			$data_list['total_kredit'] = '';	
		} else {
			$result = $this->flow_transaction_model->read_id($id);
			
			if ($result) // cek dulu apakah data ditemukan 
			{
				$data = $result;
				$data['row_id'] = $id;	
				
				
			}
		}	
		$this->load->model('global_model');
		$this->load->helper('form');
		
		$list_nama_pendapatan = $this->flow_transaction_model->get_list_nama_pendapatan();
		$data_pendapatan = $this->flow_transaction_model->get_data_pendapatan($id);
			
		$this->render->add_form('app/flow_transaction/form', $data);
		$this->render->build('Transaksi Harian');
		$this->render->add_view('app/flow_transaction/transient_list', array('list' => $list_nama_pendapatan, 'data_pendapatan' => $data_pendapatan));
		$this->render->build('Detail Transaksi Harian');
		if($id){
		//$this->access->generate_log_view($id);
		}
		$this->render->show('Transaksi Harian');
	}
	
	function form_detail($id = 0)
	{
		$data = array();
		
	
				$data['row_id'] = '';
				$data['period_id'] = $id;	
				
			$list = $this->flow_transaction_model->get_list_nama_pendapatan();
				
				$no = 0;
				foreach($list as $item): 
          			
					$data['subject_id'][$no] = $item['coa_id'];
					$data['subject_name'][$no] = $item['coa_name'];
					$data['subject_value'][$no] = '';
    				$no++;
					
			 	endforeach; 	
				
				$data['no'] = $no;
			

		
		$this->load->model('global_model');
		$this->load->helper('form');
		
		$this->render->add_form('app/flow_transaction/form_detail', $data);
		$this->render->build('Transaksi Harian');
		$this->render->show('Transaksi Harian');
	}
	
	function form_detail_edit($id = 0)
	{
		$data = array();
		
	
				
				$result = $this->flow_transaction_model->read_id_item($id);
				if($result){
					$data = $result;
					$data['row_id'] = $id;
					$data['ft_date'] = date('d/m/Y', strtotime($data['ft_date']));
				}
				
				
			$list = $this->flow_transaction_model->get_list_nama_pendapatan_edit($id);
				
				$no = 0;
				foreach($list as $item): 
          			
					$data['subject_id'][$no] = $item['fti_id'];
					$data['subject_name'][$no] = $item['coa_name'];
					$data['subject_value'][$no] = $item['fti_value'];
    				$no++;
					
			 	endforeach; 	
				
				$data['no'] = $no;
			

		
		$this->load->model('global_model');
		$this->load->helper('form');
		
		$this->render->add_form('app/flow_transaction/form_detail_edit', $data);
		$this->render->build('Transaksi Harian');
		$this->render->show('Transaksi Harian');
	}
	
	function form_action($is_delete = 0) // jika 0, berarti insert atau update, bila 1 berarti delete
	{
		$this->load->library('form_validation');
		
		// bila operasinya DELETE -----------------------------------------------------------------------------------------		
		if($is_delete)
		{
			$this->load->model('flow_transaction_model');
			$id = $this->input->post('row_id');
			$is_process_error = $this->flow_transaction_model->delete($id);
			send_json_action($is_process_error, "Data telah dihapus", "Data gagal dihapus");
		}
		
		// bila bukan delete, berarti create atau update ------------------------------------------------------------------
	
		// definisikan kriteria data
		$no = $this->input->post('i_no');
		
		$this->form_validation->set_rules('i_ft_date','Tanggal','trim|required|valid_date|sql_date');
		for($i=0; $i<$no; $i++){
		$this->form_validation->set_rules('i_subject_value'.$i, 'Jumlah '.($i+1), 'trim|required|is_numeric'); // gunakan selalu trim di awal
		}
	
		// cek data berdasarkan kriteria
		if ($this->form_validation->run() == FALSE) send_json_validate(); 
		
		$id = $this->input->post('row_id');
		$data['period_id'] 					= $this->input->post('i_period_id');
		$data['ft_date'] 					= $this->input->post('i_ft_date');
		
		
		
		
		
			if(empty($id)) // jika tidak ada id maka create
			{ 
			
				$data_is_exist = $this->flow_transaction_model->data_is_exist($data['period_id'], $data['ft_date']);
				if($data_is_exist){
					send_json_error("Simpan gagal. Data penjualan untuk tanggal ". $data['ft_date'] ." sudah ada");
				}
				
				for($u=0; $u<$no; $u++){
					$items['coa_id'][$u] = $this->input->post('i_subject_id'.$u);
					$items['fti_value'][$u] = $this->input->post('i_subject_value'.$u);
				}
			
				$error = $this->flow_transaction_model->create($data, $items, $no);
				send_json_action($error, "Data telah ditambah", "Data gagal ditambah");
			}
			else // id disebutkan, lakukan proses UPDATE
			{
				for($u=0; $u<$no; $u++){
					$items['fti_id'][$u] = $this->input->post('i_subject_id'.$u);
					$items['fti_value'][$u] = $this->input->post('i_subject_value'.$u);
				}
				
				$error = $this->flow_transaction_model->update($id, $data, $items, $no);
				send_json_action($error, "Data telah direvisi", "Data gagal direvisi");
			}		
		
	}
	function detail_list_loader($period_id=0)
	{
		if($period_id == 0)send_json(make_datatables_list(null)); 
				
		$data = $this->flow_transaction_model->detail_list_loader($period_id);
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
		$this->render->add_form('app/flow_transaction/transient_form', $data);
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
		
		$period_name 	= $this->flow_transaction_model->get_period_name($period);
		$market_name 	= $this->flow_transaction_model->get_market_name($market);
		$coa_name	 	= $this->flow_transaction_model->get_coa_name($coa);
		$coa_hierarchy	= $this->flow_transaction_model->get_coa_hierarchy($coa);
		
		
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
