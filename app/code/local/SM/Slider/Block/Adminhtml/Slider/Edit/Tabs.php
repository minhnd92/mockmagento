<?php
class SM_Slider_Block_Adminhtml_Slider_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('slider_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('slider')->__("Slider Information"));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section',[
            'label' => Mage::helper('slider')->__('General Information'),
            'title' => Mage::helper('slider')->__('General Information'),
            'content' => $this->getLayout()->createBlock('slider/adminhtml_slider_edit_tab_form')->toHtml(),
        ]);

        $this->addTab('image_section',[
            'label' => Mage::helper('slider')->__('Manage Images'),
            'title' => Mage::helper('slider')->__('Manage Images'),
            'content' => $this->getLayout()->createBlock('slider/adminhtml_slider_edit_tab_image_image')->toHtml()
        ]);

        $this->addTab('choose_image',[
            'label' => Mage::helper('slider')->__('Choose Images'),
            'title' => Mage::helper('slider')->__('Choose Images'),
            'content' => $this->getLayout()->createBlock('slider/adminhtml_slider_edit_tab_image_choose')->toHtml(),
        ]);

        return parent::_beforeToHtml();
    }
}