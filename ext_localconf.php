<?php
defined('TYPO3_MODE') || die();

/***************
 * Add default RTE configuration
 */
$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['gowest_config'] = 'EXT:gowest_config/Configuration/RTE/Default.yaml';
$GLOBALS['TYPO3_CONF_VARS']['FE']['hidePagesIfNotTranslatedByDefault'] = 1;
$GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'] = sprintf(
	'%s: %s (%s)',
	str_replace('.go-west.at', '', php_uname('n')),
	$_SERVER['HTTP_HOST'],
	(string)TYPO3\CMS\Core\Core\Environment::getContext()
);

/*** Enable changes via tsconfig for type and renderType (workaround: https://forge.typo3.org/issues/55976#note-12) ***/
$reflectionClassFormEngineUtility = new ReflectionClass(\TYPO3\CMS\Backend\Form\Utility\FormEngineUtility::class);
$reflectionPropertyAllowOverrideMatrix = $reflectionClassFormEngineUtility->getProperty('allowOverrideMatrix');
$reflectionPropertyAllowOverrideMatrix->setAccessible(true); // make protected property accessible
$allowOverrideMatrix = $reflectionPropertyAllowOverrideMatrix->getValue();
$allowOverrideMatrix['input'][] = 'type';
$allowOverrideMatrix['input'][] = 'renderType';
$reflectionPropertyAllowOverrideMatrix->setValue($allowOverrideMatrix);

// Cache clear hooks
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc'][] = \Gowest\GowestConfig\Hooks\CacheClearHook::class . '->clearCachePostProc';

/***************
 * PageTS
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:gowest_config/Configuration/TsConfig/Page/All.tsconfig">');




