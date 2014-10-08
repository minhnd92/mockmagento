<?php
/*
 * Adminhtml Megamenu grid block
 *
 * @category    SM
 * @package    SM_Megamenu
 * @author    MinhND <minhnd@smartosc.com>
 */
class SM_Megamenu_Block_Adminhtml_Megamenu_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('megamenuGrid');
        $this->setDefaultSort('item_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('megamenu/megamenu')->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('item_id', array(
            'header'    => Mage::helper('megamenu')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'item_id',
        ));

        $this->addColumn('item_name', array(
            'header'    => Mage::helper('megamenu')->__('Name'),
            'align'     =>'left',
            'index'     => 'item_name',
        ));

        $this->addColumn('item_type', array(
			'header'    => Mage::helper('megamenu')->__('Type'),
			'width'     => '150px',
			'index'     => 'item_type',
            'type'      => 'options',
            'options'   => array(
                1 =>    'Category',
                2 =>    'Block',
                3 =>    'Link'
            )
        ));

        $this->addColumn('sort_order',[
            'header' => Mage::helper('megamenu')->__('Order'),
            'width' => '50px',
            'index' => 'sort_order'
        ]);


        $this->addColumn('item_status', array(
            'header'    => Mage::helper('megamenu')->__('Status'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'item_status',
            'type'      => 'options',
            'options'   => array(
                1 => 'Enabled',
                0 => 'Disabled',
            ),
        ));

        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('megamenu')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('megamenu')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));

		$this->addExportType('*/*/exportCsv', Mage::helper('megamenu')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('megamenu')->__('XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('item_id');
        $this->getMassactionBlock()->setFormFieldName('megamenu');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'    => Mage::helper('megamenu')->__('Delete'),
            'url'      => $this->getUrl('*/*/massDelete'),
            'confirm'  => Mage::helper('megamenu')->__('Are you sure?')
        ));

        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}