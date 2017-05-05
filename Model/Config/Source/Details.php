<?php


namespace lucy\LucyMail\Model\Config\Source;

class Details implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \lucy\LucyMail\Model\Api|null
     */
    protected $_api     = null;
    /**
     * @var null
     */
    protected $_options = null;
    /**
     * @var \lucy\LucyMail\Helper\Data|null
     */
    protected $_helper  = null;

    /**
     * @param \lucy\LucyMail\Helper\Data $helper
     */
    public function __construct(
        \lucy\LucyMail\Helper\Data $helper,
        \lucy\LucyMail\Model\Api $api
    ) {
    
        $this->_helper  = $helper;
        $this->_api = $api;
        if ($helper->getApiKey()) {
            $this->_options = $this->_api->info();
        }
    }
    public function toOptionArray()
    {
        if (isset($this->_options->account_name)) {
            return [
                ['value'=>'Account Name',      'label'=> $this->_options->account_name],
                ['value'=>'Company',           'label'=> $this->_options->contact->company],
                ['value'=>'Total Subscribers', 'label'=> $this->_options->total_subscribers],
            ];
        } else {
            return [
                ['value'=>'Error','label' => __('Invalid API Key')]
            ];
        }
    }
//    public function toArray()
//    {
//        return array(
//            'Account Name' => $this->_options->account_name
//        );
//
//    }
}
