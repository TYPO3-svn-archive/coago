<?php

/**
 * ************************************************************
 *  Copyright notice
 *
 *  (c) Krystian Szymukowicz (typo3@prolabium.com)
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 *
 */

 
class tx_coago_tcemainproc {

	
	/**
	 * TCEmain hook for cmds
	 *
	 * @param	string		Command from tcemain.
	 * @param	string		Table the comman process on.
	 * @param	integer		Id of the record.
	 * @param	string		Value for the command.
	 * @param	object		Parent object.
	 * @return	void
	 */
	function processCmdmap_preProcess ($command, $table, $id, $value, &$thisRef) {

		$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['newcachecobj']); 		

		if ( t3lib_div::inList($table, $confArr['clearOnTablesChange']) ) {
			$this->deleteAllFiles(PATH_site . $confArr['cacheDirectory']);
			$this->regenerateCachedCObj();
		}
		
	}

	/**
	 * TCEmain hook 
	 *
	 * @param	string		Operation status. 
	 * @param	string		Table the operation was processed on.
	 * @param	integer		Id of the record.
	 * @param	array       Fields that have been changed.
	 * @param	object		Parent object.
	 * @return	void
	 */
	function processDatamap_afterDatabaseOperations($status, $table, $id, &$fieldArray, &$thisRef) {

		die;
		
		$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['newcachecobj']); 		

		if ( t3lib_div::inList($table, $confArr['clearOnTablesChange']) ) {
			$this->deleteAllFiles(PATH_site . $confArr['cacheDirectory']);
			$this->regenerateCachedCObj();
		}
		
	}	

	
	function deleteAllFiles($cacheDirectory) {

		die;
		
	if( file_exists($cacheDirectory) ){
	
			$filesToDelete = t3lib_div::getFilesInDir($cacheDirectory);
			
			if(is_array($filesToDelete)) {
				foreach($filesToDelete as $fileToDelete) {
					@unlink($cacheDirectory . $fileToDelete);
				}
			}
		}
	}

	function regenerateCachedCObj() {
		
		die;
		
		//debug($this->loadTS(640));
	
		
		if (!defined('PATH_tslib')) {
		   if (@is_dir(PATH_site.TYPO3_mainDir.'sysext/cms/tslib/')) {
			 define('PATH_tslib', PATH_site.TYPO3_mainDir.'sysext/cms/tslib/');
		   } elseif (@is_dir(PATH_site.'tslib/')) {
			 define('PATH_tslib', PATH_site.'tslib/');
		   }
		}

		require_once(PATH_t3lib.'class.t3lib_timetrack.php');
							
		$GLOBALS['TT'] = new t3lib_timeTrack;
		$GLOBALS['TT']->start();
		$GLOBALS['TT']->push('','Script start');
							
		require_once (PATH_tslib."class.tslib_fe.php");
		require_once (PATH_tslib."class.tslib_content.php");
		require_once (PATH_t3lib."class.t3lib_page.php");
		require_once (PATH_t3lib."class.t3lib_userauth.php");
		require_once (PATH_tslib."class.tslib_feuserauth.php");
		require_once (PATH_t3lib."class.t3lib_tstemplate.php");	
		require_once (PATH_t3lib.'class.t3lib_cs.php');				
		$temp_TSFEclassName = t3lib_div::makeInstanceClassName('tslib_fe');	 
		$GLOBALS['TSFE'] = new $temp_TSFEclassName(
			$TYPO3_CONF_VARS,
			640,
			0,
			1,
			t3lib_div::_GP('cHash'),
			t3lib_div::_GP('jumpurl'),
			t3lib_div::_GP('MP'),
			t3lib_div::_GP('RDCT')
		   );
		$GLOBALS['TSFE']->connectToDB();
		$GLOBALS['TSFE']->initFEuser();

		$GLOBALS['TSFE']->determineId();					
		$GLOBALS['TSFE']->getCompressedTCarray();					
		$GLOBALS['TSFE']->initTemplate();
		$GLOBALS['TSFE']->getConfigArray();

		$cObj = t3lib_div::makeInstance('tslib_cObj');

		
		$GLOBALS['TT']->push('Page generation','');
		if ($GLOBALS['TSFE']->doXHTML_cleaning()) {
			require_once(PATH_t3lib.'class.t3lib_parsehtml.php');
		}
		if ($GLOBALS['TSFE']->isGeneratePage()) {
			$GLOBALS['TSFE']->generatePage_preProcessing();
			$temp_theScript=$GLOBALS['TSFE']->generatePage_whichScript();

			if ($temp_theScript) {
				include($temp_theScript);
			} else {
				require_once(PATH_tslib.'class.tslib_pagegen.php');
				//include(PATH_tslib.'pagegen.php');
			}
			$GLOBALS['TSFE']->generatePage_postProcessing();
		} elseif ($GLOBALS['TSFE']->isINTincScript()) {
			require_once(PATH_tslib.'class.tslib_pagegen.php');
			include(PATH_tslib.'pagegen.php');
		}
		$GLOBALS['TT']->pull();
	
	}


	function loadTS($pageUid) {
	   $sysPageObj = t3lib_div::makeInstance('t3lib_pageSelect');
	   $rootLine = $sysPageObj->getRootLine($pageUid);
	   $TSObj = t3lib_div::makeInstance('t3lib_tsparser_ext');
	   $TSObj->tt_track = 0;
	   $TSObj->init();
	   $TSObj->runThroughTemplates($rootLine);
	   $TSObj->generateConfig();
	   return $TSObj->setup;
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/coago/class.tx_coago_tcemainproc.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/coago/class.tx_coago_tcemainproc.php']);
}

?>