<script type="text/javascript">	
$(function(){


	createForm({
		id 				: "#id_form_nya", 
		actionTarget	: "user/user_form_action",
		backPage		: "user/",
		nextPage		: "user/"
	});
	
	createLookUp({
		table_id		: "#lookup_table1",
		table_width		: 300,
		listSource 		: "user/group_table_control",
		dataSource		: "user/group_lookup_id",	
		column_id 		: 0,
		component_id	: "#lookup_component1",
		filter_by		: [{id : "p1", label : "Nama"}]
	});
	createLookUp({
		table_id	: "#lookup_table2",
		table_width	: 400,
		listSource 	: "user/employee_table_control",
		dataSource	: "user/employee_lookup_id",
		column_id 	: 0,
		component_id	: "#lookup_component2",
		onSelect	: function(id){
			if($('input[name="i_nip"]').val()=='')$('#extid').show();
			else $('#extid').hide();	
		},
		filter_by		: [{id : "p2", label : "Nama"}]
	});
	if($('input[name="i_nip"]').val()=='')$('#extid').show();
	else $('#extid').hide();
	//updateAll(); // tambahkan ini disetiap form
	
});
</script>

<form id="id_form_nya">
<div class="form_area">
<div class="form_area_frame">
<table class="form_layout">
	<tr>
		<td width="150" req="req">Group ID</td>
		<td>
			<span class="lookup" id="lookup_component1">
				<input type="hidden" name="i_group" class="com_id" value="<?=$user_group?>" />
				<div class="iconic_base iconic_search com_popup"></div>	<input type="text" class="com_input required" size="6"  <?=($row_id)?'disabled="disabled"':''?> id="fixed_state" />
				<input  type="hidden" id="row_id" name="row_id" value="<?=$row_id?>" />
						</span>		</td>
	</tr>
	<tr>
	  <td req="req">Karyawan</td>
	  <td><span class="lookup" id="lookup_component2">
				<input type="hidden" name="i_karyawan" class="com_id" value="<?=$employee_id?>" /><div class="iconic_base iconic_search com_popup"></div>
				<input id="fixed_state" type="text" name="i_nip" class="com_input" size="6" <?=($row_id)?'disabled="disabled"':''?> />
				
			
		</span></td>
    </tr>
	<tr id="extid">
	  <td>User Login</td>
	  <td><input type="text" value="<?=$user_login?>" name="i_ulogin" /></td>
    </tr>
	<tr>
		<td <? if(empty($row_id)){ echo "req=req"; }; ?>>Password</td>
		<td><input type="password" name="i_sandi1" size="31"  maxlength="31" /></td>
	</tr>
	<tr>
	  <td <? if($row_id == ""){ echo "req=req"; }; ?>>Konfirmasi Password</td>
	  <td><input type="password" name="i_sandi2" size="31"  maxlength="31" value="" /></td>
    </tr>
</table>
</div>
<div class="command_bar">
	<input type="button" id="submit" value="Simpan"/>
	<input type="button" id="enable" value="Edit"/>

</div>
</div>
</form>

<div>
	<table id="lookup_table1" cellpadding="0" cellspacing="0" border="0" class="display" > 
		<thead>
			<tr>
				<th width="10%">ID</th>
				
				<th>Nama</th>
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
	<table id="lookup_table2" cellpadding="0" cellspacing="0" border="0" class="display" > 
		<thead>
			<tr>
				
				<th width="5%">ID</th>
				<th width="15%">Nik</th>
				<th width="30%">Nama</th>
                <th width="30%">Keterangan</th>
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
