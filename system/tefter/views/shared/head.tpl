<!doctype html>
<html lang="en" id="{if $user.level eq 99}admin{else}member{/if}">

<head>
	<meta charset="utf-8"/>
	
	<title>{$title} - {$title_fixed}</title>
	
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="viewport" content="initial-scale=1.0, width=device-width, maximum-scale=1.0" />
	
	<link rel="stylesheet" media="all" href="{base_url}assets/css/screen.css" />
	
	<!--[if lt IE 9]>
		<link rel="stylesheet" media="all" href="{base_url}assets/css/ie.css" />
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
