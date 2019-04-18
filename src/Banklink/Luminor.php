<?php

namespace Banklink;

use Banklink\Protocol\iPizza;

/**
 * Banklink implementation for Luminor bank using iPizza protocol for communication
 * For specs see https://www.luminor.ee/sites/default/files/documents/files/common/pangalingi-tehniline-spetsifikatsioon-ee.pdf
 *
 * @author KaidoJ <jast112@gmail.com>
 *
 * @since  31.08.2018
 */
class Luminor extends Banklink
{
    protected $productionRequestUrl = 'https://netbank.nordea.com/pnbepay/epayp.jsp';
    protected $testRequestUrl = 'https://netbank.nordea.com/pnbepaytest/epayp.jsp';

    /**
     * Force iPizza protocol
     *
     * @param iPizza  $protocol
     * @param boolean $testMode
     */
    public function __construct(iPizza $protocol, $testMode = false)
    {
        parent::__construct($protocol, $testMode);
    }

    /**
     * @inheritDoc
     */
    protected function getEncodingField()
    {
        return 'VK_ENCODING';
    }

    /**
     * Force UTF-8 encoding
     *
     * @see Banklink::getAdditionalFields()
     *
     * @return array
     */
    protected function getAdditionalFields()
    {
        return array(
            'VK_ENCODING' => $this->requestEncoding
        );
    }
}