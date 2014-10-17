<script type="text/javascript">	
$(function(){
		createLookUp({
		table_id		: "#lookup_table2",
		listSource 		: "lookup/coa_table_control",
		dataSource		: "lookup/coa_lookup_hierarchy",
		component_id	: "#lookup_component2",
		filter_by		: [{id : "p1", label : "Kode"}, {id : "p2", label : "Nama"}]
	});
	
		createLookUp({
		table_id		: "#lookup_table_market",
		//table_width		: 400,
		listSource 		: "lookup/market_table_control",
		dataSource		: "lookup/market_lookup_id",
		//column_id 		: 0,
		component_id	: "#lookup_component_market",
		//onSelect		: show_stan,
		filter_by		: [{id : "p1", label : "Kode"}, {id : "p2", label : "Nama"}]
	});
	

	
	updateAll(); // tambahkan ini disetiap form
});
</script>
<form class="subform_area">
<div class="form_area_frame">
<table class="form_layout">
	<tr>
		<td width="120" req="req">No.Akun</td>
		<td>
        	<span class="lookup" id="lookup_component2">
				<input type="hidden" name="i_account" class="com_id" value="<?=$coa_id?>" />
                <div class="iconic_base iconic_search com_popup" style="margin-top:5px;"></div>
				<input type="text" class="com_input" id="i_account" size="15" />
				
				
			</span>
            <!-- simpan data lain dari table -->
            <input type="hidden" name="i_transaction_id" value="<?=$transaction_id?>" />
            
            <!-- variable untuk transient form -->
            <input type="hidden" name="i_index" value="<?=$index?>" />
        </td>
	</tr>
	<tr>
		<td  req="req">Cabang</td>
		<td>			
			<span class="lookup" id="lookup_component_market">
			<input type="hidden" name="i_market" class="com_id" value="<?=$market_id?>" />
            <div class="iconic_base iconic_search com_popup" style="margin-top:5px;"></div>
				<input type="text" class="com_input" id="i_market" size="15" />
			
		  </span>
		</td>
	</tr>
	<tr>
		<td >Keterangan</td>
		<td>
			<textarea name="i_keterangan" id="i_keterangan" cols="40" rows="3"><?=$desc?></textarea> 
		</td>
	</tr>
	<tr>
		<td  req="req">Jumlah</td>
		<td>
			<select name="i_tipe" init="<?=$tipe?>" style="float:left; margin-left:20%; margin-top:5px; width:20%; padding:10px; height:40px; " >
			  <option value="0" <?=$tipe==0?'selected="selected"':''?> >Debit</option>
			  <option value="1" <?=$tipe==1?'selected="selected"':''?>>Kredit</option>
		  </select>
		<input type="text" name="i_jumlah" id="i_jumlah" size="15" value="<?=$amount?>" style="width:60% !important" /></td>
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
	<table id="lookup_table2" cellpadding="0" cellspacing="0" border="0" class="display" > 
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
				<th>Nama Pasar</th>
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
