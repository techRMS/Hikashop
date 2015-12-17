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
<div class="hikashop_cardstream_end" id="hikashop_cardstream_end">
    <span id="hikashop_cardstream_end_message" class="hikashop_cardstream_end_message">
        <?php echo JText::sprintf('PLEASE_WAIT_BEFORE_REDIRECTION_TO_X', $this->payment_name) . '<br/>' . JText::_('CLICK_ON_BUTTON_IF_NOT_REDIRECTED'); ?>
    </span>
    <span id="hikashop_cardstream_end_spinner" class="hikashop_cardstream_end_spinner">
        <img src="<?php echo HIKASHOP_IMAGES . 'spinner.gif'; ?>" />
    </span>
    <br/>
    <form id="hikashop_cardstream_form" name="hikashop_cardstream_form" action="https://gateway.cardstream.com/hosted/" method="post">
        <?php
        foreach ($this->fields as $key => $value) {
            echo '<input type="hidden" name="' . $key . '" value="' . htmlspecialchars((string) $value) . '" />';
        }
        $doc = JFactory::getDocument();
        $doc->addScriptDeclaration("window.hikashop.ready( function() {document.getElementById('hikashop_cardstream_form').submit();});");
        JRequest::setVar('noform', 1);
        ?>
        <input id="hikashop_cardstream_button" type="submit" class="btn btn-primary" value="<?php echo JText::_('PAY_NOW'); ?>" name="" alt="<?php echo JText::_('PAY_NOW'); ?>" />
    </form>
</div>

