<?
define('EDIT_CONTROL', 1, TRUE);
define('MEMO_CONTROL', 2, TRUE);
define('BACK_CONTROL', 4, TRUE);
define('PRINT_CONTROL', 8, TRUE);
class Schedule extends CI_Controller 
{
	
	var $id ; 
	
	function __construct()
	{		
		parent::__construct();
		//$this->load->library('access');
		//$this->load->model('schedule_model');
		//$this->load->library('render');
		//$this->load->helper('form');
		//$this->load->model('global_model');
		
		$this->load->library('render');
		$this->access->set_module('tool.schedule');	
		$this->access->user_page();
		$this->load->model('schedule_model');
		$this->load->library('render');
		$this->load->helper('form');
		$this->load->model('global_model');
	}// end of function
	
	function index()
	{	
		$this->render->add_view('app/schedule/list');
		$this->render->build('Agenda');
		$this->render->show('Agenda');
		
	}// end of function 
	
	function schedule_table_controller()
	{
	
		$data = $this->schedule_model->list_controller(get_datatables_control());
		send_json($data); 
	
	}//end of function 
	
	function form($id=0)
	{
		$data = array();
		
		if($id == 0)
		{
			$this->load->model('global_model');
			$period_id = $this->global_model->get_active_period();
			
			$data['row_id'] 					= '';
		
			$data['period_id']					= $period_id[0];
			$data['schedule_date'] 				= '';
			
		}
		else
		{
			$result = $this->schedule_model->read_id($id);
			if ($result) // cek dulu apakah data ditemukan 
			{
				$data = $result;
				
				$data['row_id'] = $id;		
				$data['schedule_date'] = date('d/m/Y', strtotime($data['schedule_date']));
				
			}
		}
		$this->load->helper('form');
		
		$this->render->add_form('app/schedule/form', $data);
		$this->render->build('Agenda');
		$this->render->add_view('app/schedule/transient_list');
		$this->render->build('Detail Agenda');
		$this->render->show('Agenda');			
	}// end of function 
	
	function form_action($is_delete = 0){
		
		$id = $this->input->post('row_id');
			
		if($is_delete){
			$is_proses_error = $this->schedule_model->delete($id);
			send_json_action($is_proses_error, "Data telah dihapus");
		}
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('i_period_id','Periode', 'trim|required');
		$this->form_validation->set_rules('i_date','Tanggal', 'trim|required|valid_date|sql_date');
		
		if($this->form_validation->run() == FALSE) send_json_validate();
		
		$data['schedule_date'] 					= $this->input->post('i_date');
		$data['period_id'] 					= $this->input->post('i_period_id');
	
		$list_time		= $this->input->post('transient_time');
		$list_name		= $this->input->post('transient_name');
		$list_description = $this->input->post('transient_description');
	
		if(!$list_time) send_json_error('Data agenda masih kosong');
	
		
		$items = array();
		if($list_time){
		foreach($list_time as $key => $value)
		{
			$items[] = array(				
				'si_time'  => $list_time[$key],
				'si_name' => $list_name[$key],
				'si_description' => $list_description[$key]
			);
			
		}
		}
		
		if(empty($id)){	
			
			$error = $this->schedule_model->create($data, $items);
			send_json_action($error, "Data telah ditambah", "Data gagal ditambah");
		}else{
			$error = $this->schedule_model->update($id, $data, $items);
			send_json_action($error, "Data telah direvisi", "Data gagal direvisi");
		}
		
	}
	
	function detail_list_loader($schedule_id=0)
	{
		if($schedule_id == 0)send_json(make_datatables_list(null)); 
				
		$data = $this->schedule_model->transient_loader($schedule_id);

		foreach($data as $key => $value) 
		{	
			
			$data[$key] = array(
				form_transient_pair('transient_time', $value['si_time']), 
				form_transient_pair('transient_name', $value['si_name']), 
				form_transient_pair('transient_description', $value['si_description'])
			);
		}
		
		send_json(make_datatables_list($data)); 
	}
	
	function detail_form($schedule_id = 0) // jika id tidak diisi maka dianggap create, else dianggap edit
	{
		$this->load->library('render');
		
		$data['schedule_id'] 	= $schedule_id;
		$index = $this->input->post('transient_index');
		
		if (strlen(trim($index)) == 0) {
					
			// TRANSIENT CREATE - isi form dengan nilai default / kosong
			$data['index']			= '';
			$data['si_time'] 		= "00:00";
			$data['si_name'] 		= '';
			$data['si_description']	= '';
		} else {

			// TRANSIENT EDIT - ambil data dari table yg dikirim dari client kemudian tampilkan
			// karena data yang dikirim adalah array, untuk mengambilnya menggunakan array_shift saja.
			$data['index'] 			= $index;
			$data['si_time'] 		= array_shift($this->input->post('transient_time'));
			$data['si_name']		= array_shift($this->input->post('transient_name'));
			$data['si_description'] = array_shift($this->input->post('transient_description'));
		}
		
		$this->render->add_form('app/schedule/transient_form', $data);
		$this->render->show_buffer();
	}
	
	function detail_form_action()
	{
		
		$this->load->library('form_validation');
		
		// selalu cek input dari client. ini kriterianya
		$this->form_validation->set_rules('i_time', 'Waktu', 'trim|required');
		$this->form_validation->set_rules('i_name', 'Agenda', 'trim|required'); // gunakan selalu trim di awal
				
		// cek data berdasarkan kriteria
		if ($this->form_validation->run() == FALSE) send_json_validate();
			
		// cek dulu apa warehouse / parent table nya ada
		$index 			= $this->input->post('i_index');
		$schedule_id	= $this->input->post('schedule_id');
		$si_time 		= $this->input->post('i_time');
		$si_name 		= $this->input->post('i_name');
		$si_description = $this->input->post('i_description');
	
		$data = array(
			form_transient_pair('transient_time', $si_time), 
			form_transient_pair('transient_name', $si_name),
			form_transient_pair('transient_description', $si_description)
		);
		
	
		send_json_transient($index, $data);
		
	}
	
	
	
	
}
// END General Journal Class

/* End of file gl.php */
/* Location: ./application/controllers/gl.php */
