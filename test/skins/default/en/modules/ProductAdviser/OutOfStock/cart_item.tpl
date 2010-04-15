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
<table border=0 cellpadding=1 cellspacing=1 IF="productNotificationEnabled&rejectedItem&rejectedItem.key=item.key">
	<tr>
		<td class="ErrorMessage" style="FONT-WEIGHT: normal;">You can only purchase <b>{rejectedItem.amount}</b> items of this product now due to low stock amount.</td>
	</tr>
	<tr>
		<td>(<a href="javascript: NotifyMe()"><u>Notify me</u></a> when the stock amount of the product increases)</td>
	</tr>
</table>
