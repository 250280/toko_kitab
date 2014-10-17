
<!--
<script type="text/javascript" src="<?=base_url()?>assets/js/new_js/js_query.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/new_js/js_query2.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/new_js/plugins/morris/morris.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/new_js/AdminLTE/app.js"></script><br />
-->

<div class="row">
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-aqua">
                                <div class="inner">
                                    <h3>
                                        <?= $col1?>
                                    </h3>
                                    <p>
                                       Penjualan
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <a href="<?=base_url()?>sales_transaction_report" class="small-box-footer">
                                    Detail <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div><!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3>
                                       <sup style="font-size: 16px">Rp</sup><span style="font-size:26px;"> <?= number_format($col2, 2)?></span>
                                    </h3>
                                    <p>
                                        Omset
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                </div>
                                <a href="<?=base_url()?>flow_transaction" class="small-box-footer">
                                    Detail <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div><!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-yellow">
                                <div class="inner">
                                    <h3>
                                        <sup style="font-size: 16px">Rp</sup><span style="font-size:26px;"> <?= number_format($col3, 2)?></span>
                                    </h3>
                                    <p>
                                        Laba Kotor
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                </div>
                                <a href="<?=base_url()?>profit" class="small-box-footer">
                                    Detail <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div><!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-red">
                                <div class="inner">
                                    <h3>
                                        <?= $col4_jumlah?>
                                    </h3>
                                    <p>
                                        <?= $col4_name?>
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <a href="<?=base_url()?>schedule" class="small-box-footer">
                                    Detail <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div><!-- ./col -->
                    </div>



<div class="row">
                        <div class="col-md-6">
                            <!-- AREA CHART -->
                            <div class="box box-info">
                                <div class="box-header">
                                    <h3 class="box-title">Top 10 Produk</h3>
                                </div>
                                <div class="box-body chart-responsive">
                                    <?= $content1?>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                            
                        </div><!-- /.col (LEFT) -->
                        
                            <div class="col-md-6">
                            <!-- BAR CHART -->
                            <div class="box box-info">
                                <div class="box-header">
                                    <h3 class="box-title">Top 10 Pelanggan</h3>
                                </div>
                                <div class="box-body chart-responsive">
                                     <?= $content2?>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->

                        </div><!-- /.col (RIGHT) -->
                    </div>
                    
                    
                      <div class="row">
                        <div class="col-md-6">
                            <!-- AREA CHART -->
                            <div class="box box-info">
                                <div class="box-header">
                                    <h3 class="box-title">Stok Menipis</h3>
                                </div>
                                <div class="box-body chart-responsive">
                                    <?= $content3?>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                            
                        </div ><!-- /.col (LEFT) -->
                        
                        <div class="col-md-6">
                            <!-- AREA CHART -->
                            <div class="box box-info">
                                <div class="box-header">
                                    <h3 class="box-title">Top 10 Salesman</h3>
                                </div>
                                <div class="box-body chart-responsive">
                                    <?= $content6?>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                            
                        </div>
                       
                    </div><!-- /.row -->


<div class="row">
                        <div class="col-md-6">
                            <!-- AREA CHART -->
                            <div class="box box-info">
                                <div class="box-header">
                                    <h3 class="box-title">Laba Kotor</h3>
                                     <div style=" padding:10px; float:right;"> <?= "Periode ". $active_period_name?></div>
                                </div>
                                <div class="box-body chart-responsive">
                                    <?= $content5?>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                            
                        </div><!-- /.col (LEFT) -->
                        
                        
                        <div class="col-md-6">
                            <!-- BAR CHART -->
                         
                            <div class="box box-success">
                                <div class="box-header">
                                    <h3 class="box-title">Omset Bulanan </h3>
                                    <div style=" padding:10px; float:right;"> <?= "Periode ". $active_period_name?></div>
                                </div>
                                <div class="box-body chart-responsive">
                                    <?= $content4?>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->

                        </div><!-- /.col (RIGHT) -->
                    </div><!-- /.row -->
                    
                        
                  
