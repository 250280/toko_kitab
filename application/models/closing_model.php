<?php

class Closing_model extends CI_Model
{
	var $market_id;
	var $errors = "";
	
	function __construct()
	{
		parent::__construct();
	}
	
	function get_week($now){
		$sql = "SELECT EXTRACT(WEEK FROM TIMESTAMP '$now') as minggu";
		$query = $this->db->query($sql);
		$result = null;
		foreach($query->result_array() as $row)	$result = format_html($row);
		return $result['minggu'];
	}
	
	function is_closed($period_id)
	{		
		$this->db->select('period_id', 1);
		$this->db->where('period_id', $period_id);
		$this->db->where('period_closed', 'TRUE');
		$query = $this->db->get('periods',1); // parameter limit harus 1
		if ($query->num_rows() > 0)
		{
			return TRUE;
		}
		return FALSE;
	}
	
	function execute($period_id, $next_period, $close, $desc, $week)
	{		
		## EXECUTE CLOSING CODES
		
		$this->isi_saldo_kosong($period_id);
		$this->isi_trial_balance($period_id,"trial_balance");
		
		//$this->isi_trial_balance($period_id,"trial_balance2");
		//$this->isi_trial_balance_mingguan($period_id,"trial_balance3", $week);
		$this->hitung_laba_berjalan($period_id);		
		$this->isi_saldo_berikutnya($period_id,$next_period);
		$this->create_beginning_balance($period_id, $next_period);
		
		$this->access->log_insert(1, 'Closing');
	}	
	
	//************************************* fungsi-fungsi closing **************************
	function isi_saldo_kosong($period_id)
	{
		$sql = "SELECT DISTINCT market_id,coa_id,period_id,balance_debit,balance_kredit
					FROM journals_sl
						LEFT JOIN (SELECT market_id,coa_id,period_id,balance_debit,balance_kredit 
							FROM balances WHERE period_id=$period_id) AS balances 
						USING(market_id,coa_id)
					ORDER BY market_id,coa_id";
		$query = $this->db->query($sql); 
		//query();
		
		foreach($query->result_array() as $row)
		{
			if($row['period_id'])continue;
			$data_balances = array('market_id'=>$row['market_id'], 'coa_id'=>$row['coa_id'],'balance_date'=>date('Y-m-d') , 'period_id'=>$period_id, 'balance_debit'=>0, 'balance_kredit'=>0);
			$this->db->insert('balances', $data_balances);
			//debug ('insert'. $this->db->last_query());
		}
	}
	
	function create_beginning_balance($prev_period, $next_period)
	{
		$this->db->where('period_id', $next_period);
		$this->db->delete('beginning_balances');
		
		$sql = "select sum(balance_debit) as total_debit, sum(balance_kredit) as total_kredit from balances
				where period_id = '$next_period'";
		$query = $this->db->query($sql); 
		//query();
		
		foreach($query->result_array() as $row)
		{
			$data_balances['period_id'] = $next_period;
			$data_balances['beginning_balance_debit'] = $row['total_debit'];
			$data_balances['beginning_balance_credit'] = $row['total_kredit'];
			$this->db->insert('beginning_balances', $data_balances);
			//debug ('insert'. $this->db->last_query());
		}
	}
	
	
	function isi_trial_balance($period_id,$tb_name)
	{
		$this->db->where('period_id', $period_id);
		$this->db->delete($tb_name);
		$sql = "
		
				INSERT INTO ".$tb_name." (period_id,coa_id,market_id,b_amount_debet,b_amount_kredit,t_amount_debet,t_amount_kredit,e_amount_debet,e_amount_kredit)
				SELECT period_id,coa_id,market_id,SUM(b_debet) AS b_debet,SUM(b_kredit) AS b_kredit,SUM(t_debet) AS t_debet, SUM(t_kredit) AS t_kredit,
					CASE WHEN SUM(b_debet)-SUM(b_kredit)+SUM(t_debet)-SUM(t_kredit)>0 THEN SUM(b_debet)-SUM(b_kredit)+SUM(t_debet)-SUM(t_kredit) ELSE 0 END AS e_debet,
					CASE WHEN SUM(b_debet)-SUM(b_kredit)+SUM(t_debet)-SUM(t_kredit)<0 THEN (SUM(b_debet)-SUM(b_kredit)+SUM(t_debet)-SUM(t_kredit))*-1 ELSE 0 END AS e_kredit
				FROM(

					SELECT period_id,coa_id,market_id,SUM(balance_debit) AS b_debet,SUM(balance_kredit) AS b_kredit,0 AS t_debet,0 AS t_kredit
					FROM balances
					WHERE period_id=".$period_id."
					GROUP BY period_id,coa_id,market_id
					UNION
					SELECT period_id,coa_id,market_id,0 AS b_debet,0 AS b_kredit,SUM(journal_debit) AS t_debet, SUM(journal_credit) AS t_kredit
					FROM journals_sl
						JOIN transactions_sl USING(transaction_id)
					WHERE period_id=".$period_id."
					GROUP BY period_id,coa_id,market_id
				) AS tb
				GROUP BY period_id,coa_id,market_id
				ORDER BY coa_id;";
		//echo $sql."<br><br>\n################\n";
		$query = $this->db->query($sql);
		//query();
	}
	
