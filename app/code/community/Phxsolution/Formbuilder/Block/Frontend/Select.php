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
class Phxsolution_Formbuilder_Block_Frontend_Select extends Mage_Core_Block_Template
{	
	protected function _prepareLayout()
    {
        //echo "<h1>test</h1>";
    }
    public function getValuesByField($_optionId)
    {
    	$fieldValues = Mage::getModel('formbuilder/options')->getCollection();
    	$fieldValues->addFieldToFilter('fields_index',array('eq'=>$_optionId));
        $fieldValues->setOrder('sort_order','ASC');
    	return $fieldValues;
    }
    public function getSelectHtml($_option)
    {   
        $_optionId = $_option['fields_index'];
        if($_option['type']=='drop_down' || $_option['type']=='multiple')
        {
            $require = ($_option->getIsRequire()) ? 'required-entry validate-select' : 'not-required';
            $extraParams = '';
            $select = $this->getLayout()->createBlock('core/html_select')
                ->setData(array(
                    'id' => 'select_'.$_option->getFieldsIndex(),
                    'class' => $require.' product-custom-option'
                ));
            if ($_option['type']=='drop_down')
            {
                $select->setName('options['.$_option->getFieldsIndex().']')
                    ->addOption('', $this->__('-- Please Select --'));
            }
            else
            {
                $select->setName('options['.$_option->getFieldsIndex().'][]');
                $select->setClass('multiselect'.$require.' product-custom-option');
                $select->addOption( '' , 'HIDDEN', array('hidden'=>'hidden','selected'=>'selected') );
            }
            
            $fieldValues = $this->getValuesByField($_optionId);
            foreach ($fieldValues as $_value)
            {
                $select->addOption( $_value->getOptionsIndex() , $_value->getTitle() );
            }
            if ($_option['type']=='multiple')
            {
                $extraParams = ' multiple="multiple"';
            }
            $select->setExtraParams($extraParams);
            /*if ($configValue) {
                $select->setValue($configValue);
            }*/
            return $select->getHtml();
        }
        elseif($_option['type']=='radio' || $_option['type']=='checkbox')
        {
        	//$require = ($_option->getIsRequire()) ? ' required-entry' : 'not-required';
            //return $require;
            $selectHtml = '<ul id="options-'.$_option['fields_index'].'-list" class="options-list">';
            $require = ($_option->getIsRequire()) ? 'validate-one-required-by-name' : '';
            $arraySign = '';
            switch ($_option->getType()) {
                case 'radio':
                    $type = 'radio';
                    $class = 'radio';
                    if (!$_option->getIsRequire())
                    {
                        $selectHtml .= '<li><input type="radio" id="options_' . $_option['fields_index'] . '" class="'
                            . $class . ' product-custom-option" name="options[' . $_option['fields_index'] . ']"'
                            . ' value="" checked="checked" /><span class="label"><label for="options_'
                            . $_option['fields_index'] . '">' . $this->__('None') . '</label></span></li>';
                    }
                    break;
                case 'checkbox':
                    $type = 'checkbox';
                    $class = 'checkbox';
                    $arraySign = '[]';
                    /*$selectHtml .= '<input type="' . $type . '"';
                    $selectHtml .= 'class="checkbox product-custom-option"';
                    $selectHtml .= ' name="options[' . $_option['fields_index'] . ']' . $arraySign . '"';
                    $selectHtml .= ' value="hidden" hidden />';*/
                    break;
            }
            $count = 1;
            $classHtml = $class." product-custom-option";
            $fieldValues = $this->getValuesByField($_optionId);
            $lastItemId = $fieldValues->getLastItem()->getOptionsIndex();
            foreach ($fieldValues as $_value)
            {
                $count++;
                $configValue = "";
                $htmlValue = $_value['options_index'];
                if ($arraySign) {
                    $checked = (is_array($configValue) && in_array($htmlValue, $configValue)) ? 'checked' : '';
                } else {
                    $checked = $configValue == $htmlValue ? 'checked' : '';
                }

                if($lastItemId==$_value['options_index'])
                	$classHtml .= " ".$require;
                $selectHtml .= '<li>';
                $selectHtml .= '<input type="' . $type . '"';
                $selectHtml .= ' class="' . $classHtml . '"';
                $selectHtml .= ' name="options[' . $_option['fields_index'] . ']' . $arraySign . '"';
                $selectHtml .= ' id="options_' . $_option['fields_index'] . '_' . $count . '"';
                $selectHtml .= ' value="' . $htmlValue . '" ' . $checked . ' />';
                $selectHtml .= '<span class="label"><label for="options_' . $_option['fields_index'] . '_' . $count . '">'
                    . $_value->getTitle() . ' ' . '</label></span>';
                if ($_option->getIsRequire()) {
                    $selectHtml .= '<script type="text/javascript">' . '$(\'options_' . $_option['fields_index'] . '_'
                    . $count . '\').advaiceContainer = \'options-' . $_option['fields_index'] . '-container\';'
                    . '$(\'options_' . $_option['fields_index'] . '_' . $count
                    . '\').callbackFunction = \'validateOptionsCallback\';' . '</script>';
                }
                $selectHtml .= '</li>';
            }
            $selectHtml .= '</ul>';

            return $selectHtml;
        }        
    }
    public function getDefaultValue()
    {
        return $this->getProduct()->getPreconfiguredValues()->getData('options/' . $this->getOption()->getId());
    }
}