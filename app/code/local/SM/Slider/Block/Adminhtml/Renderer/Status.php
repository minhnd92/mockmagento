<?php
class SM_Slider_Block_Adminhtml_Renderer_Status extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $value = $row->getData($this->getColumn()->getIndex());

        $check = Mage::getModel('slider/slider')->load($value)->getData('is_active')? "checked":"";

        $url = $this->getUrl('*/*/changeSlider',['id'=>$value]);

        return "<input ".$check." type='radio' name='status' class='status' data-url='". $url ."' />";
    }
}