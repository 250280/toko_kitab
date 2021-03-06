<?php

class Dtc_model extends CI_Model
{
	var $branch_id = 1;
	function __construct()
	{
		parent::__construct();
		$ci = & get_instance();
		if(isset($ci->access))$this->branch_id = $ci->access->branch_id;
		
	}
	
	function _cc_renderer($data)
	{
		return format_html($data);
	}
	
	## EMPLOYEE ##
	function _employee_renderer($data)
	{
		return format_html($data);
	}
	
	
	## EMPLOYEE User##
	function _employee_user_renderer($data)
	{
		return format_html($data);
	}
	
	function employee_user_control($param)
	{
		// map parameter ke variable biasa agar mudah digunakan
		$limit 		= $param['limit'];
		$offset	 	= $param['offset'];
		$category 	= $param['category'];
		$keyword 	= $param['keyword'];
		
		# order define columns start
		$sort_column_index				= $param['sort_column'];
		$sort_dir						= $param['sort_dir'];
		
		$order_by_column[] = 'a.employee_id';
		$order_by_column[] = 'a.employee_nip';
		//$order_by_column[] = 'a.employee_barcode_id';
		$order_by_column[] = 'a.employee_name';
		$order_by_column[] = 'b.employee_id';
		
		$order_by = $order_by_column[$sort_column_index] . $sort_dir;
		# order define column end
		
		
		$column['p1']			= 'employee_nip';
		$column['p2']			= 'employee_name';
		//$column['p3']			= 'employee_barcode_id';
		
		
		$this->db->start_cache();
		$this->db->where('a.employee_active_status','1');
		$this->db->where('a.employee_id <> ', 1);
		
		if ($category)
		if(array_key_exists($category, $column) && strlen($keyword) > 0)
		{
			$this->db->like($column[$category], $keyword);
		}// end if
		$this->db->stop_cache();
		
		// hitung total record
		$this->db->select('COUNT(1) AS total', 1); // pastikan ada AS total nya, 1 bila isinya adalah function (dalam hal ini COUNT)
		$this->db->from('employees a');	
		$this->db->join('users b','a.employee_id = b.employee_id','left');
		$query = $this->db->get();
		$row 	= $query->row_array(); // fungsi ci untuk mengambil 1 row saja dari query
		$total 	= $row['total'];		
		
		// proses query sesuai dengan parameter
		$this->db->select('a.*,a.employee_id as emp_id,b.*, b.employee_id as emp2_id'); // ambil seluruh data
		$this->db->from('employees a');	
		$this->db->join('users b','a.employee_id = b.employee_id','left');
		$this->db->limit($limit, $offset);
		$this->db->order_by($order_by);
		$query = $this->db->get();
		//query();
		//debug($this->db->last_query());
		
		$data = array(); // inisialisasi variabel. biasakanlah, untuk mencegah warning dari php.
		foreach($query->result_array() as $row) {
			
			(empty($row['user_id']))?$status = "Belum Dibuat":$status = "Sudah Dibuat";
			$row = $this->_employee_renderer($row);
			
			$data[] = array(
				$row['emp2_id'] ? $row['emp2_id'] : $row['emp_id'], 
				//$row['employee_barcode_id'],
				$row['employee_nip'], 
				$row['employee_name'],
				$status
			);			
		}
		
		// kembalikan nilai dalam format datatables_control
		return make_datatables_control($param, $data, $total);
	}
	
	function employee_user_get($id = 0, $mode = 1)
	{
		if (!$id) return NULL;
		
		$id = trim($id);
		if (empty($id)) return NULL;
		
		$this->db->start_cache();
		$this->db->select('e.*', 1); // ambil seluruh data
	
		$this->db->stop_cache();
		
		if ($mode == 1)
			$query = $this->db->get_where('employees e', array('employee_id' => $id), 1);
		else
			$query = $this->db->get_where('employees e', array('employee_nip' => $id), 1);

		//log_message('error',$this->db->last_query());
		
		$result = NULL;
		foreach($query->result_array() as $row)	$result = $this->_employee_renderer($row); 
		
		return $result;
	}
	
	## COA ##
	function _coa_renderer($data)
	{
		return format_html($data);
	}

	
	function coa_control()
	{
		// map parameter ke variable biasa agar mudah digunakan
		$params 	= get_datatables_control();
		$limit 		= $params['limit'];
		$offset 	= $params['offset'];
		$category 	= $params['category'];
		$keyword 	= $params['keyword'];
		
		# order define columns start
		
		$order_by_column[] = 'coa_hierarchy';
		$order_by_column[] = 'coa_id';
		$order_by_column[] = 'coa_name';
		
		$sort_column_index		= $params['sort_column'];
		$sort_dir				= $params['sort_dir'];
		
		# order define column end
		
		$column['p1']			= 'coa_hierarchy';
		$column['p2']			= 'coa_name';
		$order_by = $order_by_column[$sort_column_index] . $sort_dir;
		
		$this->db->start_cache();
		$this->db->where('coa_id <> ', 0);
		$this->db->where('coa_type', 0);
		if(array_key_exists($category, $column) && strlen($keyword) > 0)
		{
			if ($category == 'p1') $this->db->like($column[$category], $keyword, 'after'); else $this->db->like($column[$category], $keyword);
			
		}// end if
		$this->db->stop_cache();
		
		// hitung total record
		$this->db->select('COUNT(1) AS total', 1); // pastikan ada AS total nya, 1 bila isinya adalah function (dalam hal ini COUNT)
		$query	= $this->db->get('coas'); 
		$row 	= $query->row_array(); // fungsi ci untuk mengambil 1 row saja dari query
		$total 	= $row['total'];	
				
		
		// proses query sesuai dengan parameter
		$this->db->select('*', 1); // ambil seluruh data				
		$this->db->order_by($order_by);
		$query = $this->db->get('coas', $limit, $offset);
		
		$data = array(); // inisialisasi variabel. biasakanlah, untuk mencegah warning dari php.
		foreach($query->result_array() as $row) {
			
			$kode = $row['coa_id'];
			$style = $row['coa_type'] == 0 ? 'coa_c1' : 'coa_c2';
			
			$row = $this->_cc_renderer($row);
			
			$tempIndent = '';
			$CCLevel 	= $row['coa_level'] - 1;
#			for($i=0; $i<$CCLevel ; $i++) $tempIndent .='. ';
					
#			$tempIndent .= '<span id="' . $style . '">' . $row['coa_name'] . '</span>';
			$tempIndent .=  $row['coa_name'];
			
			$data[] = array(
				$row['coa_id'],
				$row['coa_hierarchy'],
				$tempIndent
			); 
		}
		
		// kembalikan nilai dalam format datatables_control
		return make_datatables_control($params, $data, $total);
	}
	
