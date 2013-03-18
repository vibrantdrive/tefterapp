<?php
$config['segment_addition'] = 0;
$config['password_salt'] = 'wkclgu...';
$config['session_password'] = 'mlxq64q36l7uy64xkxu6';
$config['title_fixed'] = 'Tefter';

if (getcwd())
{
	$config['home_path'] = getcwd() . '/';
}
else
{
	$config['home_path'] = 'C:\\wamp\\www\\tefter2\\';
}

$config['language_path'] = 'language';
$config['table_prefix'] = 'tef_';
$config['app_installed'] = true;

$db['default']['hostname'] = 'localhost';
$db['default']['username'] = 'root';
$db['default']['password'] = '';
$db['default']['database'] = 'tefter2';
$config['base_url'] = 'http://localhost/tefter2/';
?>