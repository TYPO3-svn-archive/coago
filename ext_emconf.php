<?php

########################################################################
# Extension Manager/Repository config file for ext: "coago"
#
# Auto generated 14-09-2009 10:09
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'COA_GO - per content object caching with cache period supported',
	'description' => 'This extension improves caching of content objects in TYPO3 so it can decrease time rendering of page and improve overall performance of your site.',
	'category' => 'fe',
	'shy' => 0,
	'version' => '0.1.4',
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
			'typo3' => '4.1.0-4.3.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'suggests' => array(
	),
	'_md5_values_when_last_written' => 'a:13:{s:9:"ChangeLog";s:4:"b13b";s:18:"class.tx_coago.php";s:4:"0d0d";s:21:"ext_conf_template.txt";s:4:"a3cd";s:17:"ext_localconf.php";s:4:"7f84";s:14:"ext_tables.php";s:4:"04f4";s:10:"readme.txt";s:4:"1ca7";s:17:"readme_coa_go.gif";s:4:"d09c";s:14:"doc/manual.sxw";s:4:"6a33";s:27:"misc/class.tx_coago_mod.php";s:4:"8b68";s:14:"res/ajax/1.gif";s:4:"7b97";s:20:"static/constants.txt";s:4:"8638";s:16:"static/setup.txt";s:4:"4d39";s:37:"_later/class.tx_coago_tcemainproc.php";s:4:"e4a5";}',
);

?>