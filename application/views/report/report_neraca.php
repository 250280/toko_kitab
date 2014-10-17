	<div class="report_area"><br />
<table width="100%" border="0" cellspacing="0" cellpadding="4">
<tr>
    <td width="17%">Laporan</td>
    <td width="3%">:</td>
    <td width="80%">Neraca</td>
  </tr>
  <tr>
    <td width="17%">Periode</td>
    <td width="3%">:</td>
    <td width="80%"><?=$period_name?></td>
  </tr>
</table>
<br />
<br />
<div  style="border-bottom:1px solid #000; border-top:1px solid #000;">
<table width="100%" border="0" cellspacing="0" cellpadding="4" style=" height:40px;">
  <tr>
    <td><strong>Group</strong></td>
    <td><strong>No Akun</strong></td>
    <td><strong>Nama Akun</strong></td>
    <td><strong>Debet </strong></td>
    <td><strong>Kredit</strong></td>
  </tr>
  </table>
 
  </div>
  <!-- AKTIVA -->
  <div style="border-bottom:1px solid #000";>
  <table width="100%" border="0" cellspacing="0" cellpadding="4" >
   <?php 
   $i1 = 1;
   foreach($data_coa1 as $item1): ?>
  <tr>
    <td width="16%"><strong>
      <?php if($i1 == 1){ echo "AKTIVA"; }?>
    </strong></td>
    <td width="22%"><?php echo $item1['coa_hierarchy'] ?></td>
    <td width="29%"><?php echo $item1['coa_name'] ?></td>
    <td width="16%" align="right">
    <?php
      $data_detail_coa1_debet = $this->report_neraca_model->get_detail($period_id, $item1['coa_id']);
	    foreach($data_detail_coa1_debet as $item1_debet): ?>
          
          <?php
		   echo number_format($item1_debet['debet'], 2) ?>
    		
			<?php endforeach; ?>
	  
    </td>
    <td width="17%" align="right">
    <?php
     $data_detail_coa1_credit = $this->report_neraca_model->get_detail($period_id, $item1['coa_id']);
	    foreach($data_detail_coa1_credit as $item1_credit): ?>
          
          <?php
		   echo number_format($item1_credit['credit'], 2) ?>
    		
			<?php endforeach; ?>
    </td>
  </tr>
  <?php 
  $i1++;
  endforeach; ?>
  </table>
  </div>
  <table width="100%" border="0" cellspacing="0" cellpadding="4" >
   
    <tr>
      <td width="16%">&nbsp;</td>
      <td width="22%">&nbsp;</td>
      <td width="29%"><strong>Total Aktiva</strong></td>
      <td width="16%" align="right"><strong>
        <?php
      $data_detail_total_coa1 = $this->report_neraca_model->get_detail_total($period_id, 1);
	   
		   echo number_format($data_detail_total_coa1[0], 2) ?>
      </strong></td>
      <td width="17%" align="right">
         <strong>
         <?php   
		   echo number_format($data_detail_total_coa1[1], 2) ?>
      </strong></td>
    </tr>
   
  </table>
