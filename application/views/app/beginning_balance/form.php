<script type="text/javascript">	
$(function(){

	createForm({
		id 				: "#id_form_nya", 
		actionTarget	: "beginning_balance/form_action",
		backPage		: "beginning_balance",
		nextPage		: "beginning_balance",
		printTarget		: "beginning_balance/vendor_print/<?=$row_id?>"
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
	
	//updateAll(); 
});
</script>

<form class="form_class" id="id_form_nya">	
<div class="form_area">
<div class="form_area_frame">
	<table class="form_layout">
		<tr>
			<td >Periode</td>
			<td><input type="hidden" id="row_id" name="row_id" value="<?=$row_id?>" />
                <span class="lookup" id="lookup_period">
         <div class="iconic_base iconic_search com_popup"></div> <input type="hidden" name="i_period_id" class="com_id" value="<?=$period_id?>" />
         <input type="text" class="com_input" size="6" />
       </span>
            </td> 
		</tr>
		<tr>
			<td width="150">Keterangan</td>
			<td><textarea name="i_beginning_balance_desc" cols="30" rows="3" id="i_beginning_balance_desc"><?=$beginning_balance_desc?></textarea></td>
		</tr>
		
	</table>	
	</div>
	<div class="command_bar">
   
  <input type="button" id="submit" value="Simpan"/>
		<input type="button" id="enable" value="Edit"/>
		<input type="button" id="cancel" value="Kembali"/>
	</div>
</div>
<!-- table contact -->

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

