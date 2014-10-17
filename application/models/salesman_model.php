<?php

class Salesman_model extends CI_Model{

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
		
		$columns['salesman_code'] 			= 'salesman_code';
		$columns['salesman_name'] 			= 'salesman_name';
		$columns['salesman_email']			= 'salesman_email';
		$columns['salesman_phone']			= 'salesman_phone';
		$columns['salesman_address']			= 'salesman_address';
		
		$sort_column_index = $params['sort_column'];
		$sort_dir = $params['sort_dir'];
		
		$order_by_column[] = 'salesman_id';
		$order_by_column[] = 'salesman_code';
		$order_by_column[] = 'salesman_name';
		$order_by_column[] = 'salesman_email';
		$order_by_column[] = 'salesman_phone';
		$order_by_column[] = 'salesman_address';
		
		$order_by = " order by ".$order_by_column[$sort_column_index] . $sort_dir;
		if (array_key_exists($category, $columns) && strlen($keyword) > 0) 
		{
			
				$where = " and ".$columns[$category]." like '%$keyword%'";
			
			
		}
		if ($limit > 0) {
			$limit = " limit $limit offset $offset";
		};	

		$sql = "
		select * from salesmans
		where salesman_status = 1
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
				$row['salesman_id'], 
				$row['salesman_code'],
				$row['salesman_name'],
				$row['salesman_phone'],
				$row['salesman_email'],
				$row['salesman_address']
			); 
		}
		
		// kembalikan nilai dalam format datatables_control
		return make_datatables_control($params, $data, $total);
	}
	
	function read_id($id){
		$this->db->select('*', 1);
		$this->db->where('salesman_id', $id);
		$query = $this->db->get('salesmans', 1);
		$result = null;
		foreach($query->result_array() as $row)
		{
			$result = format_html($row);
		}
		return $result;
	}
	
	function detail_list_loader($id)
	{
		// buat array kosong
		$result = array(); 		
		$sql = "
		select * from customers 
		where salesman_id = $id
		order by customer_number
			";
		$query = $this->db->query($sql);
		
		foreach($query->result_array() as $row)
		{
			$result[] = format_html($row);
		}
		return $result;
	}
	
	function create($data){
		$this->db->trans_start();
		$this->db->insert('salesmans', $data);
		$id = $this->db->insert_id();
		$this->access->log_insert($id, "salesman [".$data['salesman_name']."]");
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
	
	function update($id, $data){
		$this->db->trans_start();
		$this->db->where('salesman_id', $id);
		$this->db->update('salesmans', $data);
		$this->access->log_update($id, "salesman[".$data['salesman_name']."]");
		
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
	function delete($id){
		$this->db->trans_start();
		$this->db->where('salesman_id', $id);
		$data['salesman_status'] = 0;
		$this->db->update('salesmans', $data);
		
		$this->access->log_delete($id, "Salesman");
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
	
	
	
	
}