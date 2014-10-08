<?php
class SM_Slider_Block_Adminhtml_Image_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('slider/image')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('image_id',[
            'header' => Mage::helper('slider')->__('ID'),
            'index' => 'image_id'
        ]);
        $this->addColumn('image_name',[
            'header' => Mage::helper('slider')->__('Name'),
            'index' => 'image_name'
        ]);

        $this->addColumn('preview',[
            'header' => Mage::helper('slider')->__('Preview'),
            'index' => 'image_path',
            'renderer' => new SM_Slider_Block_Adminhtml_Renderer_Image()
        ]);
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('item_id');
        $this->getMassactionBlock()->setFormFieldName('image');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'    => Mage::helper('slider')->__('Delete'),
            'url'      => $this->getUrl('*/*/massDelete'),
            'confirm'  => Mage::helper('slider')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('slider/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
            'label'=> Mage::helper('slider')->__('Change status'),
            'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('slider')->__('Status'),
                    'values' => $statuses
                )
            )
        ));
        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit',['id'=>$row->getId()]);
    }
}