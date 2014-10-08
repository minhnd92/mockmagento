<?php
class SM_Slider_Block_Adminhtml_Slider_Edit_Tab_Image_Image extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_slider_edit_tab_image_image';
        $this->_blockGroup = 'slider';
        $this->_headerText = '';
        $this->_addButtonLabel = Mage::helper('slider')->__("Add Image To Slider");

        parent::__construct();

        $slider_id = Mage::registry('slider_data')->getData('slider_id');

        /*
         * Add 'add' button which lead to create newAction() of ImageController class
         * */
        $this->_updateButton('add','onclick',
            'setLocation(\'' . $this->getUrl('*/adminhtml_image/new',['slider_id'=>$slider_id]) .'\')'
        );
    }
}