	function coa_get($id, $mode)
	{
		if (!$id) return NULL;
		
		$id = trim($id);
		if (empty($id)) return NULL;
		
		if ($mode == 1)
			$query = $this->db->get_where('coas', array('coa_id' => $id, 'coa_type' => 0), 1);
		else
			$query = $this->db->get_where('coas', array('coa_hierarchy' => $id, 'coa_type' => 0), 1);
		
		$result = NULL;		
		foreach($query->result_array() as $row)	$result = $this->_coa_renderer($row);
		
		return $result;
	}
	
	
	## COA 2 ##
	function coa2_control($param)
	{
		// map parameter ke variable biasa agar mudah digunakan
		$limit 		= $param['limit'];
		$offset	 	= $param['offset'];
		$category 	= $param['category'];
		$keyword 	= $param['keyword'];
		
		# order define columns start
		$sort_column_index				= $param['sort_column'];
		$sort_dir						= $param['sort_dir'];
		
		$order_by_column[] = 'coa_hierarchy';
		$order_by_column[] = 'coa_id';
		$order_by_column[] = 'coa_name';
		
		$order_by = $order_by_column[$sort_column_index] . $sort_dir;
		# order define column end
		
		$column['p1']			= 'coa_hierarchy';
		$column['p2']			= 'coa_name';
		
		$this->db->start_cache();
		$this->db->where('coa_id <> ', 0);
		# $this->db->where('coa_type', 0);
		if(array_key_exists($category, $column) && strlen($keyword) > 0)
		{
			if ($category == 'p1') $this->db->like($column[$category], $keyword, 'after'); else $this->db->like($column[$category], $keyword);
			
		}// end if
		$this->db->stop_cache();
		
		// hitung total record
		$this->db->select('COUNT(1) AS total', 1); // pastikan ada AS total nya, 1 bila isinya adalah function (dalam hal ini COUNT)
		$query	= $this->db->get('coas'); 
		$row 	= $query->row_array(); // fungsi ci untuk mengambil 1 row saja dari query
		$total 	= $row['total'];	
				
		
		// proses query sesuai dengan parameter
		$this->db->select('*', 1); // ambil seluruh data				
		$this->db->order_by($order_by);
		$query = $this->db->get('coas', $limit, $offset);
		
		$data = array(); // inisialisasi variabel. biasakanlah, untuk mencegah warning dari php.
		foreach($query->result_array() as $row) {
			
			$kode = $row['coa_id'];
			$style = $row['coa_type'] == 0 ? 'coa_c1' : 'coa_c2';
			
			$row = $this->_cc_renderer($row);

			
			$tempIndent = '';
			$CCLevel 	= $row['coa_level'] - 1;
			for($i=0; $i<$CCLevel ; $i++) $tempIndent .='. ';
					
			$tempIndent .= $row['coa_name'];
#			$tempIndent .=  $row['coa_name'];
			
			$data[] = array(
				$row['coa_id'],
				$row['coa_hierarchy'],
				$tempIndent
			); 
		}
		
		// kembalikan nilai dalam format datatables_control
		return make_datatables_control($param, $data, $total);
	}
	
	function coa2_get($id, $mode)
	{
		if (!$id) return NULL;
		
		$id = trim($id);
		if (empty($id)) return NULL;
		
		if ($mode == 1)
			$query = $this->db->get_where('coas', array('coa_id' => $id), 1);
		else
			$query = $this->db->get_where('coas', array('coa_hierarchy' => $id), 1);
		
		$result = NULL;		
		foreach($query->result_array() as $row)	$result = $this->_coa_renderer($row);
		
		return $result;
	}

