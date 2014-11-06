<?php
class Purchase_report_model extends CI_Model 
{
	function __construct()
	{
		//parent::Model();
		//$this->sek_id = $this->access->sek_id;
	}
	
	function list_controller()
	{		
		$where = '';
		$params 	= get_datatables_control();
		$limit 		= $params['limit'];
		$offset 	= $params['offset'];
		$category 	= $params['category'];
		$keyword 	= $params['keyword'];
		
		// map value dari combobox ke table
		// daftar kolom yang valid
		$columns['transaction_code'] = 'transaction_code';
		$columns['stand_name'] = 'stand_name';
		$columns['transaction_type_name'] = 'transaction_type_name';
		
		$sort_column_index	= $params['sort_column'];
		$sort_dir		= $params['sort_dir'];
		
		$order_by_column[] = 'transaction_id';
		$order_by_column[] = 'transaction_date';
		$order_by_column[] = 'stand_name';
		$order_by_column[] = 'transaction_code';
		$order_by_column[] = 'transaction_type_name';
		$order_by_column[] = 'transaction_total_price';
	
		
		$order_by = " order by ".$order_by_column[$sort_column_index] . $sort_dir;
		if (array_key_exists($category, $columns) && strlen($keyword) > 0) 
		{
			
				$where = " and ".$columns[$category]." like '%$keyword%'";
			
			
		}
		if ($limit > 0) {
			$limit = " limit $limit offset $offset";
		};	

		$sql = "
		select a.* , 
	
		b.stand_name, c.transaction_type_name
		from transactions a
		join stands b on b.stand_id = a.stand_id
		join transaction_types c on c.transaction_type_id = a.transaction_type_id
		where a.transaction_type_id = 1  
		$where  $order_by
			
			";

		$query_total = $this->db->query($sql);
		$total = $query_total->num_rows();
		
		$sql = $sql.$limit;
		
		$query = $this->db->query($sql);
		//query();
		
		$data = array(); // inisialisasi variabel. biasakanlah, untuk mencegah warning dari php.
		foreach($query->result_array() as $row) {
			
			$row = format_html($row);
			
			$data[] = array(
				$row['transaction_id'], 
				$row['transaction_date'],
				$row['stand_name'], 
				$row['transaction_code'], 
				$row['transaction_type_name'],
				tool_money_format($row['transaction_total_price'])
			); 
		}
		
		// kembalikan nilai dalam format datatables_control
		return make_datatables_control($params, $data, $total);
	}
	function read_id($id)
	{
		$this->db->select('a.*, b.transaction_type_name,c.vendor_name,d.transaction_payment_method_name', 1); // ambil seluruh data
		$this->db->join('transaction_types b', 'b.transaction_type_id = a.transaction_type_id');
		$this->db->join('vendors c', 'c.vendor_id = a.subject_id', 'left');
		$this->db->join('transaction_payment_methods d', 'd.transaction_payment_method_id = a.transaction_payment_method_id');
		$this->db->where('transaction_id', $id);
		$query = $this->db->get('transactions a', 1); // parameter limit harus 1
		//query();
		$result = null; // inisialisasi variabel. biasakanlah, untuk mencegah warning dari php.
		foreach($query->result_array() as $row)	$result = format_html($row); // render dulu dunk!
		return $result; 
	}
	function delete($id)
	{
		$this->db->trans_start();
			$this->db->where('product_cat_id', $id);
		$this->db->delete('purchase_report_items');
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
			$this->db->insert('transaction_details', $row); 
			$index++;
		}
		$this->access->log_insert($id, 'Penjualan Normal');
		$this->db->trans_complete();
		return $this->db->trans_status();
	}// end of function 
	function update($id, $data, $items)
	{
		$this->db->trans_start();
		$this->db->where('product_cat_id', $id); // data yg mana yang akan di update
		$this->db->update('product_categories', $data);
		
		//Insert items
		$this->db->where('product_cat_id', $id);
		$this->db->delete('purchase_report_items');
		$index = 0;
		foreach($items as $row)
		{			
			$row['product_cat_id'] = $id;
			$this->db->insert('purchase_report_items', $row); 
			$index++;
		}
		
		$this->access->log_update($id, 'Kategori produk');
		$this->db->trans_complete();
		return $this->db->trans_status();
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
			a.*, b.product_code
			from product_stocks a 
			join products b on b.product_id = a.product_id
			where product_stock_id = $id
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
		$sql = "select b.product_code, b.product_name 
				from product_stocks a
				join products b on b.product_id = a.product_id
				where product_stock_id = '$id'
				";
		
		$query = $this->db->query($sql);
		
		$result = null;
		foreach ($query->result_array() as $row) $result = format_html($row);
		return array($result['product_code'], $result['product_name']);
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
}
#
