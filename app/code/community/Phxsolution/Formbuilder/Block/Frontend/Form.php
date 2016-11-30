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
class Phxsolution_Formbuilder_Block_Frontend_Form extends Mage_Core_Block_Template
{	
	protected function _prepareLayout()
    {
        //echo "<h1>test</h1>";
    }
	public function getCurrentFormUsingBlock($passFormId)
	{
		if($passFormId)
			$currentFormArray = Mage::getModel('formbuilder/forms')->load($passFormId);		
		return $currentFormArray;
	}
	public function getCurrentFormFields($currentFormId=0)
	{
		if(!$currentFormId)
			$currentFormId = intval($this->getRequest()->getParam('id'));
		if($currentFormId)
		{
			$currentFormFieldsCollection = Mage::getModel('formbuilder/fields')->getCollection();
			$currentFormFieldsCollection->addFieldToFilter('forms_index',array('eq'=>$currentFormId));
			$currentFormFieldsCollection->addFieldToFilter('status',array('eq'=>1));
			$currentFormFieldsCollection->setOrder('sort_order','ASC');
		}
		return $currentFormFieldsCollection;
	}
	public function getTextHtml($field)
	{
		echo $this->getLayout()
				->createBlock('formbuilder/frontend_text')
				->setData('field', $field)
				->setTemplate('formbuilder/text.phtml')
				->toHtml();
	}
	public function getSelectHtml($field)
	{
		echo $this->getLayout()
				->createBlock('formbuilder/frontend_select')
				->setData('field', $field)
				->setTemplate('formbuilder/select.phtml')
				->toHtml();
	}
	public function getDateHtml($field)
	{
		echo $this->getLayout()
				->createBlock('formbuilder/frontend_date')
				->setData('field', $field)
				->setTemplate('formbuilder/date.phtml')
				->toHtml();
	}
	public function getFileHtml($field)
	{
		echo $this->getLayout()
				->createBlock('formbuilder/frontend_file')
				->setData('field', $field)
				->setTemplate('formbuilder/file.phtml')
				->toHtml();
	}
	/*protected $_catfaq;
	protected $_catfaqcCollection;
	
	protected function _prepareLayout()
    {
        $categoryfaq = $this->getCategory();
        if ($categoryfaq !== false && $head = $this->getLayout()->getBlock('head'))
        {
            $head->setTitle($this->htmlEscape($categoryfaq->getName()) . ' - ' . $head->getTitle());
        }
    }
    public function getCategory()
    {
    	if (!$this->_catfaq)
    	{
	    	$id = intval($this->getRequest()->getParam('cat_id'));
	    	try
	    	{
				$this->_catfaq = Mage :: getModel('faq/category')->load($id);
				if ($this->_catfaq->getIsActive() != 1)
				{
					Mage::throwException('Catagory is not active');
				}
			}
			catch (Exception $e)
			{
				$this->_catfaq = false;
			}
    	}
		return $this->_catfaq;
    }    
	/**
	 * Function to gather the current faq item
	 *
	 * @return Inic_Faq_Model_Faq The current faq item
	 */
	/*public function getcatFaqCollection()
	{
		try
		{
			if (!$this->_catfaq)
			{
					Mage::throwException('Please Select Category');
			}
			else
			{
				$this->_catfaqcCollection=$this->_catfaq->getItemCollection()->addIsActiveFilter()->addStoreFilter(Mage::app()->getStore());
			}
		}
		catch (Exception $e)
		{
			$this->_catfaqcCollection = false;
		}
		return $this->_catfaqcCollection;
	}*/
}