<?php
class SM_Featured_Model_Attribute_Source_Featured extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    public function toOptionArray()
    {
        return $this->getAllOptions();
    }

    public function getAllOptions()
    {
        if(is_null($this->_options))
        {
            $this->_options=array(
                array(
                    'label' => Mage::helper('featured')->__('Yes'),
                    'value' => 1
                ),

                array(
                    'label' => Mage::helper('featured')->__('No'),
                    'value' => 0
                )
            );
        }

        return $this->_options;
    }
}