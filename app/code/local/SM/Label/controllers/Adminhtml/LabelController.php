<?php
class SM_Label_Adminhtml_LabelController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout()->_setActiveMenu('catalog/label');
        $this->renderLayout();
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('label/label')->load($id);

        if($model->getId() ||$id == 0)
        {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if(!empty($data))
            {
                $model->setData($data);
            }

            Mage::register('label_data',$model);

            $this->loadLayout();
            $this->_setActiveMenu('catalog/label');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Label Manager'),Mage::helper('adminhtml')->__('Label Manager'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent(
                $this->getLayout()->createBlock('label/adminhtml_label_edit')
            );

            $this->renderLayout();
        } else{
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('label')->__('Label does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function saveAction()
    {
        if($data = $this->getRequest()->getPost())
        {
            if(isset($_FILES['image']['name']) && $_FILES['image']['name'] !=''){
                try{
                    $uploader = new Varien_File_Uploader('image');

                    $uploader->setAllowedExtensions(['jpg','png','jpeg']);
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(false);

                    $path = Mage::getBaseDir('media') . DS . 'label' . DS;

                    $uploader->save($path, $_FILES['image']['name']);
                } catch(Exception $e)
                {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    $this->_redirect('*/*/');
                    return;
                }

                $data['path'] = $_FILES['image']['name'];
            }

            $model = Mage::getModel('label/label');

            if($model->getData('path'))
            {
                try{
                    unlink(Mage::getBaseDir('media'). DS . 'label'. DS . $model->getData('path'));
                }catch (Exception $e)
                {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                }
            }

            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));

            try{
                $model->save();

                if($this->getRequest()->getParam('back'))
                {
                    $this->_redirect('*/*/edit',['id'=>$model->getId()]);
                    return;
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('label')->__('Successfully saved'));
                $this->_redirect('*/*/');
                return;
            }catch(Exception $e)
            {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('label')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
        return;
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function deleteAction()
    {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $model = Mage::getModel('label/label');

                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }
}