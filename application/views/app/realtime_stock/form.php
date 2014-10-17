<script type="text/javascript">	
$(function(){
	createForm({
		id 				: "#id_form_nya", 
		actionTarget	: "realtime_stock/form_action",
		backPage		: "realtime_stock",
		nextPage		: "realtime_stock"
	});
	
	
	
	createDatePicker();
});

</script>

<form id="id_form_nya">
<div class="form_area">
<div class="form_area_frame">
<table class="form_layout">
	<tr>
     <td width="70" >Nama Produk
       <input name="i_product_name" type="text" id="i_product_name" value="<?=$product_name?>" />
     
	 <input type="hidden" name="row_id" value="<?=$row_id?>" /></td>
   </tr>

</table>
</div>

</div>
</form>

