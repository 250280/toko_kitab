<?php

class Realtime_price_model extends CI_Model{

	function __construct(){
		
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
		
		$columns['stand_name'] 			= 'stand_name';
		$columns['product_category_name'] 			= 'product_category_name';
		$columns['product_name'] 			= 'product_name';
		$columns['product_type_name'] 			= 'product_type_name';
		
		$sort_column_index = $params['sort_column'];
		$sort_dir = $params['sort_dir'];
		
		$order_by_column[] = 'product_stock_id';
		$order_by_column[] = 'stand_name';
		$order_by_column[] = 'product_category_name';
		$order_by_column[] = 'product_name';
		$order_by_column[] = 'product_type_name';
		$order_by_column[] = 'product_stock_qty';
		$order_by_column[] = 'user_price';
		$order_by_column[] = 'freeline_price';
		$order_by_column[] = 'counter_price';
		$order_by_column[] = 'online_price';
		$order_by_column[] = 'another_price';
		
		
		$order_by = " order by ".$order_by_column[$sort_column_index] . $sort_dir;
		if (array_key_exists($category, $columns) && strlen($keyword) > 0) 
		{
			
				$where = " where ".$columns[$category]." like '%$keyword%'";
			
			
		}
		if ($limit > 0) {
			$limit = " limit $limit offset $offset";
		};	

		$sql = "
		select a.* , b.product_name, c.stand_name, e.product_category_name
		from product_stocks a
		join products b on b.product_id = a.product_id
		join stands c on a.stand_id = c.stand_id
		join product_categories e on e.product_category_id = b.product_category_id
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
				$row['product_stock_id'], 
				$row['stand_name'],
				$row['product_category_name'],
				$row['product_name'],
				//$row['product_type_name'],
				$row['product_stock_qty'],
				tool_money_format($row['user_price']),
				//tool_money_format($row['freeline_price']),
				//tool_money_format($row['counter_price']),
				//tool_money_format($row['online_price']),
				//tool_money_format($row['another_price'])
			); 
		}
		
		// kembalikan nilai dalam format datatables_control
		return make_datatables_control($params, $data, $total);
	}
	
	function read_id($id){
		$this->db->select('*', 1);
		$this->db->where('product_stock_id', $id);
		$query = $this->db->get('product_stocks', 1);
		$result = null;
		foreach($query->result_array() as $row)
		{
			$result = format_html($row);
		}
		return $result;
	}
	
	function create($data){
		$this->db->trans_start();
		$this->db->insert('product_stocks', $data);
		$id = $this->db->insert_id();
		$this->access->log_insert($id, "Harga Realtime");
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
	
	function update($id, $data){
		$this->db->trans_start();
		$this->db->where('product_stock_id', $id);
		$this->db->update('product_stocks', $data);
		$this->access->log_update($id, "Harga Realtime");
		
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
	function delete($id){
		$this->db->trans_start();
		$this->db->where('realtime_price_id', $id);
		$this->db->delete('realtime_prices');
		
		$this->access->log_delete($id, "Produk");
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
	
	
	
	function check_data($product_id, $stand_id)
	{
		$sql = "select * from product_stocks
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