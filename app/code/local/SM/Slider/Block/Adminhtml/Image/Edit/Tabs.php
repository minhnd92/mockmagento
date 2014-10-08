<?php
class SM_Slider_Block_Adminhtml_Image_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('image_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('slider')->__("Image Information"));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section',[
            'label' => Mage::helper('slider')->__('General Information'),
            'title' => Mage::helper('slider')->__('General Information'),
            'content' => $this->getLayout()->createBlock('slider/adminhtml_image_edit_tab_form')->toHtml(),
        ]);

        return parent::_beforeToHtml();
    }
}