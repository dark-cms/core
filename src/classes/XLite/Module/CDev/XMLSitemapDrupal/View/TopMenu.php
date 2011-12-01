<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the GNU General Pubic License (GPL 2.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-2.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * PHP version 5.3.0
 * 
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Pubic License (GPL 2.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.12
 */

namespace XLite\Module\CDev\XMLSitemapDrupal\View;

/**
 * Top menu 
 * 
 * @see   ____class_see____
 * @since 1.0.12
 */
abstract class TopMenu extends \XLite\View\TopMenu implements \XLite\Base\IDecorator
{
    /**
     * getViewListChildren
     *
     * @param string $list List name
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getViewListChildren($list)
    {
        $data = parent::getViewListChildren($list);

        if ('menu.settings' == $list) {
            foreach ($data as $i => $widget) {
                if ('modules/CDev/XMLSitemap/menu.tpl' == $widget->getTpl()) {
                    unset($data[$i]);
                }
            }
        }

        return $data;
    }
}

