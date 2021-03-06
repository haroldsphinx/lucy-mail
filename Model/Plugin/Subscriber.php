<?php

namespace lucy\LucyMail\Model\Plugin;

class Subscriber
{
    /**
     * @var \lucy\LucyMail\Helper\Data
     */
    protected $_helper;
    /**
     * @var \Magento\Customer\Model\Customer
     */
    protected $_customer;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @param \lucy\LucyMail\Helper\Data $helper
     * @param \Magento\Customer\Model\Customer $customer
     * @param \Magento\Customer\Model\Session $customerSession
     */
    protected $_api = null;

    public function __construct(
        \lucy\LucyMail\Helper\Data $helper,
        \Magento\Customer\Model\Customer $customer,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \lucy\LucyMail\Model\Api $api
    ) {
    
        $this->_helper          = $helper;
        $this->_customer        = $customer;
        $this->_customerSession = $customerSession;
        $this->_storeManager    = $storeManager;
        $this->_api             = $api;
    }

    public function beforeUnsubscribeCustomerById(
        $subscriber,
        $customerId
    ) {
        $subscriber->loadByCustomerId($customerId);
        if ($subscriber->getLucyMailId() != null) {
            $api = $this->_api;
            try {
                $api->listDeleteMember($this->_helper->getDefaultList(), $subscriber->getLucyMailId());
                $subscriber->setLucyMailId('')->save();
            } catch (\Exception $e) {
                $this->_helper->log($e->getMessage());
            }
        }
        return [$customerId];
    }

    public function beforeSubscribeCustomerById(
        $subscriber,
        $customerId
    ) {
        $subscriber->loadByCustomerId($customerId);
        $subscriber->setImportMode(true);
        $storeId = $subscriber->getStoreId();
        if ($this->_helper->isMonkeyEnabled($storeId)) {
            $customer = $this->_customer->load($customerId);
            $mergeVars = $this->_helper->getMergeVars($customer);
            $api = $this->_api;
            $isSubscribeOwnEmail = $this->_customerSession->isLoggedIn()
                && $this->_customerSession->getCustomerDataObject()->getEmail() == $subscriber->getSubscriberEmail();
            if ($this->_helper->isDoubleOptInEnabled($storeId) && !$isSubscribeOwnEmail) {
                $status = 'pending';
            } else {
                $status = 'subscribed';
            }
            if ($mergeVars) {
                $data = ['list_id' => $this->_helper->getDefaultList(), 'email_address' => $customer->getEmail(), 'email_type' => 'html', 'status' => $status, 'merge_fields' => $mergeVars];
            } else {
                $data = ['list_id' => $this->_helper->getDefaultList(), 'email_address' => $customer->getEmail(), 'email_type' => 'html', 'status' => $status, 'merge_fields' => ['EMAIL'=>$customer->getEmail()]];
            }
            try {
                $emailHash = md5(strtolower($customer->getEmail()));
                $return = $api->getMember($this->_helper->getDefaultList(), $emailHash);
                if (!isset($return->id)) {
                    $return = $api->listCreateMember($this->_helper->getDefaultList(), json_encode($data));
                    if (isset($return->id)) {
                        $subscriber->setLucyMailId($return->id)->save();
                    }
                }
                $subscriber->setLucyMailId($emailHash)->save();
            } catch (\Exception $e) {
                $this->_helper->log($e->getMessage());
            }
        }
        return [$customerId];
    }

    public function beforeSubscribe(
        $subscriber,
        $email
    ) {
    
        $storeId = $this->_storeManager->getStore()->getId();

        $isSubscribeOwnEmail = $this->_customerSession->isLoggedIn()
            && $this->_customerSession->getCustomerDataObject()->getEmail() == $email;
        if (!$isSubscribeOwnEmail) {
            if ($this->_helper->isMonkeyEnabled($storeId)) {
                $subscriber->setImportMode(true);
                $api = $this->_api;
                if ($this->_helper->isDoubleOptInEnabled($storeId)) {
                    $status = 'pending';
                } else {
                    $status = 'subscribed';
                }
                $data = ['list_id' => $this->_helper->getDefaultList(), 'email_address' => $email, 'email_type' => 'html', 'status' => $status, 'merge_fields' => ['EMAIL'=>$email]];
                try {
                    $return = $api->listCreateMember($this->_helper->getDefaultList(), json_encode($data));
                    if (isset($return->id)) {
                        $subscriber->setLucyMailId($return->id);
                    }
                } catch (\Exception $e) {
                    $this->_helper->log($e->getMessage());
                }
            }
        }
        return [$email];
    }

    public function beforeUnsubscribe(
        $subscriber
    ) {
        if ($subscriber->getLucyMailId()) {
            $this->_api->listDeleteMember($this->_helper->getDefaultList(), $subscriber->getLucyMailId());
            $subscriber->setLucyMailId('');
        }
        return null;
    }
}
