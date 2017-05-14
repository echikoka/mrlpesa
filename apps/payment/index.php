<?php
	$app->post('/get/payment/{api_key}', function( $request, $response, $arr ) {

		#	--------------------------------------------------------------
		#	-----------------	POINT TO QUERY DB  	----------------------
		if(file_exists('./apps/Connect/index.php')){
			require_once './apps/Connect/index.php';
		}
		#	--------------------------------------------------------------
		if(defined('DB_LOCATION')){
			if(isset($arr['api_key'])){

				#----------------------------------------------------------------------------------
				$payment = new GetPayment();
				$outpu = $payment->ouTput();
				#----------------------------------------------------------------------------------

			}

		}
	});
?>
