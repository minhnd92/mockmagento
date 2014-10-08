<?php
class SM_Slider_Adminhtml_SliderController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('slider/items');
        return $this;
    }

    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');

        $model = Mage::getModel('slider/slider')->load($id);

        if($model->getId() || $id==0)
        {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if(!empty($data))
            {
                $model->setData($data);
            }

            Mage::register('slider_data',$model);

            $this->loadLayout();

            $this->_setActiveMenu('slider/items');

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $block = $this->getLayout()->createBlock('slider/adminhtml_slider_edit');
            $this->_addContent($block)
                ->_addLeft($this->getLayout()->createBlock('slider/adminhtml_slider_edit_tabs'));

            $this->renderLayout();
        }else
        {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('slider')->__("Slider does not exist"));
            $this->_redirect('*/*/');
        }
    }

    public function editImageAction()
    {
        $id = $this->getRequest()->getParam('id');

        $model = Mage::getModel('slider/image')->load($id);

        if($model->getId() || $id==0)
        {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if(!empty($data))
            {
                $model->setData($data);
            }

            Mage::register('image_data',$model);

            $this->loadLayout();
            $this->_setActiveMenu('slider/items');

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $block = $this->getLayout()->createBlock('slider/adminhtml_slider_image_edit');
            $this->_addContent($block)
                ->_addLeft($this->getLayout()->createBlock('slider/adminhtml_slider_image_edit_tabs'));

            $this->renderLayout();
        }else
        {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('slider')->__("Slider does not exist"));
            $this->_redirect('*/*/');
        }
    }

    public function saveAction()
    {

        if($data = $this->getRequest()->getPost())
        {
            $model = Mage::getModel('slider/slider');
            $model->setData($data)->setId($this->getRequest()->getParam('id'));

            try{
                if($model->getData('is_active')==1)
                {
                    $collection = Mage::getModel('slider/slider')->getCollection()
                        ->addFieldToFilter('is_active',1)
                        ->addFieldToFIlter('slider_id',['neq'=>$model->getData('slider_id')]);

                    foreach($collection as $obj)
                    {
                        $obj->setData('is_active',0)->save();
                    }
                }

                $model->save();

                Mage::register('slider_data',$model);

                $this->_massRemoveAction();

                $this->_massAddAction();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('slider')->__('Slider was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                if($this->getRequest()->getParam('id'))
                {
                    $this->_redirect('*/*/');
                } else {
                    $this->_redirect('*/*/edit',['id'=>$model->getData('slider_id'),'active_tab'=>'image_section']);
                }
                return;
            }catch(Exception $e)
            {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('slider')->__('Unable to find slider to save'));
        $this->_redirect('*/*/');
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function deleteAction() {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $model = Mage::getModel('slider/slider');

                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Slider was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $sliderIds = $this->getRequest()->getParam('slider');
        if(!is_array($sliderIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($sliderIds as $sliderId) {
                    $slider = Mage::getModel('slider/slider')->load($sliderId);
                    $slider->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($sliderIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction()
    {
        $this->_redirect('*/*/index');
    }

    protected function _massRemoveAction()
    {
        $imageIds = $this->getRequest()->getParam('remove_ids');

        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');

        $sliderId = Mage::registry('slider_data')->getData('slider_id');

        foreach ($imageIds as $image_id)
        {
            $query = "delete from slider_image where slider_id={$sliderId} and image_id={$image_id}";
            $connection->query($query);
        }

        return;
    }

    protected  function _massAddAction()
    {
        $imageIds = $this->getRequest()->getParam('add_ids');

        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');

        $sliderId = Mage::registry('slider_data')->getData('slider_id');

        foreach ($imageIds as $image_id)
        {
            $query = "insert into slider_image values('',$sliderId,$image_id,'','')";
            $connection->query($query);
        }

        return;

    }

    public function gridAction(){
        $this->loadLayout();

        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('slider/adminhtml_slider_edit_tab_image_grid')->toHtml()
        );
    }

    public function chooseAction(){
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('slider/adminhtml_slider_edit_tab_image_choose')->toHtml()
        );
    }

    public function changeSliderAction(){

        $id = $this->getRequest()->getParam('id');
        try{
            $collection = Mage::getModel('slider/slider')->getCollection()
                ->addFieldToFilter('is_active',1);

            foreach($collection as $obj)
            {
                $obj->setData('is_active',0)->save();
            }

            $model = Mage::getModel('slider/slider')->load($id)->setData('is_active',1)->save();

            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('slider')->__('Changed displayed slider successfully'));

            $this->_redirectReferer();

            return;
        }catch(Exception $e)
        {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());

            $this->_redirectReferer();

            return;
        }
    }

}