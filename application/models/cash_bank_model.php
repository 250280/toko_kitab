<?php

class Cash_bank_model extends CI_Model 
{

	//var $branch_id;
	var $message;
	var $module_id = 300;
	function Cash_bank_model()
	{
	
		//parent::Model();
		//$this->branch_id = $this->access->user_state('branch_id');
	
	}// end of function 

	function _cash_bank_renderer($data)
	{
		//$data['transaction_date'] = format_epoch($data['transaction_date']);
		return format_html($data);
		
	}// end of function 
	
	function get_trans_type($showall=1)
	{
		$this->db->select('transaction_type_id,transaction_type_name');
		$this->db->where('transaction_type_id = 10 or transaction_type_id = 11 or transaction_type_id = 12 or transaction_type_id = 13');
		$query = $this->db->get('transaction_types');		
		$data = array();
		foreach($query->result_array() as $row)
		{
			$data[$row['transaction_type_id']] = $row['transaction_type_name'];
		}
		return $data;
	}
	
	function cash_bank_list_controller()
	{
		$where 		= '';
		$params 	= get_datatables_control();
		$limit 		= $params['limit'];
		$offset 	= $params['offset'];
		$category 	= $params['category'];
		$keyword 	= $params['keyword'];
	
		$columns['transaction_code']		= 'transaction_code';
		$columns['transaction_type_name']		= 'transaction_type_name';
		
		$sort_column_index = $params['sort_column'];
		$sort_dir = $params['sort_dir'];
		
		$order_by_column[] = 'transaction_id';
		$order_by_column[] = 'transaction_date';
		$order_by_column[] = 'transaction_code';
		$order_by_column[] = 'transaction_type_name';
		$order_by_column[] = 'transaction_description';
		$order_by_column[] = 'debit';
		$order_by_column[] = 'kredit';
		
		$order_by = $order_by_column[$sort_column_index] . $sort_dir;

	
		if(array_key_exists($category,$columns) && strlen($keyword)>0)
		{		
			$this->db->start_cache();
			if($columns[$category] == 'transaction_date')
			{
				$key = explode("/", $keyword);
				if(count($key) > 1)
				{
				$bulan = $key[0];
				$tahun = $key[1];
				$where = "period_month = ".$bulan." and period_year = ".$tahun;
				
				
				$this->db->where($where);
				}
			
			} else {
				$this->db->like($columns[$category],$keyword);
				$this->db->stop_cache();
			}
				/*$split_date 			= explode("/", $keyword);
				if(count($split_date) > 1)
				{
					$bulan				= $split_date[0];
					$tahun				= $split_date[1];
					$bulan = (intval($bulan) > 9) ? intval($bulan) : '0'.intval($bulan);
					$keyword = "$bulan/$tahun";
					$this->db->where("to_char(transaction_date,'MM/YYYY')='$keyword'");
				}
			}
			else
				$this->db->like($columns[$category],$keyword);
				$this->db->stop_cache();*/	
		}
	
		$this->db->select('count(DISTINCT(t.transaction_id)) AS total',1);
		$this->db->from('transactions_sl t');
		$this->db->join('journals_sl j', 'j.transaction_id = t.transaction_id','left');
		$this->db->join('transaction_types k', 'k.transaction_type_id = t.transaction_type_id');
		$this->db->where('t.transaction_type_id = 10 or t.transaction_type_id = 11 or t.transaction_type_id = 12 or t.transaction_type_id = 13');
		$query 	= $this->db->get();
		
		$row 	= $query->row_array();
		$total 	= $row['total'];
		
		$this->db->select('
			t.transaction_id,
			t.transaction_code,
			t.transaction_date,
			t.transaction_description,
			k.transaction_type_name,
			sum(j.journal_debit) as debit, 
			sum(j.journal_credit) as kredit', 1);
		$this->db->from('transactions_sl t');
		$this->db->join('journals_sl j', 'j.transaction_id = t.transaction_id');
		$this->db->join('transaction_types k', 'k.transaction_type_id = t.transaction_type_id');
		$this->db->where('t.transaction_type_id = 10 or t.transaction_type_id = 11 or t.transaction_type_id = 12 or t.transaction_type_id = 13');
		$this->db->order_by($order_by);
		$this->db->group_by('
			t.transaction_id,
			t.transaction_code,
			t.transaction_date,
			t.transaction_description');
		$this->db->limit($limit, $offset);
		//$this->db->where($where);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		//query();
		$data = array(); 
		
		$tempGroup = '';
		foreach($query->result_array() as $row)
		{
			
			$row = $this->_cash_bank_renderer($row);
			$data[]	= array(
			
				$row['transaction_id'],
				$row['transaction_date'],
				$row['transaction_code'],
				$row['transaction_type_name'],
				
				$row['transaction_description'],
				tool_money_format($row['debit']),
				tool_money_format($row['kredit']),
			);
			
		}// end foreach
		
		return make_datatables_control($params, $data, $total);
	}// end function 
	
	function cash_bank_read_id($id)
	{		
		$this->db->select('t.*,tt.module_id', 1); // ambil seluruh data
		//$this->db->select('EXTRACT(EPOCH FROM transaction_date) AS transaction_date', 1); 
		$this->db->from('transactions_sl t');
		$this->db->join('transaction_types tt', 'tt.transaction_type_id = t.transaction_type_id');
		$this->db->where('t.transaction_id', $id);
		$query = $this->db->get(); // parameter limit harus 1
		//query();
		$result = null; //echo $this->db->last_query();exit;
		foreach($query->result_array() as $row)	$result = $this->_cash_bank_renderer($row);
		return $result; 
	}// end of function 
	
	function transient_loader($transaction_id)
	{		
		// buat array kosong
		$result = array(); 
		
		$this->db->select('a.*, c.coa_hierarchy,c.coa_name,b.market_id,b.market_name', 1); // ambil seluruh data
		//$this->db->select('case when (journal_debit>0) then 1 else 0 end as debit_asc', false);
		// konversikan seluruh DATE ke EPOCH, agar bisa digunakan oleh seluruh fungsi tanggal PHP
		$this->db->from('journals_sl a');
		$this->db->join('coas c', 'a.coa_id = c.coa_id');	// join table untuk mengambil tipe buku	
		$this->db->join('markets b', 'a.market_id = b.market_id');
		//$this->db->join('jobs j', 'a.job_id = j.job_id');
		//$this->db->order_by('debit_asc DESC');
		$this->db->order_by('a.journal_index ASC'); // urutkan data dari yang terbaru
		
		$this->db->where('transaction_id', $transaction_id); // where trial_book_warehouse_id = $warehouse_id
		//$this->db->where('a.branch_id', $this->branch_id); // pastikan data dari branch id saat ini
		
		$query = $this->db->get(); // karena menggunakan from, maka get tidak diberi parameter
		foreach($query->result_array() as $row)
		{
			$result[] = format_html($row); // render dulu dunk!
		}
		return $result;
	}
	
	function sum_item($transaction_id)
	{		
		// buat array kosong
		$result = array(); 
		
		$this->db->select('sum(a.journal_debit) as debit,sum(a.journal_credit) as credit', 1); // ambil seluruh data
		
		// konversikan seluruh DATE ke EPOCH, agar bisa digunakan oleh seluruh fungsi tanggal PHP
		$this->db->from('journals_sl a');
		$this->db->where('transaction_id', $transaction_id); // where trial_book_warehouse_id = $warehouse_id
		//$this->db->where('branch_id', $this->branch_id); // pastikan data dari branch id saat ini
		
		$query = $this->db->get(); // karena menggunakan from, maka get tidak diberi parameter
		$data = array('debit' => 0, 'credit' => 0);
		if($query->num_rows > 0)
		{
			$row = $query->row_array();
			$data['debit'] = format_money($row['debit']);
			$data['credit'] = format_money($row['credit']);
		}
		return $data;
	}
	
	var $insert_id = NULL;
	
	function create_transaction($data, $items, $sum_kredit, $gl_code)
	{
	
		$this->db->trans_start();
		
		
		
		$this->db->insert('transactions_sl', $data);
		$id = $this->db->insert_id();
		$this->insert_id = $id;
		
		$this->db->update('transactions_sl', array('transaction_data_id' => $id), array('transaction_id' => $id));
		
		$index = 0;
		foreach($items as $row)
		{			
			$row['transaction_id'] = $id;
			//$row['branch_id'] = $this->branch_id;
			$row['journal_index'] = $index;
			$this->db->insert('journals_sl', $row); 
			$index++;
		}
		
		$this->access->log_insert($id, 'Jurnal umum');
		$this->db->trans_complete();
		
		return $this->db->trans_status();
		
	}// end of function 
	/*
	function copy_transaction($data, $items, $sum_kredit)
	{
		$this->db->trans_start();
		$this->db->insert('transactions', $data);
		$id = $this->db->insert_id();
		$this->insert_id = $id;
		
		$this->db->update('transactions', array('transaction_data_id' => $id), array('transaction_id' => $id));
		$index = 0;
		foreach($items as $row)
		{			
			$row['transaction_id'] = $id;
			//$row['branch_id'] = $this->branch_id;
			$row['journal_index'] = $index;
			$this->db->insert('journals', $row); 
			$index++;
		}
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
	*/
	
	function create_code_prefix()
	{
	
		$this->db->select('em.employee_initial', 1);		
		$this->db->from('users u');
		$this->db->join('employees em', 'em.employee_id = u.employee_id');		
		$this->db->where('u.user_id', $this->access->user_id);
		
		$query = $this->db->get();
		$data = $query->row_array();
		$user_initial = $data['employee_initial'];
		return $user_initial;
		
	}
	
	function get_announcer()
	{		
		$this->db->select('*', 1);
		$this->db->select('EXTRACT(EPOCH FROM closing_date) AS closing_date', 1); 
		$query = $this->db->get('announcements', 1); // parameter limit harus 1
		$result = null; 
		foreach($query->result_array() as $row)	
		{
		$result = $row;
		$result['closing_date'] = format_epoch($result['closing_date']);
		}
		return $result; 
	}// end of function 
	
	function period_list($param) {
	
		// map parameter ke variable biasa agar mudah digunakan
		$limit = $param['limit'];
		$offset = $param['offset'];
		$category = $param['category'];
		$keyword = $param['keyword'];
		
		// map value dari combobox ke table
		// daftar kolom yang valid
		$columns['m'] = 'period_month';
		$columns['y'] = 'period_year';
		$columns['d'] = 'period_desc';
		
		# order define columns start
		$sort_column_index				= $param['sort_column'];
		$sort_dir						= $param['sort_dir'];
		
		$order_by_column[] = 'period_month DESC';
		$order_by_column[] = 'period_year DESC';
		$order_by_column[] = 'period_desc DESC';
		$order_by_column[] = 'period_closed DESC';
		
		$order_by = $order_by_column[$sort_column_index] . $sort_dir;
		# order define column end
		
		// check apakah parameter search dari client valid, bila tidak anggap ambil semua data
		if (array_key_exists($category, $columns) && strlen($keyword) > 0) 
		{
			// daftarkan kriteria search ke seluruh query
			$this->db->start_cache();
			if (($category == 'm') || ($category == 'y')) {
				if (empty($keyword) || !is_int($keyword)) $keyword = '0'; else
				$this->db->where($columns[$category], $keyword);
			} else {
				$this->db->like($columns[$category], $keyword);
			}
			$this->db->stop_cache();
			// bila query Anda tidak menggunakan ini, hapus dengan $this->db->flush_cache();
		}
		
		$this->db->start_cache();
		$this->db->stop_cache();
		
		// hitung total record
		$this->db->select('COUNT(1) AS total', 1); // pastikan ada AS total nya, 1 bila isinya adalah function (dalam hal ini COUNT)
		$query	= $this->db->get(); 

		$row 	= $query->row_array(); // fungsi ci untuk mengambil 1 row saja dari query
		$total 	= $row['total'];		
		
		// proses query sesuai dengan parameter
				
		// konversikan seluruh DATE ke EPOCH, agar bisa digunakan oleh seluruh fungsi tanggal PHP				
		$this->db->order_by($order_by ? $order_by : 'period_month DESC, period_year_DESC');
		
		// bila menggunakan paging gunakan limiter dan offseter
		if ($limit > 0) $this->db->limit($limit, $offset);
		$query = $this->db->get('periods');
		
#		debug($this->db->last_query());
		
		$data = array(); // inisialisasi variabel. biasakanlah, untuk mencegah warning dari php.
		foreach($query->result_array() as $row) {
			
			foreach($row as $key => $value) $row[$key] = safe_html($value);
			
			$data[] = array(
				$row['period_month'],
				$row['period_year'],
				$row['period_description'],
				$row['period_closed'] == 'f' || !$row['period_closed'] ? '---' : 'TUTUP'
			); 
		}
		
		// kembalikan nilai dalam format datatables_control
		return make_datatables_control($param, $data, $total, array(2));
	}
	
	function update_announcer($data)
	{		
		$this->db->trans_start();
		$this->db->delete('announcements', array('announce_id'=>1));
		$data['announce_id']=1;			
		if($data['aktif'])
		{
			$this->access->log_update(1, 'Closing Announcement');
		}
		else
		{
			$this->access->log_delete(1, 'Delete Closing Announcement');	
		}
		unset($data['aktif']);
		$this->db->insert('announcements', $data);
		
		$this->db->trans_complete();
		return $this->db->trans_status();
	}// end of function 
	
	function commit_journal_old($data_id, $trans_type)
	{
		//$query = $this->db->get_where('transactions_sl', array('transaction_data_id' => $data_id,'transaction_type_id' => $trans_type), 1);
		$this->db->where('transaction_data_id', $data_id);
		$this->db->where('transaction_type_id', $trans_type);
		$this->db->order_by('transaction_id desc');
		$query   = $this->db->get('transactions_sl');
		
		if ($query->num_rows() == 0)return false;
		
		$row = $query->row_array();	
		$id_sl = $row['transaction_id'];
		unset($row['transaction_id']);
		$row['transaction_is_approved'] = 't';
		//remove
		$this->db->delete('transactions_sl', array('transaction_data_id' => $data_id,'transaction_type_id' => $trans_type));
		
		$this->db->insert('transactions_sl', $row);
		$id = $this->db->insert_id();
		
		$query2 = $this->db->get_where('journals_sl', array('transaction_id' => $id_sl));
		foreach($query2->result_array() as $row2)
		{
			$row2['transaction_id'] = $id;
			unset($row2['journal_id']);
			$this->db->insert('journals_sl', $row2);
		}
	}
	
	function commit_journal($data_id)
	{
		//$query = $this->db->get_where('transactions_sl', array('transaction_data_id' => $data_id,'transaction_type_id' => $trans_type), 1);
		
		$this->db->where('transaction_data_id', $data_id);
	//	$this->db->where('transaction_type_id', $trans_type);
		$this->db->order_by('transaction_id desc');
		$query   = $this->db->get('transactions_sl');
		
		
		if ($query->num_rows() == 0)return false;
		
		$row = $query->row_array();	
		$id_sl = $row['transaction_id'];
	//	unset($row['transaction_id']);
		$row['transaction_is_approved'] = 't';
		//remove
		//$this->db->delete('transactions_sl', array('transaction_data_id' => $data_id,'transaction_type_id' => $trans_type));
		
		$this->db->insert('transactions', $row);
		//$id = $this->db->insert_id();
		
		$query2 = $this->db->get_where('journals_sl', array('transaction_id' => $id_sl));
		foreach($query2->result_array() as $row2)
		{
			//$row2['transaction_id'] = $id;
			//unset($row2['journal_id']);
			$this->db->insert('journals', $row2);
		}
	}
	
	function transient_loader_sl($transaction_id)
	{		
		// buat array kosong
		$result = array(); 
		
		$this->db->select('a.*, c.coa_hierarchy,c.coa_name,b.stand_id,b.stand_code,b.stand_name', 1); // ambil seluruh data
		//$this->db->select('case when (journal_debit>0) then 1 else 0 end as debit_asc', false);
		
		// konversikan seluruh DATE ke EPOCH, agar bisa digunakan oleh seluruh fungsi tanggal PHP
		$this->db->from('journals_sl a');
		$this->db->join('coas c', 'a.coa_id = c.coa_id');	// join table untuk mengambil tipe buku	
		$this->db->join('stands b', 'a.stand_id = b.stand_id');
		//$this->db->join('jobs j', 'a.job_id = j.job_id');
		//$this->db->order_by('debit_asc DESC');
		$this->db->order_by('a.journal_debit DESC'); // urutkan data dari yang terbaru
		
		$this->db->where('transaction_id', $transaction_id); // where trial_book_warehouse_id = $warehouse_id
		//$this->db->where('a.branch_id', $this->branch_id); // pastikan data dari branch id saat ini
		
		$query = $this->db->get(); // karena menggunakan from, maka get tidak diberi parameter
		
		foreach($query->result_array() as $row)
		{
			$result[] = format_html($row); // render dulu dunk!
		}
		return $result;
	}
	function update_transaction($id, $data, $items, $sum_kredit)
	{
		
		$this->db->trans_start();
	
		//Insert to parent
		$this->db->where('transaction_id', $id);
		$this->db->delete('journals_sl');
		
		$this->db->where('transaction_id', $id);
		$this->db->update('transactions_sl', $data);
		

		//Insert items
		$index = 0;
		foreach($items as $row)
		{			
			$row['transaction_id'] = $id;
			//$row['branch_id'] = $this->branch_id;
			$row['journal_index'] = $index;
			$this->db->insert('journals_sl', $row); 
			$index++;
		}
	
		$this->access->log_update($id, 'Jurnal umum');
		$this->db->trans_complete();		
		return $this->db->trans_status();
		
	}// end of function function
	 
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
	
	function get_approval_voters($id){
		$this->db->select('*', 1);
		$this->db->where('approval_data_id', $id);		
		$query = $this->db->get('approvals');
		
		$result = null;
		foreach($query->result_array() as $row)	$result = format_html($row); // render dulu dunk!
		return $result['approval_id'];
	}
	
	function approve($data_id, $trans)
	{
		$this->db->trans_start();
		
		$this->db->where('transaction_id', $data_id);
		$this->db->update('transactions_sl', array('transaction_is_approved' => 't'));
		
		//copykan ke transcations dan journals 
		$ci = get_instance();
		$ci->cash_bank_model->commit_journal($data_id);
		$this->db->trans_complete();
		return $this->db->trans_status();
		
		
		
		/*$this->db->trans_start();
		
		$this->db->where('ap_id', $data_id);
		$this->db->update('account_payables', array('ap_is_approved' => 't'));
		//simpan ke jurnal
		$ci = get_instance();
		$ci->load->model('gl_model');
		$ci->gl_model->commit_journal($data_id, $this->trans_type);
		
		$this->db->trans_complete();
		return $this->db->trans_status();*/
	}
	
	function lihat_cash_bank()
	{
		$result = array(); 
		
		$this->db->select('a.*'); // ambil seluruh dat
		$this->db->order_by('transaction_id desc');
		$query   = $this->db->get('transactions_sl');
		
	
		foreach($query->result_array() as $row)
		{
			$result[] = format_html($row); // render dulu dunk!
		}
		//$result = $query->result_array();
		return $result;
	}
	
	function create_cash_bank($transaction_desc, $sum_kredit, $transaction_date, $period_id){
		$this->db->trans_start();
		
		$this->load->library('authority');
		$this->authority->set($this->access->module_id, $transaction_desc, $sum_kredit, $data['transaction_date'],$data['period_id']);
		if($this->authority->is_error())
		{
			$this->message = $this->authority->error_message;
			return FALSE;
		}
		
		$this->authority->kill($id);
		$this->authority->blast($id);
		
		$this->db->trans_complete();
		
		return $this->db->trans_status();
	}
	function check_approved($id)
	{
		$this->db->select('*', 1);
		$this->db->where('transaction_id', $id);		
		$query = $this->db->get('transactions_sl');
		
		$result = null;
		foreach($query->result_array() as $row)	$result = format_html($row); // render dulu dunk!
		
		if ($result['transaction_is_approved']=='t')
		{		
			return TRUE;
		}else{
			return FALSE;
		}
		
	}
	
}// end of class 
