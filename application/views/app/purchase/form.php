<script type="text/javascript">	
$(function(){

	createForm({
		id 				: "#id_form_nya", 
		actionTarget	: "purchase/form_action",
		backPage		: "purchase",
		nextPage		: "purchase/form"
	});
	
	createLookUp({
		table_id		: "#lookup_table_stand",
		table_width		: 400,
		listSource 		: "lookup/stand_table_control",
		dataSource		: "lookup/stand_lookup_id",
		column_id 		: 0,
		component_id	: "#lookup_stand",
		filter_by		: [{id : "p1", label : "Nama Cabang"}]
	});
	
	createLookUp({
		table_id		: "#lookup_table_vendor",
		table_width		: 400,
		listSource 		: "lookup/vendor_table_control",
		dataSource		: "lookup/vendor_lookup_id",
		column_id 		: 0,
		component_id	: "#lookup_vendor",
		filter_by		: [{id : "p1", label : "Kode"}, {id : "p2", label : "Nama"}]
	});
	
	$('select[name="i_transaction_payment_method"]').change(function(){
		id = $(this).val();
		var down_payment = document.getElementById("down_payment");
		
		if(id == 2){
			down_payment.style.display = 'inline';
		}else{
			down_payment.style.display = 'none';
		}
	});
	
	createDatePicker();
	//updateAll(); 
});
</script>

<form class="form_class" id="id_form_nya">	
<div class="form_area">
<div class="form_area_frame">
	<table class="form_layout">
	<tr>
     <td width="70" >Kode<input name="i_transaction_code" type="text" id="i_code" value="<?=$transaction_code ?>" />
     
	 <input type="hidden" name="row_id" value="<?=$row_id?>" /></td>
   </tr>
   <tr>
      <td>Stand
          <span class="lookup" id="lookup_stand">
				<input type="hidden" name="i_stand_id" class="com_id" value="<?=$stand_id?>" />
                <div class="iconic_base iconic_search com_popup"></div>
				<input type="text" class="com_input" />
				
				</span>		</td>
    </tr>
    <tr>
      <td>Tanggal
        <input type="text" name="i_transaction_date" class="date_input" size="15" value="<?=$transaction_date?>" />	</td>
    </tr>
    <tr>
      <td>Vendor
          <span class="lookup" id="lookup_vendor">
				<input type="hidden" name="i_vendor_id" class="com_id" value="<?=$vendor_id?>" />
                <div class="iconic_base iconic_search com_popup"></div>
				<input type="text" class="com_input" />
				
				</span>		</td>
    </tr>
      <tr>
    <td width="70" valign="top">Pembayaran
       <?=form_dropdown('i_transaction_payment_method', $cbo_transaction_payment_method, $transaction_payment_method_id)?></td>
    </tr>
   
     <tr>
      <td> <div id="down_payment" style="display:none;">Uang Muka
        <input type="text" name="i_transaction_down_payment" size="15" value="<?=$transaction_down_payment?>" /></div>	</td>
    </tr>
    
    <tr>
    <td width="70" valign="top">PPN
      <?=form_dropdown('i_transaction_ppn', $cbo_transaction_ppn, $transaction_ppn)?>  
      </td>  
      </tr>
       <tr>
      <td>Ongkos Kirim
        <input type="text" name="i_transaction_sent_price" size="15" value="<?=$transaction_sent_price?>" />	</td>
    </tr>
    <tr>
    <td width="70" valign="top">Keterangan
      <textarea name="i_transaction_description" id="i_transaction_description" cols="45" rows="5"><?=$transaction_description?></textarea></td>
    </tr>
   
   
     </table>
     </div>
	
	<div class="command_bar">
		<input type="button" id="submit" value="Simpan"/>
		<input type="button" id="enable" value="Edit"/>
	
		<input type="button" id="cancel" value="Batal"/>
	</div>
</div>
<!-- table contact -->

</form>


<div id="">
	<table id="lookup_table_vendor" cellpadding="0" cellspacing="0" border="0" class="display" > 
		<thead>
			<tr>
			<th>ID</th>
            <th>Kode</th>
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