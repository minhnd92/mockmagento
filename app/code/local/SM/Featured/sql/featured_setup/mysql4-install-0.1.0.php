<?php

$installer = $this;

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$installer->startSetup();

$setup->addAttributeGroup('catalog_product','Default','Featured Product Information',1000);


$setup->addAttribute('catalog_product','is_featured',[
    'group' => 'Featured Product Information',
    'label' => Mage::helper('featured')->__('Is featured'),
    'note' => '',
    'type' => 'int',
    'input' => 'select',
    'frontend_class' => '',
    'source' => 'featured/attribute_source_featured',
    'backend' => '',
    'frontend' => '',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'required' => 1,
    'visible_on_front' => 0,
    'used_in_product_listing' => 1,
    'visible' => 1,
    'user_defined' => 1,
    'searchable' => 1,
    'filterable' => 1,
    'comparable' => 0,
    'visible_in_advanced_search' => 0,
    'is_html_allowed_on_front' => 0,
]);

$setup->addAttribute('catalog_product','featured_range',[
    'group' => 'Featured Product Information',
    'label' => Mage::helper('featured')->__('Featured on'),
    'note' => '',
    'type' => 'int',
    'input' => 'select',
    'frontend_class' => '',
    'source' => 'featured/attribute_source_featuredRange',
    'backend' => '',
    'frontend' => '',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'required' => 1,
    'visible_on_front' => 0,
    'used_in_product_listing' => 1,
    'visible' => 1,
    'user_defined' => 1,
    'searchable' => 1,
    'filterable' => 1,
    'comparable' => 0,
    'visible_in_advanced_search' => 0,
    'is_html_allowed_on_front' => 0,
]);

$installer->endSetup();