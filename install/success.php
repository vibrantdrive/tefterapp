<?php
	include 'lib/common.php';
	
	$common = new Common();
	$multilanguage = new Multilanguage();
	$messenger = new Messenger();
	
	$common->init();
	
	$smarty = new Smarty_Instance();
	
	# load language file
	$lang = $multilanguage->loadLanguage();
	
    $m = $messenger->getMessage('success');
    
	$smarty->assign('lang', $lang);
	$smarty->assign('m', $m);
	
	$smarty->display('success.tpl');
?>