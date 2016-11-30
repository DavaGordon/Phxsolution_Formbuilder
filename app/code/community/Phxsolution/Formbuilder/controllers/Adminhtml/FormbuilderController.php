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
* @category   adminhtml controller
* @package    Phxsolution_Formbuilder
* @author     Murad Ali
* @contact    contact@phxsolution.com
* @site       www.phxsolution.com
* @copyright  Copyright (c) 2014 Phxsolution Formbuilder
* @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/
?>
<?php
class Phxsolution_Formbuilder_Adminhtml_FormbuilderController extends Mage_Adminhtml_Controller_Action
{
	//this is ajax grid request action
    public function fieldsgridAction()
    {
        echo $this->getLayout()->createBlock('formbuilder/adminhtml_formbuilder_edit_tab_fieldsgrid')->toHtml();
    }
    public function recordsgridAction()
    {
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('formbuilder/adminhtml_formbuilder_edit_tab_recordsgrid'));
        $this->renderLayout();
        //echo $this->getLayout()->createBlock('formbuilder/adminhtml_formbuilder_edit_tab_fieldsgrid')->toHtml();
    }
	public function optionsAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }	
	public function getBaseTmpMediaUrl()
    {
        return Mage::getBaseUrl('media') . 'formbuilder';
    }	
	public function getBaseTmpMediaPath()
    {
        return Mage::getBaseDir('media') . DS . 'formbuilder';
    }	
	protected function _initAction()
	{
		$this->loadLayout()
		->_setActiveMenu('formbuilder/items')
		->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Form Manager'));
		
		return $this;
	}
	public function indexAction()
	{
		$this->_initAction()
		//$this->getLayout()->getBlock('head')->setTitle($this->__('Banner'));
		->renderLayout();
	}
	/*public function editAction()
	{
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('formbuilder/forms')->load($id);

		if ($model->getId() || $id == 0)
		{
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data))
			{
				$model->setData($data);
			}
			//echo '<pre>';			
			//$model->setData('stores',json_decode($model->getData('stores')));
			//print_R($model->getData());exit;
			Mage::register('formbuilder_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('formbuilder/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Form Manager'), Mage::helper('adminhtml')->__('Form Manager'));			

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('formbuilder/adminhtml_formbuilder_edit'))
			->_addLeft($this->getLayout()->createBlock('formbuilder/adminhtml_formbuilder_edit_tabs'));

			$this->renderLayout();
		}
		else
		{
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('formbuilder')->__('Form does not exist'));
			$this->_redirect('*//**//*');
		}
	}
	public function newAction() 
	{
		$this->_title($this->__('New Form'));

		$_model  = Mage::getModel('formbuilder/forms');
		Mage::register('formbuilder_data', $_model);
		Mage::register('current_Form', $_model);

		$this->_initAction();
		$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Form Manager'), Mage::helper('adminhtml')->__('Form Manager'), $this->getUrl('*'));
		$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Add Form'), Mage::helper('adminhtml')->__('Add Form'));

		$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

		$this->_addContent($this->getLayout()->createBlock('formbuilder/adminhtml_formbuilder_edit'))
		->_addLeft($this->getLayout()->createBlock('formbuilder/adminhtml_formbuilder_edit_tabs'));

		$this->renderLayout(); 
	}*/
	public function editAction()
	{			    
	    $this->_title($this->__("Form"));
		$this->_title($this->__("Form Listing"));
	    $this->_title($this->__("Edit Form"));
		
		$id = $this->getRequest()->getParam("id");
		$model = Mage::getModel("formbuilder/forms")->load($id);

		//echo "model->getFormsIndex() = ".$model->getFormsIndex();
		//exit();

		if ($model->getFormsIndex() || $id==0)
		{
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data))
			{
				$model->setData($data);
			}
			Mage::register("formbuilder_data", $model);
			$this->loadLayout();
			$this->_setActiveMenu("formbuilder/items");
			$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Form Manager"), Mage::helper("adminhtml")->__("Form Manager"));
			$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Form Description"), Mage::helper("adminhtml")->__("Form Description"));
			$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
			$this->_addContent($this->getLayout()->createBlock("formbuilder/adminhtml_formbuilder_edit"))->_addLeft($this->getLayout()->createBlock("formbuilder/adminhtml_formbuilder_edit_tabs"));
			$this->renderLayout();
		} 
		else {
			Mage::getSingleton("adminhtml/session")->addError(Mage::helper("formbuilder")->__("Form does not exist."));
			$this->_redirect("*/*/");
		}
	}
	public function newAction()
	{
		$this->_title($this->__("Form"));
		//$this->_title($this->__("Form"));
		$this->_title($this->__("New Form"));

	    $id   = $this->getRequest()->getParam("id");
		$model  = Mage::getModel("formbuilder/forms")->load($id);

		$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}
		Mage::register("formbuilder_data", $model);

		$this->loadLayout();
		$this->_setActiveMenu("formbuilder/items");

		$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Form Manager"), Mage::helper("adminhtml")->__("Form Manager"));
		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Form Description"), Mage::helper("adminhtml")->__("Form Description"));

		$this->_addContent($this->getLayout()->createBlock("formbuilder/adminhtml_formbuilder_edit"))->_addLeft($this->getLayout()->createBlock("formbuilder/adminhtml_formbuilder_edit_tabs"));

		$this->renderLayout();
	}
	public function saveAction()
	{
		if ($data = $this->getRequest()->getPost())
		{			
			$model = Mage::getModel("formbuilder/forms")->load($this->getRequest()->getParam("id"));
			$currentFormId = $this->getRequest()->getParam("id");
			$_helper = Mage::helper("formbuilder");

			//save image
			if (isset($data['title_image']['delete']) && $data['title_image']['delete'] == 1)
			{
				$data['title_image']='';
			}
			else
			{
				if(isset($_FILES['title_image']['name']) && $_FILES['title_image']['name'] != '')
				{
	                try
	                {
	                    $_helper = Mage::helper("formbuilder");
	                    $path = Mage::getBaseDir('media').DS.$_helper->getImageUploadPath().DS;
	                    $fname = $_FILES['title_image']['name'];
	                    $fname = str_replace(' ', '_', $fname);
	                    $uploader = new Varien_File_Uploader('title_image');
	                    $uploader->setAllowedExtensions(array('jpg','jpeg','png','gif'));
	                    $uploader->setAllowCreateFolders(true);
	                    $uploader->setAllowRenameFiles(true);
	                    $uploader->setFilesDispersion(false);
	                    $destFile = $path.$fname;
	                    $fname  = $uploader->getNewFileName($destFile);
	                    $uploader->save($path,$fname);
	                }
	                catch (Exception $e)
	                {
	                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('formbuilder')->__('Error Message: '.$e->getMessage()));
	                }
			        //this way the name is saved in DB
		  			$data['title_image'] = $_helper->getImageUploadPath().$fname;
		  		}
		  		else
		  		{
		  			unset($data['title_image']);
		  		}
			}
			//save image ends



			try
			{				
				//$model = Mage::getModel("formbuilder/forms")->load($this->getRequest()->getParam("id"));
				if(isset($data['stores']) && !empty($data['stores']))
				{			
					if(in_array('0',$data['stores'])){
						$data['stores'] = array(0);
					}				
					//$stores = Mage::helper('core')->jsonEncode($data['stores']);
					$data['stores'] = implode(',',$data['stores']);
				}
                if ($model->getCreatedTime() == NULL)
                {
                    $data['created_time'] = now();
                }
                $data['update_time'] = now();
                
				/*$fieldsModel = Mage::helper('formbuilder')->getFieldsModel();
				$getFieldsCollection = $fieldsModel->getFieldsCollection($currentFormId);
				$getNoOfFields = count($getFieldsCollection);
				$data['no_of_fields'] = $getNoOfFields;*/

                /*if($data['title']=='' || $data['title']==NULL)
                	$data['title'] = $model['title'];*/
                
                /*$data['title'] = $data['in_toplinks'];
				$data['title_image'] = $data['in_menu'];*/

				

                $model->setData($data)
                	->setFormsIndex($this->getRequest()->getParam("id"))
                	->save();

				if(!$currentFormId)
					$currentFormId = $model->getFormsIndex();
				// saveFields
                $fieldsArray = $data['product']['options'];
                //Mage::helper('formbuilder')->saveFields($fieldsArray);
                $fieldsModel = Mage::helper('formbuilder')->getFieldsModel();
                $fieldsModel->saveFields($fieldsArray,$currentFormId);
                // saveFields

                //save no of fields
                $fieldsModel = Mage::helper('formbuilder')->getFieldsModel();
				$getFieldsCollection = $fieldsModel->getFieldsCollection($currentFormId);
				$getNoOfFields = count($getFieldsCollection);
				//$data['no_of_fields'] = $getNoOfFields;
				$formsModel = Mage::helper('formbuilder')->getFormsModel();
				$formsModel->load($currentFormId);
				$formsModel->setNoOfFields($getNoOfFields);
				$formsModel->save();
				//save no of fields ends

                //$this->createCmsPage($model);

				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('formbuilder')->__('Form was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getFormsIndex()));
					return;
				}
				$this->_redirect('*/*/');
				return;
			}
			catch (Exception $e)
			{
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				Mage::getSingleton('adminhtml/session')->setFormData($data);
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return;
			}
		}
		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('formbuilder')->__('Unable to find Form to save'));
		$this->_redirect('*/*/');
	}
	public function removeFile($file) 
	{
        $_helper = Mage::helper('ram');
        $file = str_replace('\\', DS, $file);
        $directory = Mage::getBaseDir('media') . DS .'formbuilder/images' ;
        $io = new Varien_Io_File();
        $result = $io->rmdir($directory, true);
	}
	public function createCmsPage($currentModel)
	{
		$title = $currentModel['title'];
		$identifier = strtolower($title);
		$identifier = str_replace(" ","-",$identifier);

		Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
		$cmsPage = array(
		            'title' => $title,
		            'identifier' => $identifier,
		            'content' => '{{block type="formbuilder/frontend_form" name="frontend_form" template="formbuilder/form.phtml"}}',
		            'is_active' => 1,
		            'sort_order' => 0,
		            'stores' => array(0),
		            'root_template' => 'one_column'
		            );
		Mage::getModel('cms/page')->setData($cmsPage)->save();
	}
	public function deleteAction() 
	{
		if( $this->getRequest()->getParam('id') > 0 )
		{
			try
			{
				$model = Mage::getModel('formbuilder/forms');
				
				$model->setFormsIndex($this->getRequest()->getParam('id'))
				->delete();
				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Form was successfully deleted'));
				$this->_redirect('*/*/');
			}
			catch (Exception $e)
			{
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}
	public function massDeleteAction() 
	{
		$formbuilderIds = $this->getRequest()->getParam('formbuilder');
		if(!is_array($formbuilderIds))
		{
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select Form(s)'));
		}
		else
		{
			try
			{
				foreach ($formbuilderIds as $formbuilderId)
				{
					$formbuilder = Mage::getModel('formbuilder/forms')->load($formbuilderId);
					$formbuilder->delete();
				}
				Mage::getSingleton('adminhtml/session')->addSuccess(
				Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted', count($formbuilderIds)));
			}
			catch (Exception $e)
			{
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
		$this->_redirect('*/*/index');
	}	
	public function massStatusAction()
	{
		$formbuilderIds = $this->getRequest()->getParam('formbuilder');
		if(!is_array($formbuilderIds))
		{
			Mage::getSingleton('adminhtml/session')->addError($this->__('Please select Form(s)'));
		}
		else
		{
			try
			{
				foreach ($formbuilderIds as $formbuilderId)
				{
					$formbuilder = Mage::getSingleton('formbuilder/forms')
					->load($formbuilderId)
					->setStatus($this->getRequest()->getParam('status'))
					->setIsMassupdate(true)
					->save();
				}
				$this->_getSession()->addSuccess(
				$this->__('Total of %d record(s) were successfully updated', count($formbuilderIds)));
			}
			catch (Exception $e)
			{
				$this->_getSession()->addError($e->getMessage());
			}
		}
		$this->_redirect('*/*/index');
	}	
}