<script type="text/javascript">	
$(function(){

	createForm({
		id 				: "#id_form_nya2", 
		actionTarget	: "online_sales_transaction/form_action",
		backPage		: "online_sales_transaction",
		nextPage		: "online_sales_transaction/report"
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

<form class="form_class" id="id_form_nya2">	
<div class="form_area">
<div class="form_area_frame">
<table class="form_layout">
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
      </table>
      </div>
	<div class="command_bar">
     <input type="hidden" name="row_id" value="<?=$row_id?>" />
		<input type="button" id="submit" value="Proses Transaksi" style="width:150px !important;"/>
		<input type="button" id="enable" value="Edit"/>
	
		<input type="button" id="cancel" value="Batal"/>
	</div>
</div>
<!-- table contact -->

</form>
