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
* @category   frontend block
* @package    Phxsolution_Formbuilder
* @author     Murad Ali
* @contact    contact@phxsolution.com
* @site       www.phxsolution.com
* @copyright  Copyright (c) 2014 Phxsolution Formbuilder
* @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/
?>
<?php
class Phxsolution_Formbuilder_Block_Frontend_Text extends Mage_Core_Block_Template
{	
	protected function _prepareLayout()
    {
        //echo "<h1>test</h1>";
    }
    public function getDefaultValue()
    {
        /*return $this->getProduct()->getPreconfiguredValues()->getData('options/' . $this->getOption()->getId());*/
    	/*return "Enter text for ".$this->field['fields_index'];*/
    	/*$helper = Mage::helper('formbuilder');
    	$getFormData = $helper->getFormData();
    	return "getFormData = ".$getFormData;*/
    	return "";
    }	
}