	## coa_account_type ##
	function coa_account_type_renderer($data)
	{
		return format_html($data);
	}

	
	function coa_account_type_control()
	{
		// map parameter ke variable biasa agar mudah digunakan
		$params 	= get_datatables_control();
		$limit 		= $params['limit'];
		$offset 	= $params['offset'];
		$category 	= $params['category'];
		$keyword 	= $params['keyword'];
		
		# order define columns start
		
		$order_by_column[] = 'coa_hierarchy';
		$order_by_column[] = 'coa_id';
		$order_by_column[] = 'coa_name';
		
		$sort_column_index		= $params['sort_column'];
		$sort_dir				= $params['sort_dir'];
		
		# order define column end
		
		$column['p1']			= 'coa_hierarchy';
		$column['p2']			= 'coa_name';
		$order_by = $order_by_column[$sort_column_index] . $sort_dir;
		
		$this->db->start_cache();
		$this->db->where('coa_id <> ', 0);
		if(array_key_exists($category, $column) && strlen($keyword) > 0)
		{
			if ($category == 'p1') $this->db->like($column[$category], $keyword, 'after'); else $this->db->like($column[$category], $keyword);
			
		}// end if
		$this->db->stop_cache();
		
		// hitung total record
		$this->db->select('COUNT(1) AS total', 1); // pastikan ada AS total nya, 1 bila isinya adalah function (dalam hal ini COUNT)
		$this->db->where('coa_level', 2);
		$query	= $this->db->get('coas'); 
		
		$row 	= $query->row_array(); // fungsi ci untuk mengambil 1 row saja dari query
		$total 	= $row['total'];	
				
		
		// proses query sesuai dengan parameter
		$this->db->select('*', 1); // ambil seluruh data
		$this->db->where('coa_level', 2);				
		$this->db->order_by($order_by);
		$query = $this->db->get('coas', $limit, $offset);
		
		$data = array(); // inisialisasi variabel. biasakanlah, untuk mencegah warning dari php.
		foreach($query->result_array() as $row) {
			
			$kode = $row['coa_id'];
			$style = $row['coa_type'] == 0 ? 'coa_c1' : 'coa_c2';
			
			$row = $this->_cc_renderer($row);
			
			$tempIndent = '';
			$CCLevel 	= $row['coa_level'] - 1;
#			for($i=0; $i<$CCLevel ; $i++) $tempIndent .='. ';
					
#			$tempIndent .= '<span id="' . $style . '">' . $row['coa_name'] . '</span>';
			$tempIndent .=  $row['coa_name'];
			
			$data[] = array(
				$row['coa_id'],
				$row['coa_hierarchy'],
				$tempIndent
			); 
		}
		
		// kembalikan nilai dalam format datatables_control
		return make_datatables_control($params, $data, $total);
	}
	
	function coa_account_type_get($id, $mode)
	{
		if (!$id) return NULL;
		
		$id = trim($id);
		if (empty($id)) return NULL;
		
		if ($mode == 1)
			$query = $this->db->get_where('coas', array('coa_id' => $id), 1);
		else
			$query = $this->db->get_where('coas', array('coa_hierarchy' => $id), 1);
		
		$result = NULL;		
		foreach($query->result_array() as $row)	$result = $this->coa_account_type_renderer($row);
		
		return $result;
	}
	
	## Data Gedung
	function building_control($param)
	{
		// map parameter ke variable biasa agar mudah digunakan
		$limit 		= $param['limit'];
		$offset	 	= $param['offset'];
		$category 	= $param['category'];
		$keyword 	= $param['keyword'];
		
		# order define columns start
		$sort_column_index				= $param['sort_column'];
		$sort_dir						= $param['sort_dir'];
		
		$order_by_column[] = 'building_id';
		$order_by_column[] = 'building_code';
		$order_by_column[] = 'building_name';
		
		$order_by = $order_by_column[$sort_column_index] . $sort_dir;
		# order define column end
		
		$column['p1']			= 'building_code';
		$column['p2']			= 'building_name';
		
		$this->db->start_cache();
		$this->db->where('building_id <> ', 0);
		if(array_key_exists($category, $column) && strlen($keyword) > 0)
		{
			$this->db->like($column[$category], $keyword);
		}// end if
		$this->db->stop_cache();
		
		// hitung total record
		$this->db->select('COUNT(1) AS total', 1); // pastikan ada AS total nya, 1 bila isinya adalah function (dalam hal ini COUNT)
		$query	= $this->db->get('buildings'); 
		$row 	= $query->row_array(); // fungsi ci untuk mengambil 1 row saja dari query
		$total 	= $row['total'];	
				
		
		// proses query sesuai dengan parameter
		$this->db->select('*', 1); // ambil seluruh data				
		$this->db->order_by($order_by);
		$query = $this->db->get('buildings', $limit, $offset);
		
		$data = array(); // inisialisasi variabel. biasakanlah, untuk mencegah warning dari php.
		foreach($query->result_array() as $row) {
			
			$kode = $row['building_id'];
			
			$row = format_html($row);
			
			$data[] = array(
				$row['building_id'], 
				$row['building_code'], 
				$row['building_name']
			); 
		}
		
		// kembalikan nilai dalam format datatables_control
		return make_datatables_control($param, $data, $total);
	}
	
	function building_get($id, $mode)
	{
		if (empty($id) || !$id || !$mode) return NULL;
		
		$result = NULL;
		
		if ($mode == 1)
			$query = $this->db->get_where('buildings', array('building_id' => $id), 1);
		else
			$query = $this->db->get_where('buildings', array('building_code' => $id), 1);
			
		foreach($query->result_array() as $row)	$result = format_html($row);
		
		return $result;
	}
	
	
	## Data employee
	function employee_control($param)
	{
		// map parameter ke variable biasa agar mudah digunakan
		$limit 		= $param['limit'];
		$offset	 	= $param['offset'];
		$category 	= $param['category'];
		$keyword 	= $param['keyword'];
		
		# order define columns start
		$sort_column_index				= $param['sort_column'];
		$sort_dir						= $param['sort_dir'];
		
		$order_by_column[] = 'employee_id';
		$order_by_column[] = 'employee_nip';
		$order_by_column[] = 'employee_name';
		
		$order_by = $order_by_column[$sort_column_index] . $sort_dir;
		# order define column end
		
		$column['p1']			= 'employee_nip';
		$column['p2']			= 'employee_name';
		
		$this->db->start_cache();
		
		if(array_key_exists($category, $column) && strlen($keyword) > 0)
		{
			$this->db->like($column[$category], $keyword);
		}// end if
		$this->db->stop_cache();
		
		// hitung total record
		$this->db->select('COUNT(1) AS total', 1); // pastikan ada AS total nya, 1 bila isinya adalah function (dalam hal ini COUNT)
		$this->db->where('employee_id <> ', 1);
		$query	= $this->db->get('employees'); 
		$row 	= $query->row_array(); // fungsi ci untuk mengambil 1 row saja dari query
		$total 	= $row['total'];	
				
		
		// proses query sesuai dengan parameter
		$this->db->select('*', 1); // ambil seluruh data
		$this->db->where('employee_id <> ', 1);				
		$this->db->order_by($order_by);
		$query = $this->db->get('employees', $limit, $offset);
		
		$data = array(); // inisialisasi variabel. biasakanlah, untuk mencegah warning dari php.
		foreach($query->result_array() as $row) {
			
			
			$row = format_html($row);
			
			$data[] = array(
				$row['employee_id'], 
				$row['employee_nip'], 
				$row['employee_name']
			); 
		}
		
		// kembalikan nilai dalam format datatables_control
		return make_datatables_control($param, $data, $total);
	}
	
