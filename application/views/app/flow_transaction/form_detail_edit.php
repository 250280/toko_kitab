<script type="text/javascript">	
$(function(){

	createForm({
		id 				: "#id_form_nya", 
		actionTarget	: "flow_transaction/form_action",
		backPage		: "flow_transaction/form/<?=$period_id?>",
		nextPage		: "flow_transaction/form/<?=$period_id?>",
		printTarget		: "flow_transaction/ibt_entry_value_print/<?=$row_id?>"
	});
	
	createDatePicker();
	//updateAll(); 
});
</script>

<form class="form_class" id="id_form_nya">	
<div class="form_area">
<div class="form_area_frame">
  <table width="100%" cellpadding="4" >
  <tr>
	
	  
	  <td><input type="hidden" id="row_id" name="row_id" value="<?=$row_id?>" />
      <input type="hidden" id="i_period_id" name="i_period_id" value="<?=$period_id?>" />
        <input type="hidden" id="i_no" name="i_no" value="<?=$no?>" /></td>
        <td></td>
	  </tr>
       <tr>
      <td>Tanggal</td>
      <td>
        <input type="text" name="i_ft_date" class="date_input" size="15" value="<?=$ft_date?>" />	</td>
    </tr>
    <?php
    
	for($i=0; $i<$no; $i++){
	
	?>
	<tr>
		<td width="20%"><?= $subject_name[$i] ?></td>
		
		<td ><input name="i_subject_value<?= $i?>" type="text" class="required" id="i_subject_value<?= $i?>" value="<?= $subject_value[$i]?>" size="10" />
	    <input type="hidden" id="i_subject_id<?= $i?>" name="i_subject_id<?= $i?>" value="<?=$subject_id[$i]?>" /></td>
	</tr>   <?php
	}
	?>
	
 
  </table>
  </div>
  <div class="command_bar">
	<input type="button" id="submit" value="Simpan"/>
	<input type="button" id="enable" value="Edit"/>
	<input type="button" id="cancel" value="Batal" /> 
	</div>
</div>
<!-- table contact -->

</form>

