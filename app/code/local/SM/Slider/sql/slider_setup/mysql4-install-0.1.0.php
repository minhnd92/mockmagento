<?php
$installer = $this;

$installer->startSetUp();

$tableSlider = $installer->getTable('slider/slider');

if($installer->tableExists($tableSlider)){
    $installer->getConnection()->dropTable($tableSlider);
}

$newTableSlider = $installer->getConnection()->newTable($tableSlider);

$newTableSlider     ->addColumn('slider_id',Varien_Db_Ddl_Table::TYPE_INTEGER,null,[
                        'primary' => true,
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false
                    ],'Slider Id')
                    ->addColumn('slider_name',Varien_Db_Ddl_Table::TYPE_TEXT,null,[
                        'nullable' => false,
                    ],'Slider Name')
                    ->addColumn('is_active',Varien_DB_Ddl_Table::TYPE_INTEGER,null,[
                        'nullable' => false
                    ]);

$tableImage = $installer->getTable('slider/image');

if($installer->tableExists($tableImage))
{
    $installer->getConnection()->dropTable($tableImage);
}

$newTableImage = $installer->getConnection()->newTable($tableImage);

$newTableImage      ->addColumn('image_id',Varien_Db_Ddl_Table::TYPE_INTEGER,null,[
                        'primary' => true,
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false
                    ])
                    ->addColumn('image_path',Varien_Db_Ddl_Table::TYPE_TEXT,null,[
                        'nullable' => false
                    ])
                    ->addColumn('image_name',Varien_Db_Ddl_Table::TYPE_TEXT,null,[
                        'nullable' => false
                    ]);

if($installer->tableExists('slider_image'))
{
    $installer->getConnection()->dropTable('slider_image');
}

$newTableRelation = $installer->getConnection()->newTable('slider_image');

$newTableRelation      ->addColumn('id',Varien_Db_Ddl_Table::TYPE_INTEGER,null,[
    'primary' => true,
    'identity' => true,
    'unsigned' => true,
    'nullable' => false
])
    ->addColumn('slider_id',Varien_Db_Ddl_Table::TYPE_INTEGER,null,[
        'nullable' => false
    ])
    ->addColumn('image_id',Varien_Db_Ddl_Table::TYPE_INTEGER,null,[
        'nullable' => false
    ])
    ->addColumn('order',Varien_Db_Ddl_Table::TYPE_INTEGER,null,[
        'nullable' => false
    ])

    ->addColumn('title',Varien_Db_Ddl_Table::TYPE_TEXT,null,[
        'nullable' => false
    ])
    ->addForeignKey(
        $installer->getFkName('slider_image','slider_id','slider/slider','slider_id'),
        'slider_id',
        $installer->getTable('slider/slider'),
        'slider_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $installer->getFkName('slider_image','image_id','slider/image','image_id'),
        'image_id',
        $installer->getTable('slider/image'),
        'image_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    );


$installer->getConnection()->createTable($newTableSlider);
$installer->getConnection()->createTable($newTableImage);
$installer->getConnection()->createTable($newTableRelation);

$installer->endSetup();