	function isi_trial_balance_mingguan($period_id,$tb_name, $week)
	{
		$sql = "DELETE FROM ".$tb_name." WHERE period_id=".$period_id." AND week_id =".$week."; 
		
				INSERT INTO ".$tb_name." (period_id, week_id,coa_id,market_id,b_amount_debet,b_amount_kredit,t_amount_debet,t_amount_kredit,e_amount_debet,e_amount_kredit)
		
				SELECT period_id, ".$week.",coa_id,market_id,SUM(b_debet) AS b_debet,SUM(b_kredit) AS b_kredit,SUM(t_debet) AS t_debet, SUM(t_kredit) AS t_kredit,
					CASE WHEN SUM(b_debet)-SUM(b_kredit)+SUM(t_debet)-SUM(t_kredit)>0 THEN SUM(b_debet)-SUM(b_kredit)+SUM(t_debet)-SUM(t_kredit) ELSE 0 END AS e_debet,
					CASE WHEN SUM(b_debet)-SUM(b_kredit)+SUM(t_debet)-SUM(t_kredit)<0 THEN (SUM(b_debet)-SUM(b_kredit)+SUM(t_debet)-SUM(t_kredit))*-1 ELSE 0 END AS e_kredit
				FROM(

					SELECT period_id,coa_id,market_id,SUM(balance_debit) AS b_debet,SUM(balance_kredit) AS b_kredit,0 AS t_debet,0 AS t_kredit
					FROM balances
					WHERE period_id=".$period_id."
					GROUP BY period_id,coa_id,market_id
					UNION
					SELECT period_id,coa_id,market_id,0 AS b_debet,0 AS b_kredit,SUM(journal_debit) AS t_debet, SUM(journal_credit) AS t_kredit
					FROM journals_sl
						JOIN transactions_sl USING(transaction_id)
					WHERE period_id=".$period_id."
					GROUP BY period_id,coa_id,market_id
				) AS tb
				GROUP BY period_id,coa_id,market_id
				ORDER BY coa_id";
		//echo $sql."<br><br>\n################\n";
		$this->db->query($sql);
	}
	
