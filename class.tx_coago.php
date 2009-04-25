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

class tx_coago {

	private $extKey = 'coago';
	private static $counter = 1;

	function cObjGetSingleExt($name, $conf, $TSkey, &$cObj) {


		$this->cObj = $cObj;

		$content = '';

		switch($name) {

			case "COA_GO":

				// Get variables into shorter names.
				$hash = $this->cObj->stdWrap($conf['cache.']['hash'], $conf['cache.']['hash.']);
				$cachePeriod = intval($this->cObj->stdWrap($conf['cache.']['period'], $conf['cache.']['period.']));
				$cacheType = $this->cObj->stdWrap($conf['cache.']['type'], $conf['cache.']['type.']);

				if( !$hash ) {
					$hash = md5( serialize($conf) );
				}

				// Set pathes.
				$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey]);
				$filename = $hash;
				$relativePathTemp =  $confArr['cacheDirectory'];
				$absolutePathTemp = PATH_site . $relativePathTemp;
				$absolutePathTempWithFilename = $absolutePathTemp . $filename;


				switch($cacheType) {

					case 'beforeCache_db':

						// Get cached COA_GO content.
						$content = $GLOBALS['TSFE']->sys_page->getHash($hash, $cachePeriod);

						// Not yet cached? So generate nad store in cache_hash.
						if ( !strlen($content) ) {

							$content = $this->getCOA_GO($conf);
							$GLOBALS['TSFE']->sys_page->storeHash($hash, $content, 'COA_GO');

						}

						break;



					case 'beforeCache_file':

						// TO DO

						break;




					case 'afterCache_db':

						// TODO

						break;



					case 'afterCache_file':

						// use EXT_SCRIPT to include cObject stored in files
						$substKey = "EXT_SCRIPT." . $GLOBALS['TSFE']->uniqueHash();
						$content .= "<!--" . $substKey . "-->";

						// there sould be way to integrate cachePeriod here
						$GLOBALS["TSFE"]->config["EXTincScript"][$substKey] = array(
                     		"file" => $absolutePathTempWithFilename,
						);

						// cObject not yet cached in file or cache period expired? So generate and store in files.					
						if( file_exists($absolutePathTempWithFilename) ) {
							$cachedFileExist = TRUE;
							$ageInSeconds = time() - filemtime($absolutePathTempWithFilename);
						}else {
							$cachedFileExist = FALSE;
						}
						
						if(! $cachedFileExist
						||  ( $cachePeriod && ($ageInSeconds > $cachePeriod)) ){

							$contentToStore = $this->getCOA_GO($conf);

							$cacheChecks = '<?php
                     			$ageInSeconds = time() - filemtime(\''.$absolutePathTempWithFilename.'\');
                     			if( ($ageInSeconds > '.$cachePeriod.') && '.$cachePeriod.' ){
                     			t3lib_div::getURL(\''. t3lib_div::getIndpEnv('TYPO3_SITE_URL') . 'index.php?id='. $GLOBALS['TSFE']->id .'&no_cache=1\');
                     			} ?>'. "\n\n";

							$contentToStore = $cacheChecks . $contentToStore;
							$fileStatus = t3lib_div::writeFileToTypo3tempDir($absolutePathTempWithFilename, $contentToStore);
							if ($fileStatus)t3lib_div::devLog('Error writing afterCache_file: '.$fileStatus, $this->extKey, 3);

						}

						break;



					case 'afterCache_db_ajax':

						// TODO
						break;


					case 'afterCache_file_ajax':

						//generate JavaScript that will load cached cObjects
						$counter = self::$counter;
						$cachePeriod = $cachePeriod?$cachePeriod : '0';
						$siteUrl = t3lib_div::getIndpEnv('TYPO3_SITE_URL');

						$content .= "
                  <div id='ncc-{$counter}'> </div>
                  <script type='text/javascript'>
                     var http_req_{$counter} = false;
                     if(navigator.appName == 'Microsoft Internet Explorer') {
                        http_req_{$counter} = new ActiveXObject('Microsoft.XMLHTTP');
                     } else {
                        http_req_{$counter} = new XMLHttpRequest();
                     }

                     http_req_{$counter}.open('GET', '{$siteUrl}{$relativePathTemp}{$hash}',true);
                     http_req_{$counter}.send(null);
                     http_req_{$counter}.onreadystatechange=function() {
                        if(http_req_{$counter}.readyState == 4) {

                           ageInSeconds = (new Date() - Date.parse(http_req_{$counter}.getResponseHeader('Last-Modified'))) / 1000;

                           //alert('main condition:' + (http_req_{$counter}.status == 200) && ( (ageInSeconds < {$cachePeriod}) || ('{$cachePeriod}' == '0') ) + '<br /> counter :' +{$counter} + 'age cond :' + (ageInSeconds < {$cachePeriod}) + 'cache period: ' + {$cachePeriod} + 'ageinSec: ' + ageInSeconds);

                           if( (http_req_{$counter}.status == 200) && ( (ageInSeconds < {$cachePeriod}) || ('{$cachePeriod}' == '0') ) ) {
                              document.getElementById('ncc-{$counter}').innerHTML = http_req_{$counter}.responseText;
                           } else {

                              var http_req_{$counter}_r1 = false;
                              if(navigator.appName == 'Microsoft Internet Explorer') {
                                 http_req_{$counter}_r1 = new ActiveXObject('Microsoft.XMLHTTP');
                              } else {
                                 http_req_{$counter}_r1 = new XMLHttpRequest();
                              }

                              http_req_{$counter}_r1.open('GET', '{$siteUrl}index.php?id={$GLOBALS['TSFE']->id}&no_cache=1');
                              http_req_{$counter}_r1.send(null);

                              http_req_{$counter}_r1.onreadystatechange=function() {
                                 if(http_req_{$counter}_r1.readyState == 4) {

                                    var http_req_{$counter}_r2 = false;
                                    if(navigator.appName == 'Microsoft Internet Explorer') {
                                       http_req_{$counter}_r2 = new ActiveXObject('Microsoft.XMLHTTP');
                                    } else {
                                       http_req_{$counter}_r2 = new XMLHttpRequest();
                                    }

                                    http_req_{$counter}_r2.open('GET', '{$siteUrl}{$relativePathTemp}{$hash}',true);
                                    http_req_{$counter}_r2.send(null);
                                    http_req_{$counter}_r2.onreadystatechange=function() {

                                    if(http_req_{$counter}_r2.readyState == 4) {
                                          if(http_req_{$counter}_r2.status == 200) {
                                             document.getElementById('ncc-{$counter}').innerHTML = http_req_{$counter}_r2.responseText;
                                          };
                                       }
                                    }
                                 };
                              };
                           };
                        };
                     };
                  </script>
                  ";

						self::$counter++;

						// cObject not yet cached in file or cache period expired? So generate and store in files.					
						if( file_exists($absolutePathTempWithFilename) ) {
							$cachedFileExist = TRUE;
							$ageInSeconds = time() - filemtime($absolutePathTempWithFilename);
						}else {
							$cachedFileExist = FALSE;
						}
						
						if(! $cachedFileExist
						||  ( $cachePeriod && ($ageInSeconds > $cachePeriod)) ){

							$contentToStore = $this->getCOA_GO($conf);

							$fileStatus = t3lib_div::writeFileToTypo3tempDir($absolutePathTempWithFilename, $contentToStore);
							if ($fileStatus)t3lib_div::devLog('Error writing afterCache_file_ajax: '.$fileStatus, $this->extKey, 3);

						}

						break;

				}

				break;

		}

		return $content;
	}




	function getCOA_GO($conf) {

		// standard COA
		$contentToStore = $this->cObj->cObjGet($conf);
		if ( $this->cObj->checkIf($conf['if.']) )	{
			$contentToStore = $this->cObj->cObjGet($conf);
			if ( $conf['wrap'] ) {
				//$contentToStore = $this->cObj->wrap($contentToStore, $conf['wrap']);
			}
			if ( $conf['stdWrap.'] ) {
				$contentToStore = $this->cObj->stdWrap($contentToStore, $conf['stdWrap.']);
			}
		}
		return $contentToStore;
	}


}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/coago/class.tx_coago.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/coago/class.tx_coago.php']);
}

?>