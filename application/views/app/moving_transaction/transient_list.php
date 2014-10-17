<script type="text/javascript">	
$(function(){
	createTableFormTransient({
		id 				: "#transient_product",
		listSource 		: "moving_transaction/detail_list_loader/<?=$row_id?>",
		formSource 		: "moving_transaction/detail_form/<?=$row_id?>",
		controlTarget	: "moving_transaction/detail_form_action"
	});
	
	
});
</script>
<div>
<form id="tform">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="transient_product"> 
	<thead>
		<tr>
			
			<th>Kode</th>
			<th>Nama Barang</th>
	
            <th>Jumlah</th>
           
		</tr> 
	</thead> 
	<tbody> 	
	</tbody>
    
</table>
<?php
if(!$row_id){
?>
<div class="command_table" style="text-align:left;">
	
    <input type="button" id="add" value="Tambah"/>
	<input type="button" id="edit" value="Revisi"/>
    <input type="button" id="delete" value="Hapus"/>
   
</div>
<?php
}
?>
<div id="editor"></div>
</form>
</div>