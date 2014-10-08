<?php
class SM_Featured_Model_Observer
{
    /*
     * Check if featured module is enabled and if the collection of the
     * events is type of Product Collection
     */
    public function addColumnToProductGrid($observer)
    {
        if(Mage::getStoreConfig('featured_options/general/enable')){
            $collection = $observer->getCollection ();
            if (! isset ( $collection ))
                return;

            if (is_a ( $collection, 'Mage_Catalog_Model_Resource_Product_Collection' )) {
                if (($productListBlock = Mage::app ()->getLayout ()->getBlock ( 'products_list' )) != false && ($productListBlock instanceof Mage_Adminhtml_Block_Catalog_Product)) {
                    $this->_addColumn ( $productListBlock->getChild ( 'grid' ) );
                } else if (($block = Mage::app ()->getLayout ()->getBlock ( 'admin.product.grid' )) != false) {
                    $this->_addColumn ( $block );
                }
            }
        }

    }

    /*
     * Add Column Is Featured and Featured Rang in to Product Grid
     * with column filter for each column
     */
    protected  function _addColumn(Mage_Adminhtml_Block_Catalog_Product_Grid $block) {
        $block->addColumnAfter ('is_featured', array (
            'header' => Mage::helper ( 'featured' )->__ ( 'Is Featured' ),
            'index' => 'is_featured',
            'type' => 'options',
            'options' => [
                0 => 'Not featured',
                1 => 'Featured'
            ]
        ),'websites' );

        $block->addColumnAfter ('featured_range', array (
            'header' => Mage::helper('featured')->__('Featured Range'),
            'width' => '50px',
            'index' => 'featured_range',
            'type' => 'options',
            'options' => [
                1 => 'Category',
                2 => 'Website',
                3 => 'Both'
                ]
        ),'is_featured' );

        $block->sortColumnsByOrder ();

        if ($block->getCollection ())
        {
            $block->getCollection ()->addAttributeToSelect ( 'is_featured' )
                ->addAttributeToSelect('featured_range');
        }

        $this->_addColumnFilter($block,'is_featured');
        $this->_addColumnFilter($block,'featured_range');
    }

    /*
     * Add filter for specific column
     *
     */
    protected function _addColumnFilter($block,$columnName)
    {
        $filter = $block->getParam ( $block->getVarNameFilter ());

        if (is_string ( $filter ))
        {
            $filter = $block->helper ( 'adminhtml' )->prepareFilterString ( $filter );
        } else if ($filter && is_array ( $filter ))
        {
        } else if (0 !== sizeof ( $this->_defaultFilter ))
        {
            $filter = $this->_defaultFilter;
        }

        $column = $block->getColumn ( $columnName );

        if (isset ( $filter [$columnName] )
            && (! empty ( $filter [$columnName] ) || strlen ( $filter [$columnName] ) > 0)
            && $column->getFilter ())
        {
            $column->getFilter ()->setValue ( $filter [$columnName] );

            if ($block->getCollection ()) {

                $field = ($column->getFilterIndex ()) ? $column->getFilterIndex () : $column->getIndex ();

                if ($column->getFilterConditionCallback ()) {

                    $column->getFilterConditionCallback ($block->getCollection (),$column);

                } else {

                    $cond = $column->getFilter ()->getCondition ();

                    if ($field && isset ( $cond )) {

                        $block->getCollection ()->addFieldToFilter ( $field, $cond );

                    }
                }
            }
        }
    }

    /*
     * Add Massactions Remove from Featured List, Add to Featured List and Change Featured Range
     * to Product Grid
     */
    public function addMassactionToProductGrid($observer)
    {
        if(Mage::getStoreConfig('featured_options/general/enable')){
            $block = $observer->getBlock();
            $block->getMassactionBlock()->addItem('remove', array(
                'label'=> Mage::helper('featured')->__('Remove from featured lists'),
                'url'  => $block->getUrl('featured/adminhtml_featured/massRemove'),
                'confirm' => Mage::helper('featured')->__('Are you sure?')
            ));

            $block->getMassactionBlock()->addItem('add', array(
                'label'=> Mage::helper('featured')->__('Add to featured lists'),
                'url'  => $block->getUrl('featured/adminhtml_featured/massAdd'),
            ));

            $ranges =  array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('featured')->__('Category')
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('featured')->__('Website')
                ),
                array(
                    'value' => 3,
                    'label' => Mage::helper('featured')->__('Both')
                )
            );

            $block->getMassactionBlock()->addItem('change_range', array(
                'label'=> Mage::helper('featured')->__('Change Featured Range'),
                'url'  => $block->getUrl('featured/adminhtml_featured/massChangeRange'),
                'additional' => array(
                    'visibility' => array(
                        'name' => 'ranges',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => Mage::helper('featured')->__('Range'),
                        'values' => $ranges
                    )
                )
            ));
        }

    }
}