<?php /*
<div class="row">
                        <div class="col-md-6">
                            <!-- AREA CHART -->
                            <div class="box box-primary">
                                <div class="box-header">
                                    <h3 class="box-title">Area Chart</h3>
                                </div>
                                <div class="box-body chart-responsive">
                                    <div class="chart" id="revenue-chart" style="height: 300px;"></div>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->

                            <!-- DONUT CHART -->
                            <div class="box box-danger">
                                <div class="box-header">
                                    <h3 class="box-title">Donut Chart</h3>
                                </div>
                                <div class="box-body chart-responsive">
                                    <div class="chart" id="sales-chart" style="height: 300px; position: relative;"></div>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                            
                        </div><!-- /.col (LEFT) -->
                        
                        <div class="col-md-6">
                            <!-- LINE CHART -->
                            <div class="box box-info">
                                <div class="box-header">
                                    <h3 class="box-title">Line Chart</h3>
                                </div>
                                <div class="box-body chart-responsive">
                                    <div class="chart" id="line-chart" style="height: 300px;"></div>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
								
                                  <!-- BAR CHART -->
                    		  <div class="box box-success">
                                <div class="box-header">
                                    <h3 class="box-title">Bar Chart</h3>
                                </div>
                                <div class="box-body chart-responsive">
                                    <div class="chart" id="bar-chart" style="height: 300px;"></div>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                          

                        </div><!-- /.col (RIGHT) -->
                    </div><!-- /.row -->
                    

				
                 <script type="text/javascript">
            $(function() {
                "use strict";

                // AREA CHART
                var area = new Morris.Area({
                    element: 'revenue-chart',
                    resize: true,
                    data: [
                        {y: '2011 Q1', item1: 2666, item2: 2666},
                        {y: '2011 Q2', item1: 2778, item2: 2294},
                        {y: '2011 Q3', item1: 4912, item2: 1969},
                        {y: '2011 Q4', item1: 3767, item2: 3597},
                        {y: '2012 Q1', item1: 6810, item2: 1914},
                        {y: '2012 Q2', item1: 5670, item2: 4293},
                        {y: '2012 Q3', item1: 4820, item2: 3795},
                        {y: '2012 Q4', item1: 6000, item2: 5967},
                        {y: '2013 Q1', item1: 10687, item2: 4460},
                        {y: '2013 Q2', item1: 8432, item2: 5713}
                    ],
                    xkey: 'y',
                    ykeys: ['item1', 'item2'],
                    labels: ['Item 1', 'Item 2'],
                    lineColors: ['#a0d0e0', '#3c8dbc'],
                    hideHover: 'auto'
                });

                // LINE CHART
                var line = new Morris.Line({
                    element: 'line-chart',
                    resize: true,
                    data: [
                        {y: '2010', item1: 2666},
                        {y: '2011', item1: 2778},
                        {y: '2012', item1: 4912},
                        {y: '2013', item1: 3767},
                        {y: '2014', item1: 6810},
                        {y: '2015', item1: 5670},
                        {y: '2016', item1: 4820},
                        {y: '2017', item1: 15073},
                        {y: '2018', item1: 10687},
                        {y: '2019', item1: 8432}
                    ],
                    xkey: 'y',
                    ykeys: ['item1'],
                    labels: ['Item 1'],
                    lineColors: ['#3c8dbc'],
                    hideHover: 'auto'
                });

                //DONUT CHART
                var donut = new Morris.Donut({
                    element: 'sales-chart',
                    resize: true,
                    colors: ["#3c8dbc", "#f56954", "#00a65a", "#ffc"],
                    data: [
                        {label: "Download Sales", value: 12},
                        {label: "In-Store Sales", value: 30},
                        {label: "Mail-Order Sales", value: 20},
						{label: "Mail-Order Sales", value: 20}
                    ],
                    hideHover: 'auto'
                });
            
            });
        </script>
		*/
?>