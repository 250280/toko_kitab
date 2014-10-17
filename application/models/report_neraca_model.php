<?php

class Report_neraca_model extends CI_Model{

	function __construct(){
		
	}
	
	function get_period($id)
	{
		$sql = "select period_name from periods where period_id = '$id'
				";
		
		$query = $this->db->query($sql);
	//	query();
		$result = null;
		foreach ($query->result_array() as $row) $result = format_html($row);
		return $result['period_name'];
	}
	
	function get_data_coa($group_id) {
		
		$query = "select * from coas where coa_group = '$group_id' and coa_level = 3 order by coa_hierarchy";
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
	
	
	
	function get_detail($period_id, $coa_id) {
		$query = "select sum(e_amount_debet) as debet , sum(e_amount_kredit) as credit
				from trial_balance a
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
	
	
	
	function get_detail_total($period_id, $group_id)
	{
		$sql = "select case when sum(e_amount_debet) > 0 then sum(e_amount_debet) else 0 end as total_debet, case when sum(e_amount_kredit) > 0 then sum(e_amount_kredit) else 0 end as total_credit
				from trial_balance a
				join coas c on c.coa_id = a.coa_id
				where period_id = '$period_id' and c.coa_level = '3' and c.coa_group = '$group_id'
				";
		
		$query = $this->db->query($sql);
	//	query();
		$result = null;
		foreach ($query->result_array() as $row) $result = format_html($row);
		return array($result['total_debet'], $result['total_credit']);
	}
	
	function get_grand_total($period_id)
	{
		$sql = "select case when sum(e_amount_debet) > 0 then sum(e_amount_debet) else 0 end as total_debet, case when sum(e_amount_kredit) > 0 then sum(e_amount_kredit) else 0 end as total_credit
				from trial_balance a
				join coas c on c.coa_id = a.coa_id
				where period_id = '$period_id' and c.coa_level = '3'
				";
		
		$query = $this->db->query($sql);
	//	query();
		$result = null;
		foreach ($query->result_array() as $row) $result = format_html($row);
		return array($result['total_debet'], $result['total_credit']);
	}
	
	
}