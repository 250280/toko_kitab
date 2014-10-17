<script type="text/javascript">	
$(function(){
	var otableTransient = createTableFormTransient({
		id 				: "#transient_contact",
		listSource 		: "beginning_balance/detail_list_loader/<?=$period_id?>",
		formSource 		: "beginning_balance/detail_form/<?=$row_id?>",
		controlTarget	: "beginning_balance/detail_form_action",
			filter_by 		: [{id : "code", label : "Kode"}, 
				{id : "name", label : "Nama Rekening"}, 
				{id : "period", label : "Periode [mm/yyyy]"}],
		onAdd		: function (){perhitungan();},	
		onTargetLoad: function (){perhitungan();} 
	});
	
	function perhitungan()
	{
		var total_debit = 0;
		$('input[name="transient_coa_debit[]"]').each(function()
		{
			total_debit += parseFloat($(this).val());
		});
		$('input#total_debit').val(formatMoney(total_debit));
		
		var total_kredit = 0;
		$('input[name="transient_coa_kredit[]"]').each(function()
		{
			total_kredit += parseFloat($(this).val());
		});
		$('input#total_kredit').val(formatMoney(total_kredit));
	}
	
});
</script>
<div>
<form id="tform">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="transient_contact"> 
	<thead>
		<tr>
			
			<th>Periode</th>
            <th>Cabang</th>
			<th>Kode</th>
			<th>Nama Akun</th>
			<th>Debit</th>
			<th>Kredit</th>
		</tr> 
	</thead> 
	<tbody> 	
	</tbody>
</table>

<div class="command_table" style="text-align:left;">
<table align="right">
          <tr>
            <td>TOTAL</td>
            <td><input id="total_debit" value="<?=$total_debit?>" type="text" readonly="readonly" class="format_money" size="50"  style="width:100px; text-align:right"/>
            <input id="total_kredit" value="<?=$total_kredit?>" type="text" readonly="readonly" class="format_money" size="50" style="width:100px; text-align:right" /></td>
          </tr>
        </table>
      
	<input type="button" id="add" value="Tambah"/>
	<input type="button" id="edit" value="Revisi"/>
    <input type="button" id="delete" value="Hapus"/>
	
</div>
<div id="editor"></div>
</form>
</div>