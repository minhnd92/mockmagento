<?php
class SM_Filter_Model_Observer extends Mage_Core_Model_Observer
{
    public function addFilterType($observer)
    {
        $form = $observer->getForm();
        $fieldset = $form->getElement('front_fieldset');
        $fieldset->addField(
            'filter_type',
            'select',
            [
                'name' => 'filter_type',
                'label' => Mage::helper('filter')->__('Filter Type'),
                'title' => Mage::helper('filter')->__('Filter Type'),
                'values' => array(
                    array('value' => '', 'label' => Mage::helper('filter')->__('')),
                    array('value' => '1', 'label' => Mage::helper('filter')->__('Checkbox')),
                    array('value' => '2', 'label' => Mage::helper('filter')->__('Select')),
                    array('value' => '3', 'label' => Mage::helper('filter')->__('Link')),
                    array('value' => '4', 'label' => Mage::helper('filter')->__('Color')),
                ),
            ],
            'is_filterable'
        );
    }
}