	function employee_get($id, $mode)
	{
		if (empty($id) || !$id || !$mode) return NULL;
		
		$result = NULL;
		
		if ($mode == 1)
			$query = $this->db->get_where('employees', array('employee_id' => $id), 1);
		else
			$query = $this->db->get_where('employees', array('employee_nip' => $id), 1);
			
		foreach($query->result_array() as $row)	$result = format_html($row);
		
		return $result;
	}
	
	## Data product_category
	function product_category_control($param)
	{
		// map parameter ke variable biasa agar mudah digunakan
		$limit 		= $param['limit'];
		$offset	 	= $param['offset'];
		$category 	= $param['category'];
		$keyword 	= $param['keyword'];
		
		# order define columns start
		$sort_column_index				= $param['sort_column'];
		$sort_dir						= $param['sort_dir'];
		
		$order_by_column[] = 'product_category_id';
		$order_by_column[] = 'product_category_name';
		
		$order_by = $order_by_column[$sort_column_index] . $sort_dir;
		# order define column end
		
		$column['p1']			= 'product_category_name';
	
		$this->db->start_cache();
		
		if(array_key_exists($category, $column) && strlen($keyword) > 0)
		{
			$this->db->like($column[$category], $keyword);
		}// end if
		$this->db->stop_cache();
		
		// hitung total record
		$this->db->select('COUNT(1) AS total', 1); // pastikan ada AS total nya, 1 bila isinya adalah function (dalam hal ini COUNT)
		$query	= $this->db->get('product_categories'); 
		$row 	= $query->row_array(); // fungsi ci untuk mengambil 1 row saja dari query
		$total 	= $row['total'];	
				
		
		// proses query sesuai dengan parameter
		$this->db->select('*', 1); // ambil seluruh data
		$this->db->order_by($order_by);
		$query = $this->db->get('product_categories', $limit, $offset);
		
		$data = array(); // inisialisasi variabel. biasakanlah, untuk mencegah warning dari php.
		foreach($query->result_array() as $row) {
			
			
			$row = format_html($row);
			
			$data[] = array(
				$row['product_category_id'], 
				$row['product_category_name']
			); 
		}
		
		// kembalikan nilai dalam format datatables_control
		return make_datatables_control($param, $data, $total);
	}
	
	function product_category_get($id, $mode)
	{
		if (empty($id) || !$id || !$mode) return NULL;
		
		$result = NULL;
		
		if ($mode == 1)
			$query = $this->db->get_where('product_categories', array('product_category_id' => $id), 1);
		else
			$query = $this->db->get_where('product_categories', array('product_category_id' => $id), 1);
			
		foreach($query->result_array() as $row)	$result = format_html($row);
		
		return $result;
	}
	
	## Data product_type
	function product_type_control($param)
	{
		// map parameter ke variable biasa agar mudah digunakan
		$limit 		= $param['limit'];
		$offset	 	= $param['offset'];
		$category 	= $param['category'];
		$keyword 	= $param['keyword'];
		
		# order define columns start
		$sort_column_index				= $param['sort_column'];
		$sort_dir						= $param['sort_dir'];
		
		$order_by_column[] = 'product_type_id';
		$order_by_column[] = 'product_type_name';
		
		$order_by = $order_by_column[$sort_column_index] . $sort_dir;
		# order define column end
		
		$column['p1']			= 'product_type_name';
	
		$this->db->start_cache();
		
		if(array_key_exists($category, $column) && strlen($keyword) > 0)
		{
			$this->db->like($column[$category], $keyword);
		}// end if
		$this->db->stop_cache();
		
		// hitung total record
		$this->db->select('COUNT(1) AS total', 1); // pastikan ada AS total nya, 1 bila isinya adalah function (dalam hal ini COUNT)
		$query	= $this->db->get('product_types'); 
		$row 	= $query->row_array(); // fungsi ci untuk mengambil 1 row saja dari query
		$total 	= $row['total'];	
				
		
		// proses query sesuai dengan parameter
		$this->db->select('*', 1); // ambil seluruh data
		$this->db->order_by($order_by);
		$query = $this->db->get('product_types', $limit, $offset);
		
		$data = array(); // inisialisasi variabel. biasakanlah, untuk mencegah warning dari php.
		foreach($query->result_array() as $row) {
			
			
			$row = format_html($row);
			
			$data[] = array(
				$row['product_type_id'], 
				$row['product_type_name']
			); 
		}
		
		// kembalikan nilai dalam format datatables_control
		return make_datatables_control($param, $data, $total);
	}
	
	function product_type_get($id, $mode)
	{
		if (empty($id) || !$id || !$mode) return NULL;
		
		$result = NULL;
		
		if ($mode == 1)
			$query = $this->db->get_where('product_types', array('product_type_id' => $id), 1);
		else
			$query = $this->db->get_where('product_types', array('product_type_code' => $id), 1);
			
		foreach($query->result_array() as $row)	$result = format_html($row);
		
		return $result;
	}
	
