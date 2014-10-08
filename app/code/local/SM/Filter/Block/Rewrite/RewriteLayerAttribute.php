<?php
class SM_Filter_Block_Rewrite_RewriteLayerAttribute extends Mage_Catalog_Block_Layer_Filter_Attribute
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('sm/catalog/layer/filter.phtml');
    }
}