<?php


namespace lucy\LucyMail\Block\Adminhtml\System\Config;

class Account extends \Magento\Config\Block\System\Config\Form\Field
{
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $values = $element->getValues();

        $html = '<ul class="checkboxes">';
        if ($values) {
            foreach ($values as $dat) {
                $html .= "<li>{$dat['value']}: {$dat['label']}</li>";
            }
        }

        $html .= '</ul>';

        return $html;
    }
}
