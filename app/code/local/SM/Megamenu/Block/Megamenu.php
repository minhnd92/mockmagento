<?php
class SM_Megamenu_Block_Megamenu extends Mage_Core_Block_Template
{
    const _categoryType = 1;
    const _blockType = 2;
    const _pageType = 3;

    protected $_result = "";

    public function getMenu()
    {
        $storeViewId = $this->_getStoreViewId();
        $menuCollection = Mage::getModel('megamenu/megamenu')
            ->getCollection()
            ->addFieldToFilter('item_status',1)
            ->addFieldToFilter('store_view',['like'=>'%,'.$storeViewId.',%'])
            ->setOrder('sort_order','asc');


        foreach($menuCollection as $key=> $menuItem)
        {
            $this->_result .= "<li>";
            if($menuItem->getData('item_icon')!='')
            {
                $this->_result .= "<img src='".Mage::getUrl('media').DS.'menu-icons'.DS.$menuItem->getData('item_icon')."' />";
            }

            if($menuItem->getData('item_type') == 1)
            {
                $categoryIdArray = explode(',',$menuItem->getData('category_id'));
                if(count($categoryIdArray) > 1){
                    $this->_result .= "<a href='#'>".$menuItem->getData('item_name')."</a><ul>";
                    $this->_result .= $this->_processCategory($categoryIdArray);
                    $this->_result .= "</ul></li>";
                } elseif(count($categoryIdArray) == 1)
                {
                    $categoryId = $categoryIdArray[0];
                    $this->_result .= $this->_getAllChildren($categoryId);
                }
            }

            if($menuItem->getData('item_type') == 2)
            {
                $this->_result .= "<img /><a href='#'>".$menuItem->getData('item_name')."</a><div class='helper'><div>";
                $this->_result .= $this->_processBlock($menuItem->getData('block_id'));
                $this->_result .= "</div></div></li>";
            }

            if($menuItem->getData('item_type') == 3)
            {
                $this->_result .= "<img/><a href='".$this->getUrl($menuItem->getData('item_link'))."'>{$menuItem->getData('item_name')}</a></li>";
            }
        }

        return $this->_result;
    }

    protected  function _getStoreViewId()
    {
        return Mage::app()->getStore()->getId();
    }

    protected function _processCategory($categoryIdArray)
    {
        $li = '';

        foreach($categoryIdArray as $categoryId)
        {

            $categoryModel = Mage::getModel('catalog/category')->load($categoryId);

            if($categoryModel->getChildrenCount()){
                $li .= $this->_getAllChildren($categoryId);
            } else
            {
                $li .= "<li><a href='{$categoryModel->getUrl()}'>{$categoryModel->getName()}</a></li>";
            }

        }

        return $li;
    }

    protected function _processBlock($blockId)
    {
        $html = $this->getLayout()->createBlock('cms/block')->setBlockId($blockId)->toHtml();
        return $html;
    }

    protected function _getAllChildren($categoryId,$currentLevel=1) {
    	$categoryLevel = Mage::getStoreConfig('megamenu_options/general/category_level') 
    						? Mage::getStoreConfig('megamenu_options/general/category_level')
    						: 4;
    
        if($currentLevel > $categoryLevel)
        {
            return;
        }

        $categoryModel = Mage::getModel('catalog/category')->load($categoryId);

        $result = '<li>';

        $result .= "<a href='{$categoryModel->getUrl()}'>{$categoryModel->getName()}</a>";

        if($categoryModel->getChildrenCount()) {

            $result .= "<ul>";

            $childrenArray = explode(',',$categoryModel->getChildren());

            foreach($childrenArray as $childId){

                $result .= $this->_getAllChildren($childId,$currentLevel+1);

            }

            $result .="</ul>";
        }

        $result .= "</li>";

        return $result;
    }
}
