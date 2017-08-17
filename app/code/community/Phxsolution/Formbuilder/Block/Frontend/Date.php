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
class Phxsolution_Formbuilder_Block_Frontend_Date extends Mage_Core_Block_Template
{	
	protected $_option = array();
	protected function _prepareLayout()
    {

    }
    public function useCalendar()
    {
        return Mage::getStoreConfig('formbuilder_section/custom_options/use_calendar');
    }
    public function getCalendarDateHtml()
    {
        $option = $this->_option;

        $require = '';

        $year_range = Mage::getStoreConfig('formbuilder_section/custom_options/year_range');
        list($yearStart,$yearEnd) = explode(',', $year_range);

        $calendar = $this->getLayout()
            ->createBlock('core/html_date')
            ->setId('options_'.$this->_option->getFieldsIndex().'_date')
            ->setName('options['.$this->_option->getFieldsIndex().'][date]')
            ->setClass('product-custom-option datetime-picker input-text' . $require)
            ->setImage($this->getSkinUrl('images/calendar.gif'))
            ->setFormat(Mage::app()->getLocale()->getDateStrFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT))
            ->setValue($value)
            ->setYearsRange('[' . $yearStart . ', ' . $yearEnd . ']');

        return $calendar->getHtml();
    }
    protected function _getValueWithLeadingZeros($value)
    {
        if (!$this->_fillLeadingZeros) {
            return $value;
        }
        return $value < 10 ? '0'.$value : $value;
    }
	protected function _getHtmlSelect($name, $value = null)
    {
        $option = $this->_option;

        //$require = $this->_option->getIsRequire() ? ' required-entry' : '';
        $require = '';
        $select = $this->getLayout()->createBlock('core/html_select')
            ->setId('options_' . $option['fields_index'] . '_' . $name)
            ->setClass('product-custom-option datetime-picker' . $require)
            ->setExtraParams()
            ->setName('options[' . $option['fields_index'] . '][' . $name . ']');

        $extraParams = 'style="width:auto"';

        $select->setExtraParams($extraParams);

        return $select;
    }
    protected function _getSelectFromToHtml($name, $from, $to, $value = null)
    {
        $options = array(
            array('value' => '', 'label' => '-')
        );
        for ($i = $from; $i <= $to; $i++) {
            $options[] = array('value' => $i, 'label' => $this->_getValueWithLeadingZeros($i));
        }
        return $this->_getHtmlSelect($name, $value)
            ->setOptions($options)
            ->getHtml();
    }
    public function getDropDownsDateHtml()
    {
        $fieldsSeparator = '&nbsp;';

        $fieldsOrder = Mage::getStoreConfig('formbuilder_section/custom_options/date_fields_order');
        $fieldsOrder = str_replace(',', $fieldsSeparator, $fieldsOrder);

        $monthsHtml = $this->_getSelectFromToHtml('month', 1, 12);
        $daysHtml = $this->_getSelectFromToHtml('day', 1, 31);

        $year_range = Mage::getStoreConfig('formbuilder_section/custom_options/year_range');
        list($yearStart,$yearEnd) = explode(',', $year_range);

        $yearsHtml = $this->_getSelectFromToHtml('year', $yearStart, $yearEnd);

        $translations = array(
            'd' => $daysHtml,
            'm' => $monthsHtml,
            'y' => $yearsHtml
        );
        return strtr($fieldsOrder, $translations);
    }
    public function getDateHtml($currentField)
   	{
   		$this->_option = $currentField;   		
   		
   		if ($this->useCalendar()) {
            return $this->getCalendarDateHtml();
        } else {
            return $this->getDropDownsDateHtml();
        }
   	}
   	public function getTimeHtml($currentField)
    {
        $this->_option = $currentField;
        $timeFormat = Mage::getStoreConfig('formbuilder_section/custom_options/time_format');

        if ($timeFormat=='24h')
        {
            $hourStart = 0;
            $hourEnd = 23;
            $dayPartHtml = '';
        } else {
            $hourStart = 1;
            $hourEnd = 12;
            $dayPartHtml = $this->_getHtmlSelect('day_part')
                ->setOptions(array(
                    'am' => Mage::helper('catalog')->__('AM'),
                    'pm' => Mage::helper('catalog')->__('PM')
                ))
                ->getHtml();
        }
        $hoursHtml = $this->_getSelectFromToHtml('hour', $hourStart, $hourEnd);
        $minutesHtml = $this->_getSelectFromToHtml('minute', 0, 59);

        return $hoursHtml . '&nbsp;<b>:</b>&nbsp;' . $minutesHtml . '&nbsp;' . $dayPartHtml;
    }
    public function getDefaultValue()
    {
        return $this->getProduct()->getPreconfiguredValues()->getData('options/' . $this->_option->getId());
    }	
}