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
* @category   database configuration
* @package    Phxsolution_Formbuilder
* @author     Murad Ali
* @contact    contact@phxsolution.com
* @site       www.phxsolution.com
* @copyright  Copyright (c) 2014 Phxsolution Formbuilder
* @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/
?>
<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('formbuilder_forms')};
CREATE TABLE {$this->getTable('formbuilder_forms')} (
 	`forms_index` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(255) NOT NULL,
	`no_of_fields` INT(50) NULL DEFAULT '0',
	`status` SMALLINT(6) NOT NULL DEFAULT '2',
	`stores` TEXT NULL,
	`header_content` text NOT NULL ,
	`footer_content` text NOT NULL ,
	`success_msg` VARCHAR(255) NULL,
	`failure_msg` VARCHAR(255) NULL,
	`submit_text` VARCHAR(255) NULL,
	`in_menu` SMALLINT(6) NULL DEFAULT '0',
	`in_toplinks` SMALLINT(6) NULL DEFAULT '0',
	`title_image` VARCHAR(255) NULL,
	`bgcolor` VARCHAR(25) NULL DEFAULT '#fbfaf6',
	`created_time` DATETIME NULL DEFAULT NULL,
	`update_time` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`forms_index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('formbuilder_fields')};
CREATE TABLE {$this->getTable('formbuilder_fields')} (
 	`fields_index` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
 	`forms_index` INT(11) NOT NULL, 	
	`status` SMALLINT(6) NOT NULL DEFAULT '0',
	`previous_group` VARCHAR(25) NOT NULL,
	`type` VARCHAR(25) NOT NULL,	
	`title` VARCHAR(255) NOT NULL,
	`field_id` INT(11) NOT NULL,
	`options` SMALLINT(6) NOT NULL DEFAULT '0',
	`max_characters` INT(11) NULL,
	`sort_order` INT(11) NULL,	
	`is_require` SMALLINT(6) NOT NULL DEFAULT '0',
	`is_delete` SMALLINT(6) NULL,	
	`file_extension` VARCHAR(255) NULL,
	`image_size_x` INT(11) NULL,
	`image_size_y` INT(11) NULL,	
	`previous_type` VARCHAR(25) NULL,
	`option_id` INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`fields_index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('formbuilder_fields_options')};
CREATE TABLE {$this->getTable('formbuilder_fields_options')} (
 	`options_index` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
 	`option_id` INT(11) UNSIGNED NOT NULL,
 	`fields_index` INT(11) NOT NULL,
 	`is_delete` SMALLINT(6) NULL,	
	`title` VARCHAR(255) NOT NULL,
	`sort_order` INT(11) NULL,	
	PRIMARY KEY (`options_index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('formbuilder_records')};
CREATE TABLE {$this->getTable('formbuilder_records')} (
	`records_index` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
 	`forms_index` INT(11) NOT NULL,
 	`customer` VARCHAR(255) NOT NULL,
 	`fields_index` INT(11) NOT NULL,
 	`options_index` VARCHAR(255) NULL,
 	`value` text NULL,
	PRIMARY KEY (`records_index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup();