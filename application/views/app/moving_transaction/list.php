<script type="text/javascript">	
$(function(){
	var otable = createTable({
		id 		: "#table",
		listSource 	: "moving_transaction/table_controller",
		formTarget 	: "moving_transaction/form",
		actionTarget: "moving_transaction/form_action",
		column_id	: 0,
		filter_by 	: [ {id : "code", label : "Kode"}, {id : "name", label : "Cabang"}]
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
			<th>Kode</th>
			<th>Dari Cabang</th>
            <th>Ke Cabang</th>
		</tr> 
	</thead> 
	<tbody> 	
	</tbody>
</table>
<div id="panel" class="command_table">
	<input type="button" id="add" value="Tambah"/>
	<input type="button" id="edit" value="Lihat"/>
	<input type="button" id="delete" value="Hapus"/>
	<input type="button" id="refresh" value="Refresh"/>
</div>
<div id="editor"></div>
</div>
