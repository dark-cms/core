{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<widget module="AdvancedSecurity" target="create_order" template="common/dialog.tpl" body="modules/AdvancedSecurity/master_password.tpl" head="Master password">
<widget module="AOM" class="XLite_View_Tabber" target="create_order,order" body="{pageTemplate}" switch="page">
<widget module="AOM" target="order_statuses" template="common/dialog.tpl" body="modules/AOM/order_statuses.tpl" head="Order Statuses">
