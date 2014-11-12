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
      <td width="29%"><strong>Total Hutang</strong></td>
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
      <td width="29%"><strong>Total Modal</strong></td>
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
    <!-- PENDAPATAN -->
<div style="border-bottom:1px solid #000";>
  <table width="100%" border="0" cellspacing="0" cellpadding="4" >
   <?php 
   $i4 = 1;
   foreach($data_coa4 as $item4): ?>
  <tr>
    <td width="16%"><strong>
      <?php if($i4 == 1){ echo "PENDAPATAN"; }?>
    </strong></td>
    <td width="22%"><?php echo $item4['coa_hierarchy'] ?></td>
    <td width="29%"><?php echo $item4['coa_name'] ?></td>
    <td width="16%" align="right">
    <?php
      $data_detail_coa4_debet = $this->report_neraca_model->get_detail($period_id, $item4['coa_id']);
	    foreach($data_detail_coa4_debet as $item4_debet): ?>
          
          <?php
		   echo number_format($item4_debet['debet'], 2) ?>
    		
			<?php endforeach; ?>
	  
    </td>
    <td width="17%" align="right">
    <?php
     $data_detail_coa4_credit = $this->report_neraca_model->get_detail($period_id, $item4['coa_id']);
	    foreach($data_detail_coa4_credit as $item4_credit): ?>
          
          <?php
		   echo number_format($item4_credit['credit'], 2) ?>
    		
			<?php endforeach; ?>
    </td>
  </tr>
  <?php 
  $i4++;
  endforeach; ?>
  </table>
  </div>
  <table width="100%" border="0" cellspacing="0" cellpadding="4" >
    <tr>
      <td width="16%">&nbsp;</td>
      <td width="22%">&nbsp;</td>
      <td width="29%"><strong>Total Pendapatan</strong></td>
      <td width="16%" align="right"><strong>
        <?php
      $data_detail_total_coa4 = $this->report_neraca_model->get_detail_total($period_id, '4');
	   
		   echo number_format($data_detail_total_coa4[0], 2) ?>
      </strong></td>
      <td width="17%" align="right"><strong>
        <?php
     $data_detail_total_coa4 = $this->report_neraca_model->get_detail_total($period_id, '4');
		   echo number_format($data_detail_total_coa4[1], 2) ?>
      </strong></td>
    </tr>
  </table>
  <!-- HPP -->
<div style="border-bottom:1px solid #000";>
  <table width="100%" border="0" cellspacing="0" cellpadding="4" >
   <?php 
   $i5 = 1;
   foreach($data_coa5 as $item5): ?>
  <tr>
    <td width="16%"><strong>
      <?php if($i5 == 1){ echo "HPP"; }?>
    </strong></td>
    <td width="22%"><?php echo $item5['coa_hierarchy'] ?></td>
    <td width="29%"><?php echo $item5['coa_name'] ?></td>
    <td width="16%" align="right">
    <?php
      $data_detail_coa5_debet = $this->report_neraca_model->get_detail($period_id, $item5['coa_id']);
	    foreach($data_detail_coa5_debet as $item5_debet): ?>
          
          <?php
		   echo number_format($item5_debet['debet'], 2) ?>
    		
			<?php endforeach; ?>
	  
    </td>
    <td width="17%" align="right">
    <?php
     $data_detail_coa5_credit = $this->report_neraca_model->get_detail($period_id, $item4['coa_id']);
	    foreach($data_detail_coa5_credit as $item5_credit): ?>
          
          <?php
		   echo number_format($item5_credit['credit'], 2) ?>
    		
			<?php endforeach; ?>
    </td>
  </tr>
  <?php 
  $i5++;
  endforeach; ?>
  </table>
  </div>
  <table width="100%" border="0" cellspacing="0" cellpadding="4" >
    <tr>
      <td width="16%">&nbsp;</td>
      <td width="22%">&nbsp;</td>
      <td width="29%"><strong>Total Hpp</strong></td>
      <td width="16%" align="right"><strong>
        <?php
      $data_detail_total_coa5 = $this->report_neraca_model->get_detail_total($period_id, '5');
	   
		   echo number_format($data_detail_total_coa5[0], 2) ?>
      </strong></td>
      <td width="17%" align="right"><strong>
        <?php
     $data_detail_total_coa5 = $this->report_neraca_model->get_detail_total($period_id, '5');
		   echo number_format($data_detail_total_coa5[1], 2) ?>
      </strong></td>
    </tr>
  </table>
  <!-- Biaya -->
<div style="border-bottom:1px solid #000";>
  <table width="100%" border="0" cellspacing="0" cellpadding="4" >
   <?php 
   $i6 = 1;
   foreach($data_coa6 as $item6): ?>
  <tr>
    <td width="16%"><strong>
      <?php if($i6 == 1){ echo "BIAYA"; }?>
    </strong></td>
    <td width="22%"><?php echo $item6['coa_hierarchy'] ?></td>
    <td width="29%"><?php echo $item6['coa_name'] ?></td>
    <td width="16%" align="right">
    <?php
      $data_detail_coa6_debet = $this->report_neraca_model->get_detail($period_id, $item6['coa_id']);
	    foreach($data_detail_coa6_debet as $item6_debet): ?>
          
          <?php
		   echo number_format($item6_debet['debet'], 2) ?>
    		
			<?php endforeach; ?>
	  
    </td>
    <td width="17%" align="right">
    <?php
     $data_detail_coa6_credit = $this->report_neraca_model->get_detail($period_id, $item6['coa_id']);
	    foreach($data_detail_coa6_credit as $item6_credit): ?>
          
          <?php
		   echo number_format($item6_credit['credit'], 2) ?>
    		
			<?php endforeach; ?>
    </td>
  </tr>
  <?php 
  $i6++;
  endforeach; ?>
  </table>
  </div>
  <table width="100%" border="0" cellspacing="0" cellpadding="4" >
    <tr>
      <td width="16%">&nbsp;</td>
      <td width="22%">&nbsp;</td>
      <td width="29%"><strong>Total Biaya</strong></td>
      <td width="16%" align="right"><strong>
        <?php
      $data_detail_total_coa6 = $this->report_neraca_model->get_detail_total($period_id, '6');
	   
		   echo number_format($data_detail_total_coa6[0], 2) ?>
      </strong></td>
      <td width="17%" align="right"><strong>
        <?php
     $data_detail_total_coa6 = $this->report_neraca_model->get_detail_total($period_id, '6');
		   echo number_format($data_detail_total_coa6[1], 2) ?>
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
