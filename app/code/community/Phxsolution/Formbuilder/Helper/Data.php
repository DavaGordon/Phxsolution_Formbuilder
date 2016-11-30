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
* @category   helper
* @package    Phxsolution_Formbuilder
* @author     Murad Ali
* @contact    contact@phxsolution.com
* @site       www.phxsolution.com
* @copyright  Copyright (c) 2014 Phxsolution Formbuilder
* @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/
?>
<?php
class Phxsolution_Formbuilder_Helper_Data extends Mage_Core_Helper_Abstract
{
	protected $_formData;
    
    public function isProductEdit()
    {
        $currentProduct = Mage::registry('product');
        if(isset($currentProduct))
            return true;
        else
            return false;
    }
    public function setFormData($data)
    {
        $this->_formData = $data;
    }
    public function getFormData()
    {
        return $this->_formData;
    }
    public function getRedirectUrl()
    {
        return Mage::getStoreConfig('formbuilder_section/form_submission/redirect_url');
    }
    public function getLimitFormSubmissionForRegistered()
    {
        return Mage::getStoreConfig('formbuilder_section/form_submission/limit_form_submission_for_registered');
    }
    public function getLimitFormSubmissionForGuest()
    {
        return Mage::getStoreConfig('formbuilder_section/form_submission/limit_form_submission_for_guest');
    }
    public function getYearRange()
    {
        return Mage::getStoreConfig('formbuilder_section/custom_options/year_range');
    }
    public function getTimeFormat()
    {
        return Mage::getStoreConfig('formbuilder_section/custom_options/time_format');
    }
    public function getDateFieldsOrder()
    {
        return Mage::getStoreConfig('formbuilder_section/custom_options/date_fields_order');
    }
    public function useCalendar()
    {
        return Mage::getStoreConfig('formbuilder_section/custom_options/use_calendar');
    }
    public function getCustomerInfo()
    {
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        return $customer;
    }
    public function getFormsModel()
    {
        return Mage::getModel('formbuilder/forms');
    }
    public function getFieldsModel()
    {
        return Mage::getModel('formbuilder/fields');
    }
    public function getOptionsModel()
    {
        return Mage::getModel('formbuilder/options');
    }
    public function getRecordsModel()
    {
        return Mage::getModel('formbuilder/records');
    }    
    public function isEnabled()
    {
        return Mage::getStoreConfig('formbuilder_section/general/active');
    }
    public function registeredOnly()
    {
        return Mage::getStoreConfig('formbuilder_section/form_submission/registered_only');
    }
    public function showLinkinToplinks()
    {
        return Mage::getStoreConfig('formbuilder_section/general/in_toplinks');
    }
    public function showLinkinTopmenu()
    {
        return Mage::getStoreConfig('formbuilder_section/general/in_topmenu');
    }
    public function showLinkinFooterlinks()
    {
        return Mage::getStoreConfig('formbuilder_section/general/in_footerlinks');
    }
    public function getFormCollection()
    {
        $formCollection = array();
        $formCollection = Mage::getModel('formbuilder/forms')->getCollection();
        return $formCollection;
    }
    public function getCurrentFormDetails($currentFormId)
    {
        $currentForm = array();
        $currentForm = Mage::getModel('formbuilder/forms')->load($currentFormId);
        return $currentForm;
    }
    /*public function getcurrentFormFieldsCollection($currentFormId=0)
    {
        $fieldsCollection = array();
        //$currentFormId = Mage::getSingleton('core/session')->getCurrentFormId();
        if(!$currentFormId)
            $currentFormId = $this->getCurrentFormId();
        $fieldsCollection = Mage::getModel('formbuilder/fields')->getCollection();
        $fieldsCollection->addFieldToFilter('forms_index',array('eq'=>$currentFormId));
        return $fieldsCollection;
    }*/
    public function getCurrentFormId()
    {
        $sessionFormId = intval(Mage::getSingleton('core/session')->getCurrentFormId());
        //$registryFormId = intval(Mage::registry('formbuilder_data')->getId());
        //$currentFormId = $sessionFormId ? $sessionFormId : $registryFormId;
        if(is_int($currentFormId = $sessionFormId))
            return $currentFormId;
    }
    /*public function saveFields($fieldsArray)
    {
        $fieldsCollection = Mage::getModel('formbuilder/fields')->getCollection();
        if(count($fieldsCollection))
        {
            foreach ($fieldsArray as $fieldsArrayItem)
            {
                foreach ($fieldsCollection as $fieldsItem)
                {
                    if($fieldsArrayItem['id']==$fieldsItem['field_id'] && $this->getCurrentFormId()==$fieldsItem['forms_index'])
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
                    $this->removeField( $fieldsItem['fields_index'] );
                    if(!$fieldsArrayItem['is_delete'])
                        $this->addField($fieldsArrayItem);
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
    public function addField($fieldsArrayItem)
    {
        //add field then save options if any
        $fieldsModel = Mage::getModel('formbuilder/fields');
        $data = array();

        $data['forms_index'] = $this->getCurrentFormId();
        $data['field_id'] = $fieldsArrayItem['id'];
        $data['previous_group'] = $fieldsArrayItem['previous_group'];
        $data['type'] = $fieldsArrayItem['type'];
        $data['title'] = $fieldsArrayItem['title'];
        if(array_key_exists('values', $fieldsArrayItem))
            $data['options'] = 1;
        else
            $data['options'] = 0;
        $data['sort_order'] = $fieldsArrayItem['sort_order'];
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
            $this->saveOptions($optionsArray,$getFieldsIndex);            
        }        
    }
    public function removeField($getFieldsIndex)
    {
        //remove field then remove options
        $fieldsModel = Mage::getModel('formbuilder/fields')->load($getFieldsIndex);
        $fieldsModel->setFieldsIndex($getFieldsIndex);
        $fieldsModel->delete();
        $this->removeOptions($getFieldsIndex);
    }*/
    /*public function saveOptions($optionsArray,$getFieldsIndex)
    {
        if($this->_isNewField)
        {
            $this->addOptions($optionsArray,$getFieldsIndex);
        }
        else
        {
            $this->removeOptions($getFieldsIndex);
            $this->addOptions($optionsArray,$getFieldsIndex);
        }
    }
    public function addOptions($optionsArray,$getFieldsIndex)
    {
        $optionsModel = Mage::getModel('formbuilder/options');
        $data = array();
        foreach ($optionsArray as $key => $optionsArrayItem)
        {
            if(!$optionsArrayItem['is_delete'])
            {
                $data['fields_index'] = $getFieldsIndex;
                $data['is_delete'] = $optionsArrayItem['is_delete'];
                $data['title'] = $optionsArrayItem['title'];
                $data['sort_order'] = $optionsArrayItem['sort_order'];
                $optionsModel->setData($data);
                $optionsModel->save();
            }
        }        
    }
    public function removeOptions($getFieldsIndex)
    {
        $optionsCollection = Mage::getModel('formbuilder/options')->getCollection();
        foreach ($optionsCollection as $key => $option)
        {
            if($option['fields_index']==$getFieldsIndex)
            {
                $currentOptionIndex = $option['options_index'];
                $optionsModel = Mage::getModel('formbuilder/options')->load($currentOptionIndex);
                $optionsModel->setOptionsIndex($currentOptionIndex);
                $optionsModel->delete();
            }
        }
    }*/
    /**
     * Path for config.
     */
    const XML_CONFIG_PATH = 'formbuilder/general/';

