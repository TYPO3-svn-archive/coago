<?php

if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

if (TYPO3_MODE == 'FE') {

	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['cObjTypeAndClass'][] = 
	array('COA_GO', 'EXT:newcachecobj/class.tx_newcachecobj.php:&tx_newcachecobj');		
		
}

if (TYPO3_MODE == 'BE') {

// TO DO

	//$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:newcachecobj/class.tx_newcachecobj_tcemainproc.php:tx_newcachecobj_tcemainproc';
	
	//$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = 'EXT:newcachecobj/class.tx_newcachecobj_tcemainproc.php:tx_newcachecobj_tcemainproc';

}

?>