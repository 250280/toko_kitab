<script type="text/javascript">	
$(function(){
	
	//updateAll(); // tambahkan ini disetiap form
});
</script>
<form class="subform_area">
<div class="form_area_frame">
<table class="form_layout">
	<tr>
		<td width="120"  req="req">Waktu</td>
		<td><input type="text" value="<?=$si_time?>"  name="i_time" size="11" /> <input type="hidden" name="i_index" value="<?=$index?>" />    <input type="hidden" name="i_schedule_id" value="<?=$schedule_id?>" /></td>
	</tr>
    <tr>
		<td  req="req">Agenda</td>
		<td><input type="text" name="i_name" id="i_name" size="15" value="<?=$si_name?>" /></td>
	</tr>	
	<tr>
		<td >Keterangan</td>
		<td>
			<textarea name="i_description" id="i_description" cols="40" rows="3"><?=$si_description?></textarea> 
		</td>
	</tr>
	
</table>
</div>
<div class="command_bar">
	<input type="button" id="submit" value="Simpan" />
	<input type="reset" id="reset"  value="Reset" />
	<input type="button" id="cancel" value="Batal" />
</div>

</form>
