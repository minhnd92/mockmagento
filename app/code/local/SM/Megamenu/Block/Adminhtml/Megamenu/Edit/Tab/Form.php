<?php

class SM_Megamenu_Block_Adminhtml_Megamenu_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset('megamenu_form', array(
                'legend' => Mage::helper('megamenu')->__('Menu Item information'))
        );

        $fieldset->addField('item_type', 'select', array(
            'label' => Mage::helper('megamenu')->__('Type'),
            'name' => 'item_type',
            'required' => true,
            'class' => 'required-entry',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('megamenu')->__('Category')
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('megamenu')->__('CMS Block')
                ),
                array(
                    'value' => 3,
                    'label' => Mage::helper('megamenu')->__('CMS Page')
                )
            )
        ));

        $fieldset->addField('item_name', 'text', array(
            'label' => Mage::helper('megamenu')->__('Name'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'item_name',
        ));

        $fieldset->addField('store_view', 'multiselect', array(
            'label' => Mage::helper('megamenu')->__('Store View'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'store_view[]',
            'values' => $this->_getListStoreView(),
        ));

        $fieldset->addField('sort_order', 'text', array(
            'label' => Mage::helper('megamenu')->__('Sort Order'),
            'required' => false,
            'name' => 'sort_order'
        ));

        $fieldset->addField('item_status', 'select', array(
            'label' => Mage::helper('megamenu')->__('Status'),
            'name' => 'item_status',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('megamenu')->__('Enabled'),
                ),

                array(
                    'value' => 0,
                    'label' => Mage::helper('megamenu')->__('Disabled'),
                ),
            ),
        ));

        $fieldset->addField('block_id', 'select', array(
            'label' => Mage::helper('megamenu')->__('CMS Block'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'block_id',
            'values' => $this->_getCmsBlocks()
        ));

        $fieldset->addField('item_link', 'select', array(
            'label' => Mage::helper('megamenu')->__('CMS Page'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'item_link',
            'values' => $this->_getCmsPages()
        ));

        $fieldset->addField('category_id', 'multiselect', array(
            'label' => Mage::helper('megamenu')->__('Category'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'category_id[]',
            'values' => $this->_getListCategories(),
        ));

        /*
         * Add Field Denpendency for category_id, block_id and item_link to item_type
         * */
        $this->setChild('form_after', $this->getLayout()
                ->createBlock('adminhtml/widget_form_element_dependence')
                ->addFieldMap('item_type', 'item_type')
                ->addFieldMap('block_id', 'block_id')
                ->addFieldMap('item_link', 'item_link')
                ->addFieldMap('category_id', 'category_id')
                ->addFieldDependence('category_id', 'item_type', 1)
                ->addFieldDependence('block_id', 'item_type', 2)
                ->addFieldDependence('item_link', 'item_type', 3)
        );

        if (Mage::getSingleton('adminhtml/session')->getMegamenuData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getMegamenuData());
            Mage::getSingleton('adminhtml/session')->setMegamenuData(null);
        } elseif (Mage::registry('megamenu_data')) {
            $form->setValues(Mage::registry('megamenu_data')->getData());
        }
        return parent::_prepareForm();
    }

    /*
     * Get List of all store view available in all websites
     *
     * */
    protected function _getListStoreView()
    {
        $websites = Mage::getModel('core/website')->getCollection()->addFieldToFilter('code', ['neq' => 'admin']);

        $store_view_list = [];

        $id = Mage::app()->getRequest()->getParam('id');

        $currentStoreViewList = Mage::getModel('megamenu/megamenu')->load($id)->getData('store_view');

        $currentStoreViewArray = explode('|', trim($currentStoreViewList, '|'));

        foreach ($websites as $website) {
            $store_view_list[] = array(
                'value' => 'website:' . $website->getData('website_id'),
                'label' => Mage::helper('megamenu')->__($website->getData('name')),
            );

            foreach ($website->getGroupCollection() as $store) {
                $store_view_list[] = array(
                    'value' => 'store_group:' . $store->getData('group_id'),
                    'label' => Mage::helper('megamenu')->__('--------' . $store->getData('name') . '--------')
                );

                foreach ($store->getStoreCollection() as $storeView) {
                    $store_view_list[] = array(
                        'value' => $storeView->getData('store_id'),
                        'label' => Mage::helper('megamenu')->__('----------------' . $storeView->getData('name') . '----------------')
                    );
                }
            }
        }

        return $store_view_list;
    }

    /*
     * Get list of all CMS Blocks created by admin users
     * */
    protected function _getCmsBlocks()
    {
        $listOption = [];

        $listBlocks = Mage::getModel('cms/block')->getCollection();

        foreach ($listBlocks as $block) {
            $listOption[] = array(
                'label' => Mage::helper('megamenu')->__($block->getData('title')),
                'value' => $block->getData('block_id')
            );
        }

        return $listOption;
    }

    /*
     * Get list of all CMS Pages created by admin users
     * */
    protected function _getCmsPages()
    {
        $listOption = [];

        $listPages = Mage::getModel('cms/page')->getCollection();

        foreach ($listPages as $page) {
            $listOption[] = array(
                'label' => Mage::helper('megamenu')->__($page->getData('title')),
                'value' => $page->getData('identifier')
            );
        }

        return $listOption;
    }

    /*
     * Get List of all Categories in all websites
     * */
    protected function _getListCategories()
    {
        $listOption = [];

        $listCategories = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('*');

        foreach ($listCategories as $category) {
            $listOption[] = array(
                'label' => Mage::helper('megamenu')->__($category->getName()),
                'value' => $category->getId()
            );
        }

        return $listOption;
    }
}