	## Data customer
	function customer_control($param)
	{
		// map parameter ke variable biasa agar mudah digunakan
		$limit 		= $param['limit'];
		$offset	 	= $param['offset'];
		$category 	= $param['category'];
		$keyword 	= $param['keyword'];
		$where = '';
		# order define columns start
		$sort_column_index				= $param['sort_column'];
		$sort_dir						= $param['sort_dir'];
		
		$order_by_column[] = 'customer_id';
		$order_by_column[] = 'customer_number';
		$order_by_column[] = 'customer_name';
		
		$order_by = $order_by_column[$sort_column_index] . $sort_dir;
		# order define column end
		
		$column['p1']			= 'customer_number';
		$column['p2']			= 'customer_name';
	
		$this->db->start_cache();
		
		$order_by = " order by ".$order_by_column[$sort_column_index] . $sort_dir;
		if (array_key_exists($category, $column) && strlen($keyword) > 0) 
		{
			
				$where = " where ".$column[$category]." like '%$keyword%'";
			
			
		}
		if ($limit > 0) {
			$limit = " limit $limit offset $offset";
		};	

		$sql = "
		select * from customers
		$where  $order_by
			
			";

		$query_total = $this->db->query($sql);
		$total = $query_total->num_rows();
		
		$sql = $sql.$limit;
		
		$query = $this->db->query($sql);
		
		$data = array(); // inisialisasi variabel. biasakanlah, untuk mencegah warning dari php.
		foreach($query->result_array() as $row) {
			
			
			$row = format_html($row);
			
			$data[] = array(
				$row['customer_id'], 
				$row['customer_number'],
				$row['customer_name']
			); 

		}
		
		// kembalikan nilai dalam format datatables_control
		return make_datatables_control($param, $data, $total);
	}
	
	function customer_get($id, $mode)
	{
		if (empty($id) || !$id || !$mode) return NULL;
		
		$result = NULL;
		
		if ($mode == 1)
			$query = $this->db->get_where('customers', array('customer_id' => $id), 1);
		else
			$query = $this->db->get_where('customers', array('customer_number' => $id), 1);
			
		foreach($query->result_array() as $row)	$result = format_html($row);
		
		return $result;
	}

		## Data active product
	function active_product_control($param, $price_id = 0, $stand_id = 0)
	{
		// map parameter ke variable biasa agar mudah digunakan
		$limit 		= $param['limit'];
		$offset	 	= $param['offset'];
		$category 	= $param['category'];
		$keyword 	= $param['keyword'];
		$where = '';
		# order define columns start
		$sort_column_index				= $param['sort_column'];
		$sort_dir						= $param['sort_dir'];
		
		$order_by_column[] = 'product_stock_id';
		$order_by_column[] = 'stand_name';
		$order_by_column[] = 'product_category_name';
		$order_by_column[] = 'product_code';
		$order_by_column[] = 'product_name';
		$order_by_column[] = 'product_type_name';
		$order_by_column[] = 'product_stock_qty';
		if($price_id == 1){
			$order_by_column[] = 'user_price';
		}else if($price_id == 2){
			$order_by_column[] = 'freeline_price';
		}else if($price_id == 3){
			$order_by_column[] = 'counter_price';
		}else if($price_id == 4){
			$order_by_column[] = 'online_price';
		}else if($price_id == 5){
			$order_by_column[] = 'another_price';
		}
		
		$order_by = $order_by_column[$sort_column_index] . $sort_dir;
		# order define column end
		
		$column['p1']			= 'product_category_name';
		$column['p2']			= 'product_name';
		$column['p3']			= 'product_type_name';
		$column['p4']			= 'product_code';
	
		$this->db->start_cache();
		
		$order_by = " order by ".$order_by_column[$sort_column_index] . $sort_dir;
		if (array_key_exists($category, $column) && strlen($keyword) > 0) 
		{
			
				$where = " and ".$column[$category]." like '%$keyword%'";
			
			
		}
		if ($limit > 0) {
			$limit = " limit $limit offset $offset";
		};	
		
		if($stand_id){
			$where .= "and e.stand_id = '$stand_id'";
		}
		
		$sql = "
		select a.*, b.product_name, b.product_code, c.product_category_name,  e.stand_name
		from product_stocks a
		join products b on b.product_id = a.product_id
		join product_categories c on c.product_category_id = b.product_category_id
		
		join stands e on e.stand_id = a.stand_id
		where product_stock_qty > 0
		$where  $order_by
			
			";

		$query_total = $this->db->query($sql);
		$total = $query_total->num_rows();
		
		$sql = $sql.$limit;
		
		$query = $this->db->query($sql);
		
		$data = array(); // inisialisasi variabel. biasakanlah, untuk mencegah warning dari php.
		foreach($query->result_array() as $row) {
			
			if($price_id == 1){
				$price = $row['user_price'];
			}else if($price_id == 2){
				$price = $row['freeline_price'];
			}else if($price_id == 3){
				$price = $row['counter_price'];
			}else if($price_id == 4){
				$price = $row['online_price'];
			}else if($price_id == 5){
				$price = $row['another_price'];
			}
			
			$row = format_html($row);
			
			$data[] = array(
				$row['product_stock_id'], 
				$row['stand_name'],
				$row['product_category_name'],
				$row['product_code'],
				$row['product_name'],
				//$row['product_type_name'],
				$row['product_stock_qty'],
				tool_money_format($price)
				
			); 
		}
		
		// kembalikan nilai dalam format datatables_control
		return make_datatables_control($param, $data, $total);
	}
	
