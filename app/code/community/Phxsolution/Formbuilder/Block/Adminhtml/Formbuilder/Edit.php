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
class Phxsolution_Formbuilder_Block_Adminhtml_Formbuilder_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'formbuilder';
        $this->_controller = 'adminhtml_formbuilder';
        
        $this->_updateButton('save', 'label', Mage::helper('formbuilder')->__('Save Form'));
        $this->_updateButton('delete', 'label', Mage::helper('formbuilder')->__('Delete Form'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('formbuilder_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'formbuilder_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'formbuilder_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }
    public function getHeaderText()
    {
        if( Mage::registry('formbuilder_data') && Mage::registry('formbuilder_data')->getFormsIndex() )
        {
            $currentFormId = Mage::registry('formbuilder_data')->getFormsIndex();
            Mage::getSingleton('core/session')->setCurrentFormId($currentFormId);
            return Mage::helper('formbuilder')->__("Edit Form '%s'", $this->htmlEscape(Mage::registry('formbuilder_data')->getTitle()));            
        }
        else
        {
            Mage::getSingleton('core/session')->setCurrentFormId(0);
            return Mage::helper('formbuilder')->__('Add Form');
        }
    }
}