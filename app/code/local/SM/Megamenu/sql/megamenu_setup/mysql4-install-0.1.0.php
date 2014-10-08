<?php
$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()->newTable($installer->getTable('megamenu/megamenu'))
    ->addColumn('item_id', Varien_Db_Ddl_Table::TYPE_INTEGER,null,[
        'unsigned' => true,
        'nullable' => false,
        'primary'   => true,
        'identity' =>true
    ])
    ->addColumn('item_name', Varien_Db_Ddl_Table::TYPE_TEXT,null,[
        'nullable' => false,
    ])
    ->addColumn('store_view', Varien_Db_Ddl_Table::TYPE_TEXT,null,[
        'nullable' => false,
    ])
    ->addColumn('sort_order', Varien_Db_Ddl_Table::TYPE_INTEGER,null,[
        'nullable' => false,
    ])
    ->addColumn('item_status', Varien_Db_Ddl_Table::TYPE_INTEGER,null,[
        'nullable' => false,
    ])
    ->addColumn('item_type', Varien_Db_Ddl_Table::TYPE_INTEGER,null,[
        'nullable' => false,
        'unsigned' => true
    ])
    ->addColumn('item_icon', Varien_Db_Ddl_Table::TYPE_TEXT,null,[
        'nullable' => true,
    ])
    ->addColumn('category_id', Varien_Db_Ddl_Table::TYPE_TEXT,null,[
        'nullable' => true,
    ])
    ->addColumn('item_link', Varien_Db_Ddl_Table::TYPE_TEXT,null,[
        'nullable' => true,
    ])
    ->addColumn('block_id', Varien_Db_Ddl_Table::TYPE_TEXT,null,[
        'nullable' => true,
    ])
;
$installer->getConnection()->createTable($table);

$installer->endSetup();