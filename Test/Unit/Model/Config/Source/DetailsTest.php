<?php

namespace lucy\LucyMail\Test\Unit\Model\Config\Soruce;

class DetailsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \lucy\LucyMail\Model\Config\Source\Details
     */
    protected $_collection;
    protected $_collectionEmpty;
    /**
     * @var \|\PHPUnit_Framework_MockObject_MockObject|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $_apiMock;

    protected function setUp()
    {
//        $apiMock = $this->getMockBuilder('lucy\LucyMail\Model\Api')
//            ->disableOriginalConstructor()
//            ->getMock();
        $helperMock = $this->getMockBuilder('lucy\LucyMail\Helper\Data')
            ->disableOriginalConstructor()
            ->getMock();
        $mcapiMock = $this->getMockBuilder('lucy\LucyMail\Model\MCAPI')
            ->disableOriginalConstructor()
            ->getMock();

        $helperMock->expects($this->any())
            ->method('getApiKey')
            ->willReturn('apikey');

        $options = ['account_name'=>'lucy','total_subscribers'=>5,'contact'=>(object)['company'=>'lucy']];
        $mcapiMock->expects($this->any())
            ->method('info')
            ->willReturn((object)$options);

//        $apiMock->expects($this->any())
//            ->method('loadByStore')
//            ->willReturn($mcapiMock);

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

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $apiMock = $objectManager->getObject(
            'lucy\LucyMail\Model\Api',
            [
                'helper' => $helperMock,
                'mcapi'  => $mcapiMock,
                'storeManager' => $storeManagerMock
            ]
        );

        $mcapiEmptyMock = $this->getMockBuilder('lucy\LucyMail\Model\MCAPI')
            ->disableOriginalConstructor()
            ->getMock();
        $optionsEmpty = (object)['nolists'=>(object)[(object)[]]];
        $mcapiEmptyMock->expects($this->any())
            ->method('info')
            ->willReturn($optionsEmpty);

        $apiEmptyMock = $objectManager->getObject(
            'lucy\LucyMail\Model\Api',
            [
                'helper' => $helperMock,
                'mcapi'  => $mcapiEmptyMock,
                'storeManager' => $storeManagerMock
            ]
        );
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->_collection = $objectManager->getObject(
            'lucy\LucyMail\Model\Config\Source\Details',
            [
                'helper' => $helperMock,
                'api' => $apiMock
            ]
        );
        $this->_collectionEmpty = $objectManager->getObject(
            'lucy\LucyMail\Model\Config\Source\Details',
            [
                'api' => $apiEmptyMock,
                'helper' => $helperMock
            ]
        );
    }
    public function testToOptionArray()
    {
        $this->_collectionEmpty->toOptionArray();
        $this->assertNotEmpty($this->_collection->toOptionArray());

        foreach ($this->_collection->toOptionArray() as $item) {
            $this->assertArrayHasKey('value', $item);
            $this->assertArrayHasKey('label', $item);
        }
    }
}
