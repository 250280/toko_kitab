<?php 

function create_permit_item($level, $name, $module_code, $checked = array()) {

	if (!$module_code) $name = "<b>$name</b>";
	
	$checked_item = array('', '', '', '');
	
	$crud = array_key_exists($module_code ? $module_code : '#', $checked) ? $checked[$module_code] : '';
	
	if (strpos($crud, 'c') !== FALSE) $checked_item[0] = 'checked';
	if (strpos($crud, 'r') !== FALSE) $checked_item[1] = 'checked';
	if (strpos($crud, 'u') !== FALSE) $checked_item[2] = 'checked';
	if (strpos($crud, 'd') !== FALSE) $checked_item[3] = 'checked';
	
?>		
	<tr>
		<td><div  style="margin-left:<?php $width = $level * 20; echo $width."px; "?>"><?php echo $name; ?></div></td>
		<?php if ($module_code) { ?>
			<td>&nbsp;
				<input type="button" class="all_checked_button checked_full" value="F" title='Full' />
				<input type="button" class="all_checked_button checked_limited" value="N" title='Create Read'/>
				<input type="button" class="all_checked_button checked_update" value="U" title='Update Read' />&nbsp;&nbsp;
				<input type="button" class="all_checked_button checked_clear" value="C" title='Clear / Reset'/>
				<input type="button" class="all_checked_button checked_toggle" value="T" title='Checked Toggle' />
			</td>
			<td><input type="checkbox" name="ip_c[]" class="limited" value="<?php echo $module_code; ?>" <?php echo $checked_item[0]; ?> /></td>
			<td><input type="checkbox" name="ip_r[]" class="limited update"  value="<?php echo $module_code; ?>" <?php echo $checked_item[1]; ?> /></td>
			<td><input type="checkbox" name="ip_u[]" class="update" value="<?php echo $module_code; ?>" <?php echo $checked_item[2]; ?> /></td>
			<td><input type="checkbox" name="ip_d[]" class="" value="<?php echo $module_code; ?>" <?php echo $checked_item[3]; ?> /></td>
		<?php } else { ?>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		<?php } ?>
	</tr>
<?php } ?>
<script type="text/javascript">
$(function() {
	
	$('.tree_list tbody tr:odd').addClass('odd');
	$('.tree_list tbody tr:even').addClass('even');
	
	$('.checked_limited').click(function() {
		$(this).parent().parent().find('input[type="checkbox"]').each(function() {
			this.checked = false;
		});
		$(this).parent().parent().find('input[type="checkbox"].limited').each(function() {
			this.checked = true;
		});
	});
	
	$('.checked_update').click(function() {
		$(this).parent().parent().find('input[type="checkbox"]').each(function() {
			this.checked = false;
		});
		$(this).parent().parent().find('input[type="checkbox"].update').each(function() {
			this.checked = true;
		});
	});
	
	$('.checked_full').click(function() {
		$(this).parent().parent().find('input[type="checkbox"]').each(function() {
			this.checked = true;
		});
	});
	
	$('.checked_clear').click(function() {
		$(this).parent().parent().find('input[type="checkbox"]').each(function() {
			this.checked = false;
		});
	});
	
	$('.checked_toggle').click(function() {
		$(this).parent().parent().find('input[type="checkbox"]').each(function() {
			this.checked = !this.checked;
		});
	});
	
	$('#btReset').click(function() {
		if (!window.confirm('Anda ingin me-reset form ini ke kondisi awal ?')) return;
		$('form').each(function() { this.reset(); });
	});
	
	$('#btSubmit').click(function() {
	
		var data = $('form input').serialize();

		var submitHandler = function(feedback) {
			if (feedback == null) return;
			if (feedback.type == 'success') {
				alert('Simpan Berhasil');
				window.location.href = '<?php echo $next_url; ?>';
			}
		}
		
		$.ajax({
			url 	: site_url + 'permit/action',
			dataType: "json",
			data	: data,
			type	: "POST",
			success	: submitHandler			
		});
		
	});
	
	$('#btKembali').click(function() {
		window.location.href = '<?php echo $next_url; ?>';
	});
 
});
</script>
<div class="tree_list">
<div class="form_area_frame">
<form>
<input type="hidden" name="ip_group_id" value="<?php echo $group_id; ?>" />
<table width="100%" cellspacing="0" cellpadding="4">
<thead>
	<tr>
		<th width="540">Modul</th>
		<th width="304">All</th>
		<th width="15">C</th>
		<th width="15">R</th>
		<th width="15">U</th>
		<th width="15">D</th>
	</tr>
