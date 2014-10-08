<?php
class SM_Slider_Block_Adminhtml_Image_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'slider';
        $this->_controller = 'adminhtml_image';

        $this->_updateButton('save','label',Mage::helper('slider')->__('Save Image'));
        $this->_updateButton('delete','label',Mage::helper('slider')->__('Delete Image'));

        if($this->getRequest()->getParam('slider_id'))
        {
            $this->_updateButton('delete','onclick','setLocation(\'' . $this->getUrl('*/*/deleteRelation',['id' => $this->getRequest()->getParam('id'),'slider_id'=>$this->getRequest()->getParam('slider_id')]) .'\')');
            $this->_updateButton('back','onclick','setLocation(\'' . $this->getUrl('*/adminhtml_slider/edit',['id' => $this->getRequest()->getParam('slider_id')]) .'\')');
        }

        $this->_addButton('saveandcontinue',[
            'label' => Mage::helper('slider')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
        ],-100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('slider_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'image_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'image_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }

        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('image_data') && Mage::registry('image_data')->getId() ) {
            return Mage::helper('slider')->__("Edit Image %s", $this->htmlEscape(Mage::registry('image_data')->getTitle()));
        } else {
            return Mage::helper('slider')->__('Image');
        }
    }
}