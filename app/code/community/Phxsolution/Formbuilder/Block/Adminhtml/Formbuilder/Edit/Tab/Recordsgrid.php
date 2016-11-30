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
class Phxsolution_Formbuilder_Block_Adminhtml_Formbuilder_Edit_Tab_Recordsgrid extends Mage_Adminhtml_Block_Widget_Grid
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    public function _construct()
    {
        parent::_construct();
        $this->setId('records_grid');
        $this->setDefaultSort('records_index');
        $this->setDefaultDir('ASC');
        //enable ajax grid
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
    }
    protected function _prepareCollection()
    {
        /*if(!Mage::helper('formbuilder')->isEnabled())
        {
            Mage::getSingleton('adminhtml/session')->addError('Extension is disabled');
            return;
        }*/
        $currentFormId = $this->getRequest()->getParam('id');
        $recordsModel = Mage::helper('formbuilder')->getRecordsModel();
        $collection = $recordsModel->getRecordsCollection($currentFormId);
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    protected function _prepareColumns()
    {
        //$titleArray = array(8=>'First Name',9=>'Last Name',10=>'12th Result');
        $currentFormId = $this->getRequest()->getParam('id');
        $fieldsModel = Mage::helper('formbuilder')->getFieldsModel();
        $prepareFieldTitles = array();
        $prepareFieldTitles = $fieldsModel->prepareFieldTitles($currentFormId);
        
        /*echo "<pre>";
        print_r($prepareFieldTitles);
        echo "</pre>";*/
        if(count($prepareFieldTitles))
        {
            $this->addColumn('records_index', array(
                'header' => Mage::helper('formbuilder')->__('ID'),
                'align' => 'left',
                'index' => 'records_index',
            ));
            $this->addColumn('customer', array(
                'header' => Mage::helper('formbuilder')->__('Customer'),
                'align' => 'left',
                'index' => 'customer',
            ));
            $i=1;
            foreach ($prepareFieldTitles as $fieldId => $fieldTitle)
            {                
                $this->addColumn($fieldId, array(
                    'header' => Mage::helper('formbuilder')->__($fieldTitle),
                    'align' => 'left',
                    'name'  =>  $i++,
                    'index' => $fieldId,
                    'renderer'  => 'formbuilder/adminhtml_formbuilder_renderer_recordvalue'
                ));
            }
        }
        
        /*$this->addColumn('records_index', array(
            'header' => Mage::helper('formbuilder')->__('ID'),
            'align' => 'left',
            'index' => 'records_index',
        ));
        $this->addColumn('forms_index', array(
            'header' => Mage::helper('formbuilder')->__('FORM'),
            'align' => 'left',
            'index' => 'forms_index',
        ));
        $this->addColumn('fields_index', array(
            'header' => Mage::helper('formbuilder')->__('FIELD ID'),
            'align' => 'left',
            'index' => 'fields_index',
            'renderer'  => 'formbuilder/adminhtml_formbuilder_renderer_fieldtitle'
        ));
        $this->addColumn('options_index', array(
            'header' => Mage::helper('formbuilder')->__('OPTION ID'),
            'align' => 'left',
            'index' => 'options_index',
        ));
        $this->addColumn('value', array(
            'header' => Mage::helper('formbuilder')->__('VALUE'),
            'align' => 'left',
            'index' => 'value',
        ));*/
        return parent::_prepareColumns();
    }
    //this method is reuired if you want ajax grid
    public function getGridUrl()
    {
        return $this->getUrl('*/*/recordsgrid', array('_current' => true));
    }
    public function canShowTab()
    {
        return true;
    }
    public function isHidden()
    {
        return false;
    }
    public function getTabLabel()
    {
        return $this->__('Fields List');
    }
    public function getTabTitle()
    {
        return $this->__('Fields List');
    }
}