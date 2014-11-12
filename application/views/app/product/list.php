<script type="text/javascript">	
$(function(){
	var otable = createTable({
		id 		: "#table",
		listSource 	: "product/table_controller",
		formTarget 	: "product/form",
		actionTarget: "product/form_action",
		column_id	: 0,
		
		filter_by 	: [ 
		{id : "product_code", label : "Kode"}, 
		{id : "product_name", label : "Nama"}, 
		{id : "product_category_name", label : "Kategori Produk"},
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
            <th>Kode</th>
            <th>Nama Cabang</th>
			<th>Kategori Produk</th>
            <th>Harga Beli</th>
		</tr> 
	</thead> 
	<tbody> 	
	</tbody>
</table>
<div id="panel" class="command_table">
	<input type="button" id="add" value="Tambah"/>
	<input type="button" id="edit" value="Revisi"/>
	<input type="button" id="delete" value="Hapus"/>
	<input type="button" id="refresh" value="Refresh"/>
</div>
<div id="editor"></div>
</div>