	function active_product_get($id, $mode)
	{
		if (empty($id) || !$id || !$mode) return NULL;
		
		$result = NULL;
		
		if ($mode == 1){
			$this->db->select('a.*, b.product_name, b.product_code, c.stand_name');
			$this->db->join('products b','b.product_id = a.product_id');
			$this->db->join('stands c','c.stand_id = a.stand_id');
			$query = $this->db->get_where('product_stocks a', array('a.product_stock_id' => $id), 1);
		}else{
			$query = $this->db->get_where('product_stocks', array('product_stock_code' => $id), 1);
		}
		foreach($query->result_array() as $row)	$result = format_html($row);
		
		return $result;
	}
	
	## Data produk
	function product_control($param)
	{
		// map parameter ke variable biasa agar mudah digunakan
		$limit 		= $param['limit'];
		$offset	 	= $param['offset'];
		$category 	= $param['category'];
		$keyword 	= $param['keyword'];
		$where = '';
		# order define columns start
		$sort_column_index				= $param['sort_column'];
		$sort_dir						= $param['sort_dir'];
		
		$order_by_column[] = 'product_id';
		$order_by_column[] = 'product_code';
		$order_by_column[] = 'product_name';
		//$order_by_column[] = 'product_type_name';
		$order_by_column[] = 'product_category_name';
		
		$order_by = $order_by_column[$sort_column_index] . $sort_dir;
		# order define column end
		
		$column['p1']			= 'product_code';
		$column['p2']			= 'product_name';
		//$column['p3']			= 'product_type_name';
		$column['p4']			= 'product_category_name';
	
		$this->db->start_cache();
		
		$order_by = " order by ".$order_by_column[$sort_column_index] . $sort_dir;
		if (array_key_exists($category, $column) && strlen($keyword) > 0) 
		{
			
				$where = " where ".$column[$category]." like '%$keyword%'";
			
			
		}
		if ($limit > 0) {
			$limit = " limit $limit offset $offset";
		};	

		$sql = "
		select a.*, c.product_category_name 
		from products a
		join product_categories c on c.product_category_id = a.product_category_id
		$where  $order_by
			
			";

		$query_total = $this->db->query($sql);
		$total = $query_total->num_rows();
		
		$sql = $sql.$limit;
		
		$query = $this->db->query($sql);
		
		$data = array(); // inisialisasi variabel. biasakanlah, untuk mencegah warning dari php.
		foreach($query->result_array() as $row) {
			
			
			$row = format_html($row);
			
			$data[] = array(
				$row['product_id'], 
				$row['product_code'],
				$row['product_name'],
				//$row['product_type_name'],
				$row['product_category_name']
			); 

		}
		
		// kembalikan nilai dalam format datatables_control
		return make_datatables_control($param, $data, $total);
	}
	
	function product_get($id, $mode)
	{
		if (empty($id) || !$id || !$mode) return NULL;
		
		$result = NULL;
		
		if ($mode == 1)
			$query = $this->db->get_where('products', array('product_id' => $id), 1);
		else
			$query = $this->db->get_where('products', array('product_code' => $id), 1);
			
		foreach($query->result_array() as $row)	$result = format_html($row);
		
		return $result;
	}


	## Data stand
	function stand_control($param)
	{
		// map parameter ke variable biasa agar mudah digunakan
		$limit 		= $param['limit'];
		$offset	 	= $param['offset'];
		$category 	= $param['category'];
		$keyword 	= $param['keyword'];
		
		# order define columns start
		$sort_column_index				= $param['sort_column'];
		$sort_dir						= $param['sort_dir'];
		
		$order_by_column[] = 'stand_id';
		$order_by_column[] = 'stand_name';
		
		$order_by = $order_by_column[$sort_column_index] . $sort_dir;
		# order define column end
		
		$column['p1']			= 'stand_name';
	
		$this->db->start_cache();
		
		if(array_key_exists($category, $column) && strlen($keyword) > 0)
		{
			$this->db->like($column[$category], $keyword);
		}// end if
		$this->db->stop_cache();
		
		// hitung total record
		$this->db->select('COUNT(1) AS total', 1); // pastikan ada AS total nya, 1 bila isinya adalah function (dalam hal ini COUNT)
		$query	= $this->db->get('stands'); 
		$row 	= $query->row_array(); // fungsi ci untuk mengambil 1 row saja dari query
		$total 	= $row['total'];	
				
		
		// proses query sesuai dengan parameter
		$this->db->select('*', 1); // ambil seluruh data
		$this->db->order_by($order_by);
		$query = $this->db->get('stands', $limit, $offset);
		
		$data = array(); // inisialisasi variabel. biasakanlah, untuk mencegah warning dari php.
		foreach($query->result_array() as $row) {
			
			
			$row = format_html($row);
			
			$data[] = array(
				$row['stand_id'], 
				$row['stand_name']
			); 
		}
		
		// kembalikan nilai dalam format datatables_control
		return make_datatables_control($param, $data, $total);
	}
	
	function stand_get($id, $mode)
	{
		if (empty($id) || !$id || !$mode) return NULL;
		
		$result = NULL;
		
		if ($mode == 1)
			$query = $this->db->get_where('stands', array('stand_id' => $id), 1);
		else
			$query = $this->db->get_where('stands', array('stand_name' => $id), 1);
			
		foreach($query->result_array() as $row)	$result = format_html($row);
		
		return $result;
	}
	
