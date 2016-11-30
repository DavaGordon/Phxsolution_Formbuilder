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
* @category   adminhtml block renderer
* @package    Phxsolution_Formbuilder
* @author     Murad Ali
* @contact    contact@phxsolution.com
* @site       www.phxsolution.com
* @copyright  Copyright (c) 2014 Phxsolution Formbuilder
* @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/
?>
<?php
class Phxsolution_Formbuilder_Block_Adminhtml_Formbuilder_Renderer_Recordvalue extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{     
    public function render(Varien_Object $row, $recordId=0, $fieldId=0, $serializedValue=0)
    {
        /*$recordId = intval($row->getData('records_index'));
        $fieldId = intval($this->getColumn()->getIndex());
        $i = intval($this->getColumn()->getName());*/
        $recordId = ($recordId) ? $recordId : intval($row->getData('records_index'));
        $fieldId = ($fieldId) ? $fieldId : intval($this->getColumn()->getIndex());
        $found = false;
        
		//$serialized = 'a:3:{i:8;s:16:"test first name3";i:9;s:15:"test last name3";i:10;s:1:"7";}';
		$serializedValue = ($serializedValue) ? $serializedValue : $row->getData('value');
		$unserialized = unserialize($serializedValue);
        $recordsModel = Mage::helper('formbuilder')->getRecordsModel();
        foreach ($unserialized as $key => $value)
        {        	
	        $recordsModel->load($recordId);
	        if($key==$fieldId)
	        {	
	        	$found = true;
	        	$returnValue = $value;
	        	break;
	        }
        }
        if($found)
        {
        	$optionsModel = Mage::helper('formbuilder')->getOptionsModel();
			if(is_array($returnValue))
			{
				foreach ($returnValue as $key)
				{
					$optionsModel->load($key);
					if($title = $optionsModel['title'])
		        		$temp[] = $title;
		        	else
		        		$temp[] = $returnValue;
				}
				return implode(',',$temp);
			}
			else
			{
				if(is_numeric($returnValue))
				{
					$optionsModel->load($returnValue);				
		        	if($title = $optionsModel['title'])
		        		return $title;		        	
		        }
		        else
	        	{
	        		$supported_image = array('gif','jpg','jpeg','png');
					$src_file_name = $returnValue;
					$ext = strtolower(pathinfo($src_file_name, PATHINFO_EXTENSION)); // Using strtolower to overcome case sensitive
					if (in_array($ext, $supported_image))
					{
					    $file_path = Mage::getBaseUrl('media') . $returnValue;
					    $gd = @imagecreatefromstring(file_get_contents($file_path));
					    if ($gd === false)
					    	return "Image seems corrupted or not found";
					    //else					    
					    $html = '<img height=75 width=150 ';
				        $html .= 'src="'. Mage::getBaseUrl('media') . $returnValue . '" ';
				        $html .= 'class="grid-image" ';
						$html .= 'style="max-height:75px;max-width:150px" />';
				        return $html;
					}
					else
					    return $returnValue;
	        	}
	        }
        }
        return "";
    }
}