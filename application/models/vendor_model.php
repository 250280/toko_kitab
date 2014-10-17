<?php

class Vendor_model extends CI_Model{

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
		
		$columns['vendor_code'] 			= 'vendor_code';
		$columns['vendor_name'] 			= 'vendor_name';
		$columns['vendor_email']			= 'vendor_email';
		$columns['vendor_phone']			= 'vendor_phone';
		
		$sort_column_index = $params['sort_column'];
		$sort_dir = $params['sort_dir'];
		
		$order_by_column[] = 'vendor_id';
		$order_by_column[] = 'vendor_code';
		$order_by_column[] = 'vendor_name';
		$order_by_column[] = 'vendor_email';
		$order_by_column[] = 'vendor_phone';
		
		$order_by = " order by ".$order_by_column[$sort_column_index] . $sort_dir;
		if (array_key_exists($category, $columns) && strlen($keyword) > 0) 
		{
			
				$where = " where ".$columns[$category]." like '%$keyword%'";
			
			
		}
		if ($limit > 0) {
			$limit = " limit $limit offset $offset";
		};	

		$sql = "
		select * from vendors
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
				$row['vendor_id'], 
				$row['vendor_code'],
				$row['vendor_name'],
				$row['vendor_phone'],
				$row['vendor_email']
			); 
		}
		
		// kembalikan nilai dalam format datatables_control
		return make_datatables_control($params, $data, $total);
	}
	
	function read_id($id){
		$this->db->select('*', 1);
		$this->db->where('vendor_id', $id);
		$query = $this->db->get('vendors', 1);
		$result = null;
		foreach($query->result_array() as $row)
		{
			$result = format_html($row);
		}
		return $result;
	}
	
	function create($data){
		$this->db->trans_start();
		$this->db->insert('vendors', $data);
		$id = $this->db->insert_id();
		$this->access->log_insert($id, "Vendor [".$data['vendor_name']."]");
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
	
	function update($id, $data){
		$this->db->trans_start();
		$this->db->where('vendor_id', $id);
		$this->db->update('vendors', $data);
		$this->access->log_update($id, "Vendor[".$data['vendor_name']."]");
		
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
	function delete($id){
		$this->db->trans_start();
		$this->db->where('vendor_id', $id);
		$this->db->delete('vendors');
		
		$this->access->log_delete($id, "Kelas Aliyah");
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
	
	
	
	
}