<script type="text/javascript">	
$(function(){
	createForm({
		id 				: "#id_form_nya", 
		actionTarget	: "vendor/form_action",
		backPage		: "vendor",
		nextPage		: "vendor"
	});
	
	
	
	createDatePicker();
});

</script>

<form id="id_form_nya">
<div class="form_area">
<div class="form_area_frame">
<table class="form_layout">
	<tr>
     <td >Kode<input name="i_code" type="text" id="i_code" value="<?=$vendor_code ?>" />
     
	 <input type="hidden" name="row_id" value="<?=$row_id?>" /></td>
     
      
       
      
   </tr>
   
    <tr>
     <td>Nama
       <input name="i_name" type="text" id="i_name" value="<?=$vendor_name ?>" size="10"/></td>
     </tr>
   <tr>
     <td>Telepon
       <input name="i_phone" type="text" id="i_phone" value="<?=$vendor_phone ?>" size="10"/></td>
     </tr>
   
   <tr>
     <td>Email
       <input name="i_email" type="text" id="i_email" value="<?=$vendor_email ?>" size="10"/></td>
     </tr>
 <tr>
     <td width="70" valign="top">Alamat
       <textarea name="i_address" id="i_address" cols="45" rows="5"><?= $vendor_address ?></textarea></td>
     </tr>
  <tr>
    <td width="70" valign="top">Keterangan
      <textarea name="i_description" id="i_description" cols="45" rows="5"><?= $vendor_description ?></textarea></td>
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
