<?php
class SM_ProductZoom_Helper_ProductZoom extends Varien_Object
{

    public function __construct()
    {
        parent::__construct();

        $productZoomConfig = Mage::getStoreConfig('productzoom_options');

        $this->setData('productZoomConfig',$productZoomConfig);

    }
}