<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

t3lib_extMgm::addPlugin(Array('LLL:EXT:recentcontent/locallang_db.xml:tt_content.menu_type_pi1', $_EXTKEY.'_pi1'),'menu_type');

t3lib_extMgm::addStaticFile($_EXTKEY,"static/","Recent Content Menu");
?>
