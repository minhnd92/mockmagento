<?php
class SM_Slider_Block_Adminhtml_Image extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_image';
        $this->_blockGroup = 'slider';
        $this->_headerText = Mage::helper('slider')->__("Image Manager");
        $this->_addButtonLabel = Mage::helper('slider')->__("Add Image");

        parent::__construct();
    }
}