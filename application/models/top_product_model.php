<?php

class Top_product_model extends CI_Model{

	function __construct(){
		
	}
	
	
	function get_data_product() {
		
		$query = "SELECT a.product_id, a.product_code, a.product_name, b.qty, c.product_type_name
				FROM products a
				JOIN product_types c on c.product_type_id = a.product_type_id  
				JOIN (
				
				SELECT sum( transaction_detail_qty ) AS qty, product_id
				FROM transaction_details a
				JOIN transactions b on b.transaction_id = a.transaction_id
				WHERE b.transaction_type_id <> 1
				GROUP BY product_id
				) AS b ON b.product_id = a.product_id
				order by qty desc
				limit 10
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