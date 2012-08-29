{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Suppliers list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<div class="{getListCSSClasses()}">

  <ul class="list" IF="getPageData()">
    {foreach:getPageData(),method}
      <li class="{getLineClass(method)}">

        <div class="row">

        <div class="action left-action">
          {if:canSwitch(method)}
            <div class="switch {if:method.getEnabled()}enabled{else:}disabled{end:}"><img src="images/spacer.gif" alt="{t(#Switch#)}" /></div>
          {else:}
            {if:canEnable(method)}
              <div class="not-disable"><img src="images/spacer.gif" alt="{getForbidDisableNote(method)}" /></div>
            {else:}
              <div class="not-enable"><img src="images/spacer.gif" alt="{getForbidEnableNote(method)}" /></div>
            {end:}
          {end:}
          <img src="images/spacer.gif" class="separator" alt="" />
        </div>

        <div IF="hasIcon(method)" class="icon"><img src="{getIconURL(method)}" alt="" /></div>

        <div class="title">{method.getName()}</div>

        <div class="action right-action">
          <img src="images/spacer.gif" class="separator" alt="" />
          <div IF="canRemoveMethod(method)" class="remove"><img src="images/spacer.gif" alt="{t(#Remove#)}" /></div> 
          {if:hasWarning(method)}
            <img IF="canRemoveMethod(method)" src="images/spacer.gif" class="subseparator" alt="" />
            <div class="warning"><img src="images/spacer.gif" alt="{getWarning(method)}" /></div>
          {else:}
            {if:isConfigurable(method)}
              <img IF="canRemoveMethod(method)" src="images/spacer.gif" class="subseparator" alt="" />
              <div class="configure"><a href="{buildURL(#payment_method#,##,_ARRAY_(#method_id#^method.getMethodId()))}"><img src="images/spacer.gif" alt="{t(#Configure#)}" /></a></div>
            {end:}
          {end:}
        </div>

        </div>

        <widget IF="hasWarning(method)&isConfigurable(method)" class="XLite\View\Button\Link" label="{t(#Configure#)}" location="{buildURL(#payment_method#,##,_ARRAY_(#method_id#^method.getMethodId()))}" style="configure"/>

      </li>

    {end:}

  </ul>

</div>
