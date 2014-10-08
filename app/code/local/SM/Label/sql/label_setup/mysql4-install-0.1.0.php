<?php
$installer = $this;

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$installer->startSetup();

$setup->addAttributeGroup('catalog_product','Default','Label');

$setup->addAttribute('catalog_product','label',[
    'group' => 'Label',
    'label' => Mage::helper('label')->__('Choose label'),
    'type' => 'text',
    'input' => 'multiselect',
    'source' => 'label/attribute_source_label',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible_on_front' => 1,
    'used_in_product_listing' => 1,
    'visible' => 1,
    'user_defined' => 1,
    'searchable' => 1,
    'filterable' => 1,
    'visible_in_advanced_search'=>0,
    'required' => 0,
    'is_html_allowed_on_front' => 0,
    'backend' => 'eav/entity_attribute_backend_array',

]);

$table = $installer->getTable('label/label');

if($installer->tableExists($table))
{
    $installer->getConnection()->dropTable($table);
}

$newTable = $installer->getConnection()->newTable($table);

$newTable ->addColumn('id',Varien_Db_Ddl_Table::TYPE_INTEGER,null,[
        'primary' => true,
        'identity' => true,
        'unsigned' => true,
        'nullable' => false
    ],'Label Id')

    ->addColumn('name',Varien_Db_Ddl_Table::TYPE_TEXT,null,[
        'nullable' => false
    ],'Label Name')

    ->addColumn('path',Varien_Db_Ddl_Table::TYPE_TEXT,null,[
        'nullable' => false
    ],'Label path')

    ->addColumn('position',Varien_Db_Ddl_Table::TYPE_INTEGER,null,[
        'nullable' => false
    ],'Label Position')
;

$installer->getConnection()->createTable($newTable);

$installer->endSetup();