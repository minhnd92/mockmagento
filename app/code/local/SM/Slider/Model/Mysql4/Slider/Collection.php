<?php
class SM_Slider_Model_Mysql4_Slider_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('slider/slider');
    }
}