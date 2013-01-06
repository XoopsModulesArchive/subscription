<?php
if (!defined("SUB_LISTS_INCLUDED")) {
	define("SUB_LISTS_INCLUDED", 1);

	class SubscriptionLists {
		function &getSubscriptionTypeList($psid) {
			$db = &XoopsDatabaseFactory::getDatabaseConnection();
			$sql = "select subtypeid, type from " . $db->prefix("subscription_type");
			if (isset($psid)) {
				 $sql .= " where psid = $psid";
			}
			$sql .= " order by type asc";
			$ret = array();
			$result = $db->query($sql);
			$ret[0] = 'None';
			while ($row = $db->fetchArray($result)) {
				$ret[$row['subtypeid']] = $row['type'];
			}
			return $ret;
		}	
		function &getSubscriptionIntervalList() {
			$db = &XoopsDatabaseFactory::getDatabaseConnection();
			$sql = "select subintervalid, name from " . $db->prefix("subscription_interval") . 
				" order by orderbit asc";
			$ret = array();
			$result = $db->query($sql);
			while ($row = $db->fetchArray($result)) {
				$ret[$row['subintervalid']] = $row['name'];
			}
			return $ret;
		}

		function &getSubscriptionList() {
			$db = &XoopsDatabaseFactory::getDatabaseConnection();
			$sql = "select subid, name, type from " . $db->prefix("subscription") . 
				"," . $db->prefix("subscription_type") . " where " .
				$db->prefix("subscription") . ".subtypeid = " .
				$db->prefix("subscription_type") . ".subtypeid" .
				" order by orderbit asc";
			$ret = array();
			$result = $db->query($sql);
			while ($row = $db->fetchArray($result)) {
				$ret[$row['subid']] = ($row['name'] . " " . $row['type']);
			}
			return $ret;
		}

		function &getGatewayList() {
			global $xoopsModule;
			$ret = array();
			$db = &XoopsDatabaseFactory::getDatabaseConnection();
			$sql = "select conf_id , conf_value from " . $db->prefix("config") . 
					" where conf_name = 'gateway' and conf_modid = " .
					$xoopsModule->getVar('mid');
			$result = $db->query($sql);
			list($conf_id, $current_value) = $db->fetchRow($result);
			if (empty($conf_id)) {
				return $ret;
			}

			$sql = "select confop_name, confop_value from " . $db->prefix("configoption") . 
					" where conf_id = $conf_id";
			$result = $db->query($sql);
			while(list($conf_name, $conf_value) = $db->fetchRow($result)) {
				$ret[$conf_name] = $conf_value;
			}
			return $ret;
			
		}

	} //end class def	
}

