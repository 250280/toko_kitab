<script type="text/javascript">	
$(function(){
	createForm({
		id 				: "#id_form_nya", 
		actionTarget	: "employee/form_action",
		backPage		: "employee",
		nextPage		: "employee"
	});
	
	createLookUp({
		table_id		: "#lookup_table_stand",
		table_width		: 400,
		listSource 		: "lookup/stand_table_control",
		dataSource		: "lookup/stand_lookup_id",
		column_id 		: 0,
		component_id	: "#lookup_stand",
		filter_by		: [{id : "p1", label : "Nama"}]
	});
	
	createLookUp({
		table_id		: "#lookup_table_employee_position",
		table_width		: 400,
		listSource 		: "lookup/employee_position_table_control",
		dataSource		: "lookup/employee_position_lookup_id",
		column_id 		: 0,
		component_id	: "#lookup_employee_position",
		filter_by		: [{id : "p2", label : "Nama"}]
	});
	
	createDatePicker();
});



function ajaxFileUpload()
	{

	
		$("#loading")
		.ajaxStart(function(){
			$(this).show();
		})
		.ajaxComplete(function(){
			$(this).hide();
		});
 
		$.ajaxFileUpload({
				url:'<?=site_url('employee/do_upload')?>',
				secureuri:false,
				fileElementId:'fileToUpload',
				dataType: 'json',
				data:{name:'logan', id:'id'},
				success: function (data, status)
				{
					if(typeof(data.error) != 'undefined')
					{
						if(data.error != '')
						{
							alert(data.error);
						}else
						{
							reloadImage('#imagex','<?=base_url()?>/tmp/'+data.value);
							$('#photo').val(data.value);
						}
					}
				},
				error: function (data, status, e)
				{
					alert('error ~ '+e);
				}
		}); 
		//alert(1);
		return false;
 
	}

</script>

<form id="id_form_nya">
<div class="form_area">
<div class="form_area_frame">
<table class="form_layout">
	<tr>
     <td >NIP<input name="i_nip" type="text" id="i_nip" value="<?=$employee_nip ?>" />
     
	 <input type="hidden" name="row_id" value="<?=$row_id?>" /></td>
   </tr>
   
    <tr>
     <td>Nama
       <input name="i_name" type="text" id="i_name" value="<?=$employee_name ?>" size="10"/></td>
     </tr>
      <tr>
     <td>Tanggal Lahir
       <input name="i_birth" type="text" id="i_birth" value="<?=$employee_birth ?>" class="date_input" size="10"/></td>
     </tr>
   <tr>
     <td>Jenis Kelamin
       <p>
       <label style="margin-left:20%;">
         <input type="radio" name="i_gender" value="1" id="i_gender1" <?php if($employee_gender == 1 || $employee_gender == ""){?> checked="checked"<?php } ?> />
         Laki - laki</label>
       <br />
       <label style="margin-left:20%;">
         <input type="radio" name="i_gender" value="2" id="i_gender2" <?php if($employee_gender == 2){?> checked="checked"<?php } ?> />
         Perempuan</label>
      
     </p>
       </td>
     </tr>
       <tr>
     <td>Jabatan
        <span class="lookup" id="lookup_employee_position">
				<input type="hidden" name="i_position_id" class="com_id" value="<?=$employee_position_id?>" />
                <div class="iconic_base iconic_search com_popup"></div>
				<input type="text" class="com_input" />
				
				</span>	
       </td>
     </tr>
      <tr>
     <td>Cabang<span class="lookup" id="lookup_stand">
				<input type="hidden" name="i_stand_id" class="com_id" value="<?=$stand_id?>" />
                <div class="iconic_base iconic_search com_popup"></div>
				<input type="text" class="com_input" />
				
				</span>	
       </td>
     </tr>
 <tr>
   <tr>
     <td>No KTP
       <input name="i_ktp" type="text" id="i_ktp" value="<?=$employee_ktp ?>" size="10"/></td>
     </tr>
      <tr>
     <td>No Telepon
       <input name="i_phone" type="text" id="i_phone" value="<?=$employee_phone?>" size="10"/></td>
     </tr>
   <tr>
     <td>Email
       <input name="i_email" type="text" id="i_email" value="<?=$employee_email?>" size="10"/></td>
     </tr>
   
   
  <tr>
    <td width="70" valign="top">Alamat
      <textarea name="i_address" id="i_address" cols="45" rows="5"><?= $employee_address?></textarea></td>
    </tr>
  <tr>
    <td valign="top">
      <?php
	   if($row_id == "" || $employee_pic == ""){
	   ?><div id="foto_hidden2" style="width:100px; height:70px; border:1px solid #999; text-align:center; padding-top:40px;"><b>FOTO</b></div>
	   <?php
	   }
	   ?>
    <div class="img" >
 <img id="imagex" src="<?=base_url().'storage/img_employee/'.$employee_pic?>" alt="" />
 <input type="hidden" name="i_photo" id="photo" value="<?=$employee_pic?>" />
 <input type="hidden" name="i_oldphoto" value="<?=$employee_pic?>" />
 <div class="desc"></div>
</div>
	  <input id="fileToUpload" type="file" size="10" name="fileToUpload" class="input">
<input type="button" id="buttonUpload" onclick="ajaxFileUpload();return false;" value="Upload" />	</td>
  </tr>

</table>
<div class="form_category">Bank</div>
<table class="form_layout">
	<tr>
     <td >Rekening Bank<input name="i_bank_number" type="text" id="i_bank_number" value="<?=$employee_bank_number?>" />
     </td>
   </tr>
   <tr>
     <td >Nama Bank<input name="i_bank_name" type="text" id="i_bank_name" value="<?=$employee_bank_name?>" />
     </td>
   </tr>
   <tr>
     <td >Atas Nama<input name="i_bank_beneficiary" type="text" id="i_bank_beneficiary" value="<?=$employee_bank_beneficiary?>" />
     </td>
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
	<table id="lookup_table_stand" cellpadding="0" cellspacing="0" border="0" class="display" > 
		<thead>
			<tr>
			<th>ID</th>
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
	<table id="lookup_table_employee_position" cellpadding="0" cellspacing="0" border="0" class="display" > 
		<thead>
			<tr>
			<th>ID</th>
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