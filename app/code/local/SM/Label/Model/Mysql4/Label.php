<?php
class SM_Label_Model_Mysql4_Label extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('label/label','id');
    }
}