</div>


  <!-- Hutang -->
  <div style="border-bottom:1px solid #000";>
  <table width="100%" border="0" cellspacing="0" cellpadding="4" >
   <?php 
   $i2 = 1;
   foreach($data_coa2 as $item2): ?>
  <tr>
    <td width="16%"><strong>
      <?php if($i2 == 1){ echo "HUTANG"; }?>
    </strong></td>
    <td width="22%"><?php echo $item2['coa_hierarchy'] ?></td>
    <td width="29%"><?php echo $item2['coa_name'] ?></td>
    <td width="16%" align="right">
    <?php
      $data_detail_coa2_debet = $this->report_neraca_model->get_detail($period_id, $item2['coa_id']);
	    foreach($data_detail_coa2_debet as $item2_debet): ?>
          
          <?php
		   echo number_format($item2_debet['debet'], 2) ?>
    		
			<?php endforeach; ?>
	  
    </td>
    <td width="17%" align="right">
    <?php
     $data_detail_coa2_credit = $this->report_neraca_model->get_detail($period_id, $item2['coa_id']);
	    foreach($data_detail_coa2_credit as $item2_credit): ?>
          
          <?php
		   echo number_format($item2_credit['credit'], 2) ?>
    		
			<?php endforeach; ?>
    </td>
  </tr>
  <?php 
  $i2++;
  endforeach; ?>
  </table>
  </div>
  <table width="100%" border="0" cellspacing="0" cellpadding="4" >
   
    <tr>
      <td width="16%">&nbsp;</td>
      <td width="22%">&nbsp;</td>
      <td width="29%"><strong>Total Aktiva</strong></td>
      <td width="16%" align="right"><strong>
        <?php
      $data_detail_total_coa2 = $this->report_neraca_model->get_detail_total($period_id, '2');
	   
		   echo number_format($data_detail_total_coa2[0], 2) ?>
      </strong></td>
      <td width="17%" align="right">
         <strong>
         <?php
     $data_detail_total_coa2 = $this->report_neraca_model->get_detail_total($period_id, '2');
		   echo number_format($data_detail_total_coa2[1], 2) ?>
      </strong></td>
    </tr>
   
  </table>
</div>


  <!-- MODAL -->
<div style="border-bottom:1px solid #000";>
  <table width="100%" border="0" cellspacing="0" cellpadding="4" >
   <?php 
   $i3 = 1;
   foreach($data_coa3 as $item3): ?>
  <tr>
    <td width="16%"><strong>
      <?php if($i3 == 1){ echo "MODAL"; }?>
    </strong></td>
    <td width="22%"><?php echo $item3['coa_hierarchy'] ?></td>
    <td width="29%"><?php echo $item3['coa_name'] ?></td>
    <td width="16%" align="right">
    <?php
      $data_detail_coa3_debet = $this->report_neraca_model->get_detail($period_id, $item3['coa_id']);
	    foreach($data_detail_coa3_debet as $item3_debet): ?>
          
          <?php
		   echo number_format($item3_debet['debet'], 2) ?>
    		
			<?php endforeach; ?>
	  
    </td>
    <td width="17%" align="right">
    <?php
     $data_detail_coa3_credit = $this->report_neraca_model->get_detail($period_id, $item3['coa_id']);
	    foreach($data_detail_coa3_credit as $item3_credit): ?>
          
          <?php
		   echo number_format($item3_credit['credit'], 2) ?>
    		
			<?php endforeach; ?>
    </td>
  </tr>
  <?php 
  $i3++;
  endforeach; ?>
  </table>
  </div>
<table width="100%" border="0" cellspacing="0" cellpadding="4" >
    <tr>
      <td width="16%">&nbsp;</td>
      <td width="22%">&nbsp;</td>
      <td width="29%"><strong>Total Aktiva</strong></td>
      <td width="16%" align="right"><strong>
        <?php
      $data_detail_total_coa3 = $this->report_neraca_model->get_detail_total($period_id, '3');
	   
		   echo number_format($data_detail_total_coa3[0], 2) ?>
      </strong></td>
      <td width="17%" align="right"><strong>
        <?php
     $data_detail_total_coa3 = $this->report_neraca_model->get_detail_total($period_id, '3');
		   echo number_format($data_detail_total_coa3[1], 2) ?>
      </strong></td>
    </tr>
  </table>
  <br />
  <table width="100%" border="0" cellspacing="0" cellpadding="4" >
    <tr>
      <td width="16%">&nbsp;</td>
      <td width="22%">&nbsp;</td>
      <td width="29%"><strong>TOTAL</strong></td>
      <td width="16%" align="right"><strong>
        <?php
      $data_grand_total = $this->report_neraca_model->get_grand_total($period_id);
	   
		   echo number_format($data_grand_total[0], 2) ?>
      </strong></td>
      <td width="17%" align="right"><strong>
        <?php
     $data_grand_total  = $this->report_neraca_model->get_grand_total($period_id);
		   echo number_format($data_grand_total[1], 2) ?>
      </strong></td>
    </tr>
  </table>

  </div>
