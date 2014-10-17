<script type="text/javascript">	
$(function(){
	createForm({
		id 				: "#id_form_nya", 
		actionTarget	: "schedule/form_action",
		backPage		: "schedule",
		nextPage		: "schedule"
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
	
	createDatePicker();
	//updateAll();
	
	
});
</script>

<form class="form_class" id="id_form_nya">	
<div class="form_area">
<div class="form_area_frame">
<!-- panel input -->
<table class="form_layout">

	
	<tr>
	  <td req="req">Periode
        <span class="lookup" id="lookup_period">
         <div class="iconic_base iconic_search com_popup"></div> <input type="hidden" name="i_period_id" class="com_id" value="<?=$period_id?>" />
         <input type="text" class="com_input" size="6" />
       </span></td>
    </tr>
	<tr>
		<td width="150" req="req">Tanggal
	    <input type="hidden" id="row_id" name="row_id" value="<?=$row_id?>" />
	    <input type="text" value="<?=$schedule_date?>"  name="i_date" class="date_input" size="11" /></td>
	</tr>
</table>
</div>

<div class="command_bar">
<input type="button" id="submit" value="Simpan"/>
		
	
	<input type="button" id="enable" value="Edit"/>
			
	<input type="button" id="cancel" value="Kembali" /> 
		
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

