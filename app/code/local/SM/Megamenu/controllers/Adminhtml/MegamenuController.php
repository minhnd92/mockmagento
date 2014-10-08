<?php

class SM_Megamenu_Adminhtml_MegamenuController extends Mage_Adminhtml_Controller_action
{
    /*
     * Set active for menu MegaMenu
     * */
	protected function _initAction()
    {
		$this->loadLayout()
			->_setActiveMenu('megamenu/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction()
    {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('megamenu/megamenu')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);

			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('megamenu_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('megamenu/items');

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('megamenu/adminhtml_megamenu_edit'))
				->_addLeft($this->getLayout()->createBlock('megamenu/adminhtml_megamenu_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('megamenu')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction()
    {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {

            /*
             * Process data information before save
             * */
            $data = $this->_manipulateData($data);

			$model = Mage::getModel('megamenu/megamenu');		
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try {
				$model->save();

                Mage::getSingleton('adminhtml/session')->addSuccess('Item has been successfully saved');

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('megamenu')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('megamenu/megamenu');
				 
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
        $megamenuIds = $this->getRequest()->getParam('megamenu');
        if(!is_array($megamenuIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($megamenuIds as $megamenuId) {
                    $megamenu = Mage::getModel('megamenu/megamenu')->load($megamenuId);
                    $megamenu->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($megamenuIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /*
     * Process information from field include category_id, store_view
     * */
    protected function _manipulateData($data)
    {

        $data['category_id'] = implode(',',$data['category_id']);

        foreach ($data['store_view'] as $key => $value)
        {

            if(!is_numeric($value)){

                $array = explode(':',$value);

                $id = array_pop($array);

                $obj = Mage::getModel('core/'.array_shift($array))->load($id);

                foreach($obj->getStoreIds() as $storeId)
                {
                    $data['store_view'][] = $storeId;
                }

                unset($data['store_view'][$key]);
            }
        }

        $data['store_view'] = ','.implode(',',$data['store_view']).',';
        return $data;
    }
}