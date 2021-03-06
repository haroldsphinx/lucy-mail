<?php
/**
 * lucy_LucyMail Magento JS component
 *
 */

namespace lucy\LucyMail\Block\Adminhtml\System\Config;

class OauthWizard extends \Magento\Config\Block\System\Config\Form\Field
{
    protected $_template    = 'system/config/oauth_wizard.phtml';

    protected $_authorizeUri     = "https://login.mailchimp.com/oauth2/authorize";
    protected $_accessTokenUri   = "https://login.mailchimp.com/oauth2/token";
    protected $_redirectUri      = "http://lucy.com.ng/magento/mailchimp/oauth2/complete_header_M2.php";
    protected $_clientId         = 976537930266;

    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $originalData = $element->getOriginalData();

        $label = $originalData['button_label'];

        $this->addData([
            'button_label' => __($label),
            'button_url'   => $this->authorizeRequestUrl(),
            'html_id' => $element->getHtmlId(),
        ]);
        return $this->_toHtml();
    }
    public function authorizeRequestUrl()
    {

        $url = $this->_authorizeUri;
        $redirectUri = urlencode($this->_redirectUri);

        return "{$url}?redirect_uri={$redirectUri}&response_type=code&client_id={$this->_clientId}";
    }
}
