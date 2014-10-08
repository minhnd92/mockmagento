<?php
class SM_Featured_Adminhtml_FeaturedController extends Mage_Adminhtml_Controller_Action
{
    /*
     * Remove products from featured list
     */
    public function massRemoveAction()
    {
        $this->_massUpdate('is_featured',0);
    }

    /*
     * Add products into featured list
     */
    public function massAddAction()
    {
        $this->_massUpdate('is_featured',1);
    }

    /*
     * Change featured range of products
     */
    public function massChangeRangeAction()
    {
        $range = (int) $this->getRequest()->getParam('ranges');
        $this->_massUpdate('featured_range',$range);

    }

    protected function _massUpdate($attribute,$value)
    {
        $productIds =  (array) $this->getRequest()->getParam('product');
        $storeId    = (int) $this->getRequest()->getParam('store', 0);

        try{
            Mage::getSingleton('catalog/product_action')
                ->updateAttributes($productIds,array($attribute=>$value),$storeId);
            Mage::getSingleton('adminhtml/session')->addSuccess(
                $this->__('Total of %d record(s) have been updated.', count($productIds))
            );
        }
        catch (Mage_Core_Model_Exception $e)
        {
            $this->_getSession()->addError($e->getMessage());
        }
        catch (Mage_Core_Exception $e)
        {
            $this->_getSession()->addError($e->getMessage());
        }
        catch (Exception $e) {
            $this->_getSession()
                ->addException($e, $this->__('An error occurred while updating the product(s) featured range.'));
        }

        $this->_redirectReferer();

    }
}