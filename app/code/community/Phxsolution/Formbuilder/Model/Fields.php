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
class Phxsolution_Formbuilder_Model_Fields extends Mage_Core_Model_Abstract
{
    protected $_isNewField = true;
    protected $_currentFormId = 0;

    public function _construct()
    {
        parent::_construct();
        $this->_init('formbuilder/fields');
    }
    public function getCheckboxTypeIds($currentFormId=0)
    {
        if($currentFormId)
        {
            $fieldsCollection = $this->getFieldsCollection($currentFormId);
            $fieldsCollection->addFieldToFilter('status',array('eq'=>1));
            $fieldsCollection->addFieldToFilter('type',array('eq'=>'checkbox'));
            $fieldsCollection->addFieldToSelect('fields_index');
            if(count($fieldsCollection))
                return $fieldsCollection->getData();
        }
        return false;
    }
    public function prepareFieldTitles($currentFormId=0)
    {
        $fieldsArray = array();
        
        if($currentFormId)
        {
            $fieldsCollection = $this->getFieldsCollection($currentFormId);
            $fieldsCollection->addFieldToFilter('status',array('eq'=>1));
            $fieldsCollection->setOrder('sort_order','asc');
            if(count($fieldsCollection))
            {
                foreach ($fieldsCollection as $key => $value)
                {
                    $fieldTitle = $value['title'];
                    $fieldsArray[$key] = $fieldTitle;
                }
            }
            if(count($fieldsArray))
                return $fieldsArray;
        }
        return false;
    }
    public function getcurrentFormFieldsCollection($currentFormId=0)
    {
    	$fieldsCollection = array();
    	$fieldsModel = Mage::helper('formbuilder')->getFieldsModel();
    	$fieldsCollection = $fieldsModel->getCollection();
        $fieldsCollection->addFieldToFilter('forms_index',array('eq'=>$currentFormId));
        $fieldsCollection->setOrder('sort_order','asc');
        return $fieldsCollection;
    }
    public function getFieldsCollection($currentFormId=0)
    {
    	$fieldsCollection = array();
    	$fieldsModel = Mage::helper('formbuilder')->getFieldsModel();
		$fieldsCollection = $fieldsModel->getCollection();
    	if($currentFormId)
    		$fieldsCollection->addFieldToFilter('forms_index',array('eq'=>$currentFormId));
    	//$fieldsCollection->setOrder('field_id','asc');
        return $fieldsCollection;
    }
    public function saveFields($fieldsArray,$currentFormId=0)
    {
        //$fieldsCollection = Mage::getModel('formbuilder/fields')->getCollection();
        $this->_currentFormId = $currentFormId;
        if($this->_currentFormId)
        {
	        $fieldsCollection = $this->getFieldsCollection($this->_currentFormId);
	        if(count($fieldsCollection))
	        {
	            foreach ($fieldsArray as $fieldsArrayItem)
	            {
	                foreach ($fieldsCollection as $fieldsItem)
	                {
	                    if($fieldsArrayItem['id']==$fieldsItem['field_id'])
	                    {
	                        $this->_isNewField = false;
	                        break;
	                    }
	                }
	                if($this->_isNewField)
	                {
	                    //  adding new field to existing collection
	                    if(!$fieldsArrayItem['is_delete'])
	                        $this->addField($fieldsArrayItem);
	                }
	                else
	                {
	                    //  editing existing field
	                    if($fieldsArrayItem['is_delete'])
                            $this->removeField( $fieldsItem['fields_index'] );
                        else
                            $this->editField( $fieldsArrayItem,$fieldsItem['fields_index'] );
                        /*$this->removeField( $fieldsItem['fields_index'] );
	                    if(!$fieldsArrayItem['is_delete'])
	                        $this->addField($fieldsArrayItem);*/
	                }
	            }
	        }
	        elseif($this->_isNewField)
	        {
	            //  adding first field
	            foreach ($fieldsArray as $fieldsArrayItem)
	            {
	                if(!$fieldsArrayItem['is_delete'])
	                    $this->addField($fieldsArrayItem);
	            }
	        }
	    }
    }
    public function editField( $fieldsArrayItem,$getFieldsIndex )
    {
        $fieldsModel = Mage::helper('formbuilder')->getFieldsModel();
        $data = array();
        if($fieldsModel->load($getFieldsIndex))
        {
            $data['fields_index'] = $getFieldsIndex;
            $data['forms_index'] = $this->_currentFormId;
            $data['field_id'] = $fieldsArrayItem['id'];
            $data['previous_group'] = $fieldsArrayItem['previous_group'];
            $data['type'] = $fieldsArrayItem['type'];
            $data['title'] = $fieldsArrayItem['title'];
            
            $inputType = $fieldsArrayItem['type'];
            $allowedTypes = array("drop_down","radio","checkbox","multiple");
            if( array_key_exists('values', $fieldsArrayItem) && in_array($inputType,$allowedTypes) )
                $data['options'] = 1;
            else
                $data['options'] = 0;

            $data['sort_order'] = $fieldsArrayItem['sort_order'];
            $data['status'] = $fieldsArrayItem['field_status'];
            $data['is_require'] = $fieldsArrayItem['is_require'];
            $data['is_delete'] = $fieldsArrayItem['is_delete'];
            $data['option_id'] = $fieldsArrayItem['option_id'];
            $data['previous_type'] = $fieldsArrayItem['previous_type'];

            if($fieldsArrayItem['previous_group']=='text')
                $data['max_characters'] = $fieldsArrayItem['max_characters'];
            elseif($fieldsArrayItem['previous_group']=='file')
            {
                $data['file_extension'] = $fieldsArrayItem['file_extension'];
                $data['image_size_x'] = $fieldsArrayItem['image_size_x'];
                $data['image_size_y'] = $fieldsArrayItem['image_size_y'];
            }
            
            $fieldsModel->setData($data);
            $fieldsModel->save();

            if( array_key_exists('values', $fieldsArrayItem) )
            {
                if( in_array($inputType,$allowedTypes) )
                {
                    $optionsArray = array();
                    $optionsArray = $fieldsArrayItem['values'];
                    //$getFieldsIndex = $fieldsModel->getFieldsIndex();
                    $optionsModel = Mage::helper('formbuilder')->getOptionsModel();
                    $optionsModel->saveOptions($optionsArray,$getFieldsIndex,$this->_isNewField);
                }
                else
                {
                    $optionsModel = Mage::helper('formbuilder')->getOptionsModel();
                    $optionsModel->removeOptions($getFieldsIndex);
                }
            }
        }
    }
    public function addField($fieldsArrayItem)
    {
        //add field then save options if any
        //$fieldsModel = Mage::getModel('formbuilder/fields');
        $fieldsModel = Mage::helper('formbuilder')->getFieldsModel();
        $data = array();

        $data['forms_index'] = $this->_currentFormId;
        $data['field_id'] = $fieldsArrayItem['id'];
        $data['previous_group'] = $fieldsArrayItem['previous_group'];
        $data['type'] = $fieldsArrayItem['type'];
        $data['title'] = $fieldsArrayItem['title'];
        if(array_key_exists('values', $fieldsArrayItem))
            $data['options'] = 1;
        else
            $data['options'] = 0;
        $data['sort_order'] = $fieldsArrayItem['sort_order'];
        $data['status'] = $fieldsArrayItem['field_status'];
        $data['is_require'] = $fieldsArrayItem['is_require'];
        $data['is_delete'] = $fieldsArrayItem['is_delete'];
        $data['option_id'] = $fieldsArrayItem['option_id'];
        $data['previous_type'] = $fieldsArrayItem['previous_type'];

        if($fieldsArrayItem['previous_group']=='text')
            $data['max_characters'] = $fieldsArrayItem['max_characters'];
        elseif($fieldsArrayItem['previous_group']=='file')
        {
            $data['file_extension'] = $fieldsArrayItem['file_extension'];
            $data['image_size_x'] = $fieldsArrayItem['image_size_x'];
            $data['image_size_y'] = $fieldsArrayItem['image_size_y'];
        }
        
        $fieldsModel->setData($data);
        $fieldsModel->save();

        if(array_key_exists('values', $fieldsArrayItem))
        {
            $optionsArray = array();
            $optionsArray = $fieldsArrayItem['values'];
            $getFieldsIndex = $fieldsModel->getFieldsIndex();
            //$this->saveOptions($optionsArray,$getFieldsIndex,$this->_isNewField);
            $optionsModel = Mage::helper('formbuilder')->getOptionsModel();
            $optionsModel->saveOptions($optionsArray,$getFieldsIndex,$this->_isNewField);
        }        
    }
    public function removeField($getFieldsIndex)
    {
        //remove field then remove options
        //$fieldsModel = Mage::getModel('formbuilder/fields')->load($getFieldsIndex);
		$fieldsModel = Mage::helper('formbuilder')->getFieldsModel();
		$fieldsModel->load($getFieldsIndex);
        $fieldsModel->setFieldsIndex($getFieldsIndex);
        $fieldsModel->delete();
        //$this->removeOptions($getFieldsIndex);
        $optionsModel = Mage::helper('formbuilder')->getOptionsModel();
        $optionsModel->removeOptions($getFieldsIndex);
    }
}