<?php
	/**
		-
	*/

	class GetPayment extends dbConnect {

		private $sd = null;

		function __construct( $method = 1 ) {
			$this->md = (isset($_POST['md']))? 2 : $method ;
			// Make sure we actually have a text to reply to
			if (!$this->inboundText()) {
				return false;
			}
			
			return $this->insPayment();
		}               

		public function inboundText(){
			if(!$this->sd){ $this->sd = ($this->md == 1)? $_GET : $_POST ; }
			if(!isset($this->sd["SUPER_TYPE"], $this->sd["TYPE"], $this->sd["RECEIPT"], $this->sd["TIME"], $this->sd["PHONE"], $this->sd["NAME"], $this->sd["AMOUNT"])){ return false ; }

			return true;
		}

		function ckPayment(){

			$sql_array  = array( $this->sd["RECEIPT"] );
			$sql_texts  = "SELECT * FROM `tbl_payment` WHERE tbl_payment.RECEIPT = ? LIMIT 1;";

			$result = $this->sqlQuery(PR_DATABASE, PR_USERNAME, 'select', $sql_texts, $sql_array);
			if(isset($result[0]['ID'])){
				$this->dt = $result[0];
				return 1;
			}

			return 0;
		}

		function insPayment(){

			$sql_array  = array( $this->sd["SUPER_TYPE"], $this->sd["TYPE"], $this->sd["RECEIPT"], $this->sd["TIME"], $this->sd["PHONE"], $this->sd["NAME"], $this->sd["AMOUNT"] );
			$sql_texts  = "INSERT INTO tbl_payment( SUPER_TYPE, TYPE, RECEIPT, `TIME`, PHONE, `NAME`, AMOUNT ) VALUES( ?, ?, ?, ?, ?, ?, ? );";

			if($this->ckPayment() > 0){ return 0; }
			$result = $this->sqlQuery(PR_DATABASE, PR_USERNAME, 'insert', $sql_texts, $sql_array);
			if($result > 0){
				$this->ec = json_encode(array("status" => "success", "errorno" => 0, "data" => array('msg' => 'Transaction Success')));
				return 1;
			}

			$this->ec = json_encode(array("status" => "failed", "errorno" => 1, "data" => array('msg' => 'Transaction Failed')));
			return 0;
		}

		function ouTput(){

			header("Content-type: application/json; charset=utf-8");

			if (isset($this->ec)) {
				echo($this->ec);
			} else {
				echo(json_encode(array("status" => "failed", "errorno" => 2, "data" => array('msg' => 'Invalid Transaction'))));
			}
		}
	}
?>