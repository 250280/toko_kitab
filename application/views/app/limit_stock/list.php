<script type="text/javascript">	
$(function(){
	var otable = createTable({
		id 		: "#table",
		listSource 	: "limit_stock/table_controller",
		formTarget 	: "limit_stock/form",
		actionTarget: "limit_stock/form_action",
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
			<th>Tipe Produk</th>
            <th>Qty</th>
		</tr> 
	</thead> 
	<tbody> 	
	</tbody>
</table>

<div id="editor"></div>
</div>
