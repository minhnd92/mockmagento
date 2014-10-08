<?php

class SM_Slider_Helper_Data extends Mage_Core_Helper_Abstract
{
    protected function _getTableName($tablename) {
        return Mage::getSingleton('core/resource')->getTableName($tablename);
    }

    public function getTableName($tablename) {
        return $this->_getTableName($tablename);
    }

    public function getEffectsConfig()
    {
        $array =  Mage::getStoreConfig('slider_options/effects');
        return $array;
    }
}