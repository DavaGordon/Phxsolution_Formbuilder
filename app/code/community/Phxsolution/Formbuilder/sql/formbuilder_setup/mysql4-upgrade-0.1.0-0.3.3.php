<?php
$installer = $this;
$installer->startSetup();
$installer->run("
ALTER TABLE {$this->getTable('formbuilder_forms')} CHANGE `title` `form_title` VARCHAR(255) NOT NULL; 
ALTER TABLE {$this->getTable('formbuilder_forms')} ADD `cssclass` VARCHAR(255) NOT NULL;");
$installer->endSetup();