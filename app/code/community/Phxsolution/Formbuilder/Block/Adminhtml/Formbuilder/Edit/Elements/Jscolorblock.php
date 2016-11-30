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
class Phxsolution_Formbuilder_Block_Adminhtml_Edit_Elements_Jscolorblock extends Varien_Data_Form_Element_Text
{
	protected function _getElementHtml( Varien_Data_Form_Element_Abstract $element )
	{
		$color = new Varien_Data_Form_Element_Text();
		$data = array(
			'name'      => $element->getName(),
			'html_id'   => $element->getId(),
		);
		$color->setData( $data );
		$color->setValue( $element->getValue(), $format );
		$color->setForm( $element->getForm() );
		$color->addClass( 'color ' . $element->getClass() );

		return $color->getElementHtml();
	}
}
//Elements_Jscolorblock
//<input class="color {required:false, adjust:false, hash:true}">