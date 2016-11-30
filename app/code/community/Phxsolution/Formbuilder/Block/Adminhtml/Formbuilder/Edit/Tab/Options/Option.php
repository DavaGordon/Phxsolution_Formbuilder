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
class Phxsolution_Formbuilder_Block_Adminhtml_Formbuilder_Edit_Tab_Options_Option extends Mage_Adminhtml_Block_Widget
//class Phxsolution_Formbuilder_Block_Adminhtml_Formbuilder_Edit_Tab_Options_Option extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Options_Option
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
    /**
     * Get Product
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        if (!$this->_productInstance) {
            if ($product = Mage::registry('product')) {
                $this->_productInstance = $product;
            } else {
                $this->_productInstance = Mage::getSingleton('catalog/product');
            }
        }

        return $this->_productInstance;
    }
    public function setProduct($product)
    {
        $this->_productInstance = $product;
        return $this;
    }
    /**
     * Retrieve options field name prefix
     *
     * @return string
     */
    public function getFieldName()
    {
        return 'product[options]';
    }
    /**
     * Retrieve options field id prefix
     *
     * @return string
     */
    public function getFieldId()
    {
        return 'product_option';
    }
    /**
     * Check block is readonly
     *
     * @return boolean
     */
    public function isReadonly()
    {
         return $this->getProduct()->getOptionsReadonly();
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
                    (string) Mage::getConfig()->getNode($path . '/' . $group->getName() . '/render')
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
        $select = $this->getLayout()->createBlock('adminhtml/html_select')
            ->setData(array(
                'id' => $this->getFieldId().'_{{id}}_type',
                'class' => 'select select-product-option-type required-option-select'
            ))
            ->setName($this->getFieldName().'[{{id}}][type]')
            ->setOptions(Mage::getSingleton('adminhtml/system_config_source_product_options_type')->toOptionArray());

        return $select->getHtml();
    }
    public function getStatusHtml()
    {
        $select = $this->getLayout()->createBlock('adminhtml/html_select')
            ->setData(array(
                'id' => $this->getFieldId().'_{{id}}_field_status',
                'class' => 'select'
            ))
            ->setName($this->getFieldName().'[{{id}}][field_status]')
            ->setOptions(Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray());

        return $select->getHtml();
    }
    public function getRequireSelectHtml()
    {
        $select = $this->getLayout()->createBlock('adminhtml/html_select')
            ->setData(array(
                'id' => $this->getFieldId().'_{{id}}_is_require',
                'class' => 'select'
            ))
            ->setName($this->getFieldName().'[{{id}}][is_require]')
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
        $canEditPrice = $this->getCanEditPrice();
        $canReadPrice = $this->getCanReadPrice();
        $this->getChild('select_option_type')
            ->setCanReadPrice($canReadPrice)
            ->setCanEditPrice($canEditPrice);

        $this->getChild('file_option_type')
            ->setCanReadPrice($canReadPrice)
            ->setCanEditPrice($canEditPrice);

        $this->getChild('date_option_type')
            ->setCanReadPrice($canReadPrice)
            ->setCanEditPrice($canEditPrice);

        $this->getChild('text_option_type')
            ->setCanReadPrice($canReadPrice)
            ->setCanEditPrice($canEditPrice);

        $templates = $this->getChildHtml('text_option_type') . "\n" .
            $this->getChildHtml('file_option_type') . "\n" .
            $this->getChildHtml('select_option_type') . "\n" .
            $this->getChildHtml('date_option_type');

        return $templates;
    }
    public function getOptionValues()
    {
        $optionsArr = array_reverse($this->getProduct()->getOptions(), true);
        //$optionsArr = $this->getProduct()->getOptions();

        if (!$this->_values)
        {
            $showPrice = $this->getCanReadPrice();
            $values = array();
            $scope = (int) Mage::app()->getStore()->getConfig(Mage_Core_Model_Store::XML_PATH_PRICE_SCOPE);
            foreach ($optionsArr as $option)
            {
                /* @var $option Mage_Catalog_Model_Product_Option */

                $this->setItemCount($option->getOptionId());

                $value = array();

                $value['id'] = $option->getOptionId();
                $value['item_count'] = $this->getItemCount();
                $value['option_id'] = $option->getOptionId();
                $value['title'] = $this->escapeHtml($option->getTitle());
                $value['type'] = $option->getType();
                $value['is_require'] = $option->getIsRequire();
                $value['sort_order'] = $option->getSortOrder();
                $value['can_edit_price'] = $this->getCanEditPrice();

                if ($this->getProduct()->getStoreId() != '0')
                {
                    $value['checkboxScopeTitle'] = $this->getCheckboxScopeHtml($option->getOptionId(), 'title',
                        is_null($option->getStoreTitle()));
                    $value['scopeTitleDisabled'] = is_null($option->getStoreTitle())?'disabled':null;
                }
                if ($option->getGroupByType() == Mage_Catalog_Model_Product_Option::OPTION_GROUP_SELECT)
                {
                    //$valuesArr = array_reverse($option->getValues(), true);

                    $i = 0;
                    $itemCount = 0;
                    foreach ($option->getValues() as $_value)
                    {
                        /* @var $_value Mage_Catalog_Model_Product_Option_Value */
                        $value['optionValues'][$i] = array(
                            'item_count' => max($itemCount, $_value->getOptionTypeId()),
                            'option_id' => $_value->getOptionId(),
                            'option_type_id' => $_value->getOptionTypeId(),
                            'title' => $this->escapeHtml($_value->getTitle()),
                            'price' => ($showPrice)
                                ? $this->getPriceValue($_value->getPrice(), $_value->getPriceType()) : '',
                            'price_type' => ($showPrice) ? $_value->getPriceType() : 0,
                            'sku' => $this->escapeHtml($_value->getSku()),
                            'sort_order' => $_value->getSortOrder(),
                        );

                        if ($this->getProduct()->getStoreId() != '0')
                        {
                            $value['optionValues'][$i]['checkboxScopeTitle'] = $this->getCheckboxScopeHtml(
                                $_value->getOptionId(), 'title', is_null($_value->getStoreTitle()),
                                $_value->getOptionTypeId());
                            $value['optionValues'][$i]['scopeTitleDisabled'] = is_null($_value->getStoreTitle())
                                ? 'disabled' : null;
                            if ($scope == Mage_Core_Model_Store::PRICE_SCOPE_WEBSITE)
                            {
                                $value['optionValues'][$i]['checkboxScopePrice'] = $this->getCheckboxScopeHtml(
                                    $_value->getOptionId(), 'price', is_null($_value->getstorePrice()),
                                    $_value->getOptionTypeId());
                                $value['optionValues'][$i]['scopePriceDisabled'] = is_null($_value->getStorePrice())
                                    ? 'disabled' : null;
                            }
                        }
                        $i++;
                    }
                }
                else
                {
                    $value['price'] = ($showPrice)
                        ? $this->getPriceValue($option->getPrice(), $option->getPriceType()) : '';
                    $value['price_type'] = $option->getPriceType();
                    $value['sku'] = $this->escapeHtml($option->getSku());
                    $value['max_characters'] = $option->getMaxCharacters();
                    $value['file_extension'] = $option->getFileExtension();
                    $value['image_size_x'] = $option->getImageSizeX();
                    $value['image_size_y'] = $option->getImageSizeY();
                    if ($this->getProduct()->getStoreId() != '0' &&
                        $scope == Mage_Core_Model_Store::PRICE_SCOPE_WEBSITE)
                    {
                        $value['checkboxScopePrice'] = $this->getCheckboxScopeHtml($option->getOptionId(),
                            'price', is_null($option->getStorePrice()));
                        $value['scopePriceDisabled'] = is_null($option->getStorePrice())?'disabled':null;
                    }
                }
                $values[] = new Varien_Object($value);
            }//     foreach ($optionsArr as $option)
            $this->_values = $values;
        }//     if (!$this->_values)

        
        
        
        
        if($this->_values)          //  if product-id found then
            return $this->_values;  //  return custom options collection of current product
        else                       //  else return formbuilder custom options collection of current form
        {
            $currentFormId = Mage::getSingleton('core/session')->getCurrentFormId();
            if($currentFormId)
            {
                $fieldsModel = Mage::helper('formbuilder')->getFieldsModel();
                $fieldsCollection  = $fieldsModel->getFieldsCollection($currentFormId);
                $fieldsCollection->setOrder('field_id','asc');
                
                if (!$this->_values2)
                {
                    $values2 = array();
                    foreach ($fieldsCollection as $key => $field)
                    {
                        //if($field['forms_index']==$currentFormId && !$field['is_delete'])
                        if(!$field['is_delete'])
                        {
                            $value2 = array();
                            $value2['id'] = $field['field_id'];
                            $value2['item_count'] = $field['field_id'];
                            $value2['option_id'] = $field['field_id'];
                            $value2['title'] = $field['title'];
                            //$value2['previous_group'] = $field['previous_group'];
                            $value2['type'] = $field['type'];
                            $value2['is_require'] = $field['is_require'];
                            $value2['field_status'] = $field['status'];
                            $value2['sort_order'] = $field['sort_order'];
                            $value2['can_edit_price'] = 1;
                            if($field['previous_group']=='text')
                            {
                                $value2['price'] = 0.00;
                                $value2['price_type'] = 'fixed';
                                $value2['sku'] = '';
                                $value2['max_characters'] = $field['max_characters'];
                                $value2['image_size_x'] = $field['image_size_x'];
                                $value2['image_size_y'] = $field['image_size_y'];
                            }
                            elseif($field['previous_group']=='file')
                                $value2['file_extension'] = $field['file_extension'];
                            if($field['previous_group']=='select')
                            {
                                $optionsModel = Mage::helper('formbuilder')->getOptionsModel();
                                $optionsCollection  = $optionsModel->getOptionsCollection( $field['fields_index'] );
                                //$optionsCollection  = Mage::getModel('formbuilder/options')->getCollection();
                                $i=0;
                                foreach ($optionsCollection as $key => $option)
                                {
                                    //if($field['fields_index']==$option['fields_index'])
                                    //{
                                        $value2['optionValues'][$i]['item_count']       = $option['option_id'];
                                        $value2['optionValues'][$i]['option_id']        = $field['field_id'];
                                        $value2['optionValues'][$i]['option_type_id']   = $option['option_id'];
                                        $value2['optionValues'][$i]['title']            = $option['title'];
                                        $value2['optionValues'][$i]['price']            = 0.00;
                                        $value2['optionValues'][$i]['price_type']       = 'fixed';
                                        $value2['optionValues'][$i]['sku']              = '';
                                        $value2['optionValues'][$i]['sort_order']       = $option['sort_order'];
                                        $i++;
                                    //}
                                }
                            }
                            $values2[] = new Varien_Object($value2);
                        }
                    }                
                    return $this->_values2 = $values2;
                }
            }
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
    public function getCheckboxScopeHtml($id, $name, $checked=true, $select_id='-1')
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
            $this->getFieldName() . '['.$id.']' . $selectNameHtml . '[scope][' . $name . ']" value="1" ' .
            $checkedHtml . '/>';
        $checkbox .= '<label class="normal" for="' . $this->getFieldId() . '_' . $id . '_' .
            $selectIdHtml . $name . '_use_default">' . $this->__('Use Default Value') . '</label>';
        return $checkbox;
    }
    public function getPriceValue($value, $type)
    {
        if ($type == 'percent') {
            return number_format($value, 2, null, '');
        } elseif ($type == 'fixed') {
            return number_format($value, 2, null, '');
        }
    }
}