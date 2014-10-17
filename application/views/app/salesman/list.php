<script type="text/javascript">	
$(function(){
	var otable = createTable({
		id 		: "#table",
		listSource 	: "salesman/table_controller",
		formTarget 	: "salesman/form",
		actionTarget: "salesman/form_action",
		column_id	: 0,
		
		filter_by 	: [ 
		{id : "salesman_code", label : "Kode"}, 
		{id : "salesman_name", label : "Nama"}, 
		{id : "salesman_email", label : "Email"},
		{id : "salesman_phone", label : "Telepon"},
		{id : "salesman_address", label : "Alamat"}],
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
            <th>Alamat</th>
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
