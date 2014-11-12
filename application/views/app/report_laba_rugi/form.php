<script type="text/javascript">	
$(function(){
	createForm({
		id 				: "#id_form_nya", 
		actionTarget	: "report_laba_rugi/action_report",
		backPage		: "report_laba_rugi",
		nextPage		: "report_laba_rugi/create_report"
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
});

</script>

<form id="id_form_nya">
<div class="form_area">
<div class="form_area_frame">
<table class="form_layout">
	<tr>
			<td >Periode
                <span class="lookup" id="lookup_period">
         <div class="iconic_base iconic_search com_popup"></div> <input type="hidden" name="i_period_id" class="com_id" value="<?=$period_id?>" />
         <input type="text" class="com_input" size="6" />
       </span> <input type="hidden" name="row_id" value="<?=$row_id?>" />
            </td> 
		</tr>
  

</table>
</div>
<div class="command_bar">
	<input type="button" id="submit" value="Cetak"/>
	<input type="button" id="enable" value="Edit"/>
	<input type="button" id="cancel" value="Batal" /> 
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