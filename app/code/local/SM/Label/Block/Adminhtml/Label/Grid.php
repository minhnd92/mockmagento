<?php
class SM_Label_Block_Adminhtml_Label_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('labelGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('label/label')->getCollection();
        $this->setCollection($collection);
        parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id',[
            'header' => Mage::helper('label')->__('ID'),
            'index' => 'id'
        ]);

        $this->addColumn('name',[
            'header'=> Mage::helper('label')->__('Name'),
            'index' => 'name'
        ]);

        $this->addColumn('position',[
            'header' => Mage::helper('label')->__('Position'),
            'index' => 'position',
            'type' => 'options',
            'options' => [
                    1 => Mage::helper('label')->__('Top-Left'),
                    2 => Mage::helper('label')->__('Top-Right'),
                    3 => Mage::helper('label')->__('Bot-Left'),
                    4 => Mage::helper('label')->__('Bot-Right'),

            ]
        ]);

        $this->addColumn('image',[
            'header'=>Mage::helper('label')->__('Image'),
            'index' => 'path',
            'renderer'  => new SM_Label_Model_Renderer_Image()
        ]);

        parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit',['id'=>$row->getId()]);
    }
}