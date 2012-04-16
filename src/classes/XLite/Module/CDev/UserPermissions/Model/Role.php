<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * PHP version 5.3.0
 * 
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.17
 */

namespace XLite\Module\CDev\UserPermissions\Model;

/**
 * Role 
 * 
 * @see   ____class_see____
 * @since 1.0.17
 *
 * @MappedSuperclass
 */
abstract class Role extends \XLite\Model\Role implements \XLite\Base\IDecorator
{
    /**
     * Enabled
     *
     * @var   boolean
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="boolean")
     */
    protected $enabled = true;

    /**
     * Check - specified permission is allowed or not
     *
     * @param string $code Permission code
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function isPermissionAllowed($code)
    {
        return $this->getEnabled() && parent::isPermissionAllowed($code);
    }

    /**
     * Check - role is permanent (unremovable and foreave enable) or not
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function isPermanentRole()
    {
        return $this->getId() == $this->getRepository()->getPermanentRole()->getId();
    }
}

