<?php

#
# --- datatable controller ---
#

class Dtc 
{
	function Dtc()
	{
		$ci = & get_instance();
		$ci->load->model('dtc_model');
	}
	
	## EMPLOYEE USER ##
	function employee_user_control()
	{
		$ci = & get_instance();
		$data = $ci->dtc_model->employee_user_control(get_datatables_control());
		
		send_json($data);
	}
	
	function employee_user_get()
	{
		$ci = & get_instance();
		$mode = $ci->input->post('mode');
		$data = $ci->input->post('data');// if (!$data) $data = 1;
		
		$result = $ci->dtc_model->employee_user_get($data, $mode);
		
		if ($result) 
		{ 
			$details['name'] = $result['employee_name'];
			$details['gender'] = $result['employee_gender'] == 0 ? 'Laki-laki' : 'Perempuan';
			$details['grade'] = '';//$result['grade_name'];
			$details['cc'] = '';//$result['lc_name'];
			$details['position'] = '';//$result['position_name'];
			$details['loa'] = '';//$result['loa_tag_name'];
			
			$details = $ci->load->view('dtc/detail_employee', $details, true);
			
			send_json_lookup_feedback($result['employee_id'], $result['employee_nip'], $result['employee_name'], $details);
		}
		else send_json_error_feedback();
	}
	
	## COA ##
	
	function coa_control()
	{
		$ci = & get_instance();
		$data = $ci->dtc_model->coa_control(get_datatables_control());
		send_json($data); 
	}
	
	function coa_get()
	{
		$ci = & get_instance();
		$mode = $ci->input->post('mode');
		$data = $ci->input->post('data');

		$result = $ci->dtc_model->coa_get($data, $mode);
		
		if ($result) 
		{ 
			send_json_lookup_feedback($result['coa_id'], $result['coa_hierarchy'], $result['coa_name']);

		}
		else send_json_error_feedback();
	}
	
	## COA2 ##
	
	function coa2_control()
	{
		$ci = & get_instance();
		$data = $ci->dtc_model->coa2_control(get_datatables_control());
		send_json($data); 
	}
	
	function coa2_get()
	{
		$ci = & get_instance();
		$mode = $ci->input->post('mode');
		$data = $ci->input->post('data');

		$result = $ci->dtc_model->coa2_get($data, $mode);
		
		if ($result) 
		{ 
			send_json_lookup_feedback($result['coa_id'], $result['coa_hierarchy'], $result['coa_name']);

		}
		else send_json_error_feedback();
	}

	## COA account type ##
	
	function coa_account_type_control()
	{
		$ci = & get_instance();
		$data = $ci->dtc_model->coa_account_type_control(get_datatables_control());
		send_json($data); 
	}
	
	function coa_account_type_get()
	{
		$ci = & get_instance();
		$mode = $ci->input->post('mode');
		$data = $ci->input->post('data');

		$result = $ci->dtc_model->coa_account_type_get($data, $mode);
		
		if ($result) 
		{ 
			send_json_lookup_feedback($result['coa_id'], $result['coa_name'], $result['coa_name']);

		}
		else send_json_error_feedback();
	}
	
	# lookup data gedung
	function building_control()
	{
		$ci = & get_instance();
		$data = $ci->dtc_model->building_control(get_datatables_control());
		send_json($data); 
	}
	
	function building_get()
	{
		$ci = & get_instance();
		$mode = $ci->input->post('mode');
		$data = $ci->input->post('data');

		$result = $ci->dtc_model->building_get($data, $mode);
		
		if ($result) 
		{ 
			send_json_lookup_feedback($result['building_id'], $result['building_code'], $result['building_name']);
		}
		else send_json_error_feedback();
	}
	
	# lookup data employee
	function employee_control()
	{
		$ci = & get_instance();
		$data = $ci->dtc_model->employee_control(get_datatables_control());
		send_json($data); 
	}
	
	function employee_get()
	{
		$ci = & get_instance();
		$mode = $ci->input->post('mode');
		$data = $ci->input->post('data');

		$result = $ci->dtc_model->employee_get($data, $mode);
		
		if ($result) 
		{ 
			send_json_lookup_feedback($result['employee_id'], $result['employee_nip']." - ".$result['employee_name'], $result['employee_name']);
		}
		else send_json_error_feedback();
	}
	
	# lookup data product_category
	function product_category_control()
	{
		$ci = & get_instance();
		$data = $ci->dtc_model->product_category_control(get_datatables_control());
		send_json($data); 
	}
	
	function product_category_get()
	{
		$ci = & get_instance();
		$mode = $ci->input->post('mode');
		$data = $ci->input->post('data');

		$result = $ci->dtc_model->product_category_get($data, $mode);
		
		if ($result) 
		{ 
			send_json_lookup_feedback($result['product_category_id'], $result['product_category_name'], $result['product_category_name']);
		}
		else send_json_error_feedback();
	}
	
	# lookup data product_type
	function product_type_control()
	{
		$ci = & get_instance();
		$data = $ci->dtc_model->product_type_control(get_datatables_control());
		send_json($data); 
	}
	
	function product_type_get()
	{
		$ci = & get_instance();
		$mode = $ci->input->post('mode');
		$data = $ci->input->post('data');

		$result = $ci->dtc_model->product_type_get($data, $mode);
		
		if ($result) 
		{ 
			send_json_lookup_feedback($result['product_type_id'], $result['product_type_name'], $result['product_type_name']);
		}
		else send_json_error_feedback();
	}
	
