<?php

class realtime_stock_model extends CI_Model{

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
		SELECT a . * , b.product_name, e.product_category_name
		FROM product_stocks a
		JOIN products b ON b.product_id = a.product_id
		JOIN product_categories e ON e.product_category_id = b.product_category_id
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
				tool_money_format($row['freeline_price']),
				tool_money_format($row['counter_price']),
				tool_money_format($row['online_price']),
				tool_money_format($row['another_price'])
			); 
		}
		
		// kembalikan nilai dalam format datatables_control
		return make_datatables_control($params, $data, $total);
	}
	
	function read_id($id){
		$this->db->select('*', 1);
		$this->db->where('product_id', $id);
		$query = $this->db->get('products', 1);
		$result = null;
		foreach($query->result_array() as $row)
		{
			$result = format_html($row);
		}
		return $result;
	}
	
	function create($data){
		$this->db->trans_start();
		$this->db->insert('realtime_stocks', $data);
		$id = $this->db->insert_id();
		$this->access->log_insert($id, "produk [".$data['realtime_stock_name']."]");
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
	
	function update($id, $data){
		$this->db->trans_start();
		$this->db->where('realtime_stock_id', $id);
		$this->db->update('realtime_stocks', $data);
		$this->access->log_update($id, "produk[".$data['realtime_stock_name']."]");
		
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
	function delete($id){
		$this->db->trans_start();
		$this->db->where('realtime_stock_id', $id);
		$this->db->delete('realtime_stocks');
		
		$this->access->log_delete($id, "Produk");
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
	
	function get_list_stand() {
		
		$query = "select 
						stand_id, stand_name from stands
						where stand_status = 1
					order by stand_id
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
	
	function get_data_product() {
		
		$query = "select a.*, c.product_category_name
					from products a
					
					join product_categories c on c.product_category_id = a.product_category_id
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
	
	function get_qty($stand_id, $product_id) {
		$query = "select product_stock_qty from product_stocks 
				where stand_id = '$stand_id' and product_id = '$product_id'
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
	
	function get_total_qty($product_id) {
		$query = "select sum(product_stock_qty) as total from product_stocks 
				where product_id = '$product_id'
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
	
	function detail_list_loader($id)
	{
		// buat array kosong
		$result = array(); 		
		$sql = "
		SELECT a.product_id, d.product_name, b.debet, c.kredit, e.transaction_date
		FROM transaction_details a
		join transactions e on e.transaction_id = a.transaction_id
		join products d on d.product_id = a.product_id
		LEFT JOIN (
			SELECT sum( transaction_detail_qty ) AS debet, product_id, transaction_date
			FROM transaction_details z
				join transactions y on y.transaction_id = z.transaction_id
			WHERE y.transaction_type_id = 1
			and z.product_id = '$id'
			GROUP BY product_id, transaction_date
		) AS b ON b.product_id = a.product_id 
		
		LEFT JOIN (
			SELECT sum( transaction_detail_qty ) AS kredit, product_id, transaction_date
			FROM transaction_details z
				join transactions y on y.transaction_id = z.transaction_id
			WHERE y.transaction_type_id = 2 or y.transaction_type_id = 3 or y.transaction_type_id = 4 or y.transaction_type_id = 5 or y.transaction_type_id = 6
			and z.product_id = '$id'
			
			GROUP BY product_id, transaction_date
			) AS c ON c.product_id = a.product_id
		where a.product_id = '$id'
		group by a.product_id, e.transaction_date
			";
		$query = $this->db->query($sql);
		//query();
		foreach($query->result_array() as $row)
		{
			$result[] = format_html($row);
		}
		return $result;
	}
	
	
	
}