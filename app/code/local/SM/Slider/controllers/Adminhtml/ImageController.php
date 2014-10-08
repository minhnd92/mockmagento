<?php

class SM_Slider_Adminhtml_ImageController extends Mage_Adminhtml_Controller_action
{

    protected function _initAction() {
        $this->loadLayout()
            ->_setActiveMenu('slider/items');

        return $this;
    }

    public function indexAction() {
        $this->_initAction()
            ->renderLayout();
    }

    public function editAction() {

        $id     = $this->getRequest()->getParam('id');
        $model  = Mage::getModel('slider/image')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('image_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('slider/items');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('slider/adminhtml_image_edit'))
                ->_addLeft($this->getLayout()->createBlock('slider/adminhtml_image_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('slider')->__('Image does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {

            if(isset($_FILES['image_path']['name']) && $_FILES['image_path']['name'] != '') {
                try {
                    /* Starting upload */
                    $uploader = new Varien_File_Uploader('image_path');

                    // Any extention would work
                    $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
                    $uploader->setAllowRenameFiles(false);

                    // Set the file upload mode
                    // false -> get the file directly in the specified folder
                    // true -> get the file in the product like folders
                    //	(file.jpg will go in something like /media/f/i/file.jpg)
                    $uploader->setFilesDispersion(false);

                    // We set media as the upload dir
                    $path = Mage::getBaseDir('media') . DS.'slider'.DS ;
                    $uploader->save($path, $_FILES['image_path']['name'] );

                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    Mage::getSingleton('adminhtml/session')->setFormData($data);
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                    return;
                }

                //this way the name is saved in DB
                $data['image_path'] = Mage::getBaseUrl('media').'slider'.DS.$_FILES['image_path']['name'];
            }


            $model = Mage::getModel('slider/image');
            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));

            try {
                if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
                    $model->setCreatedTime(now())
                        ->setUpdateTime(now());
                } else {
                    $model->setUpdateTime(now());
                }

                $model->save();

                if($data['slider_id'])
                {
                    $connection = Mage::getSingleton('core/resource')->getConnection('core_write');

                    $query = "select * from slider_image where slider_id = {$data['slider_id']} and image_id = {$model->getData('image_id')}";

                    $row = $connection->fetchAll($query);

                    if(empty($row)){
                        $connection->insert(
                            'slider_image',
                            array(
                                'slider_id' => $data['slider_id'],
                                'image_id' => $model->getData('image_id'),
                                'order' => $data['order'],
                                'title' => $data['title']
                            )
                        );
                    } else
                    {
                        $sql = "update slider_image
                                set `order` = '{$data['order']}', `title`= '{$data['title']}'
                                where slider_id={$data['slider_id']}
                                    and image_id = {$model->getData('image_id')}";
                        $connection->query($sql);
                    }
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('slider')->__('Item was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    if($data['slider_id'])
                    {
                        $this->_redirect('*/*/edit',['slider_id'=>$data['slider_id'],'id'=>$model->getData('image_id')]);
                        return;
                    }
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                if($data['slider_id'])
                {
                    $this->_redirect('*/adminhtml_slider/edit',['id'=>$data['slider_id'],'active_tab'=>'image_section']);
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('slider_id'=>$data['slider_id'] ,'id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('slider')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $model = Mage::getModel('slider/image');

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

    public function massDeleteAction() {
        $imageIds = $this->getRequest()->getParam('image');
        if(!is_array($imageIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($imageIds as $imageId) {
                    $image = Mage::getModel('slider/image')->load($imageId);
                    $image->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($imageIds)
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
        $imageIds = $this->getRequest()->getParam('image');
        if(!is_array($imageIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($imageIds as $imageId) {
                    $image = Mage::getSingleton('image/image')
                        ->load($imageId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($imageIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function deleteRelationAction()
    {
        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');

        $slider_id = $this->getRequest()->getParam('slider_id');

        $image_id = $this->getRequest()->getParam('id');

        $sql = 'delete from slider_image where slider_id = '.$slider_id.' and image_id = '.$image_id;

        $connection->query($sql);

        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('slider')->__('Image has been removed from slider'));


        $this->_redirect('*/adminhtml_slider/edit',['id'=>$slider_id]);
        return;
    }
}