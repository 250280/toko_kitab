<div class="table_title">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
   
    <td width="32%">Kode </td>
    <td width="25%">Nama</td>
    <td width="16%" align="right">Total</td>
  </tr>
  </table>
  </div>
  <div class="table_content">
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <?php foreach($data as $item): ?>
  <tr>
   
    <td width="31%"><?=$item['customer_number']?></td>
    <td width="26%"><?=$item['customer_name']?></td>
    <td width="16%" align="right"><?=$item['qty']?></td>
  </tr>
  <?php endforeach; ?>
  </table>