</thead>
<tbody>
	<?php 
				/* ------ List Setup menu dashboard ------ */
		create_permit_item(1, 'Dashboard', 'master.dashboard', $checked); 
		
				/* ------ List Setup menu master ------ */
		create_permit_item(1, 'Data Master', 'main.master', $checked); 
			create_permit_item(2, 'Pelanggan', 'master.customer', $checked);
			create_permit_item(2, 'Vendor', 'master.vendor', $checked);
			create_permit_item(2, 'Produk', 'master.product', $checked);
				create_permit_item(3, 'Kategori Produk', 'master.product_category', $checked);
				create_permit_item(3, 'Tipe Produk', 'master.product_type', $checked);
			//create_permit_item(2, 'Souvenir', 'master.souvenir', $checked);
			create_permit_item(2, 'Cabang', 'master.stand', $checked);
			//create_permit_item(2, 'Salesman', 'master.salesman', $checked);
		
		
		/* ------ List Setup menu Akuntansi ------ */
		create_permit_item(1, 'Akuntansi', 'main.accounting', $checked); 
			create_permit_item(2, 'Jurnal', 'accounting.gl', $checked);
			create_permit_item(2, 'Kas dan Bank', 'accounting.cash_bank', $checked);
			create_permit_item(2, 'Saldo Awal', 'accounting.beginning_balance', $checked);
			create_permit_item(2, 'Akun', 'accounting.coa', $checked);
			create_permit_item(2, 'Omset', 'accounting.flow_transaction', $checked);
			create_permit_item(2, 'Laba Kotor', 'accounting.profit', $checked);
			
		/* ------ List Setup menu Penjualan ------ */
		create_permit_item(1, 'Penjualan', 'main.sales_transaction', $checked); 
			create_permit_item(2, 'Penjualan User', 'transaction.normal_sales_transaction', $checked);
			//create_permit_item(2, 'Penjualan Freeline', 'transaction.freeline_sales_transaction', $checked);
			//create_permit_item(2, 'Penjualan Distributor', 'transaction.distributor_sales_transaction', $checked);
			//create_permit_item(2, 'Penjualan Counter', 'transaction.counter_sales_transaction', $checked);
			//create_permit_item(2, 'Penjualan Online', 'transaction.online_sales_transaction', $checked);
			//create_permit_item(2, 'Retur Penjualan', 'transaction.retur_sales_transaction', $checked);
			
		/* ------ List Setup menu Pembelian ------ */
		create_permit_item(1, 'Pembelian', 'main.purchase', $checked); 
			create_permit_item(2, 'Pembelian', 'transaction.purchase', $checked);
			create_permit_item(2, 'Retur Pembelian', 'transaction.retur_purchase', $checked);
		
		/* ------ List Setup menu Persediaan ------ */
		create_permit_item(1, 'Persediaan', 'main.stock', $checked); 
			create_permit_item(2, 'Harga Realtime', 'stock.realtime_price', $checked);
			create_permit_item(2, 'Stok Realtime', 'stock.realtime_stock', $checked);
			
		create_permit_item(1, 'Pindah Gudang', 'transaction.moving_transaction', $checked);
		
		 /* ------ List Setup menu pengaturan ------ */
		create_permit_item(1, 'Pengaturan', 'main.tool', $checked); 
			create_permit_item(2, 'User', 'tool.user', $checked);
			create_permit_item(2, 'Hak Akses', 'tool.permit', $checked);
			create_permit_item(2, 'Agenda', 'tool.schedule', $checked);
			create_permit_item(2, 'Periode', 'tool.periode', $checked);
		
		/* ------ List Setup menu pegawai ------ */
		create_permit_item(1, 'Pegawai', 'main.employee', $checked); 
			create_permit_item(2, 'Pegawai', 'employee.employee', $checked);
			create_permit_item(2, 'Jabatan', 'employee.employee_position', $checked);
			
		/* ------ List Setup menu laporan ------ */
		create_permit_item(1, 'Laporan', 'main.report', $checked); 
			create_permit_item(2, 'Top 10 Pelanggan', 'report.top_customer', $checked);
			create_permit_item(2, 'Top 10 Produk', 'report.top_product', $checked);
			create_permit_item(2, 'Laba / Rugi', 'report.laba_rugi', $checked);
			create_permit_item(2, 'Neraca', 'report.neraca', $checked);
			create_permit_item(2, 'Pembelian', 'report.purchase_report', $checked);
			create_permit_item(2, 'Penjualan', 'report.sales_transaction_report', $checked);
		
	?>
</tbody>
<tfoot>
	<tr>
		<td colspan="10" style="padding: 5px; " class="command_table">
			<div id="console"></div>
			<input type="button" id="btSubmit" value="Simpan" />
			<input type="button" id="btReset" value="Reset" />
			<input type="button" id="btKembali" value="Kembali" />
		</td>
	</tr>
</tfoot>
</table>
</form>
</div>
</div>
