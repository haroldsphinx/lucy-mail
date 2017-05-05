<?php

namespace lucy\LucyMail\Model;

class Api
{
    /**
     * @var MCAPI|null
     */
    protected $_mcapi   = null;
    /**
     * @var \lucy\LucyMail\Helper\Data|null
     */
    protected $_helper = null;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface|null
     */
    protected $_storeManager = null;

    /**
     * Api constructor.
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \lucy\LucyMail\Helper\Data $helper
     * @param MCAPI $mcapi
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \lucy\LucyMail\Helper\Data $helper,
        \lucy\LucyMail\Model\MCAPI $mcapi
    ) {
    
        $this->_helper = $helper;
        $this->_mcapi = $mcapi;
        $this->_storeManager = $storeManager;
    }
    public function __call($method, $args = null)
    {
        return $this->call($method, $args);
    }
    public function call($command, $args)
    {
        $result = null;
        if ($args) {
            if (is_callable([$this->_mcapi, $command])) {
                $reflectionMethod = new \ReflectionMethod($this->_mcapi, $command);
                $result = $reflectionMethod->invokeArgs($this->_mcapi, $args);
            }
        } else {
            if (is_callable([$this->_mcapi, $command])) {
                $result = $this->_mcapi->{$command}();
            }
        }
        return $result;
    }
}
