<script type="text/javascript">	
$(function(){
	createForm({
		id 				: "#id_form_nya", 
		actionTarget	: "realtime_price/form_action",
		backPage		: "realtime_price",
		nextPage		: "realtime_price"
	});
	
	createLookUp({
		table_id		: "#lookup_table_product",
		table_width		: 400,
		listSource 		: "lookup/product_table_control",
		dataSource		: "lookup/product_lookup_id",
		column_id 		: 0,
		component_id	: "#lookup_product",
		filter_by		: [{id : "p1", label : "Kode Produk"}, {id : "p2", label : "Nama Produk"}, {id : "p3", label : "Tipe"}, {id : "p4", label : "Kategori"}],
		onSelect		: load_category
		
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
	
	function load_category()
	{
		var id 	= $('input[name="i_product_id"]').val();
		
		if(id == ""){
			return;
		}
		var data ='id='+id; 
		
		$.ajax({
			type: 'POST',
			url: '<?=site_url('realtime_price/load_category')?>',
			data: data,
			dataType: 'json',
			success: function(data){	
				$('input[name="i_category_id"]').val(data.content['product_category_id']);
				load_category_item();
			}
			
		});
	}
	
	function load_category_item(){
		 var i_category_id = $('input[name="i_category_id"]').val();
		var expired = document.getElementById("expired");
		if(i_category_id == 1){
			expired.style.display = 'inline';
		}else{
			expired.style.display = 'none';
		}
		
	}

	createDatePicker();
});

</script>

<form id="id_form_nya">
<div class="form_area">
<div class="form_area_frame">
<table class="form_layout">
     <tr>
       <td> <input type="hidden" name="row_id" value="<?=$row_id?>" />Produk <span class="lookup" id="lookup_product">
           <input type="hidden" name="i_product_id" class="com_id" value="<?=$product_id?>" />
          
          <div class="iconic_base iconic_search com_popup"></div>
           <input type="text" class="com_input" />
           
           </span>	
         </td>
     </tr>
       <tr>
     <td>Cabang
        <span class="lookup" id="lookup_stand">
				<input type="hidden" name="i_stand_id" class="com_id" value="<?=$stand_id?>" />
                <div class="iconic_base iconic_search com_popup"></div>
				<input type="text" class="com_input" />
				
				</span>	
       </td>
     </tr>
       <tr>
     <td>Qty
       <input name="i_qty" type="text" id="i_qty" value="<?=$product_stock_qty ?>" size="10"/></td>
     </tr>
     <tr >
     <td id="expired">Expired
       <input name="i_expired" type="text" id="i_expired" value="<?=$expired ?>" class="date_input" size="10"/></td>
     </tr>
     <tr>
     <td>     
         <input name="i_category_id" type="hidden" id="i_category_id" value="<?=$user_price ?>" size="10"/></td>
     </tr>
      <tr>
     <td>Harga
         <input name="i_user_price" type="text" id="i_user_price" value="<?=$user_price ?>" size="10"/></td>
     </tr>
       <tr>
     <!--<td>Harga
       Distributor
         <input name="i_another_price" type="text" id="i_another_price" value="<?=$another_price ?>" size="10"/></td>
     </tr>
       <tr>
     <td>Harga
       Freeline
         <input name="i_freeline_price" type="text" id="i_freeline_price" value="<?=$freeline_price ?>" size="10"/></td>
     </tr>
       <tr>
     <td>Harga
       Counter
         <input name="i_counter_price" type="text" id="i_counter_price" value="<?=$counter_price ?>" size="10"/></td>
     </tr>
       <tr>
     <td>Harga
Online         
       <input name="i_online_price" type="text" id="i_online_price" value="<?=$online_price ?>" size="10"/></td>
     </tr>-->
   
   
   
  <tr>
    <td width="70" valign="top">Keterangan
      <textarea name="i_description" id="i_description" cols="45" rows="5"><?= $product_stock_description ?></textarea></td>
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
	<table id="lookup_table_product" cellpadding="0" cellspacing="0" border="0" class="display" > 
		<thead>
			<tr>
			<th>ID</th>
            <th>Kode</th>
				<th>Nama</th>
            <th>Kategori</th>
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