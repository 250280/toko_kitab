<script type="text/javascript">	
var oTableRoom;
$(function(){
	createForm({
		id 				: "#id_form_nya", 
		actionTarget	: "coa/coa_form_action",
		backPage		: "coa/coa",
		nextPage		: "coa/coa"
	});
	
	createLookUp({
		table_id		: "#lookup_table_coa_account_type",
		table_width		: 400,
		listSource 		: "lookup/coa_account_type_table_control",
		dataSource		: "lookup/coa_account_type_lookup_id",
		column_id 		: 0,
		component_id	: "#lookup_coa_account_type",
		filter_by		: [{id : "p1", label : "Kode Akun"}, {id : "p2", label : "Nama"}],
		onSelect		: load_sub_account
	});		
	
	function load_sub_account(){
							
		var id 	= $('input[name="i_coa_account_type"]').val();
	
		if(id == ""){
			return;
		}
		var data ='id='+id; 
		
		
		
			$.ajax({
				type: 'POST',
				url: '<?=site_url('coa/get_data_coa')?>',
				data: data,
				dataType: 'json',
				success: function(data){		
				
					$('input[name="i_coa_group"]').val(data.content['coa_group']);
					$('input[name="i_coa_hierarchy"]').val(data.content['coa_hierarchy']);
					$('input[name="i_coa_hierarchy2"]').val(data.content['coa_code']);
				}
				
			});
		
	}
	
	//updateAll(); // tambahkan ini disetiap form
});</script>
<form id="id_form_nya">
<!-- panel loader status -->
<div class="form_area">
<div class="form_area_frame">
<!-- panel input -->
<table class="form_layout">
  <tr>
    <td width="10%">Account Type</td>
    <td><input type="hidden" id="row_id" name="row_id" value="<?=$row_id?>" />
      <input type="hidden" name="i_coa_hierarchy" value='<?=$coa_hierarchy?>' />
      <input type="hidden" name="i_coa_id" class="com_id" value="<?=$coa_id?>" />
      <input type="hidden" name="i_coa_level" value='<?=$coa_level?>' />
      <input type="hidden" name="i_coa_parent" value='<?=$parent_coa_id?>' />
      <input type="hidden" name="i_coa_group" value='<?=$coa_group?>' />
      <span class="lookup" id="lookup_coa_account_type">
		  <input type="hidden" name="i_coa_account_type" class="com_id" value="<?=$parent_coa_id?>" />
				<input type="hidden" id="i_coa_level" size="1" value="<?=$coa_level?>" readonly="readonly"/>
                <div class="iconic_base iconic_search com_popup"></div>
				
				<input type="text" class="com_input" size="6" />
				
            </span>
      </td>
  </tr>
  <tr>
    <td width="150">No Account</td>
    <td>
      <input name="i_coa_hierarchy2" type="text" id="i_coa_hierarchy2" value="" size="10" maxlength="" style="width:60% !important" />
      <input name="i_coa_hierarchy" type="text" id="i_coa_hierarchy" value="<?=$coa_code?>" size="20" maxlength="<?=$coa_hierarchy?>" readonly="readonly" style="width:20% !important;" /></td>
  </tr>
  <tr>
    <td width="150">Account Name</td>
    <td><input name="i_coa_name" type="text" id="i_coa_name" value="<?=$coa_name?>" size="50" maxlength="50" />
    </td>
  </tr>
</table>
</div>
<!-- panel control -->
<div class="command_bar">
		<input type="button" id="submit" value="Simpan"/>
		<input type="button" id="enable" value="Edit"/>
		<input type="button" id="delete" value="Hapus"/>
		<input type="button" id="cancel" value="Batal"/>
	</div>
</div>
</form>

<div id="">
	<table id="lookup_table_coa_account_type" cellpadding="0" cellspacing="0" border="0" class="display" > 
		<thead>
			<tr>
            	<th>ID</th>
				<th>Kode Akun</th>
				<th>Nama </th>
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
