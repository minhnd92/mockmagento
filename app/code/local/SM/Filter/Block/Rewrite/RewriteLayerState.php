<?php
class SM_Filter_Block_Rewrite_RewriteLayerState extends Mage_Catalog_Block_Layer_State
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('sm/catalog/layer/state.phtml');
    }

    public function getClearUrl()
    {
        $filterState = array();
        foreach ($this->getActiveFilters() as $item) {
            $filterState[$item->getFilter()->getRequestVar()] = $item->getFilter()->getCleanValue();
        }
        $params['_current']     = true;
        $params['_use_rewrite'] = true;
        $params['_query']       = $filterState;
        $params['_escape']      = true;
        return Mage::getUrl('*/*/view', $params);
    }
}