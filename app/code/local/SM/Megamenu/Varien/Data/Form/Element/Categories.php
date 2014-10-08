<?php
class SM_Megamenu_Varien_Data_Form_Element_Categories extends Varien_Data_Form_Element_Abstract
{
    public function getElementHtml()
    {
        $block = new SM_Megamenu_Block_Adminhtml_Megamenu_Edit_Tab_Categories();
        return $block->toHtml();
    }
}