<script type="text/javascript">	
$(function(){
	var otable = createTable({
		id 		: "#table",
		listSource 	: "realtime_price/table_controller",
		formTarget 	: "realtime_price/form",
		actionTarget: "realtime_price/form_action",
		column_id	: 0,
		
		filter_by 	: [ 
		{id : "stand_name", label : "Cabang"}, 
		{id : "product_category_name", label : "Kategori Produk"}, 
		{id : "product_name", label : "Nama Produk"},
		{id : "product_type_name", label : "Tipe Produk"}],
		"aLengthMenu"		: [[50, 100, 250, 500], [50, 100, 250, 500]],
	});
	otable.fnSetColumnVis(0, false, false);
});
</script>
<div id="example">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="table"> 
	<thead>
		<tr>
			<th>ID</th>
            <th>Cabang</th>
            <th>Kategori</th>
            <th>Nama Produk</th>
			
            <th>Qty</th>
            <th>Harga User</th>
           <!-- <th>Harga Freeline</th>
            <th>Harga Counter</th>
            <th>Harga Online</th>
            <th>Harga Distributor</th>-->
		</tr> 
	</thead> 
	<tbody> 	
	</tbody>
</table>
<div id="panel" class="command_table">
	<input type="button" id="add" value="Tambah"/>
	<input type="button" id="edit" value="Revisi"/>
	
	<input type="button" id="refresh" value="Refresh"/>
</div>
<div id="editor"></div>
</div>
