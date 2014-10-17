<script type="text/javascript">	
$(function(){

	createForm({
		id 				: "#id_form_nya", 
		actionTarget	: "sales_transaction_report/form_action",
		backPage		: "sales_transaction_report",
		nextPage		: "sales_transaction_report"
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
		table_id		: "#lookup_table_customer",
		table_width		: 400,
		listSource 		: "lookup/customer_table_control",
		dataSource		: "lookup/customer_lookup_id",
		column_id 		: 0,
		component_id	: "#lookup_customer",
		filter_by		: [{id : "p1", label : "Nomor"}, {id : "p2", label : "Nama"}]
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
      <td>Pelanggan
          <span class="lookup" id="lookup_customer">
				<input type="hidden" name="i_customer_id" class="com_id" value="<?=$customer_id?>" />
                <div class="iconic_base iconic_search com_popup"></div>
				<input type="text" class="com_input" />
				
				</span>		</td>
    </tr>
      <tr>
    <td width="70" valign="top">Pembayaran
       <?=form_dropdown('i_transaction_payment_method', $cbo_transaction_payment_method, $transaction_payment_method_id)?></td>
    </tr>
    <tr>
    <td width="70" valign="top">PPN
      <?=form_dropdown('i_transaction_ppn', $cbo_transaction_ppn, $transaction_ppn)?>  
      </td>  
      </tr>
    <tr>
    <td width="70" valign="top">Keterangan
      <textarea name="i_transaction_description" id="i_transaction_description" cols="45" rows="5"><?=$transaction_description?></textarea></td>
    </tr>
   
   
     </table>
     </div>
	
	<div class="command_bar">
		<!--<input type="button" id="submit" value="Simpan"/>
		<input type="button" id="enable" value="Edit"/>
	-->
     <input type="button" value="Print" onclick="location.href='<?=site_url('sales_transaction_report/report/'.$row_id)?>'" />
		<input type="button" id="cancel" value="Kembali"/>
	</div>
</div>
<!-- table contact -->

</form>


<div id="">
	<table id="lookup_table_customer" cellpadding="0" cellspacing="0" border="0" class="display" > 
		<thead>
			<tr>
			<th>ID</th>
            <th>Nomor</th>
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