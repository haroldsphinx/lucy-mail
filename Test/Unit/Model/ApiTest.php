<?php
namespace lucy\LucyMail\Test\Unit\Model;

class ApiTest extends \PHPUnit_Framework_TestCase
{
    protected $api;
    public function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $helperMock = $this->getMockBuilder('lucy\LucyMail\Helper\Data')
            ->disableOriginalConstructor()
            ->getMock();
        $helperMock->expects($this->any())
            ->method('getApiKey')
            ->willReturn('api-key');

        $mcapiMock = $this->getMockBuilder('lucy\LucyMail\Model\MCAPI')
            ->disableOriginalConstructor()
            ->getMock();
        $mcapiMock->expects($this->once())
            ->method('info')
            ->willReturn(true);
        $mcapiMock->expects($this->once())
            ->method('listMembers')
            ->willReturn(true);
        $storeManagerMock = $this->getMockBuilder('Magento\Store\Model\StoreManagerInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $storeMock = $this->getMockBuilder('Magento\Store\Model\Store')
            ->disableOriginalConstructor()
            ->getMock();
        $storeMock->expects($this->any())
            ->method('getId')
            ->willReturn(1);
        $storeManagerMock->expects($this->any())
            ->method('getStore')
            ->willReturn($storeMock);

        $this->api = $objectManager->getObject(
            'lucy\LucyMail\Model\Api',
            [
                'storeManager' => $storeManagerMock,
                'helper' => $helperMock,
                'mcapi'  => $mcapiMock
            ]
        );
    }

    /**
     * @covers lucy\LucyMail\Model\Api::call
     */
    public function testCall()
    {
        $this->api->info();
        $this->api->listMembers(1);
    }
}
