<?php

/**
 * make sure this is only included once!
 */
if ( !defined("XOOPS_SUB_PAYMENT_GATEWAY") ) {
	define("XOOPS_SUB_PAYMENT_GATEWAY",1);

/**
 * Abstract base class for Database access classes
 * 
 * @abstract
 * 
 * @author Kazumi Ono <onokazu@xoops.org>
 * @copyright copyright (c) 2000-2003 XOOPS.org
 * 
 * @package kernel
 * @subpackage database
 */
class PaymentGateway {

		/**
		 * reference to a {@link XoopsLogger} object
         * @see XoopsLogger
		 * @var object XoopsLogger
		 */
		var $logger;

		var $indirectUrl;
		var $cancelUrl;
		var $config;
		var $delayedCapture;

		/**
		 * constructor
         * 
         * will always fail, because this is an abstract class!
		 */
		function PaymentGateway()
		{
			// exit("Cannot instantiate this class directly");
		}

		/**
		 * assign a {@link XoopsLogger} object to the payment gateway
		 * 
         * @see XoopsLogger
         * @param object $logger reference to a {@link XoopsLogger} object
		 */
		function setLogger(&$logger)
		{
			$this->logger =& $logger;
		}

		function submitPayment($paymentDetails) {
		}

		function isDirect() {
		}

		function setConfig($cfg) {
			$this->config = $cfg;
		}

		function setDelayedCapture($dc) {
			$this->delayedCapture = $dc;
		}
	}	
}

?>
