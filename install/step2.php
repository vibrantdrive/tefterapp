<?php
	include 'lib/common.php';

	$common = new Common();
	$multilanguage = new Multilanguage();
	$messenger = new Messenger();
	
	$common->init();
	
	$smarty = new Smarty_Instance();
	
	# load language file
	$lang = $multilanguage->loadLanguage();
	
	$agree = $common->getParameter('license', 'text');
    $posted = $common->getPostedFromSession('step2');
    
    if ($posted != null)
    {
    	$agree = $posted['license'];
	}

	if ($agree != null)
	{
		if ($agree != 'yes')
		{
			$message = new Message();
			
			$message->page = 'step1';
			$message->type = 'PROBLEM';
			$message->content = $lang['message_not_agreed'];
			
			$messenger->setMessage($message);
			
			header("Location: " . 'index.php');
			die;
		}
	}
	else
	{
		$message = new Message();
		
		$message->page = 'step1';
		$message->type = 'PROBLEM';
		$message->content = $lang['message_not_agreed'];
		
		$messenger->setMessage($message);
		
		header("Location: " . 'index.php');
		die;
	}
	
    $m = $messenger->getMessage('step2');
    
	if (getcwd())
	{
		$smarty->assign('cwd', str_replace('install', '', getcwd()));
	}
	else
	{
		$smarty->assign('cwd', '');
	}
	
	$smarty->assign('lang', $lang);
	$smarty->assign('m', $m);
	$smarty->assign('posted', $posted);
	$smarty->assign('license', $agree);
	
	$common->removePostedFromSession('step2');

	$smarty->display('install-step2.tpl');
?>