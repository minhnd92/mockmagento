<?php
class SM_Slider_Block_Adminhtml_Slider_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('sliderGrid');
        $this->setDefaultSort('slider_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('slider/slider')->getCollection();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('slider_id',[
            'header' => Mage::helper('slider')->__('ID'),
            'index' => 'slider_id'
        ]);

        $this->addColumn('slider_name',[
            'header' => Mage::helper('slider')->__('Name'),
            'index' => 'slider_name'
        ]);

        $this->addColumn('status', array(
            'header_css_class' => 'a-center',
            'header' => Mage::helper('slider')->__('Displayed'),
            'align' => 'center',
            'index' => 'slider_id',
            'renderer' => new SM_Slider_Block_Adminhtml_Renderer_Status(),
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit',['id'=>$row->getId()]);
    }
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('slider_id');
        $this->getMassactionBlock()->setFormFieldName('slider');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'    => Mage::helper('slider')->__('Delete'),
            'url'      => $this->getUrl('*/*/massDelete'),
            'confirm'  => Mage::helper('slider')->__('Are you sure?')
        ));
        return $this;
    }

}