<?php
class SM_Slider_Block_Adminhtml_Slider_Edit_Tab_Image_Image_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('imageGrid');
        $this->setUseAjax(true);
        $this->setDefaultSort('image_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(false);
    }

    protected function _prepareCollection()
    {
        if($slider_id = (int) $this->getRequest()->getParam('id'))
        {
            $collection = Mage::getModel('slider/slider')->load($slider_id)->getAllImages();
        } else $collection = null;

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

        $this->addColumn('remove_ids', array(
            'header' => Mage::helper('slider')->__('Remove'),
            'type' => 'checkbox',
            'align' => 'center',
            'field_name' => 'remove_ids[]',
            'index' => 'image_id'
        ));

        $this->addColumn('image_id',[
            'header' => Mage::helper('slider')->__('ID'),
            'index' => 'image_id',
            'width' => '20px',
            'filter_condition_callback' =>array($this,'_imageIdFilter')
        ]);

        $this->addColumn('image_path',[
            'header' => Mage::helper('slider')->__('Image'),
            'index' => 'image_path',
            'width' => '100px',
            'renderer' => new SM_Slider_Block_Adminhtml_Renderer_Image()
        ]);

        $this->addColumn('image_name',[
            'header' => Mage::helper('slider')->__('Name'),
            'index' => 'image_name',
            'filter_condition_callback' =>[$this,'_imageNameFilter']
        ]);

        $this->addColumn('title',[
            'header' => Mage::helper('slider')->__('Title'),
            'index' => 'title',
            'filter_condition_callback' =>[$this,'_titleFilter']

        ]);

        $this->addColumn('order',[
            'header' => Mage::helper('slider')->__('Order'),
            'index' => 'order',
            'filter_condition_callback' =>[$this,'_indexFilter']

        ]);

        return parent::_prepareColumns();
    }

    protected function _imageIdFilter($collection, $column)
    {


        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }
        $this->getCollection()->getSelect()->where(
            "image.image_id like ?", "%$value%");

        return $this;
    }

    protected function _imageNameFilter($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }
        $this->getCollection()->getSelect()->where(
            "image.image_name like ?", "%$value%");

        return $this;
    }

    protected function _titleFilter($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }
        $this->getCollection()->getSelect()->where(
            "slider_image.title like ?", "%$value%");

        return $this;
    }

    protected function _indexFilter($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }
        $this->getCollection()->getSelect()->where(
            "slider_image.index like ?", "%$value%");

        return $this;
    }

    public function getNoFilterMassactionColumn()
    {
        return true;
    }

    public function getRowUrl($row)
    {
        $id = $this->getRequest()->getParam('id');
        return $this->getUrl('*/adminhtml_image/edit',['slider_id'=>$id,'id'=>$row->getData('image_id')]);
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

}