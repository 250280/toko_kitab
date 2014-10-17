<?php 

class Moving_transaction extends CI_Controller 
{	
	function __construct()
	{
		parent::__construct();	
		$this->load->model('moving_transaction_model');
		$this->load->library('render');
		
		// set kode module ini .. misal usr
		$this->access->set_module('transaction.moving_transaction');
		// default access adalah User
		$this->load->library('access');
		
	
	}
	function index()
	{
		$this->render->add_view('app/moving_transaction/list');
		$this->render->build('Transaksi Pindah Gudang');
		$this->render->show('Transaksi Pindah Gudang');
		
	}
	function table_controller()
	{
		$data = $this->moving_transaction_model->list_controller();
		send_json($data); 
	}
	
	function form($id = 0)
	{
		
		$data = array();
		if ($id == 0) {
			$data['row_id'] = '';
			$data['transaction_code']			= format_code('transactions','transaction_code','PG',7);
			$data['stand_id'] = '';
			$data['transaction_date'] = date('d/m/Y');
			$data['stand_to_id'] = '';
			$data['transaction_description'] = '';
			$data['transaction_total_price'] = '0';
			$data['transaction_sent_price'] = '0';	
			$this->load->model('global_model');
			$period_id = $this->global_model->get_active_period();
			$data['period_id'] = $period_id[0];
			
		} else {
			$result = $this->moving_transaction_model->read_id($id);
			
			if ($result) // cek dulu apakah data ditemukan 
			{
				$data = $result;
				$data['row_id'] = $id;		
				$data['stand_to_id'] = $result['subject_id'];	
				$data['transaction_date'] = date('d/m/Y', strtotime($result['transaction_date']));
			}
		}	
		
		
		$this->load->helper('form');
		
		$this->render->add_form('app/moving_transaction/form', $data);
		$this->render->build('Transaksi Pindah Gudang');
		
		$this->render->add_view('app/moving_transaction/transient_list');
		$this->render->build('Data Produk');
		
		$this->render->show('Transaksi Pindah Gudang');
	}
	
	function form_action($is_delete = 0) // jika 0, berarti insert atau update, bila 1 berarti delete
	{
		$this->load->library('form_validation');
		
		// bila operasinya DELETE -----------------------------------------------------------------------------------------		
		if($is_delete)
		{
			$this->load->model('moving_transaction_model');
			$id = $this->input->post('row_id');
			$is_process_error = $this->moving_transaction_model->delete($id);
			send_json_action($is_process_error, "Data telah dihapus", "Data gagal dihapus");
		}
		
		// bila bukan delete, berarti create atau update ------------------------------------------------------------------
	
		// definisikan kriteria data
		$this->form_validation->set_rules('i_period_id','Periode','trim|required');
		$this->form_validation->set_rules('i_transaction_code','Kode','trim|min_length[3]|max_length[50]|required');
		$this->form_validation->set_rules('i_stand_id','Dari Cabang','trim|required|integer');
		$this->form_validation->set_rules('i_stand_to_id','Ke Cabang','trim|required|integer');
		$this->form_validation->set_rules('i_transaction_sent_price','Ongkos Kirim','trim|required|numeric');
		$this->form_validation->set_rules('i_transaction_date','Tanggal','trim|required|valid_date|sql_date');
		$this->form_validation->set_rules('i_transaction_description','Keterangan','trim|required');
		
		// cek data berdasarkan kriteria
		if ($this->form_validation->run() == FALSE) send_json_validate(); 

		$id = $this->input->post('row_id');
		$data['transaction_code'] 			= $this->input->post('i_transaction_code');
		$data['stand_id'] 					= $this->input->post('i_stand_id');
		$data['transaction_type_id'] 		= 9;
		$data['transaction_date'] 			= $this->input->post('i_transaction_date');
		$data['transaction_datetime'] 		= date("Y-m-d H:m:s");
		$data['transaction_total_price']	= 0;
		$data['subject_id']					= $this->input->post('i_stand_to_id');
		$data['transaction_payment_method_id']	= 1;
		$data['transaction_description']	= $this->input->post('i_transaction_description');
		$data['transaction_approval']		= null;
		$data['transaction_ppn']			= 0;
		$data['transaction_ppn_percent']	= 0;
		$data['transaction_ppn_value']		= 0;
		$data['transaction_sent_price']		= ($this->input->post('i_transaction_sent_price') ? $this->input->post('i_transaction_sent_price') : 0);
		$data['transaction_final_total_price']	= 0;
		$data['transaction_down_payment']		= 0;
		$data['transaction_sisa']			= 0;
		$data['period_id']					= $this->input->post('i_period_id');
		$data['transaction_payed']			= 0;
		$data['transaction_change']			= 0;
		$data['salesman_id']				= 0;
		
		
		
		
		
		$list_product_id		= $this->input->post('transient_product_id');
		$list_product_stock_id		= $this->input->post('transient_product_stock_id');
		$list_transaction_detail_qty	= $this->input->post('transient_transaction_detail_qty');
		
		if(!$list_product_id) send_json_error('Data item produk belum ada');
		if($data['stand_id'] == $data['subject_id']) send_json_error('Cabang tidak boleh sama');
		
		
		$items = array();
		if($list_product_id){
		foreach($list_product_id as $key => $value)
		{
			
			$items[] = array(				
				'product_id'  => $list_product_id[$key],
				'price_id' => '1',
				'product_stock_id' => $list_product_stock_id[$key],
				'transaction_detail_qty'  => $list_transaction_detail_qty[$key],
				'transaction_detail_price'  => 0,
				'transaction_detail_purchase_price' => 0,
				'transaction_detail_total_price'  => 0
			);
		
		}
		}
		
	
		//send_json_error($data['transaction_total_price']);
		
		if(empty($id)) // jika tidak ada id maka create
		{ 
			$data['transaction_code'] 			= format_code('transactions','transaction_code','PG',7);
			$data['transaction_status']			= 1;
			$data['transaction_active_status']			= 1;
			
			$error = $this->moving_transaction_model->create($data, $items);
			send_json_action($error, "Data telah ditambah", "Data gagal ditambah", $this->moving_transaction_model->insert_id);
		}
		else // id disebutkan, lakukan proses UPDATE
		{
			$error = $this->moving_transaction_model->update($id, $data, $items);
			send_json_action($error, "Data telah direvisi", "Data gagal direvisi");
		}		
	}
	function detail_list_loader($transaction_id=0)
	{
		if($transaction_id == 0)send_json(make_datatables_list(null)); 
				
		$data = $this->moving_transaction_model->detail_list_loader($transaction_id);
		$sort_id = 0;
		foreach($data as $key => $value) 
		{	
		
		$data[$key] = array(
				form_transient_pair('transient_product_id', $value['product_code'], $value['product_id'],
				
				array(
                    'transient_product_stock_id' => $value['product_stock_id'],
					'transient_product_code' => $value['product_code']
				)),
				form_transient_pair('transient_product_name', $value['product_name']),
				
				form_transient_pair('transient_transaction_detail_qty', $value['transaction_detail_qty'], $value['transaction_detail_qty'])
		);
		
		
		}		
		send_json(make_datatables_list($data)); 
	}
	function detail_form($transaction_id = 0) // jika id tidak diisi maka dianggap create, else dianggap edit
	{		
		$this->load->library('render');
		$index = $this->input->post('transient_index');
		if (strlen(trim($index)) == 0) {
					
			// TRANSIENT CREATE - isi form dengan nilai default / kosong
			$data['index']			= '';
			$data['transaction_id'] 				= $transaction_id;
			$data['product_id']	= '';	
			$data['product_stock_id']	= '';
			$data['product_stock_qty']	= '';		
			$data['product_name'] = '';			
			$data['transaction_detail_qty'] 	= '';
			$data['transaction_detail_price'] 	= '';
			$data['transaction_detail_purchase_price'] = '';
			$data['transaction_detail_total_price'] = '';
			$data['transaction_detail_description'] = '';
		} else {
			
			$data['index']			= $index;
			$data['transaction_id'] 				= $transaction_id;
			$data['product_id']	= array_shift($this->input->post('transient_product_id'));
			$data['product_code']	= array_shift($this->input->post('transient_product_code'));
			$data['product_stock_id']	= array_shift($this->input->post('transient_product_stock_id'));
			$data['product_name'] = array_shift($this->input->post('transient_product_name'));
			$data['transaction_detail_qty'] 	= array_shift($this->input->post('transient_transaction_detail_qty'));
			$data['transaction_detail_price'] = array_shift($this->input->post('transient_transaction_detail_price'));
			$data['transaction_detail_qty'] = array_shift($this->input->post('transient_transaction_detail_qty'));
			$data['transaction_detail_total_price'] = array_shift($this->input->post('transient_transaction_detail_total_price'));
			
		}		
		$this->render->add_form('app/moving_transaction/transient_form', $data);
		$this->render->show_buffer();
	}
	function detail_form_action()
	{		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('i_product_id', 'Produk', 'trim|required');
		$this->form_validation->set_rules('i_transaction_detail_qty', 'Jumlah', 'trim|required|numeric');
		
		$index = $this->input->post('i_index');		
		// cek data berdasarkan kriteria
		if ($this->form_validation->run() == FALSE) send_json_validate(); 
		
		$this->load->model('global_model');	
		
		$no 		= $this->input->post('i_index');
		$product_id 	= $this->input->post('i_product_id');
		$product_stock_id 		= $this->input->post('i_product_stock_id');
		$product_code 	= $this->input->post('i_product_code');
		$transaction_detail_qty 	= $this->input->post('i_transaction_detail_qty');
		
		$get_data_product = $this->moving_transaction_model->get_data_product($product_stock_id);
		
		if($transaction_detail_qty > $this->input->post('i_product_stock_qty')){
			send_json_error('Simpan gagal. Jumlah tidak boleh melebihi stok');
		}
	
		//send_json_error($no);
		
		$data = array(
				form_transient_pair('transient_product_id', $product_code, $product_id),
				form_transient_pair('transient_product_name', $get_data_product[1]),
				form_transient_pair('transient_transaction_detail_qty', $transaction_detail_qty, $transaction_detail_qty, 
				array(
                    'transient_product_stock_id' => $product_stock_id,
					'transient_product_code' => $product_code
				)
				)
		);
		 
		send_json_transient($index, $data);
	}
	
	function load_product_stock()
	{
		$id 	= $this->input->post('product_stock_id');
		
		$query = $this->moving_transaction_model->load_product_stock($id);
		$data = array();
		
		foreach($query->result_array() as $row)
		{
			$data['product_stock_id'] = $row['product_stock_id'];
			$data['product_id'] = $row['product_id'];
			$data['product_code'] = $row['product_code'];
			$data['product_stock_qty'] = $row['product_stock_qty'];
		}
		send_json_message('Product Stock', $data);
	}
	
	function report($id = 0){
	
	if($id){
	   $this->load->model('global_model');
	   
	   $result = $this->moving_transaction_model->read_id($id);
			
			if ($result) // cek dulu apakah data ditemukan 
			{
				$data = $result;
				$data['row_id'] = $id;		
				$data['customer_id'] = $result['subject_id'];	
				$data['transaction_date'] = strtotime($result['transaction_date']);
				$data['customer_name'] = ($result['customer_name']) ? $result['customer_name'] : "-";
				
			}
			
		$data_detail = $this->moving_transaction_model->get_data_detail($id);
	   
	   $this->global_model->create_report('moving_transaction', 'report/moving_transaction.php', $data, $data_detail);
	}
	}
}
