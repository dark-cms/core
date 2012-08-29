{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Paypal Payments Advanced settings
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}


<div class="payment-settings {paymentMethod.getServiceName()}">

  <div class="middle">

    <div class="settings">

      <h2>{t(#Your account settings#)}</h2>

        <ul class="options">

          <li>
            <span><label for="pp_partner">{t(#Partner name#)}:</label></span>
            <input type="text" id="pp_partner" name="settings[partner]" value="{paymentMethod.getSetting(#partner#)}" />
            <widget
              class="\XLite\View\Tooltip"
              id="pp-partner-help"
              text="{t(#Your partner name is PayPal#)}"
              caption=""
              isImageTag="true"
              className="help-icon" />
          </li>

          <li>
            <span><label for="pp_vendor">{t(#Merchant login#)}:</label></span>
            <input type="text" id="pp_vendor" name="settings[vendor]" value="{paymentMethod.getSetting(#vendor#)}" />
            <widget
              class="\XLite\View\Tooltip"
              id="pp-vendor-help"
              text="{t(#This is the login name you created when signing up for PayPal Payments Advanced.#)}"
              caption=""
              isImageTag="true"
              className="help-icon" />
          </li>

          <li>
            <span><label for="pp_user">{t(#User#)}:</label></span>
            <input type="text" id="pp_user" name="settings[user]" value="{paymentMethod.getSetting(#user#)}" />
            <widget
              class="\XLite\View\Tooltip"
              id="pp-vendor-help"
              text="{t(#PayPal recommends entering a User Login here instead of your Merchant Login. You can set up a User profile in <a href="https://manager.paypal.com">PayPal Manager</a>. This will enhance security and prevent service interruption should you change your Merchant Login password.#)}"
              caption=""
              isImageTag="true"
              className="help-icon" />
          </li>

          <li>
            <span><label for="pp_pwd">{t(#Password#)}:</label></span>
            <input type="text" id="pp_pwd" name="settings[pwd]" value="{paymentMethod.getSetting(#pwd#)}" />
            <widget
              class="\XLite\View\Tooltip"
              id="pp-vendor-help"
              text="{t(#This is the password you created when signing up for PayPal Payments Advanced or the password you created for API calls.#)}"
              caption=""
              isImageTag="true"
              className="help-icon" />
          </li>

        </ul>

      <h2>{t(#Additional settings#)}</h2>

        <ul class="options ">

          <li>
            <span><label for="pp_transaction_type">{t(#Transaction type#)}:</label></span>
            <select id="pp_transaction_type" name="settings[transaction_type]">
              <option value="S" selected="{isSelected(#S#,paymentMethod.getSetting(#transaction_type#))}">{t(#Auth and Capture#)}</option>
              <option value="A" selected="{isSelected(#A#,paymentMethod.getSetting(#transaction_type#))}">{t(#Auth only#)}</option>
            </select>
          </li>

          <li>
            <span><label for="pp_test">{t(#Test mode#)}:</label></span>
            <select id="pp_test" name="settings[test]">
              <option value="N" selected="{isSelected(#N#,paymentMethod.getSetting(#test#))}">{t(#Live mode#)}</option>
              <option value="Y" selected="{isSelected(#Y#,paymentMethod.getSetting(#test#))}">{t(#Test mode#)}</option>
            </select>
          </li>

          <li>
            <span><label for="pp_order_prefix">{t(#Order id prefix#)}:</label></span>
            <input type="text" id="pp_order_prefix" name="settings[prefix]" value="{paymentMethod.getSetting(#prefix#)}" />
            <widget
              class="\XLite\View\Tooltip"
              id="pp-vendor-help"
              text="{t(#You can define an order id prefix, which would precede each order number in your shop, to make it unique (each transaction id must be unique for a Paypal account). This options is relevant only if you share your Paypal account with other online shops#)}"
              caption=""
              isImageTag="true"
              className="help-icon" />
          </li>

      </ul>

      <div class="buttons">
        <widget class="\XLite\View\Button\Submit" label="{t(#Save changes#)}" style="main-button" />
      </div>

    </div>

    <div class="help">
      <div class="logo-ppa"></div>

      <div class="help-title">Accept Payments Directly on Your Site</div>

      <div class="help-text">Accept Visa, MasterCard&reg;, American Express, Discover, and PayPal payments. Customers stay on your site for the entire checkout process with this all-in-one option. It simplifies PCI compliance and opens the door to more than 100 million active PayPal customers. Requires credit approval (2-3 business days).
      </div>


      <div class="help-link">Don't have an account? <a href="{paymentMethod.getReferralPageURL()}">Sign Up Now</a></div>

  </div>

  <div IF="!{paymentMethod.getSetting(#hide_instruction#)}" class="footer">

    <h2>Configure Paypal - Instructions</h2>

    <div>In order to accept payments via Paypal, you must complete the following steps.</div>

    <div>Please do not change any other values, as LiteCommerce will pass these values on your behalf for the ease of configuration.</div>

    <div class="clear"></div>

    <div class="left-part">
      <ul>
        <li>1. Log in to <a href="https://manager.paypal.com">PayPal Manager</a>.</li>
        <li>2. Select Hosted Checkout Pages then Set Up.</li>
        <li>3. Under Security Options, set the Secure Token to Yes.</li>
      </ul>
    </div>

    <div class="right-part">
      <a href="http://youtu.be/y9IGQpJCJeE">View a tutorial</a> | <a href="https://www.paypal.com/us/cgi-bin/webscr?cmd=_help">Paypal Help</a>
    </div>

    <div><a href="{buildURL(#payment_method#,#hide_instruction#,_ARRAY_(#method_id#^paymentMethod.getMethodId()))}">I've done this, dismiss the instruction</a></div>

  </div>

  <div IF="{paymentMethod.getSetting(#hide_instruction#)}" class="footer">

    <div class="pp-token-enabled">The Paypal Secure Token is enabled</div>

    <div class="pp-token-enabled-link"><a href="{buildURL(#payment_method#,#show_instruction#,_ARRAY_(#method_id#^paymentMethod.getMethodId()))}">Show the instruction</a></div>

  </div>

</div>

