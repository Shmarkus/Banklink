<?php

namespace Inori\Banklink;

use Inori\Banklink\Protocol\iPizza;

/**
 * Banklink implementation for Swedbank bank using iPizza protocol for communication
 *
 * @author Roman Marintsenko <roman.marintsenko@knplabs.com>
 * @since  11.01.2012
 */
class Swedbank extends Banklink
{
    public function __construct(iPizza $protocol)
    {
        parent::__construct($protocol);
    }

    public function preparePaymentRequest($orderId, $sum, $message = '', $language = 'EST', $currency = 'EUR')
    {
        return $this->protocol->preparePaymentRequest($orderId, $sum, $message, $language, $currency, $this->getAdditionalFields());
    }

    protected function getRequestUrl()
    {
        return 'http://swedbank.ee';
    }

    protected function getProtocolVersion()
    {
        return '008';
    }

    protected function getAdditionalFields()
    {
        return array(
            'VK_ENCODING' => 'UTF-8'
        );
    }
}