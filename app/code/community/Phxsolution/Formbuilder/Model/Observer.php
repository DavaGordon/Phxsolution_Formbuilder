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
class Phxsolution_Formbuilder_Model_Observer
{
    public function addToTopmenu(Varien_Event_Observer $observer)
    {
        if(Mage::helper('formbuilder')->showLinkinTopmenu())
        {
            $menu = $observer->getMenu();
            $tree = $menu->getTree();
         
            $node = new Varien_Data_Tree_Node(array(
                    'name'   => 'Formbuilder',
                    'id'     => 'formbuilder',
                    'url'    => Mage::getUrl('formbuilder'), // point somewhere
            ), 'id', $tree, $menu);
         
            $menu->addChild($node);
         
            // Children menu items
            $collection = Mage::getModel('formbuilder/forms')->getCollection();
            $collection->addFieldToFilter('status',array('eq'=>1));
            $collection->addFieldToFilter('in_menu',array('eq'=>1));
            foreach ($collection as $category)
            {
                $tree = $node->getTree();
                $data = array(
                    'name'   => $category->getTitle(),
                    'id'     => 'category-node-'.$category->getFormsIndex(),
                    'url'    => Mage::getUrl('formbuilder/index/view').'id/'.$category->getFormsIndex(),
                );     
                $subNode = new Varien_Data_Tree_Node($data, 'id', $tree, $node);
                $node->addChild($subNode);
            }
        }
    }
}