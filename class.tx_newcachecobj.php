<?php

class tx_newcachecobj {

   private $extKey = 'newcachecobj';
   public static $counter = 1;

   function cObjGetSingleExt($name, $conf, $TSkey, &$cObj) {

      $content = "";
      $this->cObj = $cObj;


      switch($name) {

         case "COA_GO":

            // Get variables into shorter names.
            $hash = $conf['cache.']['hash'];
            $cachePeriod = intval($conf['cache.']['period']);
            $cacheType = $conf['cache.']['type'];
            if( !$hash ) {
               $hash = md5( serialize($conf) );
            }

            // Set pathes.
            $confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['newcachecobj']);
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

                  // cObject not yet cached or cache or cachePeriod? So generate and store in files.
                  $ageInSeconds = time() - filemtime($absolutePathTempWithFilename);
                  if(! file_exists($absolutePathTempWithFilename)
                     ||  ( $cachePeriod && ($ageInSeconds > $cachePeriod)) ){

                     $contentToStore = $this->getCOA_GO($conf);

                     $cacheChecks = '<?php
                     $ageInSeconds = time() - filemtime(\''.$absolutePathTempWithFilename.'\');
                     if( ($ageInSeconds > '.$cachePeriod.') && '.$cachePeriod.' ){
                     t3lib_div::getURL(\''. t3lib_div::getIndpEnv('TYPO3_SITE_URL') . 'index.php?id='. $GLOBALS['TSFE']->id .'&no_cache=1\');
                     } ?>'. "\n\n";

                     $contentToStore = $cacheChecks . $contentToStore;
                     $fileStatus = t3lib_div::writeFileToTypo3tempDir($absolutePathTempWithFilename, $contentToStore);
                     if ($fileStatus)t3lib_div::devLog('Error in afterCache_file: '.$fileStatus, $this->extKey, 3);

                  }

                  break;



               case 'ajax_db':

                  // TODO
                  break;


               case 'ajax_file':

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

                  $ageInSeconds = time() - filemtime($absolutePathTempWithFilename);
                  if(! file_exists($absolutePathTempWithFilename)
                     ||  ( ($ageInSeconds > $cachePeriod) && $cachePeriod) ){

                     $contentToStore = $this->getCOA_GO($conf);

                     $fileStatus = t3lib_div::writeFileToTypo3tempDir($absolutePathTempWithFilename, $contentToStore);
                     if ($fileStatus)t3lib_div::devLog('Error in ajax_file: '.$fileStatus, $this->extKey, 3);

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

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/newcachecobj/class.tx_newcachecobj.php']) {
   include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/newcachecobj/class.tx_newcachecobj.php']);
}

?>