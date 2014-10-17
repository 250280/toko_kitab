<script type="text/javascript">	
$(function(){

	createForm({
		id 		: "#id_form_nya", 
		actionTarget	: "closing/action",
		backPage	: "<?=$close_mode?'closing/form_closing':'closing'?>",
		nextPage	: "<?=$close_mode?'closing/form_closing':'closing'?>"
	});
	
	createLookUp({
		table_id		: "#lookup_table_period",
		table_width		: 400,
		listSource 		: "lookup/period_table_control",
		dataSource		: "lookup/period_lookup_id",
		column_id 		: 0,
		component_id	: "#lookup_period",
		filter_by		: [{id : "p1", label : "Kode"},{id : "p2", label : "Nama"}]
	});
	
	createLookUp({
		table_id		: "#lookup_table_period2",
		table_width		: 400,
		listSource 		: "lookup/period_table_control",
		dataSource		: "lookup/period_lookup_id",
		column_id 		: 0,
		component_id	: "#lookup_period2",
		filter_by		: [{id : "p1", label : "Kode"},{id : "p2", label : "Nama"}]
	});
});
</script>

<form class="form_class" id="id_form_nya">	
<div class="form_area">
<div class="form_area_frame">
<table width="482" class="form_layout">
	<tr>
		  <td width="150">Periode</td>
		  <td>                <span class="lookup" id="lookup_period">
         <div class="iconic_base iconic_search com_popup"></div> <input type="hidden" name="i_period" class="com_id" value="<?=$period_id?>" />
         <input type="text" class="com_input" size="6" />
       </span>
	     </td>
	</tr>
	<tr>
		<td>Periode Berikutnya</td>
		<td>
                <span class="lookup" id="lookup_period2">
         <div class="iconic_base iconic_search com_popup"></div> <input type="hidden" name="n_period" class="com_id" value="<?=$period_id?>" />
         <input type="text" class="com_input" size="6" />
       </span></td>
	</tr>	
	<tr>
		<td>
			<input type="hidden" name="i_closed" value="<?=$close_mode?>" />
			 <input type="hidden" id="row_id" name="row_id" value="" />
		</td>
	</tr>   
</table>
</div>
<div class="command_bar">
	<input type="button" id="submit" value="Proses"/>
	<input type="button" id="refresh" value="Refresh"/>
</div>
 </div>
</form>


<div id="">
	<table id="lookup_table_period" cellpadding="0" cellspacing="0" border="0" class="display" > 
		<thead>
			<tr>
			<th>ID</th>
				<th>Periode</th>
				<th>Status</th>
			</tr> 
		</thead> 
		<tbody> 	
		</tbody>
	</table>
	<div id="panel">
		<input type="button" id="choose" value="Pilih Data"/>
		<input type="button" id="refresh" value="Refresh"/>
		<input type="button" id="cancel" value="Cancel" />
	</div>	
</div>




<div id="">
	<table id="lookup_table_period2" cellpadding="0" cellspacing="0" border="0" class="display" > 
		<thead>
			<tr>
			<th>ID</th>
				<th>Periode</th>
				<th>Status</th>
			</tr> 
		</thead> 
		<tbody> 	
		</tbody>
	</table>
	<div id="panel">
		<input type="button" id="choose" value="Pilih Data"/>
		<input type="button" id="refresh" value="Refresh"/>
		<input type="button" id="cancel" value="Cancel" />
	</div>	
</div>


