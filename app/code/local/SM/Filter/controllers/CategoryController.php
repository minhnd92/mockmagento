<?php
require_once Mage::getModuleDir('controllers', 'Mage_Catalog').DS.'CategoryController.php';

class SM_Filter_CategoryController extends Mage_Catalog_CategoryController
{
    public function viewAction()
    {
        if ($category = $this->_initCatagory()) {
            $design = Mage::getSingleton('catalog/design');
            $settings = $design->getDesignSettings($category);

            // apply custom design
            if ($settings->getCustomDesign()) {
                $design->applyCustomDesign($settings->getCustomDesign());
            }

            Mage::getSingleton('catalog/session')->setLastViewedCategoryId($category->getId());

            $update = $this->getLayout()->getUpdate();
            $update->addHandle('default');

            if (!$category->hasChildren()) {
                $update->addHandle('catalog_category_layered_nochildren');
            }
            $this->addActionLayoutHandles();
            $update->addHandle($category->getLayoutUpdateHandle());
            $update->addHandle('CATEGORY_' . $category->getId());
            $this->loadLayoutUpdates();

            // apply custom layout update once layout is loaded
            if ($layoutUpdates = $settings->getLayoutUpdates()) {
                if (is_array($layoutUpdates)) {
                    foreach($layoutUpdates as $layoutUpdate) {
                        $update->addUpdate($layoutUpdate);
                    }
                }
            }

            $this->generateLayoutXml()->generateLayoutBlocks();
            // apply custom layout (page) template once the blocks are generated
            if ($settings->getPageLayout()) {
                $this->getLayout()->helper('page/layout')->applyTemplate($settings->getPageLayout());
            }

            if ($root = $this->getLayout()->getBlock('root')) {
                $root->addBodyClass('categorypath-' . $category->getUrlPath())
                    ->addBodyClass('category-' . $category->getUrlKey());
            }

            $this->_initLayoutMessages('catalog/session');
            $this->_initLayoutMessages('checkout/session');



            $headers = apache_request_headers();
            $isAjax = (isset($headers['X-Requested-With']) && $headers['X-Requested-With'] == 'XMLHttpRequest');

            if($isAjax){
                $result = array();
                    $result['productList'] = $this->getLayout()->getBlock('product_list')->toHtml();
                    $result['leftFirst'] = $this->getLayout()->getBlock('left_first')->toHtml();

                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                return;
            }else{
                $this->renderLayout();
            }
        }
        elseif (!$this->getResponse()->isRedirect()) {
            $this->_forward('noRoute');
        }
    }
}