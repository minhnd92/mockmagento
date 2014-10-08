<?php
class SM_Slider_Model_Mysql4_Image extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('slider/image','image_id');
    }
}