<script type="text/javascript">	
$(function(){
	createTableFormTransient({
		id 				: "#transient_contact",
		listSource 		: "realtime_stock/detail_list_loader/<?=$row_id?>",
		formSource 		: "realtime_stock/detail_form/<?=$row_id?>",
		controlTarget	: "realtime_stock/detail_form_action"
	});
	
});
</script>
<div>
<form id="tform">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="transient_contact"> 
	<thead>
		<tr>
			<th>Tanggal</th>
			<th>Debet</th>
			<th>Kredit</th>
            <th>Saldo</th>
            
		</tr> 
	</thead> 
	<tbody> 	
	</tbody>
    
</table>


<div id="editor"></div>

</form>

</div>

