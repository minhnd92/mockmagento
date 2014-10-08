<?php
class SM_BestSeller_Block_BestSeller extends Mage_Catalog_Block_Product_New
{
    public function getHomeBestSellerProduct()
    {
        $storeId = Mage::app()->getStore()->getId();

        $date = new Zend_Date();

        $calc_range = (int) Mage::getStoreConfig('bestseller_options/general/calc_range');

        switch($calc_range){
            case 1:
                $toDate = (string) $date->getDate()->get('YYYY-MM-dd');
                $fromDate = (string) $date->subWeek(1)->getDate()->get('YYYY-MM-dd');
                break;
            case 2:
                $toDate = (string) $date->setDay(1)->get('YYYY-MM-dd');
                $fromDate = (string) $date->subMonth(1)->get('YYYY-MM-dd');
                break;
            case 3:
                $toDate = (string) $date->getDate()->get('YYYY-MM-dd');
                $fromDate = (string) $date->setDayOfYear(1)->get('YYYY-MM-dd');
                break;
            case 4:
                $toDate = (string) $date->subYear(1)->get('YYYY-MM-dd');
                $fromDate = (string) $date->setDayOfYear(1)->get('YYYY-MM-dd');
                break;
        }
        $collection = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('*')
            ->addStoreFilter();
        ;

        $collection->getSelect()
            ->joinLeft(
                array('aggregation' => $collection->getResource()->getTable('sales/bestsellers_aggregated_daily')),
                "e.entity_id = aggregation.product_id AND aggregation.store_id = {$storeId} AND aggregation.period >= date '{$fromDate}' AND aggregation.period < date '{$toDate}' ",
                array('sum(aggregation.qty_ordered) as sold_quantity')
            )
            ->group('e.entity_id')
            ->order('sold_quantity DESC')
        ;

        $pageSize = Mage::getStoreConfig('bestseller_options/slider_effect/max_slider')? Mage::getStoreConfig('bestseller_options/slider_effect/max_slider') : 10 ;

        $collection->setPageSize((int) $pageSize );

        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);

        return $collection;
    }

    public function getCategoryBestSellerProduct()
    {
        $category = Mage::registry('current_category');
        $displayMode = ($category->getDisplayMode());
        if($displayMode=='PRODUCTS' || $displayMode=='PRODUCTS_AND_PAGE'){

            $id = Mage::registry('current_category')->getId();

            $storeId = Mage::app()->getStore()->getId();

            $date = new Zend_Date();

            $calc_range = (int) Mage::getStoreConfig('bestseller_options/general/calc_range');

            switch($calc_range){
                case 1:
                    $toDate = (string) $date->getDate()->get('YYYY-MM-dd');
                    $fromDate = (string) $date->subWeek(1)->getDate()->get('YYYY-MM-dd');
                    break;
                case 2:
                    $toDate = (string) $date->setDay(1)->get('YYYY-MM-dd');
                    $fromDate = (string) $date->subMonth(1)->get('YYYY-MM-dd');
                    break;
                case 3:
                    $toDate = (string) $date->getDate()->get('YYYY-MM-dd');
                    $fromDate = (string) $date->setDayOfYear(1)->get('YYYY-MM-dd');
                    break;
                case 4:
                    $toDate = (string) $date->subYear(1)->get('YYYY-MM-dd');
                    $fromDate = (string) $date->setDayOfYear(1)->get('YYYY-MM-dd');
                    break;
            }
            $collection = Mage::getModel('catalog/product')->getCollection()
                ->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id = entity_id', null, 'left')
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('category_id', $id)
                ->addStoreFilter();
            ;

            $collection->getSelect()
                ->joinLeft(
                    array('aggregation' => $collection->getResource()->getTable('sales/bestsellers_aggregated_daily')),
                    "e.entity_id = aggregation.product_id AND aggregation.store_id = {$storeId} AND aggregation.period >= date '{$fromDate}' AND aggregation.period < date '{$toDate}' ",
                    array('sum(aggregation.qty_ordered) as sold_quantity')
                )
                ->group('e.entity_id')
                ->order('sold_quantity DESC')
            ;

            $pageSize = Mage::getStoreConfig('bestseller_options/slider_effect/max_slider')? Mage::getStoreConfig('bestseller_options/slider_effect/max_slider') : 10 ;

            $collection->setPageSize((int) $pageSize );

            Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
            Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);

            return $collection;
        }
        return;
    }
}
