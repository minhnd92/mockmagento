<?php
class SM_Megamenu_Block_Adminhtml_Megamenu_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'megamenu';
        $this->_controller = 'adminhtml_megamenu';

        //Change Save and Delete Button Label
        $this->_updateButton('save', 'label', Mage::helper('megamenu')->__('Save Menu Item'));
        $this->_updateButton('delete', 'label', Mage::helper('megamenu')->__('Delete Menu Item'));

        //Add SaveAndContinueEdit Button
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        //Add saveAndContinueEdit javascript function
        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }

        ";
    }

    //Display Header Text
    public function getHeaderText()
    {
        if( Mage::registry('megamenu_data') && Mage::registry('megamenu_data')->getId() ) {
            return Mage::helper('megamenu')->__("Edit Menu Item %s", $this->htmlEscape(Mage::registry('megamenu_data')->getTitle()));
        } else {
            return Mage::helper('megamenu')->__('Add Item');
        }
    }
}