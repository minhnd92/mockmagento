<?php
class SM_Slider_Block_Adminhtml_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $id = $this->getRequest()->getParam('id');
        $value = $row->getData($this->getColumn()->getIndex());
        return "<img width='100px' height='100px' src='{$value}'/>";
    }
}