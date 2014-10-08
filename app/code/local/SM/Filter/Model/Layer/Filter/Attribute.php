<?php
class SM_Filter_Model_Layer_Filter_Attribute extends Mage_Catalog_Model_Layer_Filter_Attribute
{
    public function apply(Zend_Controller_Request_Abstract $request, $filterBlock)
    {
        $filter = $request->getParam($this->_requestVar);
        if(is_array($filter)) {
            $filter = array_unique($filter);
            foreach($filter as $value)
            {
                $text = $this->_getOptionText($value);
                if ($value && strlen($text)) {
                    $this->getLayer()->getState()->addFilter($this->_createItem($text, $value));
                }
            }
            $this->_getResource()->applyFilterToCollection($this, $filter);
            return $this;
        }

        $text = $this->_getOptionText($filter);
        if ($filter && strlen($text)) {
            $this->_getResource()->applyFilterToCollection($this, $filter);
            $this->getLayer()->getState()->addFilter($this->_createItem($text, $filter));
            $this->_items = [];
        }

        return $this;
    }
}