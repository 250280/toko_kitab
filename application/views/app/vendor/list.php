<script type="text/javascript">	
$(function(){
	var otable = createTable({
		id 		: "#table",
		listSource 	: "vendor/table_controller",
		formTarget 	: "vendor/form",
		actionTarget: "vendor/form_action",
		column_id	: 0,
		
		filter_by 	: [ 
		{id : "vendor_code", label : "Kode"}, 
		{id : "vendor_name", label : "Nama"}, 
		{id : "vendor_email", label : "Email"},
		{id : "vendor_phone", label : "Telepon"}],
		"aLengthMenu"		: [[50, 100, 250, 500], [50, 100, 250, 500]],
	});
	otable.fnSetColumnVis(0, false, false);
});
</script>
<div id="example">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="table"> 
	<thead>
		<tr>
			<th>ID</th>
            <th>Kode</th>
            <th>Nama</th>
			<th>Email</th>
            <th>Telepon</th>
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
