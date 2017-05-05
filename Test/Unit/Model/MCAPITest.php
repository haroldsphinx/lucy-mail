<?php
namespace lucy\LucyMail\Test\Unit\Model;

class MCAPITest extends \PHPUnit_Framework_TestCase
{
    protected $_mcapi;

    public function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $curlMock = $this->getMockBuilder('Magento\Framework\HTTP\Adapter\Curl')
            ->disableOriginalConstructor()
            ->getMock();
        $curlMock->expects($this->any())
            ->method('addOption')
            ->willReturn(true);
        $curlMock->expects($this->any())
            ->method('connect')
            ->willReturn(true);
        $curlMock->expects($this->any())
            ->method('read')
            ->willReturn(true);
        $curlMock->expects($this->any())
            ->method('getInfo')
            ->willReturn(true);
        $curlMock->expects($this->any())
            ->method('close')
            ->willReturn(true);

        $helperMock = $this->getMockBuilder('lucy\LucyMail\Helper\Data')
            ->disableOriginalConstructor()
            ->getMock();
        $helperMock->expects($this->any())
            ->method('getApiKey')
            ->willReturn('api-key');


        $this->_mcapi = $objectManager->getObject(
            'lucy\LucyMail\Model\MCAPI',
            [
                'helper' => $helperMock,
                'curl'  => $curlMock
            ]
        );
    }

    /**
     * @covers lucy\LucyMail\Model\MCAPI::load
     * @covers lucy\LucyMail\Model\MCAPI::getApiKey
     */
    public function testLoad()
    {
        $mcapi = $this->_mcapi->load('apikey');
        $this->assertEquals($mcapi->getApiKey(), 'apikey');
    }
    /**
     * @covers lucy\LucyMail\Model\MCAPI::setTimeout
     * @covers lucy\LucyMail\Model\MCAPI::getTimeout
     */
    public function testTimeout()
    {
        $this->_mcapi->setTimeout(10);
        $this->assertEquals($this->_mcapi->getTimeout(), 10);
    }
    /**
     * @covers lucy\LucyMail\Model\MCAPI::info
     * @covers lucy\LucyMail\Model\MCAPI::callServer
     */
    public function testInfo()
    {
        $this->_mcapi->info();
    }
    /**
     * @covers lucy\LucyMail\Model\MCAPI::lists
     * @covers lucy\LucyMail\Model\MCAPI::callServer
     */
    public function testLists()
    {
        $this->_mcapi->lists();
    }
    /**
     * @covers lucy\LucyMail\Model\MCAPI::listMembers
     * @covers lucy\LucyMail\Model\MCAPI::callServer
     */
    public function testListMembers()
    {
        $this->_mcapi->listMembers(1);
    }
    /**
     * @covers lucy\LucyMail\Model\MCAPI::listCreateMember
     * @covers lucy\LucyMail\Model\MCAPI::callServer
     */
    public function testListCreateMember()
    {
        $this->_mcapi->listCreateMember(1, ['name'=>'name']);
    }
    /**
     * @covers lucy\LucyMail\Model\MCAPI::listDeleteMember
     * @covers lucy\LucyMail\Model\MCAPI::callServer
     * @covers lucy\LucyMail\Model\MCAPI::getHost
     */
    public function testListDeleteMember()
    {
        $this->_mcapi->listDeleteMember(1, 1);
    }
}
