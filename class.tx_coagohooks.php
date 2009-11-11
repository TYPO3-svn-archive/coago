<?php

class tx_coagohooks {

	/*
	 * Hooks into t3lib_tcemain clear_cache function
	 *
	 * @return	void
	 */
	function clearCachePostProc(&$params, &$pObj) {

	if( $params['cacheCmd'] == 'all' || $params['cacheCmd'] == 'pages' ) {

			//remove all files from cache directory
			$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['coago']);
			if( !$confArr['cacheDirectory'] ) {
				$confArr['cacheDirectory'] = 'typo3temp/cached_cobj/';
			}

			if( strlen($confArr['cacheDirectory']) ) {
				$absolutePath = PATH_site . $confArr['cacheDirectory'];

				if( file_exists($absolutePath) ){
					$filesToDelete = t3lib_div::getFilesInDir($absolutePath);
					if( is_array($filesToDelete) ) {
						foreach($filesToDelete as $fileToDelete) {
							@unlink($absolutePath . $fileToDelete);
						}
					}
				}
			}
		}
	}



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

		if($command == 'delete') {
			$this->processCacheRemove($command, $table, $id, $thisRef);
		}

	}

	/**
	 * TCEmain hook update/new
	 *
	 * @param	string		Operation status.
	 * @param	string		Table the operation was processed on.
	 * @param	integer		Id of the record.
	 * @param	array       Fields that have been changed.
	 * @param	object		Parent object.
	 * @return	void
	 */
	function processDatamap_afterDatabaseOperations($status, $table, $id, &$fieldArray, &$thisRef) {

		if($status == 'update' || $status == 'new') {
			$this->processCacheRemove($status, $table, $id, $thisRef);
		}

	}


	/*
	 * Remove cache from database and files. Care for "table" based cache.
	 *
	 * @param 	strinhr		$dir: The full path
	 * @return	void
	 */

	function processCacheRemove($what, $table, $id, &$thisRef) {

		$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['coago']);
		if($confArr['clearOnTablesOperation'] && $table) {

			// selective database cache delete
			$GLOBALS['TYPO3_DB']->exec_DELETEquery ('cache_hash', 'ident = \'COA_GO_' . $GLOBALS['TYPO3_DB']->quoteStr($table, $table) .'\'');

			// selective file cache delete
			$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['coago']);
			if( !$confArr['cacheDirectory'] ) {
				$confArr['cacheDirectory'] = 'typo3temp/cached_cobj/';
			}

			if( strlen($confArr['cacheDirectory']) ) {
				$absolutePath = PATH_site . $confArr['cacheDirectory'];
				if( file_exists($absolutePath) ){
					$filesToDelete = t3lib_div::getFilesInDir($absolutePath);
					if(is_array($filesToDelete)) {
						foreach($filesToDelete as $fileToDelete) {
							if( preg_match('/.*'.$table.'.*/', $fileToDelete) ) {
								@unlink($absolutePath . $fileToDelete);
							}
						}
					}
				}
			}
		}
	}
	
	
}

?>