<?php
class SM_Slider_Block_Adminhtml_Slider_Edit_Tab_Image_Choose extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('chooseGrid');
        $this->setUseAjax(true);
        $this->setDefaultSort('image_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(false);
    }

    protected function _prepareCollection()
    {
        if($slider_id = $this->getRequest()->getParam('id'))
        {
            $collection = Mage::getModel('slider/slider')->load($slider_id)->getAvailableImages();
        } else
        {
            $collection = Mage::getModel('slider/image')->getCollection();
        }
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

        $this->addColumn('add_ids', array(
            'header' => Mage::helper('slider')->__('Add to slider'),
            'type' => 'checkbox',
            'align' => 'center',
            'field_name' => 'add_ids[]',
            'index' => 'image_id'
        ));

        $this->addColumn('choose_image_id',[
            'header' => Mage::helper('slider')->__('ID'),
            'index' => 'image_id',
            'width' => '20px',
            'filter_condition_callback' =>[$this,'_imageIdFilter']
        ]);

        $this->addColumn('choose_image_path',[
            'header' => Mage::helper('slider')->__('Image'),
            'index' => 'image_path',
            'width' => '100px',
            'renderer' => new SM_Slider_Block_Adminhtml_Renderer_Image()
        ]);

        $this->addColumn('choose_image_name',[
            'header' => Mage::helper('slider')->__('Name'),
            'index' => 'image_name',
            'filter_condition_callback' =>[$this,'_imageNameFilter']
        ]);


        return parent::_prepareColumns();
    }

    protected function _imageIdFilter($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }
        $this->getCollection()->getSelect()->where(
            "image_id like ?", "%$value%");

        return $this;
    }

    protected function _imageNameFilter($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }
        $this->getCollection()->getSelect()->where(
            "image_name like ?", "%$value%");

        return $this;
    }

    /*
     * Prevent Filter on Massaction Column
     * */
    public function getNoFilterMassactionColumn()
    {
        return true;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/choose', array('_current'=>true));
    }
}