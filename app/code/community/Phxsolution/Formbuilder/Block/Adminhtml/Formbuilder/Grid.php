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
class Phxsolution_Formbuilder_Block_Adminhtml_Formbuilder_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('formbuilderGrid');
      $this->setDefaultSort('forms_index');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }  
  protected function _prepareCollection()
  {
      $collection = Mage::getModel('formbuilder/forms')->getResourceCollection();
   
      foreach ($collection as $view) {
          if ( $view->getStores() && $view->getStores() != 0 ) {
              $view->setStores(explode(',',$view->getStores()));
          } else {
              $view->setStores(array('0'));
          }
      }   
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }
  protected function _prepareColumns()
  {
      $this->addColumn('forms_index', array(
          'header'    => Mage::helper('formbuilder')->__('ID'),
          'align'     =>'left',
          'width'     => '50px',
          'index'     => 'forms_index',
      ));
      $this->addColumn('title', array(
          'header'    => Mage::helper('formbuilder')->__('Title'),
          'align'     =>'left',
          'width'     => '250px',
          'index'     => 'title',
      ));
      $this->addColumn('no_of_fields', array(
          'header'    => Mage::helper('formbuilder')->__('Fields'),          
          'align'     =>'left',
          'width'     => '50px',
          'index'     => 'no_of_fields',
          'renderer'  => 'formbuilder/adminhtml_formbuilder_renderer_numberoffields'
      ));
      $this->addColumn('form_image', array(
        'header'    => Mage::helper('formbuilder')->__('Title Image'),
        'align'     =>'left',
        "width" => "80px",
        'index'     => 'form_image',
        'renderer'  => 'formbuilder/adminhtml_formbuilder_renderer_image'
      ));	    
      $this->addColumn('created_time', array(
          'header'    => Mage::helper('formbuilder')->__('Created Time'),
          'align'     => 'left',
          'width'     => '150px',
          'index'     => 'created_time',
      ));
      $this->addColumn('update_time', array(
          'header'    => Mage::helper('formbuilder')->__('Updated Time'),
          'align'     => 'left',
          'width'     => '150px',
          'index'     => 'update_time',
      ));
      $this->addColumn('status', array(
          'header'    => Mage::helper('formbuilder')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));	  
        if ( !Mage::app()->isSingleStoreMode() )
        {
            $this->addColumn('stores', array(
                'header' => Mage::helper('formbuilder')->__('Store View'),
                'width'     => '150px',
                'index' => 'stores',
                'type' => 'store',
                'store_all' => true,
                'store_view' => true,
                'sortable' => true,
                'filter_condition_callback' => array($this, '_filterStoreCondition'),
            ));
        }
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('formbuilder')->__('Action'),
                'width'     => '100px',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('formbuilder')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    ),
                    array(
                        'caption'   => Mage::helper('formbuilder')->__('View Results'),
                        'url'       => array('base'=> 'formbuilder/adminhtml_formbuilder/recordsgrid'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		//$this->addExportType('*/*/exportCsv', Mage::helper('formbuilder')->__('CSV'));
		//$this->addExportType('*/*/exportXml', Mage::helper('formbuilder')->__('XML'));
	  
      return parent::_prepareColumns();
  }
    protected function _filterStoreCondition($collection, $column)
    {
        if ( !$value = $column->getFilter()->getValue() ) {
            return;
        }
        $this->getCollection()->addStoreFilter($value);
    }
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('forms_index');
        $this->getMassactionBlock()->setFormFieldName('formbuilder');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('formbuilder')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('formbuilder')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('formbuilder/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('formbuilder')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('formbuilder')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }
  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }
}