	function hitung_laba_berjalan($period_id)
	{
		$ci=&get_instance();
		
		$sql = "SELECT coa_id FROM gl_configs WHERE gl_config_id IN(1,2) ORDER BY gl_config_id";
		$query = $this->db->query($sql);
		$laba_berjalan = array();
		foreach($query->result_array() as $row)
		{
			$laba_berjalan[] = $row['coa_id'];
		}
		
		$sql = "SELECT DISTINCT market_id,COALESCE(laba_bulan,0) AS b_laba_bulan,COALESCE(laba_tahun,0) AS b_laba_tahun
			FROM trial_balance tb1
				LEFT JOIN (SELECT market_id, 
						SUM(CASE WHEN coa_id=".$laba_berjalan[0]." THEN balance_kredit-balance_debit END) AS laba_bulan,
						SUM(CASE WHEN coa_id=".$laba_berjalan[1]." THEN balance_kredit-balance_debit END) AS laba_tahun
					FROM balances
					WHERE period_id=$period_id AND coa_id IN (".$laba_berjalan[0].",".$laba_berjalan[1].")
					GROUP BY market_id) AS tb2
				USING(market_id)
			WHERE period_id=$period_id";
		$query = $this->db->query($sql); 
		
		foreach($query->result_array() as $row)
		{
			//hitung laba bulan berjalan
			$sql = "SELECT SUM(t_amount_kredit-t_amount_debet) as lr_total
					FROM trial_balance
						JOIN coas USING(coa_id)
					WHERE period_id=$period_id AND SUBSTR(coa_hierarchy,1,1)::Integer>=4 
						AND market_id=".$row['market_id'];
			$lr_total=0;
			$query2 = $this->db->query($sql); 
			foreach($query2->result_array() as $row1)$laba_bulan = $row1['lr_total'];
			
			//Masukkan laba bulan berjalan ke trial_balance2
			$data_trial = array('period_id'=>$period_id, 'coa_id'=>$laba_berjalan[0],'market_id'=>$row['market_id'],'b_amount_debet'=>0,'b_amount_kredit'=>0);		
			/**************************************************************************************************************************/
			if($row['b_laba_bulan']<0){
				$data_trial['t_amount_debet'] = 0;
				$data_trial['t_amount_kredit'] = -$row['b_laba_bulan'];
			}else {
				$data_trial['t_amount_debet'] = $row['b_laba_bulan'];
				$data_trial['t_amount_kredit'] = 0;
			}
				
			if($laba_bulan<0){
				$data_trial['t_amount_debet'] += -$laba_bulan;
				$data_trial['t_amount_kredit'] += 0;
			}else {
				$data_trial['t_amount_debet'] += 0;
				$data_trial['t_amount_kredit'] += $laba_bulan;
			}
			
				$data_trial['e_amount_debet'] = $data_trial['t_amount_debet'];
				$data_trial['e_amount_kredit'] = $data_trial['t_amount_kredit'];
			/**************************************************************************************************************************/
			$this->db->insert('trial_balance2', $data_trial);
			
			
			//hitung laba tahun berjalan
			$sql = "SELECT SUM(b_amount_kredit-b_amount_debet) as t_laba_tahun
					FROM trial_balance
						JOIN coas USING(coa_id)
					WHERE period_id=$period_id AND SUBSTR(coa_hierarchy,1,1)::Integer>=4 
						AND market_id=".$row['market_id'];
			$query3 = $this->db->query($sql); 
			$t_laba_tahun = 0;
			foreach($query3->result_array() as $row2)$t_laba_tahun = $row2['t_laba_tahun'];

			//Masukkan laba tahun berjalan ke trial_balance2
			$data_trial2 = array('period_id'=>$period_id, 'coa_id'=>$laba_berjalan[1],'market_id'=>$row['market_id'],'b_amount_debet'=>0,'b_amount_kredit'=>0);	
			/**************************************************************************************************************************/
			if($row['b_laba_bulan']<0){
				$data_trial2['t_amount_debet'] = -$row['b_laba_bulan'];
				$data_trial2['t_amount_kredit'] = 0;
			}else {
				$data_trial2['t_amount_debet'] = 0;
				$data_trial2['t_amount_kredit'] = $row['b_laba_bulan'];
			}
			
			if($t_laba_tahun<0){
				$data_trial2['t_amount_debet'] += -$t_laba_tahun;
				$data_trial2['t_amount_kredit'] += 0;
			}else {
				$data_trial2['t_amount_debet'] += 0;
				$data_trial2['t_amount_kredit'] += $t_laba_tahun;
			}
			
				$data_trial2['e_amount_debet'] = $data_trial2['t_amount_debet'] ;
				$data_trial2['e_amount_kredit'] = $data_trial2['t_amount_kredit'] ;
			/**************************************************************************************************************************/
			$this->db->insert('trial_balance2', $data_trial2);
		}
	}
	
	function isi_saldo_berikutnya($period_id,$next_period)
	{
		$sql = "DELETE FROM balances WHERE period_id=".$next_period;
		$this->db->query($sql); 
		
		$sql = "INSERT INTO balances (coa_id,market_id,period_id, balance_debit, balance_kredit) 
			SELECT coa_id,market_id,".$next_period." as period_id,
				COALESCE(e_amount_debet,0) AS balance_debit,
				COALESCE(e_amount_kredit,0) AS balance_kredit 
			FROM trial_balance
				JOIN periods using(period_id)
				JOIN coas using(coa_id)
			WHERE coa_type=0 AND period_id=".$period_id." order by coa_id";
				
		$this->db->query($sql);
		debug ($this->db->last_query()); 
	}
	function get_period_new(){
		$this->db->select('*', 1); // ambil seluruh data
		$this->db->where('period_closed', 'false');
		$this->db->order_by('period_id asc');
		$query = $this->db->get('periods', 1); // parameter limit harus 1
		$result = null; // inisialisasi variabel. biasakanlah, untuk mencegah warning dari php.
		foreach($query->result_array() as $row)	$result = format_html($row); // render dulu dunk!
		return $result['period_id']; 
	}
}




