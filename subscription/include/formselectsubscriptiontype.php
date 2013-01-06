<?php
/**
 * lists of values
 */
include_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') .
		"/include/lists.php";
/**
 * base class
 */
include_once XOOPS_ROOT_PATH."/class/xoopsform/formselect.php";

class XoopsFormSelectSubscriptionType  extends XoopsFormSelect
{
	function XoopsFormSelectSubscriptionType($caption, $name, $value=null,
			$size=1,$psid = null) {
		$this->XoopsFormSelect($caption, $name, $value, $size);
		if (isset($psid)) {
			$this->addOptionArray(SubscriptionLists::getSubscriptionTypeList($psid));
		} else {
			$this->addOptionArray(SubscriptionLists::getSubscriptionTypeList(null));
		}

	
	}
}
?>
