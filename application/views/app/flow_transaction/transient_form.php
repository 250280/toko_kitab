<script type="text/javascript">	
$(function(){
	createLookUp({
		table_id		: "#lookup_table_coa",
		listSource 		: "lookup/coa_table_control",
		dataSource		: "lookup/coa_lookup_hierarchy",
		component_id		: "#lookup_component_coa",
		filter_by		: [{id : "p1", label : "Kode"}, {id : "p2", label : "Nama"}]
	});
	
	createLookUp({
		table_id	: "#lookup_table_market",
		table_width	: 400,
		listSource 	: "lookup/market_table_control",
		dataSource	: "lookup/market_lookup_id",
		column_id 	: 0,
		component_id	: "#lookup_market",
		filter_by		: [{id : "p1", label : "Kode"}, {id : "p2", label : "Nama"}]
	});
	
	createLookUp({
		table_id		: "#lookup_table_period2",
		table_width		: 400,
		listSource 		: "lookup/period_table_control",
		dataSource		: "lookup/period_lookup_id",
		column_id 		: 0,
		component_id	: "#lookup_period2",
		filter_by		: [{id : "p1", label : "Kode"},{id : "p2", label : "Nama"}]
	});
	
	createDatePicker(); 

	updateAll(); // tambahkan ini disetiap form
});
</script>
<form class="subform_area">
<div class="form_area_frame">
<table class="form_layout">	<tr>
	  <td >Cabang
	    <span class="lookup" id="lookup_market">
	      <div class="iconic_base iconic_search com_popup" style="margin-top:5px !important"></div>
	      <input type="hidden" name="i_market" class="com_id" value="<?=$market_id?>" />
	      <input type="text" class="com_input" size="6" />
	      <input type="hidden" name="i_index" value="<?=$index?>" />
	      </span>
	    
	    
	    </td>
	</tr>
	<tr>
	  <td>
      Akun
	    <span class="lookup" id="lookup_component_coa">
	      <div class="iconic_base iconic_search com_popup" style="margin-top:5px !important"></div>
	      
	      <input type="hidden" name="i_coa" class="com_id" value="<?=$coa_id?>" />
	      <input type="text" class="com_input" size="6" name="module" />
        </span></td>
    </tr>
	<tr>
	  <td> Periode<span class="lookup" id="lookup_period2">
	    <div class="iconic_base iconic_search com_popup" style="margin-top:5px !important"></div>
	    
	    <input type="hidden" name="i_period_id2" class="com_id" value="<?=$period_id?>" />
	    <input type="text" class="com_input" size="6" />
	    </span></td>
	</tr>
	<tr>
	  <td>Tanggal Entry	    <input name="i_balance_date" type="text" class="date_input" id="i_balance_date" value="<?=$balance_date ?>" size="20"/></td>
	</tr>
    <tr>
	<td>Nilai Debet (Rp)	  <input type="text" name="i_debit" size="30" maxlength="30" value="<?php echo ($balance_debit) ? $balance_debit : 0;?>" /></td>
    </tr>
    <tr>
	<td>Nilai Kredit (Rp)	  <input type="text" name="i_kredit" size="30" maxlength="30" value="<?php echo ($balance_kredit) ? $balance_kredit : 0;?>" /></td>
    </tr>
</table>
</div>
<div class="command_bar">
	<input type="button" id="submit" value="Simpan" />
	<input type="reset" id="reset"  value="Reset" />
	<input type="button" id="cancel" value="Batal" />
</div></form>
<div>
	<table id="lookup_table_coa" cellpadding="0" cellspacing="0" border="0" class="display" > 
		<thead>
			<tr>
				<th width="10%"></th>
				<th width="30%">Hirarki</th>
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
	<table id="lookup_table_market" cellpadding="0" cellspacing="0" border="0" class="display" > 
		<thead>
			<tr>
	    <th>ID</th>
				<th>Kode</th>
				<th>Nama </th>
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
	<table id="lookup_table_period2" cellpadding="0" cellspacing="0" border="0" class="display" > 
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
