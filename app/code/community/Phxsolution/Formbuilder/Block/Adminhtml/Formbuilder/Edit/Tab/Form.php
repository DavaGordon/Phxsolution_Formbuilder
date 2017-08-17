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

class Phxsolution_Formbuilder_Block_Adminhtml_Formbuilder_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model = Mage::registry('formbuilder_data');

        if ($model->getStores()) {
            //$_model->setPageId(Mage::helper('core')->jsonDecode($_model->getPageId()));
            $model->setStores(explode(',', $model->getStores()));
        }
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('formbuilder_form', array('legend' => Mage::helper('formbuilder')->__('Form Information')));
        $fieldset->addType('bgcolor', 'Phxsolution_Formbuilder_Block_Adminhtml_Edit_Elements_Jscolorblock');

        $fieldset->addField('form_title', 'text', array(
            'label' => Mage::helper('formbuilder')->__('Form Title'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'form_title',
        ));
        $fieldset->addField('bgcolor', 'text', array(
            'label' => Mage::helper('formbuilder')->__('Form Background Color'),
            'name' => 'bgcolor',
            'class' => 'color {required:false, adjust:true, hash:true}',
            'style' => 'width:75px;',
        ));
        $fieldset->addField('title_image', 'image', array(
            'label' => Mage::helper('formbuilder')->__('Title Image'),
            'name' => 'title_image',
            'note' => '(*.jpg, *.png, *.gif)',
        ));
        $fieldset->addField('header_content', 'editor', array(
            'name' => 'header_content',
            'label' => Mage::helper('formbuilder')->__('Content Before Form'),
            'title' => Mage::helper('formbuilder')->__('Content Before Form'),
            'style' => 'height:15em',
            'config' => Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
            'wysiwyg' => true,
            'required' => false,
        ));
        $fieldset->addField('footer_content', 'editor', array(
            'name' => 'footer_content',
            'label' => Mage::helper('formbuilder')->__('Content After Form'),
            'title' => Mage::helper('formbuilder')->__('Content After Form'),
            'style' => 'height:15em',
            'config' => Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
            'wysiwyg' => true,
            'required' => false,
        ));
        if (!Mage::app()->isSingleStoreMode()) {
            $field = $fieldset->addField('stores', 'multiselect', array(
                'name' => 'stores[]',
                'label' => Mage::helper('cms')->__('Store View'),
                'title' => Mage::helper('cms')->__('Store View'),
                'required' => true,
                'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
                'value' => $model->getStores()
                //'value'     => array('0'=>'1','1'=>'2'),
            ));
            $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
            $field->setRenderer($renderer);
        } else {
            $fieldset->addField('stores', 'hidden', array(
                'name' => 'stores[]',
                'value' => Mage::app()->getStore(true)->getId()
            ));
            $model->setStores(Mage::app()->getStore(true)->getId());
        }
        $fieldset->addField('success_msg', 'text', array(
            'label' => Mage::helper('formbuilder')->__('Success Message'),
            'name' => 'success_msg',
            'style' => 'width:500px;',
        ));
        $fieldset->addField('failure_msg', 'text', array(
            'label' => Mage::helper('formbuilder')->__('Failure Message'),
            'name' => 'failure_msg',
            'style' => 'width:500px;',
        ));
        $fieldset->addField('submit_text', 'text', array(
            'label' => Mage::helper('formbuilder')->__('Submit Button Text'),
            'name' => 'submit_text',
        ));
        $fieldset->addField('in_menu', 'select', array(
            'label' => Mage::helper('formbuilder')->__('Display in Menu'),
            'name' => 'in_menu',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('formbuilder')->__('Yes'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('formbuilder')->__('No'),
                ),
            ),
        ));
        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('formbuilder')->__('Is Active'),
            'name' => 'status',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('formbuilder')->__('Yes'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('formbuilder')->__('No'),
                ),
            ),
        ));
        if (Mage::getSingleton('adminhtml/session')->getFormData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getFormData());
            Mage::getSingleton('adminhtml/session')->setFormData(null);
        } elseif (Mage::registry('formbuilder_data')) {
            $form->setValues(Mage::registry('formbuilder_data')->getData());
        }
        return parent::_prepareForm();
    }
}