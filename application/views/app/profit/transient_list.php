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
            <th>Tanggal</th>
            <th  width="18%">PENDAPATAN UTAMA</th>
			<?php foreach($list as $item): ?>
            <th width="18%"><?=$item['coa_name']?></th>
    		
			<?php endforeach; ?>
          	<th>TOTAL</th>
            <th>CONFIG</th>
		</tr> 
	</thead> 
	<tbody> 
    <?php foreach($data_pendapatan as $item_pendapatan): ?>
    <tr>
   
     <td><?php $tanggal = date('d/m/Y', strtotime($item_pendapatan['profit_date'])); 
	 echo $tanggal; ?></td>
     <td><?php $tanggal = date('d/m/Y', strtotime($item_pendapatan['profit_date'])); 
	 echo $tanggal; ?></td>
     <td>
     
      <?php
           $list_value_utama = $this->profit_model->get_detail_utama($item_pendapatan['period_id'], $item_pendapatan['profit_date']);
           foreach($list_value_utama as $item5): ?>
          
          <?php
		  $penjualan_utama = $item5['total'];
		   echo number_format($item5['total'], 2) ?>
    		
			<?php endforeach; ?>
     
     </td>
  <?php 	
	   foreach($list as $item2):
			$list_value = $this->profit_model->get_detail($item_pendapatan['profit_id'], $item2['coa_id']);
			
			if($list_value){
			
			foreach($list_value as $item3): ?>
            <td align="right"><?= number_format($item3['profit_item_value'], 2) ?></td>
    		
			<?php endforeach;
			}else{
			 ?> 
            <td>0</td>
			<?php
            }
			endforeach;
			 ?>
           <td align="right">
           <?php
           $list_value_total = $this->profit_model->get_detail_total($item_pendapatan['profit_id']);
           foreach($list_value_total as $item4): ?>
          
          <?php
		  $final_total = $penjualan_utama + $item4['total'];
		 echo   number_format($final_total, 2) ?>
    		
			<?php endforeach;
			 ?>
           </td>  
           <td><a href="<?=site_url('profit/form_detail_edit/'.$item_pendapatan['profit_id'])?>" class="link_input"> EDIT </a></td>
       
    </tr>	
    <?php endforeach; ?>
	</tbody>
</table>
<div id="panel" class="command_table">

<input type="button" value="Tambah" onclick="location.href='<?=site_url('profit/form_detail/'.$row_id)?>'" />
</div>

<div id="editor"></div>
</div>
