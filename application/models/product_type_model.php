<?php
class Product_type_model extends CI_Model 
{
	function __construct()
	{
		//parent::Model();
		//$this->sek_id = $this->access->sek_id;
		
	}
	
	function list_loader()
	{		
		// buat array kosong
		$result = array(); 
		
		$this->db->select('*', 1); // ambil seluruh dat
		$this->db->order_by('product_type_id asc'); // urutkan data dari yang terbaru		
		$query = $this->db->get('product_types'); // karena menggunakan from, maka get tidak diberi parameter
		//query();
		foreach($query->result_array() as $row)
		{
			$row = format_html($row); // render dulu dunk!
			
			$result[] = array(
				$row['product_type_id'],
				$row['product_type_name'],
				$row['product_type_description']
				); 
		}
		return $result;
	}
	function read_id($id)
	{
		$this->db->select('*', 1); // ambil seluruh data
		$this->db->where('product_type_id', $id);
		
		$query = $this->db->get('product_types', 1); // parameter limit harus 1
		
		$result = null; // inisialisasi variabel. biasakanlah, untuk mencegah warning dari php.
		foreach($query->result_array() as $row)	$result = format_html($row); // render dulu dunk!
		return $result;
	}
	function create($data)
	{
		$this->db->trans_start();
		$this->db->insert('product_types', $data);

		$id = $this->db->insert_id();
		$this->access->log_insert($id, "Tipe Produk [$data[product_type_name]]");
		
		$this->db->trans_complete();

		return $this->db->trans_status();
	}
	
	function update($id, $data)
	{
		$this->db->trans_start();
		$this->db->where('product_type_id', $id); // data yg mana yang akan di update
		$this->db->update('product_types', $data);
		$this->access->log_update($id, "Tipe Produk [$data[product_type_name]]");
		
		$this->db->trans_complete();

		return $this->db->trans_status();
	}
	function delete($id)
	{
		$this->db->trans_start();
		$this->db->where('product_type_id', $id); // data yg mana yang akan di delete
		$this->db->delete('product_types');
		$this->access->log_delete($id, 'Tipe Produk');
		$this->db->trans_complete();

		return $this->db->trans_status();
	}
}
#
