<?php
/*
/**
* Phxsolution Formbuilder
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@magentocommerce.com so you can be sent a copy immediately.
*
* Original code copyright (c) 2008 Irubin Consulting Inc. DBA Varien
*
* @category   adminhtml block
* @package    Phxsolution_Formbuilder
* @author     Murad Ali
* @contact    contact@phxsolution.com
* @site       www.phxsolution.com
* @copyright  Copyright (c) 2014 Phxsolution Formbuilder
* @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/
?>
<?php
//class Phxsolution_Formbuilder_Block_Adminhtml_Formbuilder_Edit_Tab_Options extends Mage_Adminhtml_Block_Widget
class Phxsolution_Formbuilder_Block_Adminhtml_Formbuilder_Edit_Tab_Options extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Options
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('formbuilder/catalog/product/edit/options.phtml');
    }
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->setChild('add_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label' => Mage::helper('catalog')->__('Add New Field'),
                    'class' => 'add',
                    'id'    => 'add_new_defined_option'
                ))
        );

        $this->setChild('options_box',
            $this->getLayout()->createBlock('formbuilder/adminhtml_formbuilder_edit_tab_options_option')
        );        
    }
    /*public function getAddButtonHtml()
    {
        return $this->getChildHtml('add_button');
    }
    public function getOptionsBoxHtml()
    {
        return $this->getChildHtml('options_box');
    }*/
}