<?php
class Flow_transaction_model extends CI_Model 
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
		
		$columns['periode'] 			= 'period_name';
		
		$sort_column_index = $params['sort_column'];
		$sort_dir = $params['sort_dir'];
		
		$order_by_column[] = 'a.period_id';
		$order_by_column[] = 'period_name';
		$order_by_column[] = 'final_total';
		
		$order_by = " order by ".$order_by_column[$sort_column_index] . $sort_dir;
		if (array_key_exists($category, $columns) && strlen($keyword) > 0) 
		{
			
				$where = " where ".$columns[$category]." like '%$keyword%'";
			
			
		}
		if ($limit > 0) {
			$limit = " limit $limit offset $offset";
		};	

		$sql = "
		select a.period_id, a.period_name,  
		(case when total > 0 then total else 0 end) + (case when total_utama > 0 then total_utama else 0 end) as final_total from periods a
		left JOIN (
			select sum(fti_value) as total, b.period_id
			from flow_transaction_items a
			join flow_transactions b on b.ft_id = a.ft_id
			group by b.period_id
		) as b on b.period_id = a.period_id
		
		left JOIN(
			select sum(transaction_total_price) as total_utama, period_id
			 from transactions 
				where transaction_type_id > 1 and transaction_type_id < 7
		) as c on c.period_id = a.period_id 
		
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
				$row['period_id'], 
				$row['period_name'], 
				tool_money_format($row['final_total']), 
			); 
		}
		
		// kembalikan nilai dalam format datatables_control
		return make_datatables_control($params, $data, $total);
	}
	function read_id($id)
	{
		$this->db->select('*', 1); // ambil seluruh data
		$this->db->where('period_id', $id);
		$query = $this->db->get('periods', 1); // parameter limit harus 1
		$result = null; // inisialisasi variabel. biasakanlah, untuk mencegah warning dari php.
		foreach($query->result_array() as $row)	$result = format_html($row); // render dulu dunk!
		return $result; 
	}
	
	function read_id_item($id)
	{
		$this->db->select('a.*', 1); // ambil seluruh data
		$this->db->where('ft_id', $id);
		$query = $this->db->get('flow_transactions a', 1); // parameter limit harus 1
		$result = null; // inisialisasi variabel. biasakanlah, untuk mencegah warning dari php.
		//query();
		foreach($query->result_array() as $row)	$result = format_html($row); // render dulu dunk!
		return $result; 
	}
	function delete($id)
	{
		$this->db->trans_start();
			$this->db->where('product_cat_id', $id);
		$this->db->delete('product_category_items');
		$this->db->where('product_cat_id', $id); // data yg mana yang akan di delete
		$this->db->delete('product_categories');
	
		$this->access->log_delete($id, 'Produk Kategori');
		$this->db->trans_complete();

		return $this->db->trans_status();
	}
	function create($data, $items, $no)
	{
		$this->db->trans_start();
		$this->db->insert('flow_transactions', $data);
		$id = $this->db->insert_id();
		
		for($i=0; $i<$no; $i++)	
		{		
			$items_new['ft_id'] = $id;
			$items_new['coa_id'] = $items['coa_id'][$i];
			$items_new['fti_value'] = $items['fti_value'][$i];
			
			
				$this->db->insert('flow_transaction_items', $items_new);
			
			
		}
		
		$this->access->log_insert($id, 'Transaksi Harian');
		$this->db->trans_complete();
		return $this->db->trans_status();
	}// end of function 
	function update($id, $data, $items, $no)
	{
		$this->db->trans_start();
		$this->db->where('ft_id', $id);
		$this->db->update('flow_transactions', $data);
		
		for($i=0; $i<$no; $i++)	
		{		
			$items_new['fti_value'] = $items['fti_value'][$i];
			
				$this->db->where('fti_id', $items['fti_id'][$i]);
				$this->db->update('flow_transaction_items', $items_new);
			
			
		}
		
		$this->access->log_update($id, 'Kategori produk');
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
	
	function detail_list_loader($id)
	{
		// buat array kosong
		$result = array();
		$this->db->select('b.*, c.coa_id, coa_name,period_month, period_year, c.coa_hierarchy as coa_mode, d.market_code, d.market_name');
		$this->db->from('coas c');
		$this->db->join('balances b','(b.coa_id = c.coa_id ');
		$this->db->join('periods p','p.period_id = b.period_id','left');
		$this->db->join('markets d','d.market_id = b.market_id');
		$this->db->where('b.period_id', $id);
		$this->db->order_by('c.coa_hierarchy','asc');
		$query = $this->db->get(); debug();
		foreach($query->result_array() as $row)
		{
			$row['balance_date'] = date("d/m/Y", strtotime($row['balance_date']));
			$result[] = format_html($row);
		}
		return $result;
		
	}
	function get_period_name($id)
	{
		$data = '';		
		$this->db->select('period_year, period_month',1);
		$this->db->from('periods');
		$this->db->where('period_id', $id);
		$query = $this->db->get();
		
		if($query->num_rows>0)
		{
			$row = $query->row_array();
			$data = $row['period_month']."/".$row['period_year'];
		}
		return $data;
	}
	
	function get_market_name($id)
	{
		$data = '';		
		$this->db->select('market_name',1);
		$this->db->from('markets');
		$this->db->where('market_id', $id);
		$query = $this->db->get();
		
		if($query->num_rows>0)
		{
			$row = $query->row_array();
			$data = $row['market_name'];
		}
		return $data;
	}
	
	function get_coa_name($id)
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
	
	function get_coa_hierarchy($id)
	{
		$data = '';		
		$this->db->select('coa_hierarchy',1);
		$this->db->from('coas');
		$this->db->where('coa_id', $id);
		$query = $this->db->get();
		
		if($query->num_rows>0)
		{
			$row = $query->row_array();
			$data = $row['coa_hierarchy'];
		}
		return $data;
	}
	
	function data_is_exist($period_id, $date)
	{
		$data = '';		
		$this->db->select('ft_date',1);
		$this->db->where('period_id', $period_id);
		$this->db->where('ft_date', $date);
		$this->db->from('flow_transactions');
		$query = $this->db->get();
		//query();
		
		if($query->num_rows>0)
		{
			return true;
		}else{
			return false;
		}
	}
	
	
	function get_list_nama_pendapatan() {
		
		$query = "select 
						coa_id, coa_name from coas
						where parent_coa_id = 29
						and coa_id > 55
					order by coa_id
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
	
	function get_data_pendapatan($period_id) {
		
		$query = "select * from 
						flow_transactions where period_id = $period_id
						order by ft_date
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
	
	function get_detail($ft_id, $coa_id) {
		$query = "select * from flow_transaction_items 
				where ft_id = '$ft_id' and coa_id = '$coa_id'
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
	
	function get_detail_total($ft_id) {
		$query = "select sum(fti_value) as total from flow_transaction_items 
				where ft_id = '$ft_id'
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
	
	function get_detail_utama($period_id, $date) {
		$query = "select sum(transaction_final_total_price) as total from transactions 
				where transaction_date = '$date' and period_id = '$period_id' 
				and transaction_type_id > 1 and transaction_type_id < 7
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
	
	function get_list_nama_pendapatan_edit($id) {
		
		
		/*$query = "select 
						a.coa_id, coa_name, case when b.fti_value > 0 then b.fti_value from coas a
							left JOIN (
								select fti_value as total a.coa_id
								from flow_transaction_items a
								join flow_transactions b on b.ft_id = a.ft_id
								where b.ft_id = $id
								group by a.coa_id
							) as b on b.coa_id = a.coa_id
							left JOIN (
								select fti_id as total a.coa_id
								from flow_transaction_items a
								join flow_transactions b on b.ft_id = a.ft_id
								where b.ft_id = $id
								group by a.coa_id
							) as c on c.coa_id = a.coa_id
						where parent_coa_id = 29
						and coa_id > 55
					order by a.coa_id";*/
		$query = "select 
						a.*, b.coa_name
						 from flow_transaction_items a 
						join coas b on b.coa_id = a.coa_id
						where ft_id = $id";
		
        $query = $this->db->query($query);
        //query();
        if ($query->num_rows() == 0)
            return array();

        $data = $query->result_array();

        foreach ($data as $index => $row) {
         	
        }
        return $data;
    }
	
	
}
#
