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

class Phxsolution_Formbuilder_Block_Adminhtml_Formbuilder_Edit_Tab_Options_Option extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Options_Option
{
    protected $_product;

    protected $_productInstance;

    protected $_values;
    protected $_values2;

    protected $_itemCount = 1;

    /**
     * Class constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('formbuilder/catalog/product/edit/options/option.phtml');
        $this->setCanReadPrice(false);
        $this->setCanEditPrice(false);
    }

    public function getItemCount()
    {
        return $this->_itemCount;
    }

    public function setItemCount($itemCount)
    {
        $this->_itemCount = max($this->_itemCount, $itemCount);
        return $this;
    }

    public function getFields($form_id)
    {
        if ($form_id) {
            $model = Mage::getModel("formbuilder/fields")->getCollection();
            $model->addFieldToFilter('forms_index', $form_id);
            $model->setOrder('sort_order','DESC');
            return $model;
        }
    }

    public function getFieldOptions($option_id)
    {
            $model = Mage::getModel("formbuilder/options")->getCollection();
            $model->addFieldToFilter('fields_index', $option_id);
            $model->setOrder('sort_order','ASC');
            return $model;
    }


    protected function _prepareLayout()
    {
        $this->setChild('delete_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label' => Mage::helper('catalog')->__('Delete Field'),
                    'class' => 'delete delete-product-option '
                ))
        );

        $path = 'global/catalog/product/options/custom/groups';

        foreach (Mage::getConfig()->getNode($path)->children() as $group) {
            $this->setChild($group->getName() . '_option_type',
                $this->getLayout()->createBlock(
                    (string)Mage::getConfig()->getNode($path . '/' . $group->getName() . '/render')
                )
            );
        }
        return parent::_prepareLayout();
    }

    public function getAddButtonId()
    {
        $buttonId = $this->getLayout()
            ->getBlock('admin.product.options')
            ->getChild('add_button')->getId();
        return $buttonId;
    }

    public function getDeleteButtonHtml()
    {
        return $this->getChildHtml('delete_button');
    }

    public function getTypeSelectHtml()
    {
        $defaultOptions = Mage::getSingleton('adminhtml/system_config_source_product_options_type')->toOptionArray();
        $newOptions = array(
            'label' => 'Layout Options',
            'value' => array(
                array(
                    'label' => 'Column Start', 'value' => 'colstart',
                ),
                array(
                    'label' => 'Column End', 'value' => 'colend'
                ),
                array(
                    'label' => 'Container Start', 'value' => 'containerstart',
                ),
                array(
                    'label' => 'Container End', 'value' => 'containerend'
                )
            )
        );
        array_push($defaultOptions, $newOptions);

        $select = $this->getLayout()->createBlock('adminhtml/html_select')
            ->setData(array(
                'id' => $this->getFieldId() . '_{{id}}_type',
                'class' => 'select select-product-option-type required-option-select'
            ))
            ->setName($this->getFieldName() . '[{{id}}][type]')
            ->setOptions($defaultOptions);

        return $select->getHtml();
    }

    public function getStatusHtml()
    {
        $select = $this->getLayout()->createBlock('adminhtml/html_select')
            ->setData(array(
                'id' => $this->getFieldId() . '_{{id}}_field_status',
                'class' => 'select'
            ))
            ->setName($this->getFieldName() . '[{{id}}][field_status]')
            ->setOptions(Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray());

        return $select->getHtml();
    }

    public function getRequireSelectHtml()
    {
        $select = $this->getLayout()->createBlock('adminhtml/html_select')
            ->setData(array(
                'id' => $this->getFieldId() . '_{{id}}_is_require',
                'class' => 'select'
            ))
            ->setName($this->getFieldName() . '[{{id}}][is_require]')
            ->setOptions(Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray());

        return $select->getHtml();
    }

    /**
     * Retrieve html templates for different types of product custom options
     *
     * @return string
     */
    public function getTemplatesHtml()
    {
        $this->getChild('select_option_type')
            ->setCanReadPrice(false)
            ->setCanEditPrice(false);

        $this->getChild('file_option_type')
            ->setCanReadPrice(false)
            ->setCanEditPrice(false);

        $this->getChild('date_option_type')
            ->setCanReadPrice(false)
            ->setCanEditPrice(false);

        $this->getChild('text_option_type')
            ->setCanReadPrice(false)
            ->setCanEditPrice(false);

        $templates = $this->getChildHtml('text_option_type') . "\n" .
            $this->getChildHtml('file_option_type') . "\n" .
            $this->getChildHtml('select_option_type') . "\n" .
            $this->getChildHtml('date_option_type');

        return $templates;
    }

