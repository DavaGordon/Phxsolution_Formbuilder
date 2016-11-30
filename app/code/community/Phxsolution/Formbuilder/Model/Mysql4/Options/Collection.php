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
* @category   model
* @package    Phxsolution_Formbuilder
* @author     Murad Ali
* @contact    contact@phxsolution.com
* @site       www.phxsolution.com
* @copyright  Copyright (c) 2014 Phxsolution Formbuilder
* @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/
?>
<?php
class Phxsolution_Formbuilder_Model_Mysql4_Options_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('formbuilder/options');
    }   
    /*public function addEnableFilter($status = 1) {
        $this->getSelect()->where('main_table.status = ?', $status);
        return $this;
    }
    
    public function addStoreFilter($store) {
        if (!Mage::app()->isSingleStoreMode()) {
            
            $this->getSelect()->where('(FIND_IN_SET('.$store.',main_table.stores) or main_table.stores in (0))');
            // $this->getSelect()->where('main_table.stores in (?)', array(0, $store));         
            return $this;
        }
        return $this;
    }
    
     public function addPageFilter($page) {
        //$this->getSelect()->where('main_table.page_id in (?)', $page);
         $this->getSelect()->where('FIND_IN_SET('.$page.',main_table.page_id)');
        return $this;
    }
    
    public function addPositionFilter($position) {
        $this->getSelect()->where('main_table.position = ?', $position);
        return $this;
    }
    
     public function addCategoryFilter($category) {
        $this->getSelect()->where('FIND_IN_SET('.$category.',main_table.category_id)');
        return $this;
    }*/
}