<?php
class SM_Featured_Block_Featured extends Mage_Catalog_Block_Product_New
{
    /*
     * Get Featured Products of Website
     *
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function getHomeFeaturedProduct()
    {
        $collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToSelect('*')
            ->addFieldToFilter('is_featured',1)
            ->addFieldToFilter('featured_range',['in'=>[2,3]])
            ->addStoreFilter()
        ;

        $pageSize = Mage::getStoreConfig('featured_options/general/max_slider')? Mage::getStoreConfig('featured_options/general/max_slider') : 10 ;
        $collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds())
                    ->setPageSize((int) $pageSize );

        return $collection;
    }

    /*
     * Get Featured Product for specific category
     *
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function getCategoryFeaturedProduct()
    {
        $category = Mage::registry('current_category');
        $display_mode = ($category->getDisplayMode());
        if($display_mode=='PRODUCTS' || $display_mode=='PRODUCTS_AND_PAGE'){
            $id = Mage::registry('current_category')->getId();
            $collection = Mage::getModel('catalog/product')
                ->getCollection()
                ->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id = entity_id', null, 'left')
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('category_id', $id)
                ->addFieldToFilter('is_featured',1)
                ->addFieldToFilter('featured_range',['in'=>[1,3]])
                ->addStoreFilter()
            ;

            $page_size = Mage::getStoreConfig('featured_options/general/max_slider')? Mage::getStoreConfig('featured_options/general/max_slider') : 10 ;
            $collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds())
                ->setPageSize((int) $page_size );
            return $collection;
        }

        return;
    }
}