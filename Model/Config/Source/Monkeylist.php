<?php

namespace lucy\LucyMail\Model\Config\Source;

class Monkeylist implements \Magento\Framework\Option\ArrayInterface
{
    protected $_api     = null;
    protected $_options = null;
    protected $_helper  = null;
    /**
     * @param \lucy\LucyMail\Helper\Data $helper
     */
    public function __construct(
        \lucy\LucyMail\Helper\Data $helper,
        \lucy\LucyMail\Model\Api $api
    ) {
    

        $this->_helper = $helper;
        $this->_api = $api;
        if ($this->_helper->getApiKey()) {
            $this->_options = $this->_api->lists();
        }
    }
    public function toOptionArray()
    {
        if (isset($this->_options->lists)) {
            $rc = [];
            foreach ($this->_options->lists as $list) {
                if (isset($list->id) && isset($list->name)) {
                    $rc[] = ['value' => $list->id, 'label' => $list->name];
                }
            }
            return $rc;
        } else {
            return [['value' => 0, 'label' => __('---No Data---')]];
        }
    }

    public function toArray()
    {
        $rc = [];
        foreach ($this->_options->lists as $list) {
            $rc[$list->id] = $list->name;
        }
        return $rc;
    }
}
