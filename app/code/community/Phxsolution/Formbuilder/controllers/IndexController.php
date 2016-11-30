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
* @category   frontend controller
* @package    Phxsolution_Formbuilder
* @author     Murad Ali
* @contact    contact@phxsolution.com
* @site       www.phxsolution.com
* @copyright  Copyright (c) 2014 Phxsolution Formbuilder
* @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/
?>
<?php
class Phxsolution_Formbuilder_IndexController extends Mage_Core_Controller_Front_Action
{
	protected $_errors = array();
	protected $_fieldsModel;
	protected $_fieldTitle;
	protected $_currentFormId;
	protected $_fileType;
	protected $_recordsModel;
	protected $_fileObject;
	protected $_lastInsertedRecordId;
	protected $_lastInsertedRecordValue;
	protected $_currentFormTitle;
	protected $_filesToBeUploaded = false;
	protected $_dateToBeSaved = false;
	protected $_send_email_to_customer = false;
	protected $_send_email_to_admin = false;
	protected $_currentCustomer = false;
	
	public function preDispatch()
    {
        parent::preDispatch();
        if(!Mage::helper('formbuilder')->isEnabled())
        {
            //$this->_redirectUrl(Mage::helper('core/url')->getHomeUrl());
			$helper = Mage::helper('formbuilder');
        	$session = Mage::getSingleton('core/session');
            $session->addError($helper->__("Formbuilder Extension seems disabled."));
        }
    }
	public function indexAction()
	{
		$this->loadLayout();
		$this->getLayout()->getBlock("head")->setTitle($this->__("Formbuilder"));

		$breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
		$breadcrumbs->addCrumb("home", array(
			"label" => $this->__("Home"),
			"title" => $this->__("Home"),
			"link"  => Mage::getBaseUrl()
		));
		$breadcrumbs->addCrumb("formbuilder", array(
			"label" => $this->__("Formbuilder"),
			"title" => $this->__("Formbuilder")
		));
		$this->renderLayout();
	}
	public function viewAction()
	{
		$id = $this->getRequest()->getParam('id');
		$formsModel = Mage::helper('formbuilder')->getFormsModel();
		$formsModel->load($id);
		if(empty($formsModel['forms_index']))
		{
			Mage::getSingleton('core/session')->addError('Form not found');
			//$this->_redirectUrl(Mage::helper('core/url')->getHomeUrl());
			$this->_redirectUrl( Mage::getUrl('formbuilder') );
		}
	  	Mage::register('frontend_form',$formsModel);

		$this->loadLayout();

		$this->_currentFormId = intval($id);
		$currentForm = Mage::helper('formbuilder')->getCurrentFormDetails($this->_currentFormId);
		$currentFormTitle = "";
		$currentFormTitle = $currentForm['title'];
		$this->_currentFormTitle = $currentFormTitle;

		if($currentFormTitle)
			$this->getLayout()->getBlock("head")->setTitle($this->__($currentFormTitle));
		else
			$this->getLayout()->getBlock("head")->setTitle($this->__("Formbuilder"));

		$breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
		$breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home"),
                "title" => $this->__("Home"),
                "link"  => Mage::getBaseUrl()
		   ));      	
      	if($currentFormTitle)
      	{
      		$breadcrumbs->addCrumb("formbuilder", array(
                "label" => $this->__("Formbuilder"),
                "title" => $this->__("Formbuilder"),
                "link"  => Mage::getUrl('formbuilder')
		   ));
      		$breadcrumbs->addCrumb($currentFormTitle, array(
                "label" => $this->__($currentFormTitle),
                "title" => $this->__($currentFormTitle)
		   ));
      	}
      	else
      	{
      		$breadcrumbs->addCrumb("formbuilder", array(
                "label" => $this->__("Formbuilder"),
                "title" => $this->__("Formbuilder")                
		   ));
      	}
		$this->renderLayout();
	}
	public function checkEmpty($value)
	{
		$error = false;		
		if(!Zend_Validate::is($value, 'NotEmpty'))
			$error = $this->_helper->__("'".$this->_fieldTitle."'".' is a required field');
		return $error;
	}
	public function checkLength($value,$lengthLimit)
	{
		$error = false;
		if(strlen($value)>$lengthLimit)
			$error = $this->_helper->__("'".$this->_fieldTitle."'".' text length must be less then or equal to '.$lengthLimit);
		return $error;
	}
	public function checkFile()
	{
		$fileErrorsArray = array();
		foreach ($this->_fileObject as $key => $value)
		{
			$this->_fileCanBeUploaded = true;

			list($pre,$fileKey,$post) = explode('_',$key);
			$this->_fieldsModel->load($fileKey);
			$this->_fieldTitle = $this->_fieldsModel['title'];
			if($value['name']=='')
			{
				$this->_fileCanBeUploaded = false;
				if($this->_fieldsModel['is_require'])
				{
					$fileErrorsArray[] = "'".$this->_fieldTitle."' is a required field";					
				}
			}
			else
			{
				$allowed = explode(',',$this->_fieldsModel['file_extension']);
				$filename = $value['name'];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				
				$fileTypeString = $value['type'];
				list($fileType,$fileExtension) = explode('/',$fileTypeString);

				if(!in_array($ext,$allowed) )
				{
					$fileErrorsArray[] = "'".$this->_fieldTitle."' has invalid extension";
					$this->_fileCanBeUploaded = false;
				}
				elseif($fileType=='image' && $this->_fieldsModel['image_size_x'] || $this->_fieldsModel['image_size_y'])
				{
					$image_info = getimagesize($value['tmp_name']);
					$image_width = $image_info[0];
					$image_height = $image_info[1];
					$specified_width=$this->_fieldsModel['image_size_x'];
					$specified_height=$this->_fieldsModel['image_size_y'];
					if( $image_width > $specified_width && $image_height > $specified_height )
					{
						$fileErrorsArray[] = "'".$this->_fieldTitle."' has invalid size";
						$this->_fileCanBeUploaded = false;
					}
				}
			}
			if($this->_validateDataErrorsCount==0 && $this->_fileCanBeUploaded)
				$this->_filesToBeUploaded[] = $key;
		}
		return $fileErrorsArray;
	}
	public function validateDate($month,$day,$year,$fieldId)
	{
		$yearRange = $this->_helper->getYearRange();
		list($yearFrom,$yearTo) = explode(',',$yearRange);			

		if( !checkDate($month,$day,$year) || $year<$yearFrom || $year>$yearTo )
			return "'".$this->_fieldTitle."' is not valid";
		$day += 1;
		$dateToFormat = "$year-$month-$day";
		//$dateToFormat = '2011-12-11';
		$this->_dateToBeSaved[$fieldId] = Mage::helper('core')->formatDate($dateToFormat, 'long', false);
		return;
	}
	public function validateTime($hour="",$minute="",$day_part="",$fieldId)
	{	
		$timeFormat = $this->_helper->getTimeFormat();
		if($timeFormat=='12h' && $hour && $minute && $day_part)
		{
			//12 hour format (12:50PM)
			$time12 = "$hour:$minute$day_part";
			$res = preg_match('/^(0[1-9]|1[0-2]):([0-5][0-9])(am|pm)$/', $time12);
			if (!$time12 || !$res)
			    return "'".$this->_fieldTitle."' is not valid";
			if($this->_fieldsModel['type']=='date_time')
				$this->_dateToBeSaved[$fieldId] .= ", $time12";
			elseif($this->_fieldsModel['type']=='time')
				$this->_dateToBeSaved[$fieldId] .= "$time12";
			return;
		}
		elseif($timeFormat=='24h' && $hour && $minute)
		{
			//24 hour format (00:00:00)
			$time24 = "$hour:$minute:00";
			$res = preg_match("/^([0-2][0-3]|[01]?[1-9]):([0-5]?[0-9]):([0-5]?[0-9])$/", $time24);
	        if (!$time24 || !$res)
			    return "'".$this->_fieldTitle."' is not valid";
			if($this->_fieldsModel['type']=='date_time')
				$this->_dateToBeSaved[$fieldId] .= ", $time24";
			elseif($this->_fieldsModel['type']=='time')
				$this->_dateToBeSaved[$fieldId] .= "$time24";
			return;
		}
		return "'".$this->_fieldTitle."' is not valid";
	}
	public function checkDate( $dateArray,$fieldId )
	{		
		$month=$day=$year=$hour=$minute=$day_part="";
		foreach ($dateArray as $key => $value)
		{
			if($key=='date')
				list($month,$day,$year) = explode('/',$value);
			elseif($key=='day')
				$day = $value;
			elseif($key=='month')
				$month = $value;
			elseif($key=='year')
				$year = $value;
			elseif($key=='hour')
			{
				if($value>=0 && $value<=9)
					$hour = '0'.$value;
				else
					$hour = $value;
			}
			elseif($key=='minute')
			{
				if($value>=0 && $value<=9)
					$minute = '0'.$value;
				else
					$minute = $value;
			}
			elseif($key=='day_part')
				$day_part = $value;
		}		
		$this->_dateToBeSaved[$fieldId] = "";
		if($this->_fieldsModel['type']=='date')
		{
			if($month && $day && $year)
				return $this->validateDate($month,$day,$year,$fieldId);
		}
		elseif($this->_fieldsModel['type']=='date_time')
		{
			if($month && $day && $year)
			{
				if($error = $this->validateDate($month,$day,$year,$fieldId))
					return $error;
				else
					return $this->validateTime($hour,$minute,$day_part,$fieldId);
			}
		}
		elseif($this->_fieldsModel['type']=='time')
		{
			if($hour && $minute)
				return $this->validateTime($hour,$minute,$day_part,$fieldId);
		}		
		return false;
	}
	public function checkCheckbox($checkboxArray,$fieldId)
	{
		return "checkboxArray = ".implode(',',$checkboxArray);
	}
	public function _validateData($data)
    {
        $this->_currentFormId = $data['current_form_id'];
		$errorsArray = $this->_fileErrorsArray = array();
		$this->_fieldsModel = $this->_helper->getFieldsModel();
		$previousKey = 0;

        $optionsDataArray = $verifiedCheckboxIds = $allCheckboxTypeIds = array();
        $optionsDataArray = $data['options'];
        foreach ($optionsDataArray as $key => $value)
        {
        	$this->_fieldsModel->load($key);
        	$this->_fieldTitle = $this->_fieldsModel['title'];
        	if($this->_fieldsModel['forms_index']==$this->_currentFormId)
        	{
	        	if(is_array($value))
	        	{
	        		foreach ($value as $key2 => $value2)
	        		{
	        			if($previousKey!=$key)
	        			{		        			
		        			if($this->_fieldsModel['is_require'])
		        			{
        						$errorsArray[] = $this->checkEmpty($value2);
        						if($this->_fieldsModel['type']=='checkbox')
		        					$verifiedCheckboxIds[] = $key;
		        			}
        					if($lengthLimit = $this->_fieldsModel['max_characters'])
								$errorsArray[] = $this->checkLength($value2,$lengthLimit);
							if($this->_fieldsModel['previous_group']=='date')
							{
								$fieldId = $key;
								$errorsArray[] = $this->checkDate( $optionsDataArray[$key],$fieldId );
							}
							//$errorsArray[] = "key = ".$key;
				        }
				        $previousKey = $key;
	        		}
	        	}	
	        	else
	        	{
	        		if($this->_fieldsModel['is_require'])
						$errorsArray[] = $this->checkEmpty($value);
					if($lengthLimit = $this->_fieldsModel['max_characters'])
						$errorsArray[] = $this->checkLength($value,$lengthLimit);
					//$errorsArray[] = "key = ".$key;
	        	}
	        }
        }
        // validate checkbox
    	$allCheckboxTypeIds = $this->_fieldsModel->getCheckboxTypeIds($this->_currentFormId);
    	if($allCheckboxTypeIds)
    	{
	        foreach ($allCheckboxTypeIds as $key => $value)
	        {
	        	$checkboxId = implode(',',$value);
	        	$this->_fieldsModel->load($checkboxId);
	        	$title = $this->_fieldsModel['title'];
	        	$isRequired = ($this->_fieldsModel['is_require']) ? true : false;
	        	if( !in_array( $checkboxId,$verifiedCheckboxIds ) && $isRequired )
	        		$errorsArray[] = "'".$title."' is a required field";
	        }
		}        
        $count=0;
        $errorsArrayFinal = array();
        foreach ($errorsArray as $key => $value)
        {
        	if(!empty($value))
        	{
        		$count++;
        		$errorsArrayFinal[] = $value;
        	}
        }
        $this->_validateDataErrorsCount = $count;
        $this->_fileErrorsArray = $this->checkFile();
        foreach ($this->_fileErrorsArray as $key => $value)
        {
        	if(!empty($value))
        		$errorsArrayFinal[] = $value;
        }        
        return $errorsArrayFinal;
    }	
	public function uploadFile($key)
	{
		try
		{
			$path = Mage::getBaseDir('media') . DS . 'formbuilder/frontend/uploaded_files' . DS;
			$uploader = new Varien_File_Uploader($key);
			//$uploader->setAllowedExtensions(array('jpg','png','gif'));
			$uploader->setAllowRenameFiles(false);
			$uploader->setFilesDispersion(false);
			$destFile = $path.$this->_fileObject[$key]['name'];
			$filename = $uploader->getNewFileName($destFile);
			$uploader->save($path, $filename);
											
			return "formbuilder/frontend/uploaded_files/".$filename;
		}
		catch (Exception $e)
		{
			$this->_session->addError($this->_helper->__("Error uploading file"));
			return false;
		}
	}
    public function _saveData($data)
   	{
		$this->_fieldsModel = $this->_helper->getFieldsModel();
		$this->_optionsModel = $this->_helper->getOptionsModel();		
		$returnStatus = false;
				
        $optionsDataArray = array();
        $optionsDataArray = $data['options'];

        $serialized = serialize($optionsDataArray);
        $data['forms_index'] = $this->_currentFormId;
        $data['value'] = $serialized;
        $this->_recordsModel->setData($data);
		if($this->_recordsModel->save())
		{
			$returnStatus = true;
			$this->_lastInsertedRecordId = $this->_recordsModel->getRecordsIndex();
			$this->_lastInsertedRecordValue = $this->_recordsModel->getValue();
		}
		else
			$returnStatus = false;
		return $returnStatus;
        
	}
	public function sendEmail($data)
	{
		$currentFormId = $this->_currentFormId;
        $fieldsModel = Mage::helper('formbuilder')->getFieldsModel();
        $prepareFieldTitles = array();
        $prepareFieldTitles = $fieldsModel->prepareFieldTitles($currentFormId);

        //current form title
        $currentForm = Mage::helper('formbuilder')->getCurrentFormDetails($this->_currentFormId);
		$this->_currentFormTitle = $currentForm['title'];

        $FormData = "";
        $FormData .= '<table width="100%" style="font-family: sans-serif; border: 1px solid #ccc;"><thead>
								<tr>
									<th colspan="2" style="padding: 10px; background:#ccc;">We received the following data:</th>
								</tr>
								<tr>
									<td width="130" style="background:#f2f2f2; padding:5px; text-align: right;">Form Title</td>
									<td style="background:#f5f2f0; padding:5px;">'.$this->_currentFormTitle.'</td>
								</tr>';

        $nullObject = new Varien_Object();
        foreach ($prepareFieldTitles as $fieldId => $fieldTitle)
        {
        	if($this->_lastInsertedRecordId && $this->_lastInsertedRecordValue)
        	{
        		$_blockData = $this->getLayout()->getBlockSingleton('Phxsolution_Formbuilder_Block_Adminhtml_Formbuilder_Renderer_Recordvalue')->render($nullObject,$this->_lastInsertedRecordId,$fieldId,$this->_lastInsertedRecordValue);
        		$FormData .= '<tr>
										<td width="130" style="background:#f2f2f2; padding:5px; text-align: right;">'.$fieldTitle.'</td>
										<td style="background:#f5f2f0; padding:5px;">'.$_blockData.'</td>
									</tr>';
        	}
        }
        $FormData .= '</thead></table>';
        
		//general configuration
		$email_logo = Mage::getStoreConfig('design/email/logo');
		$senderName = Mage::getStoreConfig('formbuilder_section/form_submission_email/sender_name'); //sender name
		$senderEmail = Mage::getStoreConfig('formbuilder_section/form_submission_email/sender_email'); //sender email
		$store_name = Mage::getStoreConfig('general/store_information/name');
		$store_phone = Mage::getStoreConfig('general/store_information/phone');		
		$img_media =  Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'email/logo/';		
		$img_logo_final = $img_media.$email_logo;
		$default_logo =  Mage::getStoreConfig('design/header/logo_src');	
		$logo_default = Mage::getDesign()->getSkinUrl().$default_logo; 
		if($img_logo_final == $img_media)
			$logo_img = "<img src='$logo_default'/>"; 
		else
			$logo_img =   "<img src='$img_logo_final'/>";
		$headers = "";			
		$headers .= 'MIME-Version: 1.0'."\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From:'. $senderName.' <'.$senderEmail.'>';


		//preparing email for admin if send_email_to_admin
		if($this->_send_email_to_admin)
		{
			$email_for_admin = Mage::getStoreConfig('formbuilder_section/form_submission_email/admin_email'); //email subject
			$email_subject_for_admin = Mage::getStoreConfig('formbuilder_section/form_submission_email/email_subject_for_admin'); //email subject
			$email_content_for_admin = Mage::getStoreConfig('formbuilder_section/form_submission_email/email_content_for_admin'); //email content				
			$CustomerEmail = "Guest";
			if($this->_currentCustomer)
				$CustomerEmail = $this->_currentCustomer->getEmail();

			$email_desc2 = str_replace("{{CustomerEmail}}",$CustomerEmail,$email_content_for_admin);			
			$email_desc2 = str_replace("{{Storename}}",$store_name,$email_desc2);
			$email_desc2 = str_replace("{{FormData}}",$FormData,$email_desc2);
			$adminContent = '<table border="0">
							<tr>
								<td>
									<table border="0">
										<tr>
											<Td>'.$logo_img.'</Td>
										</tr>
										<tr>
											<td colspan="2">&nbsp;</td></tr>
										<tr>
											<Td><p>'.$email_desc2.'. </p></Td>
										</tr>											
									</table>
								</td>
							</tr>
						</table>';
		}

        if (Mage::getSingleton('customer/session')->isLoggedIn())
		{
			// Load the customer's data
    		$customer = Mage::getSingleton('customer/session')->getCustomer();
    		$email = $customer->getEmail();
    		$fname = $customer->getFirstname();
    		$lname = $customer->getLastname();
    		
			$email_subject_for_customer = Mage::getStoreConfig('formbuilder_section/form_submission_email/email_subject_for_customer'); //email subject
			$email_content_for_customer = Mage::getStoreConfig('formbuilder_section/form_submission_email/email_content_for_customer'); //email content
			
			$email_desc = str_replace("{{Name}}",$fname." ".$lname,$email_content_for_customer);
			$email_desc = str_replace("{{FormData}}",$FormData,$email_desc);			
			$email_desc = str_replace("{{Storename}}",$store_name,$email_desc);
			$email_desc = str_replace("{{Storephone}}",$store_phone,$email_desc);
			
			$customerContent = '<table border="0">
								<tr>
									<td>
										<table border="0">
											<tr>
												<Td>'.$logo_img.'</Td>
											</tr>
											<tr>
												<td colspan="2">&nbsp;</td></tr>
											<tr>
												<Td><p>'.$email_desc.'. </p></Td>
											</tr>											
										</table>
									</td>
								</tr>
							</table>';

			try
			{
				if($this->_send_email_to_customer)
					mail($email,$email_subject_for_customer,$customerContent,$headers);
				if($this->_send_email_to_admin)
					mail($email_for_admin,$email_subject_for_admin,$adminContent,$headers);
			}
			catch (Exception $e)
			{
				Mage::getSingleton('core/session')->addError('Error sending email');
			}
		}
		else
		{
			if($this->_send_email_to_admin)
				mail($email_for_admin,$email_subject_for_admin,$adminContent,$headers);
			else
				;//customer is not registered
		}
	}
	public function formsubmitAction()
	{
		$errors = array();
		$this->_helper = Mage::helper('formbuilder');
        $session = Mage::getSingleton('core/session');
        $this->_session = $session;
        $customerSession = Mage::getSingleton('customer/session');
        $this->_getLimitFormSubmissionForGuest = $this->_helper->getLimitFormSubmissionForGuest();
        $this->_getLimitFormSubmissionForRegistered = $this->_helper->getLimitFormSubmissionForRegistered();
        $this->_getRedirectUrl = $this->_helper->getRedirectUrl();

		if (!$this->_helper->isEnabled())
		{
            $session->addError($this->_helper->__('Formbuilder Extension seems disabled, Please contact Administrator.'));
            $this->_redirectUrl(Mage::helper('core/url')->getHomeUrl());
            return;
        }
        else
        {   
            if($data = $this->getRequest()->getPost())
	        {
				//field=11, form=9
				$this->_helper->setFormData($data['options'][11]);
				$this->_currentFormId = $data['current_form_id'];
	            $this->_recordsModel = $this->_helper->getRecordsModel();
			
				if($this->_helper->registeredOnly() && !$customerSession->isLoggedIn())
		        {
		            if(!$customerSession->isLoggedIn())
		            {
		            	$session->addError($this->_helper->__('You must be logged in to submit.'));
		            	$this->_redirectReferer();
		            	return;
		            }
		            else//logged in user
		            {
		                $customer = $this->_helper->getCustomerInfo();
		                //save customer id
		                $customerId = $customer->getId();
		                //check form submission
		                if($this->_getLimitFormSubmissionForRegistered)
		                {
		                	$totalRecords = $this->_recordsModel->checkFormSubmissionLimit($customerId,$this->_currentFormId);
		                	if($totalRecords>=$this->_getLimitFormSubmissionForRegistered)
		                	{
		                		$this->_session->addError($this->_helper->__('Can not submit form, limitation of form submission for current user is reached.'));
		                		$this->_redirectReferer();
		                		return;
		                	}
		                }
		                $data['customer'] = $customerId;
		            }
		        }
		        elseif(!$customerSession->isLoggedIn())//guest user
		        {
		        	$customer = $this->_helper->getCustomerInfo();
		            //save customer ip address
		            $customerIp = Mage::helper('core/http')->getRemoteAddr();
		            //check form submission
	                if($this->_getLimitFormSubmissionForGuest)
	                {
	                	$totalRecords = $this->_recordsModel->checkFormSubmissionLimit($customerIp,$this->_currentFormId);
	                	if($totalRecords>=$this->_getLimitFormSubmissionForGuest)
	                	{
	                		$this->_session->addError($this->_helper->__('Can not submit form, limitation of form submission for current user is reached.'));	                		
	                		$this->_redirectReferer();
	                		return;
	                	}
	                }
		            $data['customer'] = $customerIp;
		        }
		        elseif($customerSession->isLoggedIn())//logged in user
		        {		        	
		        	$customer = $this->_helper->getCustomerInfo();
	                //save customer id
	                $customerId = $customer->getId();
	                //check form submission
	                if($this->_getLimitFormSubmissionForRegistered)
	                {
	                	$totalRecords = $this->_recordsModel->checkFormSubmissionLimit($customerId,$this->_currentFormId);
	                	if($totalRecords>=$this->_getLimitFormSubmissionForRegistered)
	                	{
	                		$this->_session->addError($this->_helper->__('Can not submit form, limitation of form submission for current user is reached.'));
	                		$this->_redirectReferer();
	                		return;
	                	}
	                }
	                $data['customer'] = $customerId;
		        }
				if(isset($_FILES))
				{
					$this->_fileObject = $_FILES;
				}
				$errors = $this->_validateData($data);
				if (!empty($errors))
		        {
		            foreach ($errors as $error)
		            {
		                $session->addError($error);
		            }
		            $this->_redirectReferer();
		            return;
		        }		        
		        if($this->_filesToBeUploaded)
		        {
		        	foreach ($this->_filesToBeUploaded as $key)
		        	{
		        		list($pre,$fileKey,$post) = explode('_',$key);
		        		if( $res = $this->uploadFile($key) )
		        			$data['options'][$fileKey] = $res;
		        		else
		        		{
		        			$this->_redirectReferer();
		            		return;
		        		}
		        	}
		        }
		        if($this->_dateToBeSaved)
		        {
		        	foreach ($this->_dateToBeSaved as $id => $date)
		        	{
		        		//$this->_session->addError("(id=$id) = ".$date);
		        		$data['options'][$id] = $date;
		        	}
		        }

		        /*echo "<pre>";
		        echo "<h1>printing data</h1>";
		        print_r($data);
		        echo "<h1>printing data['options']</h1>";
		        print_r($data['options']);
		        echo "</pre>";
		        exit();*/
				
		        $formsModel = $this->_helper->getFormsModel();
		        $formsModel->load($this->_currentFormId);
		        if (Mage::getSingleton('customer/session')->isLoggedIn())
		        	$this->_currentCustomer = Mage::getSingleton('customer/session')->getCustomer();
		        if($this->_saveData($data))
		        {		        	
		        	$successText = $formsModel['success_msg'];
		        	if(!$successText)
		        		$successText = $this->__('Form submitted successfully, we will reach you soon.');
		        	$session->addSuccess($this->_helper->__($successText));
		        	$this->_send_email_to_customer = Mage::getStoreConfig('formbuilder_section/form_submission_email/send_email_to_customer');
    				$this->_send_email_to_admin = Mage::getStoreConfig('formbuilder_section/form_submission_email/send_email_to_admin');
		        	if($this->_send_email_to_customer || $this->_send_email_to_admin)
		        		$this->sendEmail($data);
		        	if($this->_getRedirectUrl)
		        		$this->_redirectUrl(Mage::getUrl($this->_getRedirectUrl));
		        	//return;
		        }
		        else
		        {
		        	$failureText = $formsModel['failure_msg'];
		        	if(!$failureText)
		        		$failureText = $this->__('Problem occured submitting form.');
		        	$session->addError($this->_helper->__($failureText));
		        }
		        $this->_redirectReferer();
		        return;
			}
			else
			{
	            $session->addNotice($this->_helper->__('The requested page could not be found'));
	            $this->_redirectReferer();
	            return false;            
	        }		    
		}//else extension is enabled
	}	
}