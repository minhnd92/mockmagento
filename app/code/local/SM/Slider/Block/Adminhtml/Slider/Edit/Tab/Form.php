<?php
class SM_Slider_Block_Adminhtml_Slider_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('slider_form',['legend'=>Mage::helper('slider')->__('General Information')]);

        $fieldset->addField('slider_name','text',[
            'label' => Mage::helper('slider')->__('Slider Name'),
            'name' => 'slider_name',
            'required' => true,
            'class' => 'required-entry'
        ]);

        $fieldset->addField('is_active','checkbox',[
            'label' => Mage::helper('slider')->__('Active'),
            'name' => 'is_active',
            'onclick'   => 'this.value = this.checked ? 1 : 0;',
            'checked' => Mage::registry('slider_data')->getData('is_active')==1? true : false
        ]);

        if ( Mage::getSingleton('adminhtml/session')->getSliderData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getSliderData());
            Mage::getSingleton('adminhtml/session')->setSliderData(null);
        } elseif ( Mage::registry('slider_data') ) {
            $form->setValues(Mage::registry('slider_data')->getData());
        }
        return parent::_prepareForm();
    }
}