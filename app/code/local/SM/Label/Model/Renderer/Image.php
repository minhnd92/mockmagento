<?php
class SM_Label_Model_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $value =  $row->getData($this->getColumn()->getIndex());
        return '<img width=100 height=100 src = '.Mage::getBaseUrl('media').'label/'.$value.' />';
    }
}