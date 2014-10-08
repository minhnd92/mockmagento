<?php
class SM_Filter_Adminhtml_FilterController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('catalog/items');

        $this->_addLeft($this->getLayout()->createBlock('filter/adminhtml_tree'));
        $this->renderLayout();
    }
}