<?php
class SM_Filter_Model_Layer_Filter_Item extends Mage_Catalog_Model_Layer_Filter_Item
{
    public function getColor()
    {
        return $this->getLabel();
    }

    public function getUrl()
    {
        $type = $this->getFilter()->getAttributeModel()->getFilterType();
        $code = $this->getFilter()->getAttributeModel()->getAttributeCode();

        if(in_array($type,[2,3]) || !$type || $code=='price' )
        {
            $query = array(
                $this->getFilter()->getRequestVar()=>$this->getValue(),
                Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null // exclude current page from urls
            );
            $url = Mage::getUrl('*/*/view', array('_current'=>true, '_use_rewrite'=>true, '_query'=>$query));
            return $url;
        }

        $query = array(
            $this->getFilter()->getRequestVar().'[]'=>$this->getValue(),
            Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null // exclude current page from urls
        );

        $value = (Mage::app()->getRequest()->getParam($this->getFilter()->getRequestVar()));
        if(isset($value) && $value!=''){
            if(in_array($this->getValue(),$value) )
            {
                $params = Mage::app()->getRequest()->getParams();
                $params[$this->getFilter()->getRequestVar()] = array_diff($params[$this->getFilter()->getRequestVar()],$query);
                $url = Mage::getUrl('*/*/view',array('_current'=>false, '_use_rewrite'=>true, '_query'=>$params));
                return $url;
            }
        }
        $url = Mage::getUrl('*/*/view',array('_current'=>true, '_use_rewrite'=>true, '_query'=>$query));
        return $url;
    }

    public function getRemoveUrl()
    {
        $values = Mage::app()->getRequest()->getParam($this->getFilter()->getRequestVar());
        if(is_array($values)){
            $values = array_diff($values,[$this->getValue()]);
            $query = array($this->getFilter()->getRequestVar()=>$values);
        }else
        {
            $query = array($this->getFilter()->getRequestVar()=>$this->getFilter()->getResetValue());
        }

        $params['_current']     = true;
        $params['_use_rewrite'] = true;
        $params['_query']       = $query;
        $params['_escape']      = true;
        return Mage::getUrl('*/*/view', $params);
    }

    public function getIsSelected()
    {
        if($value = (Mage::app()->getRequest()->getParam($this->getFilter()->getRequestVar())))
        {
            if(in_array($this->getValue(),$value) )
            {
                return 1;
            }
        };
        return 0;
    }
}