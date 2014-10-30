<?php

class Dashboard_model extends CI_Model{

	function __construct(){
		
	}
	
	
	function get_data_top_product() {
		
		$query = "SELECT a.product_id, a.product_code, a.product_name, b.qty
				FROM products a
				 
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
	
	function get_data_top_customer() {
		
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
	
	function get_data_top_salesman() {
		
		$query = "SELECT * from salesmans
				order by salesman_point desc
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
	
	function get_data_limit_stock() {
		
		$query = "select a.product_stock_qty, b.product_code, b.product_name, c.product_category_name,  e.stand_name
				from product_stocks a
				join products b on b.product_id = a.product_id
				join product_categories c on c.product_category_id = b.product_category_id
				
				join stands e on e.stand_id = a.stand_id 
				where  a.product_stock_qty <= b.product_min_reorder
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
	
	function get_data_limit_expired() {
		
		$query = "select a.expired, b.product_code, b.product_name, c.product_category_name,  e.stand_name
				from product_stocks a
				join products b on b.product_id = a.product_id
				join product_categories c on c.product_category_id = b.product_category_id
				
				join stands e on e.stand_id = a.stand_id 
				WHERE a.expired <>0000 -00 -00
				ORDER BY a.expired ASC
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
	
	function get_period()
	{
		$sql = "select period_id, period_name from periods where period_closed = 1";
		$query = $this->db->query($sql);
		//query();
		$result = null ; 
		foreach($query->result_array() as $row)	$result = format_html($row);
		return array($result['period_id'], $result['period_name']);
		
	}
	
	
	function get_pendapatan_utama($period_id)
	{
		$sql = "select sum(transaction_total_price) as total_utama from transactions 
				where period_id = '$period_id' 
				and transaction_type_id > 1 and transaction_type_id < 7
					";
		$query = $this->db->query($sql);
		//	query();
		$result = null ; 
		foreach($query->result_array() as $row)	$result = format_html($row);
		return $result['total_utama'];
		
		
	}
	
	function get_list_nama_pendapatan_edit($id) {
		
		$query = "select 
						a.*, b.coa_name
						 from flow_transaction_items a 
						 join flow_transactions c on c.ft_id = a.ft_id
						join coas b on b.coa_id = a.coa_id
						where c.period_id = $id
						group by coa_id
						
					"
					;
		
        $query = $this->db->query($query);
        //query();
        if ($query->num_rows() == 0)
            return array();

        $data = $query->result_array();

        foreach ($data as $index => $row) {
         	
        }
        return $data;
    }
	
	function get_laba_utama($period_id)
	{
		$sql = "
			select sum(transaction_detail_total_price - (transaction_detail_qty * transaction_detail_purchase_price)) 
			as total_utama, period_id
			 from transaction_details a 
			 join transactions b on b.transaction_id = a.transaction_id 
			
				where period_id = '$period_id' 
				and b.transaction_type_id > 1 and b.transaction_type_id < 7
					";
		$query = $this->db->query($sql);
		//	query();
		$result = null ; 
		foreach($query->result_array() as $row)	$result = format_html($row);
		return $result['total_utama'];
		
		
	}
	
	function get_list_profit($id) {
		
		$query = "select 
						a.*, b.coa_name
						 from profit_items a 
						 join profits c on c.profit_id = a.profit_id
						join coas b on b.coa_id = a.coa_id
						where c.period_id = $id
						group by coa_id
						
					"
					;
		
        $query = $this->db->query($query);
        //query();
        if ($query->num_rows() == 0)
            return array();

        $data = $query->result_array();

        foreach ($data as $index => $row) {
         	
        }
        return $data;
    }
	
	function get_detail($period_id, $coa_id) {
		$query = "select sum(fti_value) as total 
				from flow_transaction_items a 
				join flow_transactions b on b.ft_id = a.ft_id
				where period_id = '$period_id' and coa_id = '$coa_id'
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
	
	function get_detail_laba($period_id, $coa_id) {
		$query = "select sum(profit_item_value) as total 
				from profit_items a 
				join profits b on b.profit_id = a.profit_id
				where period_id = '$period_id' and coa_id = '$coa_id'
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
	
	function get_col1()
	{
		$date = date("Y-m-d");
		$sql = "
			select count(transaction_id) as jumlah from transactions where (transaction_type_id > '1' and transaction_type_id < 7) and transaction_date = '$date'";
		$query = $this->db->query($sql);
		//query();
		$result = null ; 
		foreach($query->result_array() as $row)	$result = format_html($row);
		return $result['jumlah'];
	}
	
	function get_col2_lain()
	{
		$date = date("Y-m-d");
		$sql = "select sum(fti_value) as total_lain
				from flow_transaction_items a 
				join flow_transactions b on b.ft_id = a.ft_id
				 
				
				where ft_date = '$date'
					"
					;
		$query = $this->db->query($sql);
		//query();
		$result = null ; 
		foreach($query->result_array() as $row)	$result = format_html($row);
		return $result['total_lain'];
	}
	
	function get_col2_utama()
	{
		$date = date("Y-m-d");
		$sql = "select sum(transaction_total_price) as total_utama, transaction_date from transactions 
				where transaction_date = '$date'
				and (transaction_type_id > 1 and transaction_type_id < 7)
				group by transaction_date
				
					"
					;
		$query = $this->db->query($sql);
		//query();
		$result = null ; 
		foreach($query->result_array() as $row)	$result = format_html($row);
		return $result['total_utama'];
	}
	
	function get_col3_lain()
	{
		$date = date("Y-m-d");
		$sql = "select sum(profit_item_value) as total_lain 
				from profit_items a 
				join profits b on b.profit_id = a.profit_id
				where profit_date = '$date'";
		$query = $this->db->query($sql);
		//query();
		$result = null ; 
		foreach($query->result_array() as $row)	$result = format_html($row);
		return $result['total_lain'];
	}
	
	function get_col3_utama()
	{
		$date = date("Y-m-d");
		$sql = "select sum(transaction_detail_total_price - (transaction_detail_qty * transaction_detail_purchase_price)) 
			as total_utama
			 from transaction_details a 
			 join transactions b on b.transaction_id = a.transaction_id
				where transaction_date = '$date'
				and (b.transaction_type_id > 1 and b.transaction_type_id < 7)";
		$query = $this->db->query($sql);
		//query();
		$result = null ; 
		foreach($query->result_array() as $row)	$result = format_html($row);
		return $result['total_utama'];
	}
	
	function get_col4_name()
	{
		$date = date("Y-m-d");
		$sql = "select a.si_name from schedule_items a 
		join (select min(si_id) as agenda from schedule_items a
			join schedules b on b.schedule_id = a.schedule_id
			where schedule_date = '$date'
		) b on b.agenda = a.si_id
					"
					;
		$query = $this->db->query($sql);
		//query();
		$result = null ; 
		foreach($query->result_array() as $row)	$result = format_html($row);
		return $result['si_name'];
	}
	function get_col4_jumlah()
	{
		$date = date("Y-m-d");
		$sql = "select count(si_id) as jumlah from schedule_items a
				join schedules b on b.schedule_id = a.schedule_id
				where schedule_date = '$date'
					"
					;
		$query = $this->db->query($sql);
		//query();
		$result = null ; 
		foreach($query->result_array() as $row)	$result = format_html($row);
		return $result['jumlah'];
	}
}