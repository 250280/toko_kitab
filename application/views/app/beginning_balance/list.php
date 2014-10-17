<script type="text/javascript">	
$(function(){
	var otable = createTable({
		id 		: "#table",
		listSource 	: "beginning_balance/table_controller",
		formTarget 	: "beginning_balance/form",
		actionTarget: "beginning_balance/form_action",
		column_id	: 0,
		//filter_by 	: [ {id : "code", label : "Kode"}, {id : "name", label : "Nama"}, {id : "note", label : "Keterangan"}]
	});
	otable.fnSetColumnVis(0, false, false);
});
</script>
<div id="example">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="table"> 
	<thead>
		<tr>
			<th>ID</th>
			<th>Periode</th>
			<th>Jumlah Debit</th>
			<th>Jumlah Kredit</th>
		</tr> 
	</thead> 
	<tbody> 	
	</tbody>
</table>
<div id="panel" class="command_table">
<?php 
if($akses == 0){
?>
	<input type="button" id="add" value="Tambah"/>
<?php
}else{
?>
	<input type="button" id="edit" value="Lihat Data"/>
<?php
}
?>
	<input type="button" id="refresh" value="Refresh"/>
</div>
<div id="editor"></div>
</div>
