<?php
	include 'lib/common.php';
	
	$common = new Common();
	$multilanguage = new Multilanguage();
	
	$common->init();
	
	$smarty = new Smarty_Instance();
	
	# load language file
	$lang = $multilanguage->loadLanguage();
	
	$messenger = new Messenger();
	
    $m = $messenger->getMessage('step1');
	
	$smarty->assign('lang', $lang);
	$smarty->assign('m', $m);
	
	$smarty->display('install.tpl');
?>