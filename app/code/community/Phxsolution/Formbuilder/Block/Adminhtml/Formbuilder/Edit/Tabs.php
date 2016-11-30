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
class Phxsolution_Formbuilder_Block_Adminhtml_Formbuilder_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('formbuilder_tabs');
	    $this->setName('formbuilder_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('formbuilder')->__('Form Information'));
  }
  protected function _beforeToHtml()
  {
      $this->addTab('general_section', array(
          'label'     => Mage::helper('formbuilder')->__('Form Information'),
          'title'     => Mage::helper('formbuilder')->__('Form Information'),
          'content'   => $this->getLayout()->createBlock('formbuilder/adminhtml_formbuilder_edit_tab_form')->toHtml(),
      ));

      //$fieldsCollection = Mage::helper('formbuilder')->getcurrentFormFieldsCollection();
      //if(count($fieldsCollection))
      //{
        $this->addTab('fieldsgrid_section', array(
            'label'     => Mage::helper('formbuilder')->__('Fields List'),
            'title'     => Mage::helper('formbuilder')->__('Fields List'),
            'url'   => $this->getUrl('*/*/fieldsgrid', array('_current' => true)),
            'class' => 'ajax',
        ));
      //}

      $this->addTab('fields_section', array(
          'label'     => Mage::helper('formbuilder')->__('Form Creator'),
          'title'     => Mage::helper('formbuilder')->__('Form Creator'),
          'url'   => $this->getUrl('*/*/options', array('_current' => true)),
          'class' => 'ajax',
      ));

		 return parent::_beforeToHtml();
  }
}