		## Data salesman
	function salesman_control($param)
	{
		// map parameter ke variable biasa agar mudah digunakan
		$limit 		= $param['limit'];
		$offset	 	= $param['offset'];
		$category 	= $param['category'];
		$keyword 	= $param['keyword'];
		
		# order define columns start
		$sort_column_index				= $param['sort_column'];
		$sort_dir						= $param['sort_dir'];
		
		$order_by_column[] = 'salesman_id';
		$order_by_column[] = 'salesman_name';
		
		$order_by = $order_by_column[$sort_column_index] . $sort_dir;
		# order define column end
		
		$column['p1']			= 'salesman_code';
		$column['p2']			= 'salesman_name';
		
		$this->db->start_cache();
		
		if(array_key_exists($category, $column) && strlen($keyword) > 0)
		{
			$this->db->like($column[$category], $keyword);
		}// end if
		$this->db->stop_cache();
		
		// hitung total record
		$this->db->select('COUNT(1) AS total', 1); // pastikan ada AS total nya, 1 bila isinya adalah function (dalam hal ini COUNT)
		$this->db->where('salesman_status', 1);
		$query	= $this->db->get('salesmans'); 
		$row 	= $query->row_array(); // fungsi ci untuk mengambil 1 row saja dari query
		$total 	= $row['total'];	
				
		
		// proses query sesuai dengan parameter
		$this->db->select('*', 1); // ambil seluruh data
		$this->db->where('salesman_status', 1);
		$this->db->order_by($order_by);
		$query = $this->db->get('salesmans', $limit, $offset);
		
		$data = array(); // inisialisasi variabel. biasakanlah, untuk mencegah warning dari php.
		foreach($query->result_array() as $row) {
			
			
			$row = format_html($row);
			
			$data[] = array(
				$row['salesman_id'], 
				$row['salesman_code'],
				$row['salesman_name']
			); 
		}
		
		// kembalikan nilai dalam format datatables_control
		return make_datatables_control($param, $data, $total);
	}
	
	function salesman_get($id, $mode)
	{
		if (empty($id) || !$id || !$mode) return NULL;
		
		$result = NULL;
		
		if ($mode == 1)
			$query = $this->db->get_where('salesmans', array('salesman_id' => $id), 1);
		else
			$query = $this->db->get_where('salesmans', array('salesman_name' => $id), 1);
			
		foreach($query->result_array() as $row)	$result = format_html($row);
		
		return $result;
	}
	
	## Data vendor
	function vendor_control($param)
	{
		// map parameter ke variable biasa agar mudah digunakan
		$limit 		= $param['limit'];
		$offset	 	= $param['offset'];
		$category 	= $param['category'];
		$keyword 	= $param['keyword'];
		
		# order define columns start
		$sort_column_index				= $param['sort_column'];
		$sort_dir						= $param['sort_dir'];
		
		$order_by_column[] = 'vendor_id';
		$order_by_column[] = 'vendor_code';
		$order_by_column[] = 'vendor_name';
		
		$order_by = $order_by_column[$sort_column_index] . $sort_dir;
		# order define column end
		
		$column['p1']			= 'vendor_code';
		$column['p2']			= 'vendor_name';
		
		$this->db->start_cache();
		
		if(array_key_exists($category, $column) && strlen($keyword) > 0)
		{
			$this->db->like($column[$category], $keyword);
		}// end if
		$this->db->stop_cache();
		
		// hitung total record
		$this->db->select('COUNT(1) AS total', 1); // pastikan ada AS total nya, 1 bila isinya adalah function (dalam hal ini COUNT)
		$this->db->where('vendor_status', 1);
		$query	= $this->db->get('vendors'); 
		$row 	= $query->row_array(); // fungsi ci untuk mengambil 1 row saja dari query
		$total 	= $row['total'];	
				
		
		// proses query sesuai dengan parameter
		$this->db->select('*', 1); // ambil seluruh data
		$this->db->where('vendor_status', 1);
		$this->db->order_by($order_by);
		$query = $this->db->get('vendors', $limit, $offset);
		
		$data = array(); // inisialisasi variabel. biasakanlah, untuk mencegah warning dari php.
		foreach($query->result_array() as $row) {
			
			
			$row = format_html($row);
			
			$data[] = array(
				$row['vendor_id'], 
				$row['vendor_code'],
				$row['vendor_name']
			); 
		}
		
		// kembalikan nilai dalam format datatables_control
		return make_datatables_control($param, $data, $total);
	}
	
	function vendor_get($id, $mode)
	{
		if (empty($id) || !$id || !$mode) return NULL;
		
		$result = NULL;
		
		if ($mode == 1)
			$query = $this->db->get_where('vendors', array('vendor_id' => $id), 1);
		else
			$query = $this->db->get_where('vendors', array('vendor_name' => $id), 1);
			
		foreach($query->result_array() as $row)	$result = format_html($row);
		
		return $result;
	}
	
	## Data periode
	function period_control($param)
	{
		// map parameter ke variable biasa agar mudah digunakan
		$limit 		= $param['limit'];
		$offset	 	= $param['offset'];
		$category 	= $param['category'];
		$keyword 	= $param['keyword'];
		
		# order define columns start
		$sort_column_index				= $param['sort_column'];
		$sort_dir						= $param['sort_dir'];
		
		$order_by_column[] = 'period_id';
		$order_by_column[] = 'period_month';
		$order_by_column[] = 'period_year';
		
		$order_by = $order_by_column[$sort_column_index] . $sort_dir;
		# order define column end
		
		$column['p1']			= 'period_code';
		$column['p2']			= 'period_month';
		$column['p3']			= 'period_year';
		
		$this->db->start_cache();
		if(array_key_exists($category, $column) && strlen($keyword) > 0)
		{
			$this->db->like($column[$category], $keyword);
		}// end if
		$this->db->stop_cache();
		
		// hitung total record
		$this->db->select('COUNT(1) AS total', 1); // pastikan ada AS total nya, 1 bila isinya adalah function (dalam hal ini COUNT)
		$query	= $this->db->get('periods'); 
		$row 	= $query->row_array(); // fungsi ci untuk mengambil 1 row saja dari query
		$total 	= $row['total'];	
				
		
		// proses query sesuai dengan parameter
		$this->db->select('*', 1); // ambil seluruh data				
		$this->db->order_by($order_by);
		$query = $this->db->get('periods', $limit, $offset);
			
		$data = array(); // inisialisasi variabel. biasakanlah, untuk mencegah warning dari php.
		foreach($query->result_array() as $row) {
		
			$status = 	($row['period_closed']=='1') ? "Aktif" : "Tidak Aktif";
		
			$row = format_html($row);
			
			$data[] = array(
				$row['period_id'], 
				$row['period_month']."/".$row['period_year'], 
				$status
			); 
		}
		
		// kembalikan nilai dalam format datatables_control
		return make_datatables_control($param, $data, $total);
	}
	
