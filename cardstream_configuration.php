<?php
/**
 * @package  Cardstream Payment Plugin for Hikashop and for Joomla! 2.5, Joomla! 3.x
 * @name    Cardstream Payment Plugin for Hikashop
 * @version	1.0
 * @author	Cardstream
 * @license GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die('Restricted access');
?>
<input type="hidden" name="data[payment][payment_params][type]" value="<?php echo @$this->element->payment_params->type; ?>" />
<!--<tr>
    <td class="key">
        <label for="data[payment][payment_params][type]">
            <?php //echo JText::_('Type of integration'); ?>
        </label>
    </td>
    <td>
        <?php /*
        $values = array();
        $values[] = JHTML::_('select.option', 'hosted', JText::_('Hosted'));
        $values[] = JHTML::_('select.option', 'direct', JText::_('Direct'));
        echo JHTML::_('select.genericlist', $values, "data[payment][payment_params][type]", 'class="inputbox" size="1"', 'value', 'text', @$this->element->payment_params->type); */
        ?>
    </td>
</tr>-->
<tr>
    <td class="key">
        <label for="data[payment][payment_params][merchantid]">
            <?php echo JText::_('Merchant ID'); ?>
        </label>
    </td>
    <td>
        <input type="text" name="data[payment][payment_params][merchantid]" value="<?php echo @$this->element->payment_params->merchantid; ?>" />
    </td>
</tr>
<tr>
    <td class="key">
        <label for="data[payment][payment_params][secret]">
            <?php echo JText::_('Signature Key'); ?>
        </label>
    </td>
    <td>
        <input type="text" name="data[payment][payment_params][secret]" value="<?php echo @$this->element->payment_params->secret; ?>" />
    </td>
</tr>
    <td class="key">
        <label for="data[payment][payment_params][currency_code]">
            <?php echo JText::_('Currency Code'); ?>
        </label>
    </td>
    <td>
        <input type="text" name="data[payment][payment_params][currency_code]" value="<?php echo @$this->element->payment_params->currency_code; ?>" />
    </td>
</tr>
<tr>
    <td class="key">
        <label for="data[payment][payment_params][country_code]">
            <?php echo JText::_('Country Code'); ?>
        </label>
    </td>
    <td>
        <input type="text" name="data[payment][payment_params][country_code]" value="<?php echo @$this->element->payment_params->country_code; ?>" />
    </td>
</tr>
<tr>
    <td class="key">
        <label for="data[payment][payment_params][invalid_status]">
            <?php echo JText::_('Invalid Status'); ?>
        </label>
    </td>
    <td>
        <?php echo $this->data['order_statuses']->display("data[payment][payment_params][invalid_status]", @$this->element->payment_params->invalid_status); ?>
    </td>
</tr>
<tr>
    <td class="key">
        <label for="data[payment][payment_params][verified_status]">
            <?php echo JText::_('Verified Status'); ?>
        </label>
    </td>
    <td>
        <?php echo $this->data['order_statuses']->display("data[payment][payment_params][verified_status]", @$this->element->payment_params->verified_status); ?>
    </td>
</tr>