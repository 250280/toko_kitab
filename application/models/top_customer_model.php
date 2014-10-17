<?php

class Top_customer_model extends CI_Model{

	function __construct(){
		
	}
	
	
	function get_data_customer() {
		
		$query = "SELECT a.*, b.qty
				FROM customers a
				JOIN (
				
				SELECT sum( transaction_detail_qty ) AS qty, b.subject_id
				FROM transaction_details a
				JOIN transactions b on b.transaction_id = a.transaction_id
				WHERE b.transaction_type_id <> 1
				GROUP BY subject_id
				) AS b ON b.subject_id = a.customer_id
				order by qty desc
				limit 10"
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