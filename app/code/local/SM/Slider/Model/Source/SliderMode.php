<?php
class SM_Slider_Model_Source_SliderMode extends Varien_Object
{
    const HORIZONTAL	= 1;
    const VERTICAL	= 2;

    static public function toOptionArray()
    {
        return array(
            self::HORIZONTAL    => Mage::helper('slider')->__('Horizontal'),
            self::VERTICAL   => Mage::helper('slider')->__('Vertical')
        );
    }

}
