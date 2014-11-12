<script type="text/javascript">	
$(function(){
	createForm({
		id 				: "#id_form_nya", 
		actionTarget	: "product/form_action",
		backPage		: "product",
		nextPage		: "product"
	});
	
	createLookUp({
		table_id		: "#lookup_table_product_category",
		table_width		: 400,
		listSource 		: "lookup/product_category_table_control",
		dataSource		: "lookup/product_category_lookup_id",
		column_id 		: 0,
		component_id	: "#lookup_product_category",
		filter_by		: [{id : "p1", label : "Nama"}],
		
	});
	
	createLookUp({
		table_id		: "#lookup_table_product_type",
		table_width		: 400,
		listSource 		: "lookup/product_type_table_control",
		dataSource		: "lookup/product_type_lookup_id",
		column_id 		: 0,
		component_id	: "#lookup_product_type",
		filter_by		: [{id : "p1", label : "Nama"}]
	});
	
	
	
	createDatePicker();
});



</script>

<form id="id_form_nya">
<div class="form_area">
<div class="form_area_frame">
<table class="form_layout">
	<tr>
     <td >Kode<input name="i_code" type="text" id="i_code" value="<?=$product_code ?>" />
     
	 <input type="hidden" name="row_id" value="<?=$row_id?>" /></td>
   </tr>
   
    <tr>
     <td>Nama
       <input name="i_name" type="text" id="i_name" value="<?=$product_name ?>" size="10"/></td>
     </tr>
     <tr>
     <td>Kategori Produk
        <span class="lookup" id="lookup_product_category">
				<input type="hidden" name="i_category_id" id="i_category_id" class="com_id" value="<?=$product_category_id?>" />
                <div class="iconic_base iconic_search com_popup"></div>
				<input type="text" class="com_input" />
				
				</span>	
       </td>
     </tr>
       <!--<tr>
     <td>Tipe Produk
        <span class="lookup" id="lookup_product_type">
				<input type="hidden" name="i_type_id" class="com_id" value="<?=$product_type_id?>" />
                <div class="iconic_base iconic_search com_popup"></div>
				<input type="text" class="com_input" />
				
				</span>	
       </td>
     </tr>-->
 <tr>
   <tr>
     <td>Harga Beli
       <input name="i_purchase_price" type="text" id="i_purchase_price" value="<?=$product_purchase_price ?>" size="10"/></td>
     </tr>
   
     <tr >
     <!--<td id="expired">Expired
       <input name="i_expired" type="text" id="i_expired" value="<?=$product_expired ?>" class="date_input" size="10"/></td>
     </tr>-->
   
      <tr>
     <td>Min Reorder
       <input name="i_min_reorder" type="text" id="i_min_reorder" value="<?=$product_min_reorder ?>" size="10"/></td>
     </tr>
      <!-- <tr>
     <td>Poin<input name="i_point" type="text" id="i_point" value="<?=$product_point?>" size="10"/></td>
     </tr>-->
   
   
   
  <tr>
    <td width="70" valign="top">Keterangan
      <textarea name="i_description" id="i_description" cols="45" rows="5"><?= $product_description ?></textarea></td>
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
	<table id="lookup_table_product_category" cellpadding="0" cellspacing="0" border="0" class="display" > 
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
	<table id="lookup_table_product_type" cellpadding="0" cellspacing="0" border="0" class="display" > 
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