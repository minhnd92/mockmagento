<?php
$installer = $this;

$installer->startSetup();

$installer->getConnection()->addColumn(
    $installer->getTable('catalog/eav_attribute'),
    'filter_type',
    [
        'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'nullable' => true,
        'comment' => 'Filter Type'
    ]
);

$installer->endSetup();