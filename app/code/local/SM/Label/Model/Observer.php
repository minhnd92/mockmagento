<?php
class SM_Label_Model_Observer extends Mage_Core_Model_Observer
{
    public function addLabelToProductCollection($observer)
    {
        $collection = $observer->getCollection();

        $collection->addAttributeToSelect('label');

        foreach($observer->getCollection() as $_product){

            $labelIds = explode(',', $_product->getResource()->getAttributeRawValue($_product->getId(),'label'));

            $labelCollection = Mage::getModel('label/label')->getCollection()->addFieldToFilter('id',['in'=>$labelIds]);

            $_product->setLabel($labelCollection);
        }
    }

    public function addLabelToProduct($observer)
    {
        $_product = $observer->getProduct();

        $labelIds = explode(',', $_product->getResource()->getAttributeRawValue($_product->getId(),'label'));

        $labelCollection = Mage::getModel('label/label')->getCollection()->addFieldToFilter('id',['in'=>$labelIds]);

        $_product->setLabel($labelCollection);
    }
}