<?php
class SM_Slider_Model_Slider extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        $this->_init('slider/slider');
    }

    public function getAllImages()
    {
        $collection = Mage::getModel('slider/image')->getCollection();

        $collection->getImagesForSlider($this->getData('slider_id'));

        return $collection;
    }

    public function getAvailableImages()
    {
        $helper = Mage::helper('slider');

        $collectionImageOfSlider = $this->getAllImages();

        $arr = [];

        if($collectionImageOfSlider->getData())
        {
            foreach($collectionImageOfSlider->getData() as $value)
            {
                $arr[] = $value['image_id'];
            }
        }

        $imageCollection = Mage::getModel('slider/image')->getCollection();

        if(!empty($arr))
        {
            $imageCollection->addFieldToFilter('image_id',['nin'=>$arr]);

        }

        return $imageCollection;
    }

    public function getActiveSlider()
    {
        return $this->getCollection()->addFieldToFilter('is_active',1)->getFirstItem();
    }
}