	function period_get($id, $mode)
	{
		if (empty($id) || !$id || !$mode) return NULL;
		
		$result = NULL;
		
		if ($mode == 1)
			$query = $this->db->get_where('periods', array('period_id' => $id), 1);
		else
			$query = $this->db->get_where('periods', array('period_name' => $id), 1);
			
		foreach($query->result_array() as $row)	$result = format_html($row);
		
		return $result;
	}
	
	## Data Cabang
	function market_control($param, $id)
	{
		// map parameter ke variable biasa agar mudah digunakan
		$limit 		= $param['limit'];
		$offset	 	= $param['offset'];
		$category 	= $param['category'];
		$keyword 	= $param['keyword'];
		
		# order define columns start
		$sort_column_index				= $param['sort_column'];
		$sort_dir						= $param['sort_dir'];
		
		$order_by_column[] = 'market_id';
		$order_by_column[] = 'market_code';
		$order_by_column[] = 'market_name';
		
		$order_by = $order_by_column[$sort_column_index] . $sort_dir;
		# order define column end
		
		$column['p1']			= 'market_code';
		$column['p2']			= 'market_name';
		
		$this->db->start_cache();
		$this->db->where('market_id <> ', 0);
		if(array_key_exists($category, $column) && strlen($keyword) > 0)
		{
			$this->db->like($column[$category], $keyword);
		}// end if
		
		if($id && $id!=9)
		$this->db->where('branch_id', $id);
		$this->db->stop_cache();
		
		// hitung total record
		$this->db->select('COUNT(1) AS total', 1); // pastikan ada AS total nya, 1 bila isinya adalah function (dalam hal ini COUNT)
		$query	= $this->db->get('markets'); 
		$row 	= $query->row_array(); // fungsi ci untuk mengambil 1 row saja dari query
		$total 	= $row['total'];	
				
		
		// proses query sesuai dengan parameter
		$this->db->select('*', 1); // ambil seluruh data				
		$this->db->order_by($order_by);
		$query = $this->db->get('markets', $limit, $offset);
		
		$data = array(); // inisialisasi variabel. biasakanlah, untuk mencegah warning dari php.
		foreach($query->result_array() as $row) {
			
			$kode = $row['market_id'];
			
			$row = format_html($row);
			
			$data[] = array(
				$row['market_id'], 
				$row['market_code'], 
				$row['market_name']
			); 
		}
		
		// kembalikan nilai dalam format datatables_control
		return make_datatables_control($param, $data, $total);
	}
	
	function market_get($id, $mode)
	{
		if (!$id) return NULL;
		
		$id = trim($id);
		if (empty($id)) return NULL;
		if ($mode == 1)
			$query = $this->db->get_where('markets', array('market_id' => $id), 1);
		else
			$query = $this->db->get_where('markets', array('market_code' => $id), 1);
		
		$result = NULL;		
		foreach($query->result_array() as $row)	$result = format_html($row);
		
		return $result;
	}
	
		## Data jabatan
	function employee_position_control($param, $id)
	{
		// map parameter ke variable biasa agar mudah digunakan
		$limit 		= $param['limit'];
		$offset	 	= $param['offset'];
		$category 	= $param['category'];
		$keyword 	= $param['keyword'];
		
		# order define columns start
		$sort_column_index				= $param['sort_column'];
		$sort_dir						= $param['sort_dir'];
		
		$order_by_column[] = 'employee_position_id';
		$order_by_column[] = 'employee_position_name';
		
		$order_by = $order_by_column[$sort_column_index] . $sort_dir;
		# order define column end
		
		$column['p2']			= 'employee_position_name';
		
		$this->db->start_cache();
		if(array_key_exists($category, $column) && strlen($keyword) > 0)
		{
			$this->db->like($column[$category], $keyword);
		}// end if
		
		$this->db->stop_cache();
		// hitung total record
		$this->db->select('COUNT(1) AS total', 1); // pastikan ada AS total nya, 1 bila isinya adalah function (dalam hal ini COUNT)
		$query	= $this->db->get('employee_positions'); 
		$row 	= $query->row_array(); // fungsi ci untuk mengambil 1 row saja dari query
		$total 	= $row['total'];	
				
		
		// proses query sesuai dengan parameter
		$this->db->select('*', 1); // ambil seluruh data				
		$this->db->order_by($order_by);
		$query = $this->db->get('employee_positions', $limit, $offset);
		
		
		$data = array(); // inisialisasi variabel. biasakanlah, untuk mencegah warning dari php.
		foreach($query->result_array() as $row) {
			
			$row = format_html($row);
			
			$data[] = array(
				$row['employee_position_id'], 
				$row['employee_position_name']
				
			); 
		}
		
		// kembalikan nilai dalam format datatables_control
		return make_datatables_control($param, $data, $total);
	}
	
	function employee_position_get($id, $mode)
	{
		if (!$id) return NULL;
		
		$id = trim($id);
		if (empty($id)) return NULL;
		if ($mode == 1)
			$query = $this->db->get_where('employee_positions', array('employee_position_id' => $id), 1);
		else
			$query = $this->db->get_where('employee_positions', array('employee_position_name' => $id), 1);
		
		$result = NULL;		
		foreach($query->result_array() as $row)	$result = format_html($row);
		
		return $result;
	}
	
}

#
