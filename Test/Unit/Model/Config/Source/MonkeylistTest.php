<?php
namespace lucy\LucyMail\Test\Unit\Model\Config\Source;

class MonkeylistTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var
     */
    protected $_options;

    protected function setUp()
    {
        $helperMock = $this->getMockBuilder('lucy\LucyMail\Helper\Data')
            ->disableOriginalConstructor()
            ->getMock();
        $mcapiMock = $this->getMockBuilder('lucy\LucyMail\Model\MCAPI')
            ->disableOriginalConstructor()
            ->getMock();
        $apiEmptyMock = $this->getMockBuilder('lucy\LucyMail\Model\Api')
            ->disableOriginalConstructor()
            ->getMock();
        $mcapiEmptyMock = $this->getMockBuilder('lucy\LucyMail\Model\MCAPI')
            ->disableOriginalConstructor()
            ->getMock();

        $helperMock->expects($this->any())
            ->method('getApiKey')
            ->willReturn('apikey');

        $options = (object)['lists'=>(object)[(object)['id'=>1,'name'=>'list1'],(object)['id'=>2,'name'=>'list2']]];
        $optionsEmpty = (object)['nolists'=>(object)[(object)[]]];

        $mcapiMock->expects($this->any())
            ->method('lists')
            ->willReturn($options);

        $mcapiEmptyMock->expects($this->any())
            ->method('lists')
            ->willReturn($optionsEmpty);

        $apiEmptyMock->expects($this->any())
            ->method('loadByStore')
            ->willReturn($apiEmptyMock);
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

        $this->_options = $objectManager->getObject(
            'lucy\LucyMail\Model\Config\Source\Monkeylist',
            [
                'api' => $apiMock,
                'helper' => $helperMock
            ]
        );
        $this->_optionsEmpty = $objectManager->getObject(
            'lucy\LucyMail\Model\Config\Source\Monkeylist',
            [
                'api' => $apiEmptyMock,
                'helper' => $helperMock
            ]
        );
        $this->api = $apiMock;
    }

    public function testToOptionArray()
    {
        //$this->assertNotEmpty($this->_options->lists);
        $this->api->loadByStore(1);
        $this->assertNotEmpty($this->_options->toOptionArray());
        foreach ($this->_options->toOptionArray() as $item) {
            $this->assertArrayHasKey('value', $item);
            $this->assertArrayHasKey('label', $item);
        }
        $this->assertNotEmpty($this->_optionsEmpty->toOptionArray());
    }

    public function testToArray()
    {
        $this->assertNotEmpty($this->_options->toArray());
        foreach ($this->_options->toOptionArray() as $item) {
            $this->assertArrayHasKey('value', $item);
            $this->assertArrayHasKey('label', $item);
        }
    }
}
