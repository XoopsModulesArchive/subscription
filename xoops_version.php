<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//
//	Third Eye Software, Inc.
//	http://products.thirdeyesoftware.com
//	jeff blau
//  ------------------------------------------------------------------------ //
if (!defined('XOOPS_ROOT_PATH')) {
	die('XOOPS root path not defined');
}

$modversion['name'] = _MI_SUBSCRIPTION_NAME;
$modversion['version'] = .96;
$modversion['description'] = _MI_SUBSCRIPTION_DESC;
$modversion['author'] = "Jeff Blau ( http://products.thirdeyesoftware.com/ )";
$modversion['credits'] = "";
$modversion['help']        = 'page=help';
$modversion['license']     = 'GNU GPL 2.0 or later';
$modversion['license_url'] = "www.gnu.org/licenses/gpl-2.0.html/";
$modversion['official'] = 0;
$modversion['image'] = "images/slogo_subscription.png";
$modversion['dirname'] = "subscription";
$modversion['namemod'] = "Subscriptions";

$modversion['dirmoduleadmin'] = '/Frameworks/moduleclasses/moduleadmin';
$modversion['icons16']        = '../../Frameworks/moduleclasses/icons/16';
$modversion['icons32']        = '../../Frameworks/moduleclasses/icons/32';
//about
$modversion['release_date']        = '2013/01/05';
$modversion["module_website_url"]  = "www.xoops.org";
$modversion["module_website_name"] = "XOOPS";
$modversion["module_status"]       = "Beta 1";
$modversion['min_php']             = '5.2';
$modversion['min_xoops']           = "2.5.5";
$modversion['min_admin']           = '1.1';
$modversion['min_db']              = array(
    'mysql'  => '5.0.7',
    'mysqli' => '5.0.7'
);

// Sql file (must contain sql generated by phpMyAdmin or phpPgAdmin)
// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
//$modversion['sqlfile']['postgresql'] = "sql/pgsql.sql";

// Tables created by sql file (without prefix!)
$modversion['tables'][0] = "subscription_interval";
$modversion['tables'][1] = "subscription_type";
$modversion['tables'][2] = "subscription_transaction";
$modversion['tables'][3] = "subscription_gateway_config";
$modversion['tables'][4] = "subscription_user";
$modversion['tables'][5] = "subscription";
$modversion['tables'][6] = "sequences";

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['system_menu'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

$modversion['templates'][1]['file'] = 'subscription_index.html';
$modversion['templates'][1]['description'] = 'subscription template';
$modversion['templates'][2]['file'] = 'payment_index.html';
$modversion['templates'][2]['description'] = 'payment template';
$modversion['templates'][3]['file'] = 'payment_success.html';
$modversion['templates'][3]['description'] = 'payment success template';
$modversion['templates'][4]['file'] = 'payment_error.html';
$modversion['templates'][4]['description'] = 'payment error template';

$modversion['templates'][5]['file'] = 'subscription_admin_subscriptions.html';
$modversion['templates'][5]['description'] = 'subscription admin template';
$modversion['templates'][6]['file'] = 'subscription_admin_edit_subscription.html';
$modversion['templates'][6]['description'] = 'subscription admin edit template';
$modversion['templates'][7]['file'] = 'subscription_admin_tx.html';
$modversion['templates'][7]['description'] = 'subscription admin trans template';
$modversion['templates'][8]['file'] = 'subscription_admin_tx_detail.html';
$modversion['templates'][8]['description'] = 'subscription admin tx detail template';
$modversion['templates'][9]['file'] = 'subscription_cancel.html';
$modversion['templates'][9]['description'] = 'subscription cancel form';

$modversion['templates'][10]['file'] = 'subscription_admin_subscription_types.html';
$modversion['templates'][10]['description'] = 'subscription admin sub types';

$modversion['templates'][11]['file'] = 'subscription_admin_edit_subscription_type.html';
$modversion['templates'][11]['description'] = 'subscription admin sub types';

$modversion['templates'][12]['file'] =
		'subscription_admin_edit_subscription_interval.html';
$modversion['templates'][12]['description'] = 'subscription admin sub intervals';

$modversion['templates'][13]['file'] =
		'subscription_admin_currentsubs.html';
$modversion['templates'][13]['description'] = 'subscription admin current subs';

$modversion['templates'][14]['file'] =
		'subscription_admin_index.html';
$modversion['templates'][14]['description'] = 'subscription admin index';

// blocks
$modversion['blocks'][1]['file'] = "xoopssubscription.php";
$modversion['blocks'][1]['name'] = "Subscription Menu";
$modversion['blocks'][1]['description'] = "Subscriptions Menu";
$modversion['blocks'][1]['show_func'] = "b_xoopssubscription_show";
$modversion['blocks'][1]['template'] = "xoopssubscription_block.html";

// Menu
$modversion['hasMain'] = 1;
$modversion['sub'][1]['name'] = _MI_SUBSCRIPTION_SMNAME1;
$modversion['sub'][1]['url'] = "index.php";
$modversion['sub'][2]['name'] = _MI_SUBSCRIPTION_SMNAME2;
$modversion['sub'][2]['url'] = "cancelsubscription.php";

// config
$modversion['config'][1]['name'] = 'gateway';
$modversion['config'][1]['title'] = '_MI_ACTIVE_GATEWAY';
$modversion['config'][1]['description'] = '_MI_ACTIVE_GATEWAY_DESC';
$modversion['config'][1]['formtype'] = 'select';
$modversion['config'][1]['options'] = array('default'=>'default');
$modversion['config'][1]['valuetype'] = 'text';
$modversion['config'][1]['default'] = 'default';

$modversion['config'][2]['name'] = 'currency';
$modversion['config'][2]['title'] = '_MI_DEFAULT_CURRENCY';
$modversion['config'][2]['description'] = '_MI_DEFAULT_CURRENCY_DESC';
$modversion['config'][2]['formtype'] = 'select';
$modversion['config'][2]['options'] = array('USD'=>'USD','EUR'=>'EUR','GBP'=>'GBP','JPY'=>'JPY','CAD'=>'CAD');
$modversion['config'][2]['valuetype'] = 'text';
$modversion['config'][2]['default'] = 'USD';

$modversion['config'][3]['name'] = 'delayed_capture';
$modversion['config'][3]['title'] = '_MI_DELAYED_CAPTURE';
$modversion['config'][3]['description'] = '_MI_DELAYED_CAPTURE_DESC';
$modversion['config'][3]['formtype'] = 'select';
$modversion['config'][3]['options'] = array('YES'=>'Y','NO'=>'N');
$modversion['config'][3]['valuetype'] = 'text';
$modversion['config'][3]['default'] = 'N';

$modversion['config'][4]['name'] = 'ssl_enabled';
$modversion['config'][4]['title'] = '_MI_SSL_ENABLED';
$modversion['config'][4]['description'] = '_MI_SSL_ENABLED_DESC';
$modversion['config'][4]['formtype'] = 'select';
$modversion['config'][4]['options'] = array('YES'=>'Y','NO'=>'N');
$modversion['config'][4]['valuetype'] = 'text';
$modversion['config'][4]['default'] = 'N';


// Comments
$modversion['hasComments'] = 0;
?>
