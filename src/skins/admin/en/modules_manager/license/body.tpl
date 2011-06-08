{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

{* :TODO: divide into parts *}

<div class="module-license">

  <div class="form">

    <div class="license-block">

        <table>
          <tr>
            <td class="license-text">
              <textarea class="license-area" id="license-area" readonly="readonly">
              {getLicense()}
              </textarea>
            </td>
            <td class="switch-button">
              <widget class="\XLite\View\Button\SwitchButton" first="makeSmallHeight" second="makeLargeHeight" />
            </td>
          </tr>
        </table>

      </div>

      <table class="agree">
        <tr>
          <td>
            <input type="checkbox" id="agree" name="agree" value="Y" checked="checked" />
            <label for="agree">{t(#Yes, I agree with License agreement#)}</label>
          </td>
        </tr>
      </table>

      <table class="install-addon">
        <tr>
          <td>
            <widget class="\XLite\View\Button\Addon\SelectInstallationType" moduleId="{getModuleId()}" label="Install add-on" style="submit-button main-button" disabled=true />
          </td>
        </tr>
      </table>

  </div>
</div>
