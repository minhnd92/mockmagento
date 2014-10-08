<?php

class SM_Megamenu_Model_Status extends Varien_Object
{
    const STATUS_ENABLED	= 1;
    const STATUS_DISABLED	= 0;

    static public function getOptionArray()
    {
        return array(
            [
                'label' => Mage::helper('megamenu')->__('Enabled'),
                'value' => self::STATUS_ENABLED
            ],
            [
                'label' => Mage::helper('megamenu')->__('Disabled'),
                'value' => self::STATUS_DISABLED
            ]
        );
    }
}