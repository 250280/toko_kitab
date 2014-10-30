<?php

class Limit_expired_model extends CI_Model{

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
		//$columns['product_type_name'] 			= 'product_type_name';
		
		$sort_column_index = $params['sort_column'];
		$sort_dir = $params['sort_dir'];
		
		$order_by_column[] = 'product_stock_id';
		$order_by_column[] = 'stand_name';
		$order_by_column[] = 'product_category_name';
		$order_by_column[] = 'product_name';
		//$order_by_column[] = 'product_type_name';
		$order_by_column[] = 'expired';
		
		
		$order_by = " order by ".$order_by_column[$sort_column_index] . $sort_dir;
		if (array_key_exists($category, $columns) && strlen($keyword) > 0) 
		{
			
				$where = " and ".$columns[$category]." like '%$keyword%'";
			
			
		}
		if ($limit > 0) {
			$limit = " limit $limit offset $offset";
		};	

		$sql = "
		select a.product_stock_id, a.expired, b.product_code, b.product_name, c.product_category_name,  e.stand_name
				from product_stocks a
				join products b on b.product_id = a.product_id
				join product_categories c on c.product_category_id = b.product_category_id
				
				join stands e on e.stand_id = a.stand_id 
				
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
				$row['expired']
			); 
		}
		
		// kembalikan nilai dalam format datatables_control
		return make_datatables_control($params, $data, $total);
	}
	
}