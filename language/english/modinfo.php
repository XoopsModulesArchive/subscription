<?php

// The name of this module
define("_MI_SUBSCRIPTION_NAME","Subscriptions");

// A brief description of this module
define("_MI_SUBSCRIPTION_DESC","Member Subscriptions");

// submenu labels.
define("_MI_SUBSCRIPTION_SMNAME1","Upgrade Subscription");
define("_MI_SUBSCRIPTION_SMNAME2","Cancel Subscription");

// Names of admin menu items
define("_MI_SUBSCRIPTION_ADMIN_MENU_SUBSCRIPTION_INTERVALS",
		"Manage Intervals");
define("_MI_SUBSCRIPTION_ADMIN_MENU_SUBSCRIPTION_TYPES","Manage Types");
define("_MI_SUBSCRIPTION_ADMIN_MENU_SUBSCRIPTIONS","Manage Subscriptions");
define("_MI_SUBSCRIPTION_ADMIN_MENU_GATEWAYS","Manage Payment Gateways");
define("_MI_SUBSCRIPTION_ADMIN_MENU_TRANSACTIONS","Payment Transactions Report");
define("_MI_SUBSCRIPTION_ADMIN_MENU_SUBS","Subscriptions Report");
define("_MI_SUBSCRIPTION_ADMIN_MENU_REMINDERS","Send Expiration Reminders");
define("_MI_SUBSCRIPTION_ADMIN_MENU_CRON","Auto-renew Subscriptions");

define("_MI_ACTIVE_GATEWAY", "Active Gateway");
define("_MI_ACTIVE_GATEWAY_DESC", "Payment gateway currently in use.");

define("_MI_DEFAULT_CURRENCY", "Default Currency");
define("_MI_DEFAULT_CURRENCY_DESC", "Default currency for payments");

define("_MI_DELAYED_CAPTURE", "Delayed Capture");
define("_MI_DELAYED_CAPTURE_DESC", "Manually approve payments before capturing " . 
		"funds - Direct Gateways Only");

define("_MI_SSL_ENABLED", "SSL Enabled");
define("_MI_SSL_ENABLED_DESC", "SSL Enabled for Direct Payments");
?>
