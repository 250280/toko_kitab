<script type="text/javascript">	
$(function(){
	var otable = createTableFixed({
		id	: "#table",
		formTarget 	: "customer/form",
		"useSearch" : true
	}, {
		"bPaginate" : true,
		"bAutoWidth": false,
		"sPaginationType": "full_numbers",
		"bFilter": true,
	});	
	otable.fnSetColumnVis(0, false, false);
});
</script>
<div id="example">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="table"> 
	<thead>
		<tr>
			<th>ID</th>
            <th>Nama Produk</th>
            <th>Kategori</th>
            <th>Tipe</th>
            <th>Total</th>
			<?php foreach($list as $item): ?>
            <th><?=$item['stand_name']?></th>
    		
			<?php endforeach; ?>
            <th>View</th>
		</tr> 
	</thead> 
	<tbody> 
    <?php foreach($data_product as $item_product): ?>
    <tr>
    <td><?=$item_product['product_id']?></td>
     <td><?=$item_product['product_name']?></td>
      <td><?=$item_product['product_category_name']?></td>
       <td><?=$item_product['product_type_name']?></td>
       <td><?php
       $total = $this->realtime_stock_model->get_total_qty($item_product['product_id']);
	   foreach($total as $item_total):
	   echo $item_total['total'];
	   endforeach
	   ?></td>
       
       <?php 	
	   foreach($list as $item2):
			$list_value = $this->realtime_stock_model->get_qty($item2['stand_id'], $item_product['product_id']);
			
			if($list_value){
			
			foreach($list_value as $item3): ?>
            <td><?= $item3['product_stock_qty'] ?></td>
    		
			<?php endforeach;
			}else{
			 ?> 
            <td>0</td>
			<?php
            }
			endforeach;
			 ?>
             <td><a href="<?=site_url('realtime_stock/form/'.$item_product['product_id'])?>" class="link_input"> VIEW </a></td>
       
    </tr>	
    <?php endforeach; ?>
	</tbody>
</table>
<div id="editor"></div>
</div>
