<?php

class PaymentResponse {

	var $responseMessage;
	var $responseCode;
	var $referenceNumber;
	var $authCode;

	var $CODE_SUCCESS = 0;
	var $CODE_FAILURE = 1;

	function PaymentResponse(
			$responsecode,
			$authcode,
			$responsemessage,
			$referencenumber = null) {

		$this->responseCode = $responsecode;
		$this->authCode = $authcode;
		$this->responseMessage = $responsemessage;
		$this->referenceNumber = $referencenumber;
	}

	function toString() {
		return "PaymentResponse(" .
			"authcode=" . $this->authCode . "," .
			"responseCode=" . $this->responseCode . "," .
			"responseMessage=" . $this->responseMessage. "," . 
			"referenceNumber=" . $this->referenceNumber. ")";
	}
}

?>


