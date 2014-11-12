<script type="text/javascript">	
$(function(){
	createLookUp({
		table_id		: "#lookup_table_product",
		table_width		: 400,
		listSource 		: "lookup/product_table_control",
		dataSource		: "lookup/product_lookup_id",
		column_id 		: 0,
		component_id	: "#lookup_product",
		filter_by		: [{id : "p1", label : "Kode Produk"}, {id : "p2", label : "Nama Produk"}, {id : "p3", label : "Tipe"}, {id : "p4", label : "Kategori"}],
		onSelect		: load_product
	});
	
	function load_product(id){
	
		if(id == ""){
			return;
		}
		
		var data ='product_id='+id; 
		
		$.ajax({
			type: 'POST',
			url: '<?=site_url('purchase/load_product_stock')?>',
			data: data,
			dataType: 'json',
			success: function(data){					
				$('input[name="i_product_code"]').val(data.content['product_code']);
				//$('input[name="i_transaction_detail_qty"]').val('');
				$('input[name="i_transaction_detail_price"]').val(data.content['product_purchase_price']);
				load_category();
			}
			
		});
		
	}
	
	$('input[name="i_transaction_detail_qty"]').change(function(){
		var price 	= $('input[name="i_transaction_detail_price"]').val();
		var qty = $(this).val();
		var total = price * qty;
		
		$('input[name="i_transaction_detail_total_price"]').val(total);
		
	});
	
	$('input[name="i_transaction_detail_price"]').change(function(){
		var qty 	= $('input[name="i_transaction_detail_qty"]').val();
		var price = $(this).val();
		var total = price * qty;
		
		$('input[name="i_transaction_detail_total_price"]').val(total);
		
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
			url: '<?=site_url('purchase/load_category')?>',
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
<form class="subform_area">
<div class="form_area_frame">
<table class="form_layout">
	<tr>
		<td width="150" req="req">Produk
	 <span class="lookup" id="lookup_product">
        <input type="hidden" name="i_product_id" class="com_id" value="<?=$product_id?>" />
         <div class="iconic_base iconic_search com_popup" style="margin-top:5px !important"></div>
        <input type="text" class="com_input" size="6" name="module" />
        <input type="hidden" name="i_index" value="<?=$index?>" />
      
        <input type="hidden" name="i_product_code" value="" />
        
       </span></td>
	</tr>
    <tr>
     <td>     
         <input name="i_category_id" type="hidden" id="i_category_id" value="<?=$transaction_detail_price ?>" size="10"/></td>
     </tr>
    <tr >
     <td id="expired">Expired
       <input name="i_transaction_detail_expired" type="text" id="i_transaction_detail_expired" value="<?=$transaction_detail_expired ?>" class="date_input" size="10"/></td>
     </tr>
    <tr>
     <td width="70" >Harga Beli
       <input name="i_transaction_detail_price" type="text" id="i_transaction_detail_price" value="<?=$transaction_detail_price ?>" />
     </td>
   </tr>
    <tr>
     <td width="70" >Jumlah<input name="i_transaction_detail_qty" type="text" id="i_transaction_detail_qty" value="<?=$transaction_detail_qty ?>" />
     </td>
   </tr>
    <tr>
     <td width="70" >Total<input name="i_transaction_detail_total_price" type="text" id="i_transaction_detail_total_price" value="<?=$transaction_detail_total_price ?>" readonly="readonly" />
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
<div>
	<table  id="lookup_table_product" cellpadding="0" cellspacing="0" border="0" class="display" > 
		<thead>
			<tr>
				<th width="10%">ID</th>
				<th>Kode</th>
                <th>Nama</th>
               <!-- <th>Tipe</th> -->
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