    /**
     * Name library directory.
     */
    const NAME_DIR_JS = 'formbuilder/jquery/';

    /**
     * List files for include.
     *
     * @var array
     */
    protected $_files = array(
        'jquery-1.8.1.min.js',
        'jquery.noconflict.js',
    );

    /**
     * Check enabled.
     *
     * @return bool
     */
    public function isJqueryEnabled()
    {
        return (bool) $this->_getConfigValue('jquery', $store = '');
    }

    /**
     * Return path file.
     *
     * @param $file
     *
     * @return string
     */
    public function getJQueryPath($file)
    {
        return self::NAME_DIR_JS . $file;
    }

    /**
     * Return list files.
     *
     * @return array
     */
    public function getFiles()
    {
        return $this->_files;
    }

	public function isformbuilderModuleEnabled()
    {
        return (bool) $this->_getConfigValue('active', $store = '');
    }
	
	public function isResponsiveBannerEnabled()
    {
        return (bool) $this->_getConfigValue('responsive_banner', $store = '');
    }
	
    protected function _getConfigValue($key, $store)
    {
        return Mage::getStoreConfig(self::XML_CONFIG_PATH . $key, $store = '');
    }
	
	public function resizeImg($fileName,$width='',$height='')
	{
		$baseURL = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
		$imageURL = $baseURL .'/'.'formbuilder'.'/'.$fileName;
		
		$basePath = Mage::getBaseDir('media');
		$imagePath = $basePath.DS.'formbuilder'.str_replace('/', DS,$fileName);
		
		$extra =$width . 'x' . $height;
		$newPath = Mage::getBaseDir('media') . DS .'formbuilder'.DS."resized".DS.$extra.str_replace('/', DS,$fileName);
		//if width empty then return original size image's URL
		if ($width != '' && $height != '') {
			//if image has already resized then just return URL
			if (file_exists($imagePath) && is_file($imagePath) && !file_exists($newPath)) {
				$imageObj = new Varien_Image($imagePath);
				$imageObj->constrainOnly(TRUE);
				$imageObj->keepAspectRatio(FALSE);
				$imageObj->keepFrame(FALSE);
				//$width, $height - sizes you need (Note: when keepAspectRatio(TRUE), height would be ignored)
				$imageObj->resize($width, $height);
				$imageObj->save($newPath);
			}
			$resizedURL = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . "formbuilder".'/'."resized".'/'.$extra.'/'.$fileName;
		 } else {
			$resizedURL = $imageURL;
		 }
		 return $resizedURL;
	}
    public function getImageUploadPath()
    {
        return "formbuilder/images/";
    }
}