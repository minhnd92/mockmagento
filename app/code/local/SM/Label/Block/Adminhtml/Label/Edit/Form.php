<?php
class SM_Label_Block_Adminhtml_Label_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
{
    $form = new Varien_Data_Form(
        [
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save',['id'=>$this->getRequest()->getParam('id')]),
            'method' => 'post',
            'enctype' => 'multipart/form-data'

        ]
    );

    $form->setUseContainer(true);
    $this->setForm($form);

    $fieldset = $form->addFieldset('main_information',[
        'legend' => Mage::helper('label')->__('Main Information')
    ]);

    $fieldset->addField('name','text',[
        'label' => Mage::helper('label')->__('Label Name'),
        'name' => 'name',
        'required' => true,
        'class' => 'required-entry',
    ]);

    $fieldset->addField('position','select',[
        'label' => Mage::helper('label')->__('Position'),
        'name' => 'position',
        'required' => true,
        'class' => 'required-entry',
        'values' => [
            [
                'label' => '',
                'value' => ''
            ],
            [
                'label' => Mage::helper('label')->__('Top-Left'),
                'value' => 1
            ],
            [
                'label' => Mage::helper('label')->__('Top-Right'),
                'value' => 2
            ],
            [
                'label' => Mage::helper('label')->__('Bot-Left'),
                'value' => 3
            ],
            [
                'label' => Mage::helper('label')->__('Bot-Right'),
                'value' => 4
            ]
        ]

    ]);

    $fieldset->addType('thumbnail','SM_Label_Varien_Data_Form_Element_Thumbnail');

    $fieldset->addField('path', 'thumbnail', array(
        'label'     => Mage::helper('label')->__('Preview'),
        'style'   => "display:none;",
    ));

    $fieldset->addField('image', 'file', array(
        'label'     => Mage::helper('label')->__('Upload'),
        'name'      => 'image',
    ));

    if( $value = Mage::getSingleton('adminhtml/session')->getLabelData())
    {
        $form->setValues($value);
    } elseif($value = Mage::registry('label_data')){
        $form->setValues($value);
    }

    return parent::_prepareForm();
}
}