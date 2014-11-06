<form id="id_form_nya">

  <table width="100%" border="0" cellspacing="0" cellpadding="4" class="new_table">
    <tr class="title">
    <td align="center">No </td>
      <td align="center">Kode </td>
      <td align="left">Nama </td>
    <!-- <td align="left">Tipe </td>-->
    
      <td align="left">Total</td>
    </tr>
    <?php 
	$no = 1;
	foreach($data_product as $item): ?>
             <tr <?php if($no == 1){ ?> class="tr_no1" <?php }elseif($no%2 == 0){ echo 'class="tr_1"'; }else{ echo 'class="tr_2"'; }?>>
             <td align="center"><?php
			 if($no == 1){
			 	?>
                <div class="no1">1<span style="font-size:small">st</span></div>
                <?php
			 }else{ 
			  echo $no; 
			 }?></td>
      <td align="center"><?= $item['product_code']?></td>
      <td><?= $item['product_name']?></td>
     <!-- <td><?= $item['product_type_name']?></td>-->
      
      <td><?= $item['qty'] ?></td>
    </tr>
			<?php 
			$no++;
			endforeach; ?>
  
  </table>
  <div id="panel" class="command_table">
	
	<input type="button" id="refresh" value="Print"/>
</div>


</form>