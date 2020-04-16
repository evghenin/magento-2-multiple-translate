<?php
/**
 * Magepow 
 * @category    Magepow 
 * @copyright   Copyright (c) 2014 Magepow (http://www.magepow.com/) 
 * @license     http://www.magepow.com/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2016-01-05 10:40:51
 * @@Modify Date: 2018-03-27 11:40:46
 * @@Function:
 */

namespace Magepow\MultiTranslate\Controller\Adminhtml\Catalog;

class Save extends \Magepow\MultiTranslate\Controller\Adminhtml\Catalog
{

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $resultRedirect = $this->_resultRedirectFactory->create();

        if ($data = $this->getRequest()->getPostValue()) {
            $use_default = isset($data['use_default']) ? $data['use_default'] : array();

            if ($id = $this->getRequest()->getParam('entity_id')) {
                $_data = $data;
                if(isset($_data['use_default']))  unset($_data['use_default']);
                if(isset($_data['form_key']))  unset($_data['form_key']);
                if(isset($_data['entity_id']))  unset($_data['entity_id']);
                foreach ( $_data as $key => $value) {
                	$productFactory = $this->_productFactory->create();
                    $store_id = 0;
                    $tmp = explode('_', $key);
                    if(!isset($tmp[1]) || !$tmp[1]) continue;
                    $product_id =  (int) $tmp[1];

                    if(isset($tmp[2]) && $tmp[2]){
                        $store_id =  (int) $tmp[2];
                    }

                    $product = $productFactory->setStoreId($store_id)->load($product_id);

                    /**
                     * Check "Use Default Value" checkboxes values
                     */
                    if (isset($data['use_default']["$key"])) {
                        $product->setData('name', null);
                        // if ($model->hasData('use_config_name')) {
                            $product->setData('use_config_name', false);
                        // }
                    } else {
                        $product->setData('name', $value);
                    }

                    try {
                            $product->save();
                        } catch (\Exception $e) {
                            $this->messageManager->addError($e->getMessage());
                            $this->messageManager->addException($e, __('Something went wrong while saving the product.'));
                        }
                }
            }

            // $this->_getSession()->setFormData($data);

            // return $resultRedirect->setPath(
            //     '*/*/edit',
            //     ['category_id' => $this->getRequest()->getParam('entity_id')]
            // );
            
            return $resultRedirect->setPath('*/*/');
        }

        return $resultRedirect->setPath('*/*/');
    }
}