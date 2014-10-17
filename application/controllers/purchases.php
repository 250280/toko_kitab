<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Purchases extends CI_Controller {


	var $title = "Pembelian";
	var	$nav_link = "menu > Pembelian";
	var $module_id = 37;

	function __construct(){
		parent::__construct();
		$this->load->model('purchase_model');
		$this->load->library('modul');
	}


	public function index()
	{
		$title = $this->title;
		$sub_title = "Pembelian";
		$nav_link = $this->nav_link;
		$module_id = $this->module_id;
		$data['dump'] = '';
		$data['product'] = $this->global_model->select_products();


		$this->renderpage->render('app/dashboard/purchases/form',$data,$title, $sub_title, $nav_link, $module_id);
	}

	function dump_table(){
		session_start();
		$id = $this->input->post('product_id');
		$qtt = $this->input->post('qtt');
		$price = $this->input->post('price');
		$total = $this->input->post('total');
		$description = $this->input->post('description');

		$_SESSION['product'][$id] = array ('qtt' => $qtt, 'price' => $price, 'total' => $total, 'description' => $description );

	$result =			"<table id='example1' class='table table-bordered table-striped'>
                        <thead>
                            <tr>
                                <th>Product name</th>
                                <th>Kwalitas</th>
                                <th>Qtt</th>
                                <th>harga</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>";



		 while (list( $key, $data) = each( $_SESSION['product']) ){
		 			$result .= "<tr>";
		 		$data_product =  $this->purchase_model->list_data_dump($key);

		 				$result .= "<td> ".$data_product['product_name']." </td>"; 

		 				$result .= "<td> ".$data_product['product_quality_name']." </td>"; 
		 				$result .= "<td> ".$data['qtt']." </td>"; 
		 					$result .= "<td> ".$data['price']." </td>"; 
		 				$result .= "<td> ".$data['total']." </td>"; 

		 			$result .= "</tr>";

		 }

                   $result .= "			</tbody>
                   					</table>
               					 </div>";

             echo $result;
	}

	function cancel(){
		session_start();
		unset($_SESSION['product']);
		redirect('');
	}


	function insert_data(){
		session_start();
		// create 2 sess
		$ses_price = $_SESSION['product'];
		$ses_detail = $_SESSION['product'];


		//get transaction_code
			$data_code['code']    = "P";
			$data_code['table']   = 'transactions';
			$data_code['column']  = 'transaction_code';
			$data_code['where']   = 'transaction_type_id = 1';
			$be_code = $this->modul->get_code($data_code);

		//  transasction's table
			//get total price
				$total_price = 0;
				 while (list( $id, $data) = each( $ses_price ) ){

				 	$total_price = $total_price + $data['total'];

				 }


			//insert to master transactions
				 $data_master['transaction_total_price'] = $total_price;
				 $data_master['transaction_type_id'] 	 = 1;
				 $data_master['transaction_code'] 		 = $be_code;
				 $data_master['transaction_date'] 		 = date("Y-m-d h:i:s");
				 $data_master['stand_id']				 = $this->input->post('stand_id');
				 $data_master['transaction_status']		 = $this->input->post('transaction_status');
				 $data_master['transaction_description'] = $this->input->post('transaction_description');
				 $myid = $this->purchase_model->insert_master($data_master);



				 // insert to detail
 				 while (list( $id_d, $data_d) = each( $ses_detail  ) ){

 				 	$data_detail['transaction_id'] = $myid;
 				 	$data_detail['product_id'] = $id_d;
 				 	$data_detail['transaction_detail_qty'] = $data_d['qtt'];
 				 	$data_detail['transaction_detail_purchase_price'] = $data_d['price'];
 				 	$data_detail['transaction_detail_total_price'] = $data_d['total'];
 				 	$data_detail['transaction_detail_description'] = $data_d['description'];

 				 	$product_stock = $data_d['qtt'];
 				 	$ids['stand_id'] = $this->input->post('stand_id');
 				 	$ids['product_id'] = $id_d;
 				 	$this->purchase_model->update_product_stock($product_stock,$ids);


 				 	 $this->purchase_model->insert_detail($data_detail);

				 }


		$_SESSION['product'] = '';
		unset($_SESSION['product']);
	}

}

/* End of file purchases.php */
/* Location: ./application/controllers/purchases.php */