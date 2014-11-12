<script type="text/javascript">	
$(function(){
	var otable = createTable({
		id 		: "#table",
		listSource 	: "purchase_report/table_controller",
		formTarget 	: "purchase_report/form",
		actionTarget: "purchase_report/form_action",
		column_id	: 0,
		filter_by 	: [ {id : "transaction_code", label : "Kode Transaksi"}, {id : "stand_name", label : "Cabang"}, {id : "transaction_type_name", label : "Jenis Transaksi"}]
	});
	otable.fnSetColumnVis(0, false, false);
});

</script>
<div id="example">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="table"> 
	<thead>
		<tr>
			<th>ID</th>
			<th>Tanggal</th>
			<th>Cabang</th>
			<th>Kode</th>
            <th>Jeni Transaksi</th>
            <th>Total</th>
		</tr> 
	</thead> 
	<tbody> 	
	</tbody>
</table>
<div id="panel" class="command_table">
	
    <input type="button" value="Print" onclick="location.href='<?=site_url('purchase_report/report_date')?>'" />
	<input type="button" id="edit" value="Lihat Data"/>
	<input type="button" id="refresh" value="Refresh"/>
</div>
<div id="editor"></div>
</div>
