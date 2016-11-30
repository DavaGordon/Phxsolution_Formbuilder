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
* @category   model
* @package    Phxsolution_Formbuilder
* @author     Murad Ali
* @contact    contact@phxsolution.com
* @site       www.phxsolution.com
* @copyright  Copyright (c) 2014 Phxsolution Formbuilder
* @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/
?>
<?php
class Phxsolution_Formbuilder_Model_Options extends Mage_Core_Model_Abstract
{
    protected $_isNewField = true;
    protected $_isNewOption;
    protected $_currentFieldId = 0;

    public function _construct()
    {
        parent::_construct();
        $this->_init('formbuilder/options');
    }
	public function getOptionsCollection($currentFieldId=0)
    {
    	$optionsModel = Mage::helper('formbuilder')->getOptionsModel();
		$optionsCollection = $optionsModel->getCollection();
    	if($currentFieldId)
    		$optionsCollection->addFieldToFilter('fields_index',array('eq'=>$currentFieldId));
        $optionsCollection->setOrder('sort_order','asc');
        return $optionsCollection;
    }
    public function saveOptions($optionsArray,$currentFieldId=0,$isNewField=true)
    {
        $this->_currentFieldId = $currentFieldId;
        $this->_isNewField = $isNewField;
        if($this->_isNewField)
        {
            $this->addOptions($optionsArray,$this->_currentFieldId);
        }
        else
        {
            //$this->removeOptions($this->_currentFieldId);
            //$this->addOptions($optionsArray,$this->_currentFieldId);
            $this->EditOptions($optionsArray,$this->_currentFieldId);
        }
    }
    public function EditOptions($optionsArray,$getFieldsIndex)
    {
        $optionsModel = Mage::helper('formbuilder')->getOptionsModel();
        $data = array();
        $optionsCollection = $this->getOptionsCollection($getFieldsIndex);
        if(count($optionsCollection))
        {
            foreach ($optionsArray as $optionId => $optionsArrayItem)
            {
                foreach ($optionsCollection as $key2 => $value2)
                {
                	if($value2['option_id']==$optionId)
                    {
                        $this->_isNewOption = false;
                        break;
                    }
                    else
                        $this->_isNewOption = true;
                }
                if($this->_isNewOption)
                {
                    if(!$optionsArrayItem['is_delete'])
                        $this->addOption($optionsArrayItem,$optionId);
                }
                else
                {
                    if($optionsArrayItem['is_delete'])
                        $this->removeOption( $value2['options_index'] );
                    else
                        $this->editOption( $optionsArrayItem,$value2['options_index'] );                    
                }
            }
        }
    }
    public function addOption($optionsArrayItem,$optionId)
    {
        $optionsModel = Mage::helper('formbuilder')->getOptionsModel();
        $data = array();
        $data['fields_index'] = $this->_currentFieldId;
        $data['is_delete'] = $optionsArrayItem['is_delete'];
        $data['title'] = $optionsArrayItem['title'];
        $data['sort_order'] = $optionsArrayItem['sort_order'];
        $data['option_id'] = $optionId;
        $optionsModel->setData($data);
        $optionsModel->save();
    }
    public function removeOption($getOptionsIndex)
    {
        $optionsModel = Mage::helper('formbuilder')->getOptionsModel();
        $optionsModel->load($getOptionsIndex);
        $optionsModel->delete();
    }
    public function editOption($optionsArrayItem,$getOptionsIndex)
    {
        $optionsModel = Mage::helper('formbuilder')->getOptionsModel();
        $data = array();
        $optionsModel->load($getOptionsIndex);
        $data['options_index'] = $getOptionsIndex;
        $data['title'] = $optionsArrayItem['title'];
        $data['sort_order'] = $optionsArrayItem['sort_order'];
        $optionsModel->setData($data);
        $optionsModel->save();
    }
    public function addOptions($optionsArray,$getFieldsIndex)
    {
        //$optionsModel = Mage::getModel('formbuilder/options');
        $optionsModel = Mage::helper('formbuilder')->getOptionsModel();
        $data = array();
        foreach ($optionsArray as $key => $optionsArrayItem)
        {
            if(!$optionsArrayItem['is_delete'])
            {
                $data['fields_index'] = $getFieldsIndex;
                $data['is_delete'] = $optionsArrayItem['is_delete'];
                $data['title'] = $optionsArrayItem['title'];
                $data['sort_order'] = $optionsArrayItem['sort_order'];
                $data['option_id'] = $key;
                $optionsModel->setData($data);
                $optionsModel->save();
            }
        }        
    }
    public function removeOptions($getFieldsIndex)
    {
        //$optionsCollection = Mage::getModel('formbuilder/options')->getCollection();
        $optionsCollection = $this->getOptionsCollection($getFieldsIndex);
        foreach ($optionsCollection as $key => $option)
        {
            if($option['fields_index']==$getFieldsIndex)
            {
                $currentOptionIndex = $option['options_index'];
                //$optionsModel = Mage::getModel('formbuilder/options')->load($currentOptionIndex);
                $optionsModel = Mage::helper('formbuilder')->getOptionsModel();
                $optionsModel->load($currentOptionIndex);
                $optionsModel->setOptionsIndex($currentOptionIndex);
                $optionsModel->delete();
            }
        }
    }
}