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
class Phxsolution_Formbuilder_Block_Adminhtml_Formbuilder_Edit_Tab_Fieldsgrid extends Mage_Adminhtml_Block_Widget_Grid
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    public function _construct()
    {
        parent::_construct();
        $this->setId('expert_advice_grid');
        $this->setDefaultSort('sort_order');
        $this->setDefaultDir('ASC');
        //enable ajax grid
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
    }
    protected function _prepareCollection()
    {
        //$collection = Mage::helper('formbuilder')->getcurrentFormFieldsCollection();
        $currentFormId = Mage::getSingleton('core/session')->getCurrentFormId();
        $fieldsModel = Mage::helper('formbuilder')->getFieldsModel();
        $collection = $fieldsModel->getcurrentFormFieldsCollection($currentFormId);
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    protected function _prepareColumns()
    {
        $this->addColumn('fields_index', array(
            'header' => Mage::helper('formbuilder')->__('ID'),
            'width' =>  '25px',
            'align' => 'left',
            'index' => 'fields_index',
        ));
        /*$this->addColumn('forms_index', array(
            'header' => Mage::helper('formbuilder')->__('Form'),
            'align' => 'left',
            'index' => 'forms_index',
            'renderer'  => 'formbuilder/adminhtml_formbuilder_renderer_formtitle'
        ));*/
        $this->addColumn('title', array(
            'header' => Mage::helper('formbuilder')->__('Title'),
            'width' =>  '250px',
            'align' => 'left',
            'index' => 'title',
        ));
        $this->addColumn('previous_group', array(
            'header' => Mage::helper('formbuilder')->__('Input Type'),
            'width' =>  '50px',
            'align' => 'left',
            'index' => 'previous_group',
        ));
        $this->addColumn('type', array(
            'header' => Mage::helper('formbuilder')->__('Sub Type'),
            'width' =>  '50px',
            'align' => 'left',
            'index' => 'type',
        ));
        $this->addColumn('options', array(
            'header' => Mage::helper('formbuilder')->__('Options'),
            'width' =>  '200px',
            'align' => 'left',
            'index' => 'options',
            'renderer'  => 'formbuilder/adminhtml_formbuilder_renderer_getoptions'
        ));
        $this->addColumn('is_require', array(
            'header' => Mage::helper('formbuilder')->__('Is Required'),
            'width' =>  '25px',
            'align' => 'left',
            'index' => 'is_require',
            'type'      => 'options',
            'options'   => array(
              1 => 'Yes',
              0 => 'No',
            ),
        ));
        $this->addColumn('sort_order', array(
            'header' => Mage::helper('formbuilder')->__('Sort Order'),
            'width' =>  '25px',
            'align' => 'left',
            'index' => 'sort_order',
        ));
        $this->addColumn('max_characters', array(
            'header' => Mage::helper('formbuilder')->__('Max Characters'),
            'width' =>  '25px',
            'align' => 'left',
            'index' => 'max_characters',
        ));
        $this->addColumn('status', array(
            'header' => Mage::helper('formbuilder')->__('Status'),
            'width' =>  '25px',
            'align' => 'left',
            'index' => 'status',
            'type'      => 'options',
            'options'   => array(
              1 => 'Enabled',
              0 => 'Disabled',
            ),
        ));
        
        return parent::_prepareColumns();
    }
    //this method is reuired if you want ajax grid
    public function getGridUrl()
    {
        return $this->getUrl('*/*/fieldsgrid', array('_current' => true));
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