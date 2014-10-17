<?php
class Beginning_balance_model extends CI_Model 
{
	function __construct()
	{
		//parent::Model();
		//$this->sek_id = $this->access->sek_id;
	}
	
	function list_controller()
	{		
		$params 	= get_datatables_control();
		$limit 		= $params['limit'];
		$offset 	= $params['offset'];
		$category 	= $params['category'];
		$keyword 	= $params['keyword'];
		
		// map value dari combobox ke table
		// daftar kolom yang valid
		$columns['code'] = 'product_cat_code';
		
		$sort_column_index	= $params['sort_column'];
		$sort_dir		= $params['sort_dir'];
		
		$order_by_column[] = 'beginning_balance_id';
		$order_by_column[] = 'period_id';
		$order_by_column[] = 'beginning_balance_debit';
		$order_by_column[] = 'beginning_balance_credit';
		
		$order_by = $order_by_column[$sort_column_index] . $sort_dir;
		
		$this->db->start_cache();			
		if (array_key_exists($category, $columns) && strlen($keyword) > 0) 
		{
			$this->db->like($columns[$category], $keyword);
		}
		$this->db->stop_cache();
		
		// hitung total record
		$this->db->select('COUNT(1) AS total', 1); // pastikan ada AS total nya, 1 bila isinya adalah function (dalam hal ini COUNT)
		$this->db->join('periods b','b.period_id = a.period_id' );
		$query	= $this->db->get('beginning_balances a'); 

		$row 	= $query->row_array(); // fungsi ci untuk mengambil 1 row saja dari query
		$total 	= $row['total'];		
		
		
		// proses query sesuai dengan parameter
		$this->db->select('a.*, b.period_month, b.period_year', 1);
		$this->db->join('periods b','b.period_id = a.period_id' );
		//$this->db->order_by('market_id ASC');
		$this->db->order_by($order_by);
		// bila menggunakan paging gunakan limiter dan offseter
		if ($limit > 0) $this->db->limit($limit, $offset);
		$query = $this->db->get('beginning_balances a');
		
		$data = array(); // inisialisasi variabel. biasakanlah, untuk mencegah warning dari php.
		foreach($query->result_array() as $row) {
			
			
			$row = format_html($row);
			
			$data[] = array(
				$row['beginning_balance_id'], 
				$row['period_month']."/".$row['period_year'], 
				tool_money_format($row['beginning_balance_debit']), 
				tool_money_format($row['beginning_balance_credit'])
			); 
		}
		
		// kembalikan nilai dalam format datatables_control
		return make_datatables_control($params, $data, $total);
	}
	function read_id($id)
	{
		$this->db->select('*', 1); // ambil seluruh data
		$this->db->where('beginning_balance_id', $id);
		$query = $this->db->get('beginning_balances', 1); // parameter limit harus 1
		$result = null; // inisialisasi variabel. biasakanlah, untuk mencegah warning dari php.
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
	function create($data, $items)
	{
		$this->db->trans_start();
		$this->db->insert('beginning_balances', $data);
		$id = $this->db->insert_id();
		
		//Insert items
		$index = 0;
		foreach($items as $row)
		{			
			$this->db->insert('balances', $row); 
			$index++;
		}
		$this->access->log_insert($id, 'Saldo Awal');
		$this->db->trans_complete();
		return $this->db->trans_status();
	}// end of function 
	function update($id, $data, $items)
	{
		$this->db->trans_start();
		$this->db->where('beginning_balance_id', $id); // data yg mana yang akan di update
		$this->db->update('beginning_balances', $data);
		
		//Insert items
		$this->db->where('balance_id <> 0');
		$this->db->delete('balances');
		$index = 0;
		foreach($items as $row)
		{			
			
			$this->db->insert('balances', $row); 
			$index++;
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
	
	function data_is_exist()
	{
		$data = '';		
		$this->db->select('beginning_balance_id',1);
		$this->db->from('beginning_balances');
		$query = $this->db->get();
		
		if($query->num_rows>0)
		{
			return true;
		}else{
			return false;
		}
	}
	
}
#
