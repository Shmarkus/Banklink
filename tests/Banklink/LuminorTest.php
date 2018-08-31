<?php

namespace Banklink;

use PHPUnit\Framework\TestCase;

/**
 * @author KaidoJ <jast112@gmail.com>
 *
 * @since  31.08.2018
 */
class LuminorTest extends TestCase
{
    /**
     * @var Luminor
     */
    private $luminor;

    public function setUp()
    {
        $protocol = \Mockery::mock('Banklink\Protocol\iPizza')->makePartial();
        $protocol->shouldReceive('getRequestSignature')->once()->andReturn('unit-testing');
        $protocol->configure(
            'uid258629',
            'Test Testov',
            '119933113300',
            __DIR__.'/data/iPizza/private_key.pem',
            __DIR__.'/data/iPizza/public_key.pem',
            'http://www.google.com');
        $this->luminor = new Luminor($protocol, true);
    }

    public function testPreparePaymentRequest()
    {
        $now = new \DateTime();
        $expectedRequestData = array(
            'VK_SERVICE'  => '1011',
            'VK_VERSION'  => '008',
            'VK_SND_ID'   => 'uid258629',
            'VK_STAMP'    => '1',
            'VK_AMOUNT'   => '100',
            'VK_CURR'     => 'EUR',
            'VK_ACC'      => '119933113300',
            'VK_NAME'     => 'Test Testov',
            'VK_REF'      => '13',
            'VK_MSG'      => 'Test payment',
            'VK_RETURN'   => 'http://www.google.com',
            'VK_CANCEL'   => 'http://www.google.com',
            'VK_LANG'     => 'ENG',
            'VK_MAC'      => 'unit-testing',
            'VK_DATETIME' => $now->format(\DateTime::ISO8601),
            'VK_ENCODING' => 'UTF-8'
        );
        $request = $this->luminor->preparePaymentRequest(1, 100, 'Test payment', 'ENG', 'EUR');
        $this->assertEquals($expectedRequestData, $request->getRequestData());
        $this->assertEquals('https://netbank.nordea.com/pnbepaytest/epayp.jsp', $request->getRequestUrl());
    }
}