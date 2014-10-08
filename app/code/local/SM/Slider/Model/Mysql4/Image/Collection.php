<?php
class SM_Slider_Model_Mysql4_Image_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('slider/image');
    }

    public function getImagesForSlider($sliderId)
    {
        $helper = Mage::helper('slider');

        $this->getSelect()
            ->join(array('slider_image'=>$helper->getTableName('slider_image')),'slider_image.image_id=main_table.image_id')
            ->join(array('slider'=>$helper->getTableName('slider')),'slider_image.slider_id=slider.slider_id')
            ->reset(Zend_Db_Select::COLUMNS)
            ->columns(['image_id','image_path','image_name'],'main_table')
            ->columns(['order','title'],'slider_image')
            ->columns([])
            ->where('slider.slider_id='.$sliderId)
            ->order('slider_image.order','desc');

        return $this;
    }
}