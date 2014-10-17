<script type="text/javascript">
$(function(){
	createTableFormTransient({
		id 					: "#transient_example",
		listSource 			: "cash_bank/journal_loader/<?=$transaction_id?>",
		formSource 			: "cash_bank/journal_form/<?=$transaction_id?>",
		controlTarget		: "cash_bank/journal_control",	// add edit controller
		actionTarget		: "cash_bank/journal_action/<?=$transaction_id?>",	 // insert many data at once	
		onAdd				: total,
		onComplete			: total,
		resetAfterSubmit 	: false
	});
	
	
	
	function total(){
		var debit = 0;
		var kredit = 0;
		$('input[name="transient_debit[]"]').each(function(){
			debit += parseFloat($(this).val());
		});
		$('input[name="transient_kredit[]"]').each(function(){
			kredit += parseFloat($(this).val());
		});
		$('input[name="debit"]').val(formatMoney(debit));
		$('input[name="kredit"]').val(formatMoney(kredit));
	}
	//total();

});
</script>
<form id="tform">
<div>
<table height="21" border="0" cellpadding="0" cellspacing="0" class="display" id="transient_example">
  <thead>
    <tr>
      <th style="width:100px !important">No. Akun </th>
      <th style="width:100px !important">Nama Akun</th>
      <th style="width:100px !important">Cabang</th>
      <th style="width:100px !important">Keterangan</th>
      <th style="width:100px !important">Debit</th>
      <th style="width:100px !important">Kredit</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
  </table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="display" >
  <tfoot class="summarybox">
    <tr>
      <td colspan="4" style="text-align:right !important"><b>Total &nbsp;</b></td>
      <td width="19%"><input style="font-weight:bold" type="text" class="format_money" readonly="readonly" id="debit" name="debit" /></td>
      <td width="19%"><input style="font-weight:bold" type="text" class="format_money" id="kredit" name="kredit"  readonly="readonly" /></td>
    </tr>
  </tfoot>
</table>
<div id="panel" class="command_table">
	<input type="button" id="add" <?=$row_id?'style="display:none"':''?> value="Tambah"/>
	<input type="button" id="edit" <?=$row_id?'style="display:none"':''?> value="Revisi"/> &nbsp; &nbsp;
    	<input type="button" id="delete" <?=$row_id?'style="display:none"':''?> value="Hapus"/>    
    	<?php
    	if($row_id) {?>
    	<input type="button" id="printj" value="Cetak" onclick="location.href='<?=site_url('cash_bank/print_jurnal/'.$row_id)?>'" />
    	<? } ?>
	<input type="button" id="reset" value="<?=$row_id?'Refresh':'Reset'?>"/> 	
	
</div>
<div id="editor"></div>
</form>
</div>
</div>
<!-- end semua data -->
