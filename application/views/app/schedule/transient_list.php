<script type="text/javascript">	
$(function(){
	createTableFormTransient({
		id 				: "#transient_contact",
		listSource 		: "schedule/detail_list_loader/<?=$row_id?>",
		formSource 		: "schedule/detail_form/<?=$row_id?>",
		controlTarget	: "schedule/detail_form_action"
	});
	
});
</script>
<div>
<form id="tform">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="transient_contact"> 
	<thead>
		<tr>
			
			<th>Waktu</th>
			<th>Nama</th>
			<th>Keterangan</th>
        </tr> 
	</thead> 
	<tbody> 	
	</tbody>
    
</table>

<div class="command_table" style="text-align:left;">
	
    <input type="button" id="add" value="Tambah"/>
	<input type="button" id="edit" value="Revisi"/>
    <input type="button" id="delete" value="Hapus"/>
   
</div>
<div id="editor"></div>
</form>
</div>