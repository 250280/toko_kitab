<script type="text/javascript">	
$(function(){
	createTableFormTransient({
		id 				: "#transient_contact",
		listSource 		: "salesman/detail_list_loader/<?=$row_id?>",
		formSource 		: "salesman/detail_form/<?=$row_id?>",
		controlTarget	: "salesman/detail_form_action"
	});
	
});
</script>
<div>
<form id="tform">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="transient_contact"> 
	<thead>
		<tr>
			<th>Kode Pelanggan</th>
			<th>Nama</th>
			<th>Email</th>
            <th>Telepon</th>
            <th>Alamat</th>
		</tr> 
	</thead> 
	<tbody> 	
	</tbody>
    
</table>


<div id="editor"></div>

</form>

</div>

