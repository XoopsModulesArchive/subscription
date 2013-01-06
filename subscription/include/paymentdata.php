<?php

class PaymentData {
	var $id;
	var $uid;
	var $subid;

	var $cardNumber;
	var $nameOncard;
	var $address1;
	var $address2;
	var $city;
	var $state;
	var $zipcode;
	var $countrycode;
	var $expirationMonth;
	var $expirationYear;
	var $cvv;
	var $issuerPhone;
	var $amount;
	var $invoiceNumber;
	var $txType;

	function PaymentData(
			$cardNumber,
			$name, 
			$address1,
			$address2,
			$city,
			$state,
			$zipcode,
			$countrycode,
			$month,
			$year,
			$cvv,
			$issuerphone,
			$amount,
			$invoicenumber,
			$txtype) {

		$this->cardNumber = $cardNumber;
		$this->nameOnCard = $name;
		$this->address1 = $address1;
		$this->address2 = $address2;
		$this->city = $city;
		$this->state = $state;
		$this->zipcode = $zipcode;
		$this->countrycode= $countrycode;
		$this->cvv = $cvv;
		$this->issuerPhone = $issuerphone;
		$this->amount = $amount;
		$this->invoiceNumber = $invoicenumber;
		$this->expirationMonth = $month;
		$this->expirationYear = $year;
		$this->txType = $txtype;
	}

	function toString() {
		return "PaymentData(" .
			"cardnum=" . $this->cardNumber . "," .
			"name=" . $this->nameOnCard . "," . 
			"address1=" . $this->address1 . "," . 
			"address2=" . $this->address2 . "," . 
			"city=" . $this->city . "," . 
			"state=" . $this->state . "," . 
			"zipcode=" . $this->zipcode . "," . 
			"countrycode=" . $this->countrycode. "," . 
			"cvv=" . $this->cvv . "," . 
			"issuerPhone=" . $this->issuerPhone . "," . 
			"invoicenumber=" . $this->invoiceNumber . "," . 
			"txtype=" . $this->txType. "," . 
			"id=" . $this->id . "," .
			"uid=" . $this->uid . "," .
			"subid=" . $this->subid . "," .
			"amount=" . $this->amount . ")";
	}
}

?>


