<?php

class Schedule_model extends CI_Model 
{

	//var $branch_id;
	var $message;
	var $module_id = 300;
	function Schedule_model()
	{
	
		//parent::Model();
		//$this->branch_id = $this->access->user_state('branch_id');
	
	}// end of function 

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
		
		$columns['date'] 			= 'schedule_date';
		
		$sort_column_index = $params['sort_column'];
		$sort_dir = $params['sort_dir'];
		
		$order_by_column[] = 'schedule_id';
		$order_by_column[] = 'schedule_date';
		$order_by_column[] = '';
		
		$order_by = " order by ".$order_by_column[$sort_column_index] . $sort_dir;
		if (array_key_exists($category, $columns) && strlen($keyword) > 0) 
		{
			
				$where = " where ".$columns[$category]." like '%$keyword%'";
			
			
		}
		if ($limit > 0) {
			$limit = " limit $limit offset $offset";
		};	

		$sql = "
		select a.*, b.jumlah from schedules a 
		join (select count(si_id) as jumlah, schedule_id from schedule_items
			group by schedule_id
		) b on b.schedule_id = a.schedule_id
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
				$row['schedule_id'], 
				format_new_date($row['schedule_date']), 
				$row['jumlah'], 
			); 
		}
		
		// kembalikan nilai dalam format datatables_control
		return make_datatables_control($params, $data, $total);
	}
	
	function read_id($id)
	{		
		$this->db->select('*', 1); // ambil seluruh data
		//$this->db->select('EXTRACT(EPOCH FROM transaction_date) AS transaction_date', 1); 
		$this->db->from('schedules');
		$this->db->where('schedule_id', $id);
		$query = $this->db->get(); // parameter limit harus 1
		//query();
		$result = null; //echo $this->db->last_query();exit;
		foreach($query->result_array() as $row)	$result = format_html($row);
		return $result; 
	}// end of function 
	
	function transient_loader($id)
	{		
		// buat array kosong
		$result = array(); 
		
		$this->db->select('*', 1); // ambil seluruh data
		//$this->db->select('case when (journal_debit>0) then 1 else 0 end as debit_asc', false);
		// konversikan seluruh DATE ke EPOCH, agar bisa digunakan oleh seluruh fungsi tanggal PHP
		$this->db->from('schedule_items');
		$this->db->order_by('si_time ASC'); // urutkan data dari yang terbaru
		
		$this->db->where('schedule_id', $id); // where trial_book_warehouse_id = $warehouse_id
		//$this->db->where('a.branch_id', $this->branch_id); // pastikan data dari branch id saat ini
		
		$query = $this->db->get(); // karena menggunakan from, maka get tidak diberi parameter
		foreach($query->result_array() as $row)
		{
			$result[] = format_html($row); // render dulu dunk!
		}
		return $result;
	}

	function create($data, $items){
		$this->db->trans_start();
		$this->db->insert('schedules', $data);
		$id = $this->db->insert_id();
		
		//Insert items
		$index = 0;
		foreach($items as $row)
		{			
			$row['schedule_id'] = $id;
			$this->db->insert('schedule_items', $row);
			$index++;
		}
		
		$this->access->log_insert($id, 'Agenda');
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
	
	function update($id, $data, $items)
	{
		$this->db->trans_start();
		$this->db->where('schedule_id', $id); // data yg mana yang akan di update
		$this->db->update('schedules', $data);
		
		//Insert items
		$this->db->where('schedule_id', $id);
		$this->db->delete('schedule_items');
		$index = 0;
		foreach($items as $row)
		{			
			$row['schedule_id'] = $id;
			$this->db->insert('schedule_items', $row); 
			$index++;
		}
		
		$this->access->log_update($id, 'Agenda');
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
	
	function delete($id)
	{		
		$this->db->trans_start();
		//Hapus data approval voter
		$approval_voter = $this->get_approval_voters($id);
		$this->db->where('approval_id', $approval_voter); // data yg mana yang akan di hapus
		$this->db->delete('approval_voters');
		//Hapus data approval
		$this->db->where('approval_data_id', $id); // data yg mana yang akan di hapus
		$this->db->delete('approvals');
		//hapus data jurnal
		$this->db->where('transaction_id', $id); // data yg mana yang akan di hapus
		$this->db->delete('journals_sl');
		//hapus data transaksi
		$this->db->where('transaction_id', $id); // data yg mana yang akan di happus
		$this->db->delete('transactions_sl');
		
		$this->access->log_delete($id, 'Jurnal umum');
		$this->db->trans_complete();

		return $this->db->trans_status();
	}	
	
	
}// end of class 
