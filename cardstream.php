<?php

/**
 * @package  Cardstream Payment Plugin for Hikashop and for Joomla! 2.5, Joomla! 3.x
 * @name    Cardstream Payment Plugin for Hikashop
 * @version	1.0
 * @author	Cardstream
 * @license GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die('Restricted access');
?><?php

class plgHikashoppaymentCardstream extends hikashopPaymentPlugin {

    var $accepted_currencies = array(
    'GBP', 'USD', 'EUR', 'AUD', 'CAD'
    );
    var $multiple = true;
    var $name = 'cardstream';
    var $doc_form = 'cardstream';

    function onBeforeOrderCreate(&$order, &$do) {
        if (parent::onBeforeOrderCreate($order, $do) === true)
            return true;

        if (empty($this->payment_params->merchantid) || empty($this->payment_params->type) || empty($this->payment_params->secret)) {
            $this->app->enqueueMessage('Please check your Cardstream plugin configuration', 'error');
            $do = false;
        }

        /* if (!function_exists('curl_init')) {
            $this->app->enqueueMessage('The Cardstream direct integration requires the CURL library installed but it seems that it is not available on your server. Please contact your web hosting to set it up.', 'error');
            return false;
        } */
        
        if ($this->payment_params->type != 'direct') {
            return true;
        }
    }

    function onAfterOrderConfirm(&$order, &$methods, $method_id) {
        parent::onAfterOrderConfirm($order, $methods, $method_id);

        if ($this->payment_params->type == 'hosted') {

            $address = trim($order->cart->shipping_address->address_street . ' ' . $order->cart->billing_address->address_city);
            $customerName = trim($order->cart->billing_address->address_firstname . ' ' . $order->cart->billing_address->address_lastname);
            $redirectUrl = HIKASHOP_LIVE . 'index.php?option=com_hikashop&ctrl=checkout&task=notify&notif_payment=' . $this->name . '&tmpl=component&orderid=' . $order->order_id;
            $callbackUrl = HIKASHOP_LIVE . 'index.php?option=com_hikashop&ctrl=checkout&task=after_end&order_id=' . $order->order_id;

            $fields = array(
            'merchantID' => $this->payment_params->merchantid,
            'action' => 'SALE',
            'type' => 1,
            'amount' => round($order->cart->full_total->prices[0]->price_value_with_tax, 2) * 100,
            'countryCode' => $this->payment_params->country_code,
            'currencyCode' => $this->payment_params->currency_code,
            'redirectURL' => $redirectUrl,
            'callbackURL' => $callbackUrl,
            'transactionUnique' => $order->order_id . '-' . date('Y-m-d'),
            'orderRef' => $order->order_id,
            'customerName' => $customerName,
            'customerAddress' => $address,
            'customerPostCode' => $order->cart->shipping_address->address_post_code,
            'customerPhone' => $order->cart->shipping_address->address_telephone,
            'customerEmail' => $this->user->user_email,
            );

            $fields['signature'] = createSignature($fields, $this->payment_params->secret);

            $this->fields = $fields;

            return $this->showPage('end');
        }
        
        if ($this->payment_params->type == 'direct') {
            
        }
    }

    function onPaymentNotification(&$statuses) {

        $response = $_REQUEST;
        $order_id = (int) $response['orderRef'];
        $dbOrder = $this->getOrder($order_id);

        $this->loadPaymentParams($dbOrder);
        if (empty($this->payment_params)) {
            echo 'The system can\'t load the payment params';
            return false;
        }
        $this->loadOrderData($dbOrder);

        $cancel_url = HIKASHOP_LIVE . 'index.php?option=com_hikashop&ctrl=order&task=cancel_order&order_id=' . $order_id;
        $return_url = HIKASHOP_LIVE . 'index.php?option=com_hikashop&ctrl=checkout&task=after_end&order_id=' . $order_id . $this->url_itemid;

        $history = new stdClass();
        if (isset($response['amountReceived'])){
            $history->amount = $response['amountReceived'];
        }

        if ($response['responseCode'] == 0) {
            $this->modifyOrder($order_id, $this->payment_params->verified_status, true, true);
            $this->app->enqueueMessage('Payment completed');
            $this->app->redirect($return_url);
            return true;
        } else {
            $history = new stdClass();
            $this->modifyOrder($order_id, $this->payment_params->invalid_status, $history, true);
            $this->app->enqueueMessage('Payment Failed: ' . $_REQUEST['responseMessage'], 'error');
            $this->app->redirect($cancel_url);
            return false;
        }
    }

    function getPaymentDefaultValues(&$element) {
        $element->payment_name = 'CARDSTREAM';
        $element->payment_description = 'Pay securely via Credit / Debit Card with Cardstream';
        $element->payment_images = 'MasterCard,Maestro,VISA';
        $element->payment_params->type = 'hosted';
        $element->payment_params->merchantid = '100001';
        $element->payment_params->secret = 'Circle4Take40Idea';
        $element->payment_params->currency_code = '826';
        $element->payment_params->country_code = '826';
        $element->payment_params->invalid_status = 'cancelled';
        $element->payment_params->verified_status = 'confirmed';
    }

}

function createSignature(array $fields, $key) {

    if (!$key || !is_string($key) || $key === '' || !$fields || !is_array($fields)) {
        return null;
    }

    ksort($fields);

    $ret = http_build_query($fields, '', '&');

    $ret = str_replace(array('%0D%0A', '%0A%0D', '%0D'), '%0A', $ret);

    $ret = hash('SHA512', $ret . $key);

    return $ret;
}
