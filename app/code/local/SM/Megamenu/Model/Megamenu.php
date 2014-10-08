<?php
class SM_Megamenu_Model_Megamenu extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('megamenu/megamenu');
    }

    public function toOptionArray()
    {
        return array(
            array('value'=>1, 'label'=>Mage::helper('megamenu')->__('Enabled')),
            array('value'=>0, 'label'=>Mage::helper('megamenu')->__('Disabled')),
        );
    }

}