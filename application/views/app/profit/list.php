<script type="text/javascript">	
$(function(){
	var otable = createTable({
		id 		: "#table",
		listSource 	: "profit/table_controller",
		formTarget 	: "profit/form",
		actionTarget: "profit/form_action",
		column_id	: 0,
		filter_by 	: [ {id : "periode", label : "Periode"}]
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
			<th>Jumlah </th>
		</tr> 
	</thead> 
	<tbody> 	
	</tbody>
</table>
<div id="panel" class="command_table">


	<input type="button" id="edit" value="Proses"/>

	<input type="button" id="refresh" value="Refresh"/>
</div>
<div id="editor"></div>
</div>
