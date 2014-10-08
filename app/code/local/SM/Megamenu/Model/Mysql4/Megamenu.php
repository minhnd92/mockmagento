<?php

class SM_Megamenu_Model_Mysql4_Megamenu extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {    
        // Note that the item_id refers to the key field in your database table.
        $this->_init('megamenu/megamenu', 'item_id');
    }
}