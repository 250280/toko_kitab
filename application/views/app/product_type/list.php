<script block="text/javascript">	
$(function(){
	var otable = createTable({
		id 				: "#table",
		listSource 		: "product_type/list_loader",
		formSource 		: "product_type/form",
		actionTarget	: "product_type/form_action",
		column_id		: 0
	});
	otable.fnSetColumnVis(0, false, false);
});
</script>
<div id="example">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="table"> 
	<thead>
		<tr>
			<th width="10%">ID</th>
			<th width="20%">Nama</th>
			<th width="20%">Keterangan</th>
		</tr> 
	</thead> 
	<tbody> 	
	</tbody>
</table>
<div id="panel" class="command_table">
	<input type="button" id="add" value="Tambah"/>
	<input type="button" id="edit" value="Revisi"/>
	<input type="button" id="delete" value="Hapus"/>
	<input type="button" id="refresh" value="Refresh"/>
</div>
<div id="editor"></div>
</div>
