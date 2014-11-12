<?php 

class Purchase_report extends CI_Controller 
{	
	function __construct()
	{
		parent::__construct();	
		$this->load->model('purchase_report_model');
		$this->load->library('render');
		
		// set kode module ini .. misal usr
		$this->access->set_module('report.purchase_report');
		// default access adalah User
		$this->load->library('access');
		
	
	}
	function index()
	{

		$this->render->add_view('app/purchase_report/list');
		$this->render->build('Data Transaksi Pembelian');
		$this->render->show('Transaksi Pembelian');
		
	}
	function table_controller()
	{
		$data = $this->purchase_report_model->list_controller();
		send_json($data); 
	}
	
	function detail_table_loader() {
       
        $data = $this->purchase_report_model->list_controller();
        $sort_id = 0;
        
        foreach ($data as $key => $value) {
            $data[$key] = array(
				form_transient_pair('transient_date', $value['transaction_date']),
				form_transient_pair('transient_cabang', $value['stand_name']),
				form_transient_pair('transient_kode', $value['transaction_code']),
                form_transient_pair('transient_jenis_transaksi', $value['transaction_type_name']),
				form_transient_pair('transient_total', $value['transaction_total_price']),
            );
        }
        send_json(make_datatables_list($data));
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
			$result = $this->purchase_report_model->read_id($id);
			
			if ($result) // cek dulu apakah data ditemukan 
			{
				$data = $result;
				$data['row_id'] = $id;		
				$data['vendor_id'] = $result['subject_id'];	
				
			}
		}	
		
		
		$data['cbo_transaction_payment_method'] 		= array('1' => 'Cash', '2' => 'Kredit', '3' => 'Transfer');
		$data['cbo_transaction_ppn'] 		= array('0' => 'Tanpa PPN', '1' => 'PPN 10%');
		
		$this->load->model('global_model');
		$this->load->helper('form');
		
		$this->render->add_form('app/purchase_report/form', $data);
		$this->render->build('Pembelian');
		$this->render->add_view('app/purchase_report/transient_list');
		$this->render->build('Data Produk');
		//if($id){
		//$this->access->generate_log_view($id);
		//}
		$this->render->show('Pembelian');
	}
	function form_action($is_delete = 0) // jika 0, berarti insert atau update, bila 1 berarti delete
	{
		$this->load->library('form_validation');
		
		// bila operasinya DELETE -----------------------------------------------------------------------------------------		
		if($is_delete)
		{
			$this->load->model('purchase_report_model');
			$id = $this->input->post('row_id');
			$is_process_error = $this->purchase_report_model->delete($id);
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
		$data['transaction_type_id'] 		= 1;
		$data['transaction_date'] 			= $this->input->post('i_transaction_date');
		$data['transaction_status']			= 1;
		$data['subject_id']					= $this->input->post('i_customer_id');
		$data['transaction_payment_method_id']	= $this->input->post('i_transaction_payment_method');
		$data['transaction_description']	= $this->input->post('i_transaction_description');
		$data['transaction_approval']		= null;
		
		$list_product_id		= $this->input->post('transient_product_id');
		$list_transaction_detail_qty	= $this->input->post('transient_transaction_detail_qty');
		$list_transaction_detail_price	 	= $this->input->post('transient_transaction_detail_price');
		$list_transaction_detail_total_price	= $this->input->post('transient_transaction_detail_total_price');
		
		$total_price = 0;
		
		$items = array();
		if($list_product_id){
		foreach($list_product_id as $key => $value)
		{
			$get_purchase_price = $this->purchase_report_model->get_purchase_price($list_product_id[$key]);
			
			$items[] = array(				
				'product_id'  => $list_product_id[$key],
				'price_id' => '1',
				'transaction_detail_qty'  => $list_transaction_detail_qty[$key],
				'transaction_detail_price'  => $list_transaction_detail_price[$key],
				'transaction_detail_purchase_price' => $get_purchase_price,
				'transaction_detail_total_price'  => $list_transaction_detail_total_price[$key]
			);
			$total_price += $list_transaction_detail_total_price[$key];
		}
		}
		
		$data['transaction_total_price'] = $total_price;
		
		//send_json_error($data['transaction_total_price']);
		
		if(empty($id)) // jika tidak ada id maka create
		{ 
			$data['transaction_code'] 			= format_code('transactions','transaction_code','PN',7);
			
			$error = $this->purchase_report_model->create($data, $items);
			send_json_action($error, "Data telah ditambah", "Data gagal ditambah");
		}
		else // id disebutkan, lakukan proses UPDATE
		{
			$error = $this->purchase_report_model->update($id, $data, $items);
			send_json_action($error, "Data telah direvisi", "Data gagal direvisi");
		}		
	}
	function detail_list_loader($transaction_id=0)
	{
		if($transaction_id == 0)send_json(make_datatables_list(null)); 
				
		$data = $this->purchase_report_model->detail_list_loader($transaction_id);
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
		$this->render->add_form('app/purchase_report/transient_form', $data);
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
		
		$check_stock = $this->purchase_report_model->check_stock($product_stock_id);
		
		$get_data_product = $this->purchase_report_model->get_data_product($product_stock_id);
		
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
		
		$query = $this->purchase_report_model->load_product_stock($id);
		$data = array();
		
		foreach($query->result_array() as $row)
		{
			$data['product_stock_id'] = $row['product_stock_id'];
			$data['product_code'] = $row['product_code'];
			$data['price'] = $row['user_price'];
			$data['qty'] = 1;
			$data['total'] = $row['user_price'];
		}
		send_json_message('Product Stock', $data);
	}
	
	function report($id = 0){
	
	if($id){
	   $this->load->model('global_model');
	   
	   $result = $this->purchase_report_model->read_id($id);
			
			if ($result) // cek dulu apakah data ditemukan 
			{
				$data = $result;
				$data['row_id'] = $id;		
				$data['vendor_id'] = $result['subject_id'];	
				$data['transaction_date'] = strtotime($result['transaction_date']);
				$data['vendor_name'] = ($result['vendor_name']) ? $result['vendor_name'] : "-";
				
			}
			
		$data_detail = $this->purchase_report_model->get_data_detail($id);
	   
	   $this->global_model->create_report('Transaksi Pembelian','report/purchase_report.php', $data, $data_detail,'header.php');
	}
	}
	
	function report_date(){
		
		$data = array();
				$data['transaction_code']					= '';
				$data['row_id']					= '';
				$data['stand_id']					= '';
				$data['transaction_date']					= '';
				$data['vendor_id']					= '';
								
		$this->load->helper('form');
		$this->render->add_form('app/purchase_report/form_report',$data,true);
		$this->render->build('Pembelian');
		$this->render->show('Pembelian');
	}
}
