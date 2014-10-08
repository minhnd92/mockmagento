<?php
class SM_Slider_Block_Slider extends Mage_Core_Block_Template
{
    public function _construct()
    {
        parent::_construct();

        if(Mage::getStoreConfig('slider_options/general/enable')){
            $this->setTemplate('sm/slider/threshold.phtml');
        }
    }

    /*
     * Get All Images of Active Slider
     *
     * @return SM_Slider_Model_Mysql4_Image_Collection
     * */
    public function getAllImages()
    {
        $activeSlider = Mage::getModel('slider/slider')->getActiveSlider();

        return $activeSlider->getAllImages();
    }
}