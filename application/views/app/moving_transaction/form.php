<script type="text/javascript">	
$(function(){

	createForm({
		id 				: "#id_form_nya", 
		actionTarget	: "moving_transaction/form_action",
		backPage		: "moving_transaction",
		nextPage		: "moving_transaction/form"
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
		table_id		: "#lookup_table_stand2",
		table_width		: 400,
		listSource 		: "lookup/stand_table_control",
		dataSource		: "lookup/stand_lookup_id",
		column_id 		: 0,
		component_id	: "#lookup_stand2",
		filter_by		: [{id : "p1", label : "Nama Cabang"}]
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
});
</script>

<form class="form_class" id="id_form_nya">	
<div class="form_area">
<div class="form_area_frame">
	<table class="form_layout">
    <tr>
			<td >Periode
                <span class="lookup" id="lookup_period">
         <div class="iconic_base iconic_search com_popup"></div> <input type="hidden" name="i_period_id" class="com_id" value="<?=$period_id?>" />
         <input type="text" class="com_input" size="6" />
       </span>
            </td> 
		</tr>
	<tr>
     <td width="70" >Kode<input name="i_transaction_code" type="text" id="i_code" value="<?=$transaction_code ?>" />
     
	 <input type="hidden" name="row_id" value="<?=$row_id?>" /></td>
   </tr>
    <tr>
      <td>Tanggal
        <input type="text" name="i_transaction_date" class="date_input" size="15" value="<?=$transaction_date?>" />	</td>
    </tr>
   
   <tr>
      <td>Dari Cabang
          <span class="lookup" id="lookup_stand">
				<input type="hidden" name="i_stand_id" class="com_id" value="<?=$stand_id?>" />
                <div class="iconic_base iconic_search com_popup"></div>
				<input type="text" class="com_input" />
				
				</span>		</td>
    </tr>
    <tr>
      <td>Ke Cabang
          <span class="lookup" id="lookup_stand2">
				<input type="hidden" name="i_stand_to_id" class="com_id" value="<?=$stand_to_id?>" />
                <div class="iconic_base iconic_search com_popup"></div>
				<input type="text" class="com_input" />
				
				</span>		</td>
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
    <?php
    if(!$row_id){
	?>
		<input type="button" id="submit" value="Simpan"/>
		<input type="button" id="enable" value="Edit"/>
	<?php
	}
	?>
		<input type="button" id="cancel" value="Kembali"/>
	</div>
</div>
<!-- table contact -->

</form>


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


<div id="">
	<table id="lookup_table_stand2" cellpadding="0" cellspacing="0" border="0" class="display" > 
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