	# lookup data customer
	function customer_control()
	{
		$ci = & get_instance();
		$data = $ci->dtc_model->customer_control(get_datatables_control());
		send_json($data); 
	}
	
	function customer_get()
	{
		$ci = & get_instance();
		$mode = $ci->input->post('mode');
		$data = $ci->input->post('data');

		$result = $ci->dtc_model->customer_get($data, $mode);
		
		if ($result) 
		{ 
			send_json_lookup_feedback($result['customer_id'], $result['customer_number']. " - ".$result['customer_name'], $result['customer_number']);
		}
		else send_json_error_feedback();
	}
	
	
	# lookup data active product
	function active_product_control($price_id = 0, $stand_id = 0)
	{
		$ci = & get_instance();
		$data = $ci->dtc_model->active_product_control(get_datatables_control(), $price_id, $stand_id);
		send_json($data); 
	}
	
	function active_product_get()
	{
		$ci = & get_instance();
		$mode = $ci->input->post('mode');
		$data = $ci->input->post('data');

		$result = $ci->dtc_model->active_product_get($data, $mode);
		
		if ($result) 
		{ 
			send_json_lookup_feedback($result['product_stock_id'], $result['product_code']." - ".$result['product_name']. " - ".$result['stand_name'], $result['product_name']);
		}
		else send_json_error_feedback();
	}
	
	# lookup data product
	function product_control()
	{
		$ci = & get_instance();
		$data = $ci->dtc_model->product_control(get_datatables_control());
		send_json($data); 
	}
	
	function product_get()
	{
		$ci = & get_instance();
		$mode = $ci->input->post('mode');
		$data = $ci->input->post('data');

		$result = $ci->dtc_model->product_get($data, $mode);
		
		if ($result) 
		{ 
			send_json_lookup_feedback($result['product_id'], $result['product_code']." - ".$result['product_name'], $result['product_name']);
		}
		else send_json_error_feedback();
	}
	
	# lookup data stand
	function stand_control()
	{
		$ci = & get_instance();
		$data = $ci->dtc_model->stand_control(get_datatables_control());
		send_json($data); 
	}
	
	function stand_get()
	{
		$ci = & get_instance();
		$mode = $ci->input->post('mode');
		$data = $ci->input->post('data');

		$result = $ci->dtc_model->stand_get($data, $mode);
		
		if ($result) 
		{ 
			send_json_lookup_feedback($result['stand_id'], $result['stand_name'], $result['stand_name']);
		}
		else send_json_error_feedback();
	}
	
	# lookup data salesman
	function salesman_control()
	{
		$ci = & get_instance();
		$data = $ci->dtc_model->salesman_control(get_datatables_control());
		send_json($data); 
	}
	
	function salesman_get()
	{
		$ci = & get_instance();
		$mode = $ci->input->post('mode');
		$data = $ci->input->post('data');

		$result = $ci->dtc_model->salesman_get($data, $mode);
		
		if ($result) 
		{ 
			send_json_lookup_feedback($result['salesman_id'], $result['salesman_name'], $result['salesman_name']);
		}
		else send_json_error_feedback();
	}
	
	# lookup data vendor
	function vendor_control()
	{
		$ci = & get_instance();
		$data = $ci->dtc_model->vendor_control(get_datatables_control());
		send_json($data); 
	}
	
	function vendor_get()
	{
		$ci = & get_instance();
		$mode = $ci->input->post('mode');
		$data = $ci->input->post('data');

		$result = $ci->dtc_model->vendor_get($data, $mode);
		
		if ($result) 
		{ 
			send_json_lookup_feedback($result['vendor_id'], $result['vendor_name'], $result['vendor_name']);
		}
		else send_json_error_feedback();
	}
	
	# lookup data periode 
	function period_control()
	{
		$ci = & get_instance();
		$data = $ci->dtc_model->period_control(get_datatables_control());
		send_json($data); 
	}
	
	function period_get()
	{
		$ci = & get_instance();
		$mode = $ci->input->post('mode');
		$data = $ci->input->post('data');

		$result = $ci->dtc_model->period_get($data, $mode);
		
		if ($result) 
		{ 
			send_json_lookup_feedback($result['period_id'], $result['period_name'], $result['period_month']."/".$result['period_year']);
		}
		else send_json_error_feedback();
	}
	
	
	
	
	
		# lookup data cabang
	function market_control($id=0)
	{
		$ci = & get_instance();
		$data = $ci->dtc_model->market_control(get_datatables_control(), $id);
		send_json($data); 
	}
	
	
	function market_get()
	{
		$ci = & get_instance();
		$mode = $ci->input->post('mode');
		$data = $ci->input->post('data');

		$result = $ci->dtc_model->market_get($data, $mode);
		
		if ($result) 
		{ 
			send_json_lookup_feedback($result['market_id'], $result['market_code'], $result['market_name']);

		}
		else send_json_error_feedback();
	}

	# lookup data jabatan
	function employee_position_control($id=0)
	{
		$ci = & get_instance();
		$data = $ci->dtc_model->employee_position_control(get_datatables_control(), $id);
		send_json($data); 
	}
	
	
	function employee_position_get()
	{
		$ci = & get_instance();
		$mode = $ci->input->post('mode');
		$data = $ci->input->post('data');

		$result = $ci->dtc_model->employee_position_get($data, $mode);
		
		if ($result) 
		{ 
			send_json_lookup_feedback($result['employee_position_id'], $result['employee_position_name'], $result['employee_position_name']);

		}
		else send_json_error_feedback();
	}


}

