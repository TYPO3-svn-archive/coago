<?php

########################################################################
# Extension Manager/Repository config file for ext: "coago"
#
# Auto generated 19-04-2009 23:22
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'COA_GO - per cObj caching with cache period',
	'description' => 'This extension improves caching of content objects in TYPO3 so it can increase time rendering of page and overall performance of your site. Tags: COA_CACHED, COA_INT_CACHED, COA_INT_CACHED_AJAX',
	'category' => 'fe',
	'shy' => 0,
	'version' => '0.1.2',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'beta',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Krystian Szymukowicz',
	'author_email' => 'http://typo3.prolabium.com',
	'author_company' => 'http://typo3.prolabium.com',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.1.0-4.2.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'suggests' => array(
	),
	'_md5_values_when_last_written' => 'a:7:{s:18:"class.tx_coago.php";s:4:"95f2";s:30:"class.tx_coago_tcemainproc.php";s:4:"257b";s:21:"ext_conf_template.txt";s:4:"b896";s:17:"ext_localconf.php";s:4:"a327";s:10:"readme.txt";s:4:"2f6f";s:17:"readme_coa_go.gif";s:4:"8e4c";s:14:"doc/manual.sxw";s:4:"cffc";}',
);

?>