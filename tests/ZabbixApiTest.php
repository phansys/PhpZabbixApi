<?php

namespace ZabbixApi\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use ZabbixApi\ZabbixApi;

/**
 * @author Javier Spagnoletti <phansys@gmail.com>
 */
class ZabbixApiTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ClientInterface
     */
    private $customHttpClient;

    public function setUp()
    {
        $this->customHttpClient = $this->getMockBuilder(Client::class)->getMock();
    }

    public function testCustomHttpClient()
    {
        $zabbixApi = $this->getMockBuilder(ZabbixApi::class)
            ->setConstructorArgs(['', '', '', '', '', '', $this->customHttpClient])
            ->getMock()
        ;
    }

    public function testCustomHttpClientWithRequestOptions()
    {
        $this->setExpectedException(\InvalidArgumentException::class, 'If argument 7 is provided, argument 8 must be omitted or passed with an empty array as value');
        $zabbixApi = $this->getMockBuilder(ZabbixApi::class)
            ->setConstructorArgs(['', '', '', '', '', '', $this->customHttpClient, ['verify' => true]])
            ->getMock()
        ;
    }
}
