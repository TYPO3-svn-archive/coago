<?php

if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

if (TYPO3_MODE == 'FE') {

	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['cObjTypeAndClass'][] = 
	array('COA_GO', 'EXT:coago/class.tx_coago.php:&tx_coago');		
		
}

if (TYPO3_MODE == 'BE') {

	// TODO
	//$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:coago/class.tx_coago_tcemainproc.php:tx_coago_tcemainproc';
	//$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = 'EXT:coago/class.tx_coago_tcemainproc.php:tx_coago_tcemainproc';

}

?>