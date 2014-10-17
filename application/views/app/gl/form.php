<script type="text/javascript">	
$(function(){
	createForm({
		id 				: "#id_form_nya", 
		actionTarget	: "gl/gl_form_action",
		backPage		: "gl",
		nextPage		: "gl/form"
	});
	
	createLookUp({
		table_id		: "#lookup_table_period",
		table_width		: 400,
		listSource 		: "lookup/period_table_control",
		dataSource		: "lookup/period_lookup_id",
		column_id 		: 0,
		component_id	: "#lookup_period",
		filter_by		: [{id : "p1", label : "Kode"},{id : "p2", label : "Nama"}]
	});
	
	createDatePicker();
	//updateAll();
	
		$('#enable').click(function(){
		$('input[type="button"][id="add"]').show();
		$('input[type="button"][id="edit"]').show();
		$('input[type="button"][id="delete"]').show();
	});
	$('#new').click(function(){
		location.href = site_url + "gl/form";
	});
});
</script>

<form class="form_class" id="id_form_nya">	
<div class="form_area">
<div class="form_area_frame">
<!-- panel input -->
<table class="form_layout">
	<!--<tr>
		 <td width="150">ID</td> 
		<td>
			<input type="text" id="row_id" size="10" value="<?=$transaction_id?>" disabled="disabled" />
			
		</td>
	</tr>-->
	
	<tr>
		<td width="150" req="req">No. Transaksi
	    <input type="text" name="i_kode" readonly="readonly" id="fixed_state" value="<?=$transaction_code?>" /></td>	
	</tr>
	
	<tr>
	  <td req="req">Periode
        <span class="lookup" id="lookup_period">
         <div class="iconic_base iconic_search com_popup"></div> <input type="hidden" name="i_period_id" class="com_id" value="<?=$period_id?>" />
         <input type="text" class="com_input" size="6" />
       </span></td>
    </tr>
	<tr>
		<td width="150" req="req">Tanggal
	    <input type="hidden" id="row_id" name="row_id" value="<?=$transaction_id?>" />
	    <input type="text" value="<?=$transaction_date?>"  name="i_tanggal" class="date_input" size="11" /></td>
	</tr>
	<tr>
		<td width="150" req="req">Tipe Jurnal
	    <?=form_dropdown('i_trans_type', $trans_type, $transaction_type_id )?></td>	
	</tr>
	<tr>
		<td width="150" req="req">Keterangan Jurnal
	    <textarea name="i_desc" cols="40" rows="3"><?=$transaction_description?></textarea></td>
	</tr>
</table>
</div>

<div class="command_bar">
<input type="button" id="submit" value="Simpan"/>
		
		<?php
		if($show_control & EDIT_CONTROL) 
		{
			echo '<input type="button" id="enable" value="Edit"/> &nbsp;';
			
		}
			
		if($show_control & BACK_CONTROL) echo '<input type="button" id="cancel" value="Kembali" /> &nbsp;';
		
		?>
	</div>
    </div>
</form>


<div id="">
	<table id="lookup_table_period" cellpadding="0" cellspacing="0" border="0" class="display" > 
		<thead>
			<tr>
			<th>ID</th>
				<th>Periode</th>
				<th>Status</th>
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