    public function getOptionValues()
    {

        $currentFormId = Mage::getSingleton('core/session')->getCurrentFormId();

        if ($currentFormId && $this->getFields($currentFormId)) {
            $values2 = array();
            foreach ($this->getFields($currentFormId) as $key => $field) {

                if (!$field['is_delete']) {
                    $value2 = array();
                    $value2['id'] = $field['field_id'];
                    $value2['item_count'] = $field['field_id'];
                    $value2['option_id'] = $field['field_id'];
                    $value2['title'] = $field['title'];
                    //$value2['previous_group'] = $field['previous_group'];
                    $value2['type'] = $field['type'];
                    $value2['is_require'] = $field['is_require'];
                    $value2['cssclass'] = $field['cssclass'];
                    $value2['field_status'] = $field['status'];
                    $value2['sort_order'] = $field['sort_order'];
                    $value2['can_edit_price'] = false;
                    if ($field['previous_group'] == 'text') {
                        $value2['price'] = 0.00;
                        $value2['price_type'] = 'fixed';
                        $value2['sku'] = '';
                        $value2['max_characters'] = $field['max_characters'];
                        $value2['image_size_x'] = $field['image_size_x'];
                        $value2['image_size_y'] = $field['image_size_y'];
                    } elseif ($field['previous_group'] == 'file')
                        $value2['file_extension'] = $field['file_extension'];
                    if ($field['previous_group'] == 'select') {
                        $i = 0;

                        foreach ($this->getFieldOptions($field['fields_index']) as $key => $option) {

                            $value2['optionValues'][$i]['item_count'] = $option['option_id'];
                            $value2['optionValues'][$i]['option_id'] = $field['field_id'];
                            $value2['optionValues'][$i]['option_type_id'] = $option['option_id'];
                            $value2['optionValues'][$i]['title'] = $option['title'];
                            $value2['optionValues'][$i]['price'] = 0.00;
                            $value2['optionValues'][$i]['price_type'] = 'fixed';
                            $value2['optionValues'][$i]['sku'] = '';
                            $value2['optionValues'][$i]['sort_order'] = $option['sort_order'];
                            $i++;
                        }

                    }

                    $values2[] = new Varien_Object($value2);
                }
            }
            return $this->_values2 = $values2;


        }
    }

    /**
     * Retrieve html of scope checkbox
     *
     * @param string $id
     * @param string $name
     * @param boolean $checked
     * @param string $select_id
     * @return string
     */
    public
    function getCheckboxScopeHtml($id, $name, $checked = true, $select_id = '-1')
    {
        $checkedHtml = '';
        if ($checked) {
            $checkedHtml = ' checked="checked"';
        }
        $selectNameHtml = '';
        $selectIdHtml = '';
        if ($select_id != '-1') {
            $selectNameHtml = '[values][' . $select_id . ']';
            $selectIdHtml = 'select_' . $select_id . '_';
        }
        $checkbox = '<input type="checkbox" id="' . $this->getFieldId() . '_' . $id . '_' .
            $selectIdHtml . $name . '_use_default" class="product-option-scope-checkbox" name="' .
            $this->getFieldName() . '[' . $id . ']' . $selectNameHtml . '[scope][' . $name . ']" value="1" ' .
            $checkedHtml . '/>';
        $checkbox .= '<label class="normal" for="' . $this->getFieldId() . '_' . $id . '_' .
            $selectIdHtml . $name . '_use_default">' . $this->__('Use Default Value') . '</label>';
        return $checkbox;
    }

    public
    function getPriceValue($value, $type)
    {
        if ($type == 'percent') {
            return number_format($value, 2, null, '');
        } elseif ($type == 'fixed') {
            return number_format($value, 2, null, '');
        }
    }
}