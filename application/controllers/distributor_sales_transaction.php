<?php 

class Distributor_sales_transaction extends CI_Controller 
{	
	function __construct()
	{
		parent::__construct();	
		$this->load->model('distributor_sales_transaction_model');
		$this->load->library('render');
		
		// set kode module ini .. misal usr
		$this->access->set_module('transaction.distributor_sales_transaction');
		// default access adalah User
		$this->load->library('access');
		
	
	}
	function index()
	{
		$data = array();
		
			
		$data['row_id'] = '';
		$data['transaction_code']			= format_code('transactions','transaction_code','PD',7);
		$data['stand_id'] = '';
		$data['transaction_date'] = date('d/m/Y');
		$data['customer_id'] = '';
		$data['transaction_description'] = '';
		$data['transaction_payment_method_id'] = '';
		$data['transaction_ppn'] = '';
		$data['transaction_total_price'] = '';
		$data['transaction_sent_price'] = '0';	
		$data['transaction_down_payment'] = '0';	
		
		$data['cbo_transaction_ppn'] 		= array('0' => 'Tanpa PPN', '1' => 'PPN 10%');
		
		$this->load->helper('form');
		
		$this->load->model('global_model');
		$data['cbo_transaction_payment_method'] 			= $this->global_model->get_transaction_payment_method();
		
		$this->render->add_form('app/distributor_sales_transaction/form', $data);
		$this->render->build('Transaksi Penjualan Distributor');
		
		$this->render->add_view('app/distributor_sales_transaction/transient_list');
		$this->render->build('Data Produk');
		
		$this->render->add_view('app/distributor_sales_transaction/form_end', $data);
		$this->render->build('Pembayaran');
		
		
		$this->render->show('Transaksi Penjualan Distributor');
		
	}
	function table_controller()
	{
		$data = $this->distributor_sales_transaction_model->list_controller();
		send_json($data); 
	}
	
	function form($id = 0)
	{
		$data = array();
		if ($id == 0) {
			$data['row_id'] = '';
			$data['product_cat_id'] = '';
			$data['product_cat_code'] 			= format_code('product_categories', 'product_cat_code', 'PC', 3);
			$data['product_cat_name'] = '';
			$data['product_cat_description'] = '';
		} else {
			$result = $this->distributor_sales_transaction_model->read_id($id);
			
			if ($result) // cek dulu apakah data ditemukan 
			{
				$data = $result;
				$data['row_id'] = $id;		
				$data['customer_id'] = $result['subject_id'];	
				
			}
		}	
		
		
		$data['cbo_transaction_payment_method'] 		= array('1' => 'Cash', '2' => 'Kredit', '3' => 'Transfer');
		$data['cbo_transaction_ppn'] 		= array('0' => 'Tanpa PPN', '1' => 'PPN 10%');
		
		$this->load->model('global_model');
		$this->load->helper('form');
		
		$this->render->add_form('app/distributor_sales_transaction/form', $data);
		$this->render->build('Penjualan User');
		$this->render->add_view('app/distributor_sales_transaction/transient_list');
		$this->render->build('Data Produk');
		//if($id){
		//$this->access->generate_log_view($id);
		//}
		$this->render->show('Penjualan Normal');
	}
	function form_action($is_delete = 0) // jika 0, berarti insert atau update, bila 1 berarti delete
	{
		$this->load->library('form_validation');
		
		// bila operasinya DELETE -----------------------------------------------------------------------------------------		
		if($is_delete)
		{
			$this->load->model('distributor_sales_transaction_model');
			$id = $this->input->post('row_id');
			$is_process_error = $this->distributor_sales_transaction_model->delete($id);
			send_json_action($is_process_error, "Data telah dihapus", "Data gagal dihapus");
		}
		
		// bila bukan delete, berarti create atau update ------------------------------------------------------------------
	
		// definisikan kriteria data
		$this->form_validation->set_rules('i_transaction_code','Kode','trim|min_length[3]|max_length[50]|required');
		$this->form_validation->set_rules('i_stand_id','Cabang','trim|required|integer');
		$this->form_validation->set_rules('i_transaction_date','Tanggal','trim|required|valid_date|sql_date');
		$this->form_validation->set_rules('i_transaction_description','Keterangan','trim|required');
		
		// cek data berdasarkan kriteria
		if ($this->form_validation->run() == FALSE) send_json_validate(); 
		
		
		
		$id = $this->input->post('row_id');
		$data['transaction_code'] 			= $this->input->post('i_transaction_code');
		$data['stand_id'] 					= $this->input->post('i_stand_id');
		$data['transaction_type_id'] 		= 4;
		$data['transaction_date'] 			= $this->input->post('i_transaction_date');
		$data['subject_id']					= $this->input->post('i_customer_id');
		$data['transaction_payment_method_id']	= $this->input->post('i_transaction_payment_method');
		$data['transaction_description']	= $this->input->post('i_transaction_description');
		$data['transaction_approval']		= null;
		$data['transaction_sent_price']		= ($this->input->post('i_transaction_sent_price') ? $this->input->post('i_transaction_sent_price') : 0);
		$data['transaction_down_payment']		= ($this->input->post('i_transaction_down_payment') ? $this->input->post('i_transaction_down_payment') : 0);
		
		
		
		
		$list_product_id		= $this->input->post('transient_product_id');
		$list_product_stock_id		= $this->input->post('transient_product_stock_id');
		$list_transaction_detail_qty	= $this->input->post('transient_transaction_detail_qty');
		$list_transaction_detail_price	 	= $this->input->post('transient_transaction_detail_price');
		$list_transaction_detail_total_price	= $this->input->post('transient_transaction_detail_total_price');
		
		if(!$list_product_id) send_json_error('Data item produk belum ada');
		if($data['transaction_payment_method_id'] == '2' && $data['subject_id'] == "") send_json_error('Pembayaran kredit harus memasukkan data customer');
		
		$total_price = 0;
		
		$items = array();
		if($list_product_id){
		foreach($list_product_id as $key => $value)
		{
			$get_purchase_price = $this->distributor_sales_transaction_model->get_purchase_price($list_product_id[$key]);
			
			$items[] = array(				
				'product_id'  => $list_product_id[$key],
				'price_id' => '1',
				'product_stock_id' => $list_product_stock_id[$key],
				'transaction_detail_qty'  => $list_transaction_detail_qty[$key],
				'transaction_detail_price'  => $list_transaction_detail_price[$key],
				'transaction_detail_purchase_price' => $get_purchase_price,
				'transaction_detail_total_price'  => $list_transaction_detail_total_price[$key]
			);
			$total_price += $list_transaction_detail_total_price[$key];
		}
		}
		
		$data['transaction_total_price'] = $total_price;
		
		$data['transaction_final_total_price'] = $data['transaction_total_price'] + $data['transaction_sent_price'];
		
		if($data['transaction_payment_method_id'] == 2){
			$data['transaction_sisa'] = $data['transaction_final_total_price'] - $data['transaction_down_payment'];
		}else{
			$data['transaction_sisa'] = 0;
		}
		
		if($data['transaction_sisa'] == 0){
			$data['transaction_status'] = 1;
		}else{
			$data['transaction_status'] = 0;
		}
		//send_json_error($data['transaction_total_price']);
		
		if(empty($id)) // jika tidak ada id maka create
		{ 
			$data['transaction_code'] 			= format_code('transactions','transaction_code','PD',7);
			
			$error = $this->distributor_sales_transaction_model->create($data, $items);
			send_json_action($error, "Data telah ditambah", "Data gagal ditambah", $this->distributor_sales_transaction_model->insert_id);
		}
		else // id disebutkan, lakukan proses UPDATE
		{
			$error = $this->distributor_sales_transaction_model->update($id, $data, $items);
			send_json_action($error, "Data telah direvisi", "Data gagal direvisi");
		}		
	}
	function detail_list_loader($transaction_id=0)
	{
		if($transaction_id == 0)send_json(make_datatables_list(null)); 
				
		$data = $this->distributor_sales_transaction_model->detail_list_loader($transaction_id);
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
				form_transient_pair('transient_transaction_detail_price', tool_money_format($value['transaction_detail_price']), $value['transaction_detail_price']),
				form_transient_pair('transient_transaction_detail_qty', $value['transaction_detail_qty'], $value['transaction_detail_qty']),
				form_transient_pair('transient_transaction_detail_total_price', tool_money_format($value['transaction_detail_total_price']), $value['transaction_detail_total_price'])
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
		$this->render->add_form('app/distributor_sales_transaction/transient_form', $data);
		$this->render->show_buffer();
	}
	function detail_form_action()
	{		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('i_product_id', 'Produk', 'trim|required');
		$this->form_validation->set_rules('i_transaction_detail_price', 'Harga', 'trim|required|numeric');
		$this->form_validation->set_rules('i_transaction_detail_qty', 'Jumlah', 'trim|required|numeric');
		$this->form_validation->set_rules('i_transaction_detail_total_price', 'Total', 'trim|required|numeric');
		$index = $this->input->post('i_index');		
		// cek data berdasarkan kriteria
		if ($this->form_validation->run() == FALSE) send_json_validate(); 
		
		$this->load->model('global_model');	
		
		$no 		= $this->input->post('i_index');
		$product_id 	= $this->input->post('i_product_id');
		$product_stock_id 		= $this->input->post('i_product_stock_id');
		$product_code 	= $this->input->post('i_product_code');
		$transaction_detail_price 	= $this->input->post('i_transaction_detail_price');
		$transaction_detail_qty 	= $this->input->post('i_transaction_detail_qty');
		$transaction_detail_total_price 	= $this->input->post('i_transaction_detail_total_price');
		
		$check_stock = $this->distributor_sales_transaction_model->check_stock($product_stock_id);
		
		$get_data_product = $this->distributor_sales_transaction_model->get_data_product($product_stock_id);
		
		if($check_stock < $transaction_detail_qty){
			send_json_error('Simpan gagal. Jumlah penjualan tidak boleh melebihi stok');
		}
	
		//send_json_error($no);
		
		$data = array(
				form_transient_pair('transient_product_id', $product_code, $product_id),
				form_transient_pair('transient_product_name', $get_data_product[1]),
				form_transient_pair('transient_transaction_detail_price', tool_money_format($transaction_detail_price), $transaction_detail_price),
				form_transient_pair('transient_transaction_detail_qty', $transaction_detail_qty, $transaction_detail_qty),
				form_transient_pair('transient_transaction_detail_total_price', tool_money_format($transaction_detail_total_price), $transaction_detail_total_price,
				array(
                    'transient_product_stock_id' => $product_stock_id,
					'transient_product_code' => $product_code
				))
		);
		 
		send_json_transient($index, $data);
	}
	
	function load_product_stock()
	{
		$id 	= $this->input->post('product_stock_id');
		
		$query = $this->distributor_sales_transaction_model->load_product_stock($id);
		$data = array();
		
		foreach($query->result_array() as $row)
		{
			$data['product_stock_id'] = $row['product_stock_id'];
			$data['product_code'] = $row['product_code'];
			$data['price'] = $row['another_price'];
			$data['qty'] = 1;
			$data['total'] = $row['another_price'];
		}
		send_json_message('Product Stock', $data);
	}
	
	function report($id = 0){
	
	if($id){
	   $this->load->model('global_model');
	   
	   $result = $this->distributor_sales_transaction_model->read_id($id);
			
			if ($result) // cek dulu apakah data ditemukan 
			{
				$data = $result;
				$data['row_id'] = $id;		
				$data['customer_id'] = $result['subject_id'];	
				$data['transaction_date'] = strtotime($result['transaction_date']);
				$data['customer_name'] = ($result['customer_name']) ? $result['customer_name'] : "-";
				
			}
			
		$data_detail = $this->distributor_sales_transaction_model->get_data_detail($id);
	   
	   $this->global_model->create_report('distributor_sales_transaction', 'report/distributor_sales_transaction.php', $data, $data_detail);
	}
	}
}
