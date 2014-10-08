<?php
class SM_Slider_Block_Adminhtml_Image_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('image_form',['legend'=>Mage::helper('slider')->__('General Information')]);

        if($this->getRequest()->getParam('slider_id'))
        {
            $fieldset->addField('slider_id', 'text', array(
                'name'      => 'slider_id',
                'style' => 'display:none'
            ));

            $fieldset->addField('title','text',[
                'label' => Mage::helper('slider')->__('Title'),
                'name' => 'title',
            ]);

            $fieldset->addField('order','text',[
                'label' => Mage::helper('slider')->__('Order'),
                'name' => 'order',
            ]);

        }

        $fieldset->addField('image_name', 'text', array(
            'label'     => Mage::helper('slider')->__('Name'),
            'name'      => 'image_name',
            'required' => true,
            'class' => 'required-entry'
        ));



        if(Mage::registry('image_data')->getData('image_path'))
        {
            $fieldset->addType('thumbnail','SM_Slider_Varien_Data_Form_Element_Thumbnail');

            $fieldset->addField('image_path', 'thumbnail', array(
                'label'     => Mage::helper('slider')->__('Preview'),
                'name'      => 'image_path',
                'style'   => "display:none;",
            ));

        }else{
            $fieldset->addField('image_path', 'file', array(
                'label'     => Mage::helper('slider')->__('Upload'),
                'name'      => 'image_path',
                'required' => true,
                'class' => 'required-entry'
            ));
        }



        if ( Mage::getSingleton('adminhtml/session')->getImageData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getImageData());
            Mage::getSingleton('adminhtml/session')->getImageData(null);
        } elseif ( Mage::registry('image_data') ) {
            $form->setValues(Mage::registry('image_data')->getData());
        }

        if($this->getRequest()->getParam('slider_id'))
        {
            if($this->getRequest()->getParam('id'))
            {
                $slider_id = $this->getRequest()->getParam('slider_id');
                $image_id = $this->getRequest()->getParam('id');

                $collection = Mage::getModel('slider/slider')->getCollection();

                $helper = Mage::helper('slider');

                $collection->getSelect()
                    ->join(array('slider_image'=>$helper->_getTableName('slider_image')),'slider_image.slider_id=main_table.slider_id')
                    ->join(array('image'=>$helper->_getTableName('image')),'slider_image.image_id=image.image_id')
                    ->reset(Zend_Db_Select::COLUMNS)
                    ->columns(['image_path','image_name'],'image')
                    ->columns(['order','title'],'slider_image')
                    ->columns(['slider_id'])
                    ->where('main_table.slider_id='.$slider_id)
                    ->where('slider_image.image_id='.$image_id);

                foreach($collection as $data){
                    $form->setValues($data->getData());
                }

            }else
            {
                $form->setValues(['slider_id'=>$this->getRequest()->getParam('slider_id')]);
            }
        }

        return parent::_prepareForm();
    }
}