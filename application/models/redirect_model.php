<?php

class Redirect_model extends CI_Model
{
	function save($user_id, $url)
	{

		$data['redir_code'] = code_generator(16);
		$data['redir_url'] = $url;
		$data['redir_user_id'] = $user_id;
		
		//$this->db->trans_start();
		$this->db->insert('redirects', $data);
		//$this->db->trans_complete();
		
		//if (! $this->db->trans_status()) return NULL;
		
		return site_url('home/go/' . $data['redir_code']);
		
	} # function
	
	function get($code)
	{
		$this->db->select('u.user_login, r.*');
		$this->db->join('users u', 'r.redir_user_id = u.user_id');
		$query = $this->db->get_where('redirects r', array('redir_code' => trim($code)));
		if ($query->num_rows() == 0) return NULL;
		
		$row = $query->row_array();
		
		return $row;
		
	} # function
	
} # class


#
