<?php
class SM_BestSeller_Model_ValidateNumber extends Mage_Core_Model_Config_Data
{
    public function save()
    {
        $number = $this->getValue(); //get the value from our config
        $field = (array) $this->getData('field_config');
        if($number!='')
        {
            if(!is_numeric($number))
            {
                Mage::throwException("{$field['label']} must be a number!");
            }
        }
        return parent::save();  //call original save method so whatever happened
        //before still happens (the value saves)
    }
}