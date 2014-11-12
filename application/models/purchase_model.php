<?php
class Purchase_model extends CI_Model 
{
	var $trans_type = 1;
	var $insert_id = NULL;
	
	function __construct()
	{
		//parent::Model();
		//$this->sek_id = $this->access->sek_id;
	}
	
	function list_controller()
	{		
		$params 	= get_datatables_control();
		$limit 		= $params['limit'];
		$offset 	= $params['offset'];
		$category 	= $params['category'];
		$keyword 	= $params['keyword'];
		
		// map value dari combobox ke table
		// daftar kolom yang valid
		$columns['code'] = 'transaction_code';
		$columns['name'] = 'stand_name';
	
		$sort_column_index	= $params['sort_column'];
		$sort_dir		= $params['sort_dir'];
		
		$order_by_column[] = 'transaction_id';
		$order_by_column[] = 'transaction_date';
		$order_by_column[] = 'transaction_code';
		$order_by_column[] = 'stand_name';
		$order_by_column[] = 'transaction_final_total_price';
		
		$order_by = $order_by_column[$sort_column_index] . $sort_dir;
		
		$this->db->start_cache();			
		if (array_key_exists($category, $columns) && strlen($keyword) > 0) 
		{
			$this->db->like($columns[$category], $keyword);
		}
		$this->db->stop_cache();
		
		// hitung total record
		$this->db->select('COUNT(1) AS total', 1); // pastikan ada AS total nya, 1 bila isinya adalah function (dalam hal ini COUNT)
		$this->db->join('stands b', 'b.stand_id = a.stand_id');
		$this->db->where('transaction_type_id', 1);
		$query	= $this->db->get('transactions a'); 

		$row 	= $query->row_array(); // fungsi ci untuk mengambil 1 row saja dari query
		$total 	= $row['total'];		
		
		
		// proses query sesuai dengan parameter
		$this->db->select('a.*, b.stand_name', 1);
		$this->db->join('stands b', 'b.stand_id = a.stand_id');
		$this->db->where('transaction_type_id', 1);
		$this->db->order_by($order_by);
		// bila menggunakan paging gunakan limiter dan offseter
		if ($limit > 0) $this->db->limit($limit, $offset);
		$query = $this->db->get('transactions a');
		
		$data = array(); // inisialisasi variabel. biasakanlah, untuk mencegah warning dari php.
		foreach($query->result_array() as $row) {
					
			$row = format_html($row);
			
			$data[] = array(
				$row['transaction_id'], 
				$row['transaction_date'], 
				$row['transaction_code'], 
				$row['stand_name'],
				$row['transaction_final_total_price']
			); 
		}
		
		// kembalikan nilai dalam format datatables_control
		return make_datatables_control($params, $data, $total);
	}
	function read_id($id)
	{
		$this->db->select('a.*, b.transaction_type_name, c.vendor_name, d.transaction_payment_method_name', 1); // ambil seluruh data
		$this->db->join('transaction_types b','b.transaction_type_id = a.transaction_type_id');
		$this->db->join('transaction_payment_methods d','d.transaction_payment_method_id = a.transaction_payment_method_id');
		$this->db->join('vendors c', 'c.vendor_id = a.subject_id', 'left');
		$this->db->where('transaction_id', $id);
		$query = $this->db->get('transactions a', 1); // parameter limit harus 1
		$result = null; // inisialisasi variabel. biasakanlah, untuk mencegah warning dari php.
		foreach($query->result_array() as $row)	$result = format_html($row); // render dulu dunk!
		return $result; 
	}
	function delete($id)
	{
		$this->db->trans_start();
			$this->db->where('product_cat_id', $id);
		$this->db->delete('purchase_items');
		$this->db->where('product_cat_id', $id); // data yg mana yang akan di delete
		$this->db->delete('product_categories');
	
		$this->access->log_delete($id, 'Produk Kategori');
		$this->db->trans_complete();

		return $this->db->trans_status();
	}
	function create($data, $items)
	{
		$this->db->trans_start();
		$this->db->insert('transactions', $data);
		$id = $this->db->insert_id();
		
		//Insert items
		$index = 0;
		foreach($items as $row)
		{			
			$row['transaction_id'] = $id;
			$row['product_stock_id'] = $row['product_stock_id'];
			$this->db->insert('transaction_details', $row);
			
			//edit harga beli
			$this->db->where('product_id', $row['product_id']); // data yg mana yang akan di update
			$data_product_price['product_purchase_price'] = $row['transaction_detail_price'];
			$this->db->update('products', $data_product_price);
			
			//edit expired
			$this->db->where('product_id', $row['product_id']); // data yg mana yang akan di update
			$data_product_expired['expired'] = $row['transaction_detail_expired'];
			$this->db->update('product_stocks', $data_product_expired);
			
			//tambah stok
			$qty = $this->get_old_stock($row['product_id'], $data['stand_id']);
			$check_qty = $this->check_old_stock($row['product_id'], $data['stand_id']);
			
			if($check_qty){
				$data_stock['product_stock_qty'] = $qty[1] + $row['transaction_detail_qty'];
				$this->db->where('product_stock_id', $qty[0]); // data yg mana yang akan di update
				$this->db->update('product_stocks', $data_stock);
			}else{
				$row_product['product_id'] = $row['product_id'];
				$row_product['stand_id'] = $data['stand_id'];
				$row_product['product_stock_qty'] = $row['transaction_detail_qty'];
				$this->db->insert('product_stocks', $row_product);
			}
			//$data_stock['product_stock_qty'] = $qty - $row['transaction_detail_qty'];
			
			//$this->db->where('product_stock_id', $row['product_stock_id']); // data yg mana yang akan di update
			//$this->db->update('product_stocks', $data_stock);
			
			$index++;
		}
		
		$this->insert_id = $id;
		
		//create transaction
		$this->insert_transaction($id, $data);
		
		$this->access->log_insert($id, 'Transaksi Pembelian');
		$this->db->trans_complete();
		return $this->db->trans_status();
	}// end of function 
	function update($id, $data, $items)
	{
		$this->db->trans_start();
		$this->db->where('transaction_id', $id); // data yg mana yang akan di update
		$this->db->update('transactions', $data);
		
		//Insert items
		$this->db->where('transaction_id', $id);
		$this->db->delete('transaction_details');
		$index = 0;
		foreach($items as $row)
		{			
			$row['transaction_id'] = $id;
			$this->db->insert('transaction_details', $row); 
			$index++;
		}
		
		$this->insert_id = $id;
		
		//create transaction
		$this->insert_transaction($id, $data, 1);
		
		$this->access->log_update($id, 'Transaksi Pembelian');
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
	
	function insert_transaction($data_id, $datatrans, $update_mode = 0) {
	$id = 0;

	if ($update_mode) {
	    $query = $this->db->get_where('transactions_sl', array('transaction_data_id' => $data_id, 'transaction_type_id' => $this->trans_type), 1);
	    if ($query->num_rows() > 0) {
		$row = $query->row_array();
		$id = $row['transaction_id'];
		//update transaction
		$data['transaction_date'] = $datatrans['transaction_date'];
		$data['transaction_description'] = $datatrans['transaction_description'];
		$this->db->update('transactions_sl', $data, array('transaction_id' => $id));
		$this->db->where('transaction_id', $id);
		$this->db->delete('journals_sl');
	    }
	    else
		$update_mode = 0;
	}
	if (!$update_mode) {
	    $data['transaction_date'] = $datatrans['transaction_date'];
	    $data['transaction_description'] = $datatrans['transaction_description'];
	    $data['transaction_type_id'] = $this->trans_type;
	    $data['transaction_code'] = $datatrans['transaction_code'];
	    $data['transaction_data_id'] = $data_id;
	    $data['period_id'] = 1;
	    $this->db->insert('transactions_sl', $data);
	    $id = $this->db->insert_id();
		//$this->db->update('transactions_sl', array('transaction_data_id' => $id), array('transaction_id' => $id));
	}
	if ($id == 0)
	    return;
	$index = 0;

	$i = 0;
	$journal_items['transaction_id'] = $id;
	$journal_items['stand_id'] =  $datatrans['stand_id'];
	$journal_items['market_id'] =  $datatrans['stand_id'];
	
	//pembayaran cash
	if($datatrans['transaction_payment_method_id'] == 1){
	
	$debit = 14; $kredit = 3;
	
	$journal_items['journal_index'] = $i++;
	$journal_items['journal_description'] = $datatrans['transaction_description'];
	$journal_items['journal_debit'] = $datatrans['transaction_total_price'];
	$journal_items['journal_credit'] = 0;
	$journal_items['coa_id'] = $debit;
	$this->db->insert('journals_sl', $journal_items);
	
	if($datatrans['transaction_sent_price'] > 0){
		$journal_items['journal_index'] = $i++;
		$journal_items['journal_description'] = $datatrans['transaction_description'];
		$journal_items['journal_debit'] = $datatrans['transaction_sent_price'];
		$journal_items['journal_credit'] = 0;
		$journal_items['coa_id'] = 32;
		$this->db->insert('journals_sl', $journal_items);
	}

	$journal_items['journal_index'] = $i++;
	$journal_items['journal_description'] = $datatrans['transaction_description'];
	$journal_items['journal_debit'] = 0;
	$journal_items['journal_credit'] = $datatrans['transaction_final_total_price'];
	$journal_items['coa_id'] = $kredit;
	$this->db->insert('journals_sl', $journal_items);
	
	//pembayaran kredit
	}else if($datatrans['transaction_payment_method_id'] == 2){
		$debit = 14; $kredit = 18;
	
	$journal_items['journal_index'] = $i++;
	$journal_items['journal_description'] = $datatrans['transaction_description'];
	$journal_items['journal_debit'] = $datatrans['transaction_total_price'] - $datatrans['transaction_down_payment'];
	$journal_items['journal_credit'] = 0;
	$journal_items['coa_id'] = $debit;
	$this->db->insert('journals_sl', $journal_items);
	
	if($datatrans['transaction_down_payment'] > 0){
		$journal_items['journal_index'] = $i++;
		$journal_items['journal_description'] = $datatrans['transaction_description'];
		$journal_items['journal_debit'] = 0;
		$journal_items['journal_credit'] = $datatrans['transaction_down_payment'];
		$journal_items['coa_id'] = 3;
		$this->db->insert('journals_sl', $journal_items);
	}
	
	if($datatrans['transaction_sent_price'] > 0){
		$journal_items['journal_index'] = $i++;
		$journal_items['journal_description'] = $datatrans['transaction_description'];
		$journal_items['journal_debit'] = $datatrans['transaction_sent_price'];
		$journal_items['journal_credit'] = 0;
		$journal_items['coa_id'] = 32;
		$this->db->insert('journals_sl', $journal_items);
	}

	$journal_items['journal_index'] = $i++;
	$journal_items['journal_description'] = $datatrans['transaction_description'];
	$journal_items['journal_debit'] = 0;
	$journal_items['journal_credit'] = $datatrans['transaction_final_total_price'];
	$journal_items['coa_id'] = $kredit;
	$this->db->insert('journals_sl', $journal_items);
	
	}else{
	
	$debit = 14; $kredit = 5;
	
	$journal_items['journal_index'] = $i++;
	$journal_items['journal_description'] = $datatrans['transaction_description'];
	$journal_items['journal_debit'] = $datatrans['transaction_total_price'];
	$journal_items['journal_credit'] = 0;
	$journal_items['coa_id'] = $debit;
	$this->db->insert('journals_sl', $journal_items);
	
	if($datatrans['transaction_sent_price'] > 0){
		$journal_items['journal_index'] = $i++;
		$journal_items['journal_description'] = $datatrans['transaction_description'];
		$journal_items['journal_debit'] = $datatrans['transaction_sent_price'];
		$journal_items['journal_credit'] = 0;
		$journal_items['coa_id'] = 32;
		$this->db->insert('journals_sl', $journal_items);
	}

	$journal_items['journal_index'] = $i++;
	$journal_items['journal_description'] = $datatrans['transaction_description'];
	$journal_items['journal_debit'] = 0;
	$journal_items['journal_credit'] = $datatrans['transaction_final_total_price'];
	$journal_items['coa_id'] = $kredit;
	$this->db->insert('journals_sl', $journal_items);
		
	}
	
		return $id;
    }
	
	function detail_list_loader($id)
	{
		// buat array kosong
		$result = array(); 		
		$this->db->select('a.*, c.product_code, c.product_name', 1);
		$this->db->from('transaction_details a');
		$this->db->join('products c','c.product_id = a.product_id');
		
		$this->db->where('a.transaction_id', $id);
		$query = $this->db->get(); debug();
		
		foreach($query->result_array() as $row)
		{
			$result[] = format_html($row);
		}
		return $result;
	}
	function get_debit_name($id)
	{
		$data = '';		
		$this->db->select('coa_name',1);
		$this->db->from('coas');
		$this->db->where('coa_id', $id);
		$query = $this->db->get();
		
		if($query->num_rows>0)
		{
			$row = $query->row_array();
			$data = $row['coa_name'];
		}
		return $data;
	}
	function get_credit_name($id)
	{
		$data = '';		
		$this->db->select('coa_name',1);
		$this->db->from('coas');
		$this->db->where('coa_id', $id);
		$query = $this->db->get();
		
		if($query->num_rows>0)
		{
			$row = $query->row_array();
			$data = $row['coa_name'];
		}
		return $data;
	}
	
	function load_product_stock($id)
	{
		$sql = "
			select 
			*
			from products a 
			where product_id= $id
		";
		
		
		$query = $this->db->query($sql); 
		//query();	
		return $query;
	}
	
	function check_stock($id)
	{
		$sql = "select product_stock_qty from product_stocks
				where product_stock_id = '$id'
				";
		
		$query = $this->db->query($sql);
		
		$result = null;
		foreach ($query->result_array() as $row) $result = format_html($row);
		return $result['product_stock_qty'];
	}
	
	function get_data_product($id)
	{
		$sql = "select *
				from products
				where product_id = '$id'
				";
		
		$query = $this->db->query($sql);
		
		$result = null;
		foreach ($query->result_array() as $row) $result = format_html($row);
		return array($result['product_code'], $result['product_name']);
	}
	
	function get_data_detail($id) {
		
		$query = "select a.*, b.product_code, b.product_name
				from transaction_details a
				join products b on b.product_id = a.product_id
				where transaction_id = '$id'
					"
					;
		
        $query = $this->db->query($query);
       // query();
        if ($query->num_rows() == 0)
            return array();

        $data = $query->result_array();

        foreach ($data as $index => $row) {
         	
        }
        return $data;
    }
	
	function get_purchase_price($id)
	{
		$sql = "select product_purchase_price
				from products
				where product_id = '$id'
				";
		
		$query = $this->db->query($sql);
		
		$result = null;
		foreach ($query->result_array() as $row) $result = format_html($row);
		return $result['product_purchase_price'];
	}
	
	function get_old_stock($product_id, $stand_id)
	{
		$sql = "select product_stock_id, product_stock_qty
				from product_stocks
				where product_id = '$product_id' and stand_id = '$stand_id'
				";
		
		$query = $this->db->query($sql);
	//	query();
		$result = null;
		foreach ($query->result_array() as $row) $result = format_html($row);
		return array($result['product_stock_id'], $result['product_stock_qty']);
	}
	
	function check_old_stock($product_id, $stand_id)
	{
		$sql = "select product_stock_id, product_stock_qty
				from product_stocks
				where product_id = '$product_id' and stand_id = '$stand_id'
				";
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{		
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	function read_categori($id)
	{
		$sql = "
			select * from products
			where product_id = $id
		";
		
		
		$query = $this->db->query($sql); 
		//query();	
		return $query;
	}
}
#
