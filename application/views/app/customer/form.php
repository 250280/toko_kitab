<script type="text/javascript">	
$(function(){
	createForm({
		id 				: "#id_form_nya", 
		actionTarget	: "customer/form_action",
		backPage		: "customer",
		nextPage		: "customer"
	});
	
	createLookUp({
		table_id		: "#lookup_table_salesman",
		table_width		: 400,
		listSource 		: "lookup/salesman_table_control",
		dataSource		: "lookup/salesman_lookup_id",
		column_id 		: 0,
		component_id	: "#lookup_salesman",
		filter_by		: [{id : "p1", label : "Kode"}, {id : "p2", label : "Nama Salesman"}]
	});
	
	createDatePicker();
});

</script>

<form id="id_form_nya">
<div class="form_area">
<div class="form_area_frame">
<table class="form_layout">
	<tr>
     <td >Kode<input name="i_number" type="text" id="i_number" value="<?=$customer_number ?>" />
     
	 <input type="hidden" name="row_id" value="<?=$row_id?>" /></td>
     
      
       
      
   </tr>
   
    <tr>
     <td>Nama
       <input name="i_name" type="text" id="i_name" value="<?=$customer_name ?>" size="10"/></td>
     </tr>
   <tr>
    <tr>
     <td>Nomor KTP / SIM
       <input name="i_ktp_number" type="text" id="i_ktp_number" value="<?=$customer_ktp_number ?>" size="10"/></td>
     </tr>
   <tr>
     <td>Telepon
       <input name="i_phone" type="text" id="i_phone" value="<?=$customer_phone ?>" size="10"/></td>
     </tr>
   
   <tr>
     <td>Email
       <input name="i_email" type="text" id="i_email" value="<?=$customer_email ?>" size="10"/></td>
     </tr>
 <tr>
     <td width="70" valign="top">Alamat
       <textarea name="i_address" id="i_address" cols="45" rows="5"><?= $customer_address ?></textarea></td>
     </tr>
  <tr>
    <td width="70" valign="top">Keterangan
      <textarea name="i_description" id="i_description" cols="45" rows="5"><?= $customer_description ?></textarea></td>
    </tr>
    <tr>
    <td>Salesman <span class="lookup" id="lookup_salesman">
				<input type="hidden" name="i_salesman_id" class="com_id" value="<?=$salesman_id?>" />
                <div class="iconic_base iconic_search com_popup"></div>
				<input type="text" class="com_input" />
				
				</span>	</td>
    </tr>
    

</table>
</div>
<div class="command_bar">
	<input type="button" id="submit" value="Simpan"/>
	<input type="button" id="enable" value="Edit"/>
	<input type="button" id="cancel" value="Batal" /> 
</div>
</div>
</form>


<div id="">
	<table id="lookup_table_salesman" cellpadding="0" cellspacing="0" border="0" class="display" > 
		<thead>
			<tr>
			<th>ID</th>
            <th>Kode</th>
				<th>Nama Salesman</th>
            
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
