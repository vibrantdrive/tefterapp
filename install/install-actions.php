<?php
	include 'lib/common.php';
	
	function check_permissions($path, $permission)
	{
		clearstatcache();
		
		$configmod = substr(sprintf('%o', fileperms($path)), -4);
		
		if ($configmod == $permission)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function validate_alphanumeric($str)
	{
		return preg_match('/^[A-Za-z0-9_-]+$/', $str);
	}
	
	function validate_license_format($license)
	{
		return preg_match('#([a-zA-Z0-9]+){6}\-([a-zA-Z0-9]+){6}\-([a-zA-Z0-9]+){6}\-([a-zA-Z0-9]+){6}#i', $license);
	}
	
	$common = new Common();
	$multilanguage = new Multilanguage();
	$messenger = new Messenger();
	
	$posted = $_POST;
	
	$common->init();
    
	# load language file
	$lang = $multilanguage->loadLanguage();
    
    # get parameters
    $license = $common->getParameter('license', 'text');
    $license_number = $common->getParameter('license_number', 'text');
    $company = $common->getParameter('company', 'text');
    $base_url = $common->getParameter('base_url', 'text');
    $directory_path = $common->getParameter('directory_path', 'text');
    $application_path = $common->getParameter('application_path', 'text');
    $mysql_host = $common->getParameter('mysql_host', 'text');
    $mysql_username = $common->getParameter('mysql_username', 'text');
    $mysql_password = $common->getParameter('mysql_password', 'text');
    $mysql_name = $common->getParameter('mysql_name', 'text');
    $mysql_prefix = $common->getParameter('mysql_prefix', 'text');
    $admin_username = $common->getParameter('admin_username', 'text');
    $admin_password = $common->getParameter('admin_password', 'text');
    $admin_password_again = $common->getParameter('admin_password_again', 'text');
    $server_timezone = $common->getParameter('server_timezone', 'text');
    $formatting = $common->getParameter('formatting', 'text');
    
    # check required fields
	if ($license != null)
	{
		if ($license != 'yes')
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

    # check if mcrypt is installed
    if (!function_exists('mcrypt_encrypt'))
    {
        $message = new Message();
        
        $message->page = 'step2';
        $message->type = 'PROBLEM';
        $message->content = $lang['message_mcrypt_required'];
        
        $messenger->setMessage($message);
        
        $common->storePostedIntoSession('step2', $posted, 'license_number');
        
        header("Location: " . 'step2.php');
        die;
    }

    if (empty($license_number))
    {
		$message = new Message();
		
		$message->page = 'step2';
		$message->type = 'PROBLEM';
		$message->content = $lang['message_license_number_required'];
		
		$messenger->setMessage($message);
		
		$common->storePostedIntoSession('step2', $posted, 'license_number');
		
		header("Location: " . 'step2.php');
		die;
	}

    if (!validate_alphanumeric($license_number))
    {
		$message = new Message();
		
		$message->page = 'step2';
		$message->type = 'PROBLEM';
		$message->content = $lang['message_license_number_allowed_characters'];
		
		$messenger->setMessage($message);
		
		$common->storePostedIntoSession('step2', $posted, 'license_number');
		
		header("Location: " . 'step2.php');
		die;
	}

    if (!validate_license_format($license_number))
    {
		$message = new Message();
		
		$message->page = 'step2';
		$message->type = 'PROBLEM';
		$message->content = $lang['message_license_format_incorrect'];
		
		$messenger->setMessage($message);
		
		$common->storePostedIntoSession('step2', $posted, 'license_number');
		
		header("Location: " . 'step2.php');
		die;
	}

    if (empty($base_url))
    {
		$message = new Message();
		
		$message->page = 'step2';
		$message->type = 'PROBLEM';
		$message->content = $lang['message_base_url_required'];
		
		$messenger->setMessage($message);
		
		$common->storePostedIntoSession('step2', $posted, 'base_url');

		header("Location: " . 'step2.php');
		die;
	}
	
	if (strpos($base_url, 'http://') === false)
	{
		if (strpos($base_url, 'https://') === false)
        {
            $base_url = 'http://' . $base_url;
        }
	}
	
	if (substr($base_url, -1) != '/')
	{
		$base_url = $base_url . '/';
	}

    if (empty($directory_path))
    {
		$message = new Message();
		
		$message->page = 'step2';
		$message->type = 'PROBLEM';
		$message->content = $lang['message_directory_path_required'];
		
		$messenger->setMessage($message);
		
		$common->storePostedIntoSession('step2', $posted, 'directory_path');

		header("Location: " . 'step2.php');
		die;
	}
	
    if (empty($application_path))
    {
		$message = new Message();
		
		$message->page = 'step2';
		$message->type = 'PROBLEM';
		$message->content = $lang['message_application_path_required'];
		
		$messenger->setMessage($message);
		
		$common->storePostedIntoSession('step2', $posted, 'application_path');

		header("Location: " . 'step2.php');
		die;
	}

    if (empty($mysql_host))
    {
		$message = new Message();
		
		$message->page = 'step2';
		$message->type = 'PROBLEM';
		$message->content = $lang['message_mysql_host_required'];
		
		$messenger->setMessage($message);
		
		$common->storePostedIntoSession('step2', $posted, 'mysql_host');

		header("Location: " . 'step2.php');
		die;
	}
	
    if (empty($mysql_username))
    {
		$message = new Message();
		
		$message->page = 'step2';
		$message->type = 'PROBLEM';
		$message->content = $lang['message_mysql_username_required'];
		
		$messenger->setMessage($message);
		
		$common->storePostedIntoSession('step2', $posted, 'mysql_username');

		header("Location: " . 'step2.php');
		die;
	}
    
    if (empty($mysql_name))
    {
		$message = new Message();
		
		$message->page = 'step2';
		$message->type = 'PROBLEM';
		$message->content = $lang['message_mysql_name_required'];
		
		$messenger->setMessage($message);
		
		$common->storePostedIntoSession('step2', $posted, 'mysql_name');

		header("Location: " . 'step2.php');
		die;
	}
    
    if (!empty($mysql_prefix))
    {
	    if (!validate_alphanumeric($mysql_prefix))
	    {
			$message = new Message();
			
			$message->page = 'step2';
			$message->type = 'PROBLEM';
			$message->content = $lang['message_mysql_prefix_allowed_characters'];
			
			$messenger->setMessage($message);
			
			$common->storePostedIntoSession('step2', $posted, 'mysql_prefix');
			
			header("Location: " . 'step2.php');
			die;
		}
	}
    
    if (empty($admin_username))
    {
		$message = new Message();
		
		$message->page = 'step2';
		$message->type = 'PROBLEM';
		$message->content = $lang['message_admin_username_required'];
		
		$messenger->setMessage($message);
		
		$common->storePostedIntoSession('step2', $posted, 'admin_username');

		header("Location: " . 'step2.php');
		die;
	}
	
    if (!$common->check_email_address($admin_username))
    {
		$message = new Message();
		
		$message->page = 'step2';
		$message->type = 'PROBLEM';
		$message->content = $lang['message_email_format_error'];
		
		$messenger->setMessage($message);
		
		$common->storePostedIntoSession('step2', $posted, 'admin_username');

		header("Location: " . 'step2.php');
		die;
	}
	
    if (empty($admin_password))
    {
		$message = new Message();
		
		$message->page = 'step2';
		$message->type = 'PROBLEM';
		$message->content = $lang['message_admin_password_required'];
		
		$messenger->setMessage($message);
		
		$common->storePostedIntoSession('step2', $posted, 'admin_password');

		header("Location: " . 'step2.php');
		die;
	}
	
    if (empty($admin_password_again))
    {
		$message = new Message();
		
		$message->page = 'step2';
		$message->type = 'PROBLEM';
		$message->content = $lang['message_admin_password_again_required'];
		
		$messenger->setMessage($message);
		
		$common->storePostedIntoSession('step2', $posted, 'admin_password');

		header("Location: " . 'step2.php');
		die;
	}

    if ($admin_password != $admin_password_again)
    {
		$message = new Message();
		
		$message->page = 'step2';
		$message->type = 'PROBLEM';
		$message->content = $lang['message_passwords_do_not_match'];
		
		$messenger->setMessage($message);
		
		$common->storePostedIntoSession('step2', $posted, 'admin_password');

		header("Location: " . 'step2.php');
		die;
	}

    if (empty($server_timezone))
    {
		$message = new Message();
		
		$message->page = 'step2';
		$message->type = 'PROBLEM';
		$message->content = $lang['message_server_timezone_required'];
		
		$messenger->setMessage($message);
		
		$common->storePostedIntoSession('step2', $posted, 'server_timezone');

		header("Location: " . 'step2.php');
		die;
	}

    if (empty($formatting))
    {
		$message = new Message();
		
		$message->page = 'step2';
		$message->type = 'PROBLEM';
		$message->content = $lang['message_formatting_required'];
		
		$messenger->setMessage($message);
		
		$common->storePostedIntoSession('step2', $posted, 'formatting');

		header("Location: " . 'step2.php');
		die;
	}
	
	# check install file
	if (!is_writable($directory_path . $application_path . '/tefter/config/settings.php'))
	{
		$message = new Message();
		
		$message->page = 'step2';
		$message->type = 'PROBLEM';
		$message->content = $lang['message_file_is_not_writable'] . ': ' . $directory_path . $application_path . '/tefter/config/settings.php';
		
		$messenger->setMessage($message);
		
		$common->storePostedIntoSession('step2', $posted);

		header("Location: " . 'step2.php');
		die;
	}

	// check permissions
	// settings.php 644 or 777
	// cache folder 755 or 777
	$sett = check_permissions($directory_path . $application_path . '/tefter/config/settings.php', "0644");
    $sett1 = check_permissions($directory_path . $application_path . '/tefter/config/settings.php', "0777");
	
	if (!$sett && !$sett1)
	{
		$message = new Message();
		
		$message->page = 'step2';
		$message->type = 'PROBLEM';
		$message->content = $lang['message_settings_permissions'] . ': ' . $directory_path . $application_path . '/tefter/config/settings.php';
		
		$messenger->setMessage($message);
		
		$common->storePostedIntoSession('step2', $posted);

		header("Location: " . 'step2.php');
		die;
	}

	$sett = check_permissions($directory_path . $application_path . '/tefter/views/cache', "0755");
    $sett1 = check_permissions($directory_path . $application_path . '/tefter/views/cache', "0777");
	
	if (!$sett && !$sett1)
	{
		$message = new Message();
		
		$message->page = 'step2';
		$message->type = 'PROBLEM';
		$message->content = $lang['message_cache_permissions'] . ': ' . $directory_path . $application_path . '/tefter/views/cache';
		
		$messenger->setMessage($message);
		
		$common->storePostedIntoSession('step2', $posted);

		header("Location: " . 'step2.php');
		die;
	}

	$filepath = $directory_path . $application_path . '/tefter/config/settings.php';
	
	# check db connection
	error_reporting(0);

	$link = mysql_connect($mysql_host, $mysql_username, $mysql_password);
	
	error_reporting(E_ALL);
	
	if (!$link)
	{
		$message = new Message();
		
		$message->page = 'step2';
		$message->type = 'PROBLEM';
		$message->content = $lang['message_could_not_connect'] . ': ' . mysql_error();
		
		$messenger->setMessage($message);
		
		$common->storePostedIntoSession('step2', $posted);

		header("Location: " . 'step2.php');
		die;
	}
	
	if (mysql_select_db($mysql_name, $link) === false)
	{
		$message = new Message();
		
		$message->page = 'step2';
		$message->type = 'PROBLEM';
		$message->content = $lang['message_could_not_select_database'] . ': ' . mysql_error();
		
		$messenger->setMessage($message);
		
		$common->storePostedIntoSession('step2', $posted);

		header("Location: " . 'step2.php');
		die;
	}
	
	# can write file
	$fileHandle = @fopen($filepath, 'w');
	
	if (!$fileHandle)
	{
		$message = new Message();
		
		$message->page = 'step2';
		$message->type = 'PROBLEM';
		$message->content = $lang['message_could_not_open_file'] . ': ' . $filepath;
		
		$messenger->setMessage($message);
		
		$common->storePostedIntoSession('step2', $posted);

		header("Location: " . 'step2.php');
		die;
	}
	
	# get random pass
	$sessionPass = $common->getRandomPassword(20);
	$bk = $common->getRandomPassword(20);
	$passwordSalt = $common->getRandomPassword(6) . '...';
	
	/*  write to file  */
	
	// reset file
	$writen = fwrite($fileHandle, null);
	
	if ($writen === false)
	{
		$message = new Message();
		
		$message->page = 'step2';
		$message->type = 'PROBLEM';
		$message->content = $lang['message_could_not_write_file'] . ': ' . $filepath;
		
		$messenger->setMessage($message);
		
		$common->storePostedIntoSession('step2', $posted);

		header("Location: " . 'step2.php');
		die;
	}
	
	// add lines
	$content = array();
	
	$content[0] = "<?php" . "\n";
	$content[1] = "\$config['segment_addition'] = 0;" . "\n";
	$content[2] = "\$config['password_salt'] = '" . mysql_real_escape_string($passwordSalt) . "';" . "\n";
	$content[3] = "\$config['session_password'] = '" . mysql_real_escape_string($sessionPass) . "';" . "\n";
	$content[4] = "\$config['title_fixed'] = 'Tefter';" . "\n";
	$content[5] = "" . "\n";
	$content[6] = "if (getcwd())" . "\n";
	$content[7] = "{" . "\n";
	$content[8] = "\t\$config['home_path'] = getcwd() . '/';" . "\n";
	$content[9] = "}" . "\n";
	$content[10] = "else" . "\n";
	$content[11] = "{" . "\n";
	$content[12] = "\t\$config['home_path'] = '" . mysql_real_escape_string($directory_path) . "';" . "\n";
	$content[13] = "}" . "\n";
	$content[14] = "" . "\n";
	$content[15] = "\$config['language_path'] = 'language';" . "\n";
//	$content[16] = "\$config['default_timezone'] = '" . mysql_real_escape_string($server_timezone) . "';" . "\n";
	$content[16] = "\$config['table_prefix'] = '" . mysql_real_escape_string($mysql_prefix) . "';" . "\n";
	$content[17] = "\$config['app_installed'] = true;" . "\n";
	$content[18] = "" . "\n";
	$content[19] = "\$db['default']['hostname'] = '" . mysql_real_escape_string($mysql_host) . "';" . "\n";
	$content[20] = "\$db['default']['username'] = '" . mysql_real_escape_string($mysql_username) . "';" . "\n";
	$content[21] = "\$db['default']['password'] = '" . mysql_real_escape_string($mysql_password) . "';" . "\n";
	$content[22] = "\$db['default']['database'] = '" . mysql_real_escape_string($mysql_name) . "';" . "\n";
	$content[23] = "\$config['base_url'] = '" . mysql_real_escape_string($base_url) . "';" . "\n";
	$content[24] = "?>";
	
	$insert = "";
	foreach ($content as $line)
	{
		$insert = $insert . $line;
	}
	
	try
	{
		fwrite($fileHandle, utf8_encode($insert));

		// close file
		fclose($fileHandle);
	}
	catch (Exception $e)
	{
		fclose($fileHandle);

		$message = new Message();
		
		$message->page = 'step2';
		$message->type = 'PROBLEM';
		$message->content = $lang['message_could_not_write_file'] . ': ' . $filepath;
		
		$messenger->setMessage($message);
		
		$common->storePostedIntoSession('step2', $posted);

		header("Location: " . 'step2.php');
		die;
	}
	


/* SQL SCRIPTS */
	$sqls = array();
	
	$sqls[0] = str_replace("accounts", $mysql_prefix . "accounts", 
			"DROP TABLE IF EXISTS accounts;"); 

	$sqls[1] = str_replace("account_types", $mysql_prefix . "account_types", 
			"DROP TABLE IF EXISTS account_types;"); 

	$sqls[2] = str_replace("account_types_groups", $mysql_prefix . "account_types_groups", 
			"DROP TABLE IF EXISTS account_types_groups;"); 

	$sqls[3] = str_replace("clients", $mysql_prefix . "clients", 
			"DROP TABLE IF EXISTS clients;"); 

	$sqls[4] = str_replace("countries", $mysql_prefix . "countries", 
			"DROP TABLE IF EXISTS countries;"); 

	$sqls[5] = str_replace("groups", $mysql_prefix . "groups", 
			"DROP TABLE IF EXISTS groups;"); 

	$sqls[6] = str_replace("group_accounts", $mysql_prefix . "group_accounts", 
			"DROP TABLE IF EXISTS group_accounts;"); 

	$sqls[7] = str_replace("ims", $mysql_prefix . "ims", 
			"DROP TABLE IF EXISTS ims;"); 

	$sqls[8] = str_replace("settings", $mysql_prefix . "settings", 
			"DROP TABLE IF EXISTS settings;"); 

	$sqls[9] = str_replace("uploaded_images", $mysql_prefix . "uploaded_images", 
			"DROP TABLE IF EXISTS uploaded_images;"); 

	$sqls[10] = str_replace("users", $mysql_prefix . "users", 
			"DROP TABLE IF EXISTS users;"); 

	$sqls[11] = str_replace("user_clients", $mysql_prefix . "user_clients", 
			"DROP TABLE IF EXISTS user_clients;"); 

	$sqls[12] = str_replace("user_groups", $mysql_prefix . "user_groups", 
			"DROP TABLE IF EXISTS user_groups;"); 

	$sqls[13] = str_replace("user_ims", $mysql_prefix . "user_ims", 
			"DROP TABLE IF EXISTS user_ims;"); 

	$sqls[14] = str_replace("user_roles", $mysql_prefix . "user_roles", 
			"DROP TABLE IF EXISTS user_roles;"); 

	$sqls[15] = str_replace("accounts", $mysql_prefix . "accounts", 
			"CREATE TABLE IF NOT EXISTS accounts (
				  account_id bigint(20) NOT NULL AUTO_INCREMENT,
				  account_name varchar(200) COLLATE utf8_unicode_ci NOT NULL,
				  client_id bigint(20) DEFAULT NULL,
				  type_id int(11) NOT NULL,
				  server varbinary(255) DEFAULT NULL,
				  username varbinary(255) DEFAULT NULL,
				  password varbinary(255) DEFAULT NULL,
				  port varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
				  passive_mode tinyint(4) DEFAULT NULL,
				  root_url varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
				  remote_path varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
				  login_url varbinary(500) DEFAULT NULL,
				  note varbinary(1000) DEFAULT NULL,
				  name varbinary(255) DEFAULT NULL,
				  email varbinary(255) DEFAULT NULL,
				  email_type varbinary(255) DEFAULT NULL,
				  incoming_mail_server varbinary(255) DEFAULT NULL,
				  incoming_username varbinary(255) DEFAULT NULL,
				  incoming_password varbinary(255) DEFAULT NULL,
				  outgoing_mail_server varbinary(255) DEFAULT NULL,
				  outgoing_username varbinary(255) DEFAULT NULL,
				  outgoing_password varbinary(255) DEFAULT NULL,
				  dateEntered timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  PRIMARY KEY (account_id)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");

	$sqls[16] = str_replace("account_types", $mysql_prefix . "account_types", 
				"CREATE TABLE IF NOT EXISTS account_types (
				  account_type_id int(11) NOT NULL AUTO_INCREMENT,
				  account_type_name varchar(100) COLLATE utf8_unicode_ci NOT NULL,
				  account_type_group_id int(11) NOT NULL,
				  account_type_template_name varchar(255) COLLATE utf8_unicode_ci NOT NULL,
				  account_type_login_url varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
				  PRIMARY KEY (account_type_id)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=50 ;");

	$sqls[17] = str_replace("account_types", $mysql_prefix . "account_types", 
				"INSERT INTO account_types (account_type_id, account_type_name, account_type_group_id, account_type_template_name, account_type_login_url) VALUES
					(2, 'LiveJournal', 1, 'livejournal.tpl', 'www.livejournal.com/login.bml'),
					(3, 'TypePad', 1, 'typepad.tpl', 'http://www.typepad.com/services/signin'),
					(4, 'Tumblr', 1, 'tumblr.tpl', 'http://www.tumblr.com/login'),
					(5, 'WordPress', 2, 'wordpress.tpl', NULL),
					(6, 'Drupal', 2, 'drupal.tpl', NULL),
					(7, 'ExpressionEngine', 2, 'expression-engine.tpl', NULL),
					(8, 'Joomla', 2, 'joomla.tpl', NULL),
					(9, 'Perch', 2, 'perch.tpl', NULL),
					(10, 'Textpattern', 2, 'textpattern.tpl', NULL),
					(11, 'Custom CMS', 2, 'custom-cms.tpl', NULL),
					(12, 'MySQL', 3, 'mysql.tpl', NULL),
					(13, 'MS SQL', 3, 'mssql.tpl', NULL),
					(14, 'Gmail', 4, 'gmail.tpl', 'http://mail.google.com/mail/'),
					(15, 'Hotmail', 4, 'hotmail.tpl', 'http://www.hotmail.com/'),
					(16, 'POP3 / IMAP', 4, 'pop3-imap.tpl', NULL),
					(17, 'Yahoo', 4, 'yahoo.tpl', 'http://mail.yahoo.com/'),
					(18, 'FTP', 5, 'ftp.tpl', NULL),
					(19, 'FTP with Implicit SSL', 5, 'ftp-implicit-ssl.tpl', NULL),
					(20, 'FTP with TLS/SSL', 5, 'ftp-tls-ssl.tpl', NULL),
					(21, 'SFTP', 5, 'sftp.tpl', NULL),
					(22, 'WebDav', 5, 'webdav.tpl', NULL),
					(23, 'WebDav HTTPS', 5, 'webdav-https.tpl', NULL),
					(24, 'Amazon S3', 5, 'amazon-s3.tpl', NULL),
					(25, 'ICQ', 6, 'icq.tpl', NULL),
					(26, 'Jabber', 6, 'jabber.tpl', NULL),
					(27, 'MSN Messenger', 6, 'msn-messenger.tpl', NULL),
					(28, 'Skype', 6, 'skype.tpl', NULL),
					(29, 'Bebo', 7, 'bebo.tpl', 'http://www.bebo.com/SignIn.jsp'),
					(30, 'Blinklist', 7, 'blinklist.tpl', 'http://blinklist.com/user/login'),
					(31, 'Delicious', 7, 'delicious.tpl', 'https://secure.delicious.com/login?jump=ub'),
					(32, 'Digg', 7, 'digg.tpl', 'https://digg.com/login'),
					(33, 'Facebook', 7, 'facebook.tpl', 'https://www.facebook.com/login.php'),
					(34, 'LinkedIn', 7, 'linkedin.tpl', 'https://www.linkedin.com/secure/login'),
					(35, 'MySpace', 7, 'myspace.tpl', 'http://www.myspace.com'),
					(36, 'Newswine', 7, 'newsvine.tpl', 'https://www.newsvine.com/_tools/user/login'),
					(37, 'Reddit', 7, 'reddit.tpl', 'http://www.reddit.com/login'),
					(38, 'StumbleUpon', 7, 'stumbleupon.tpl', 'http://www.stumbleupon.com/login.php'),
					(39, 'Technorati', 7, 'technorati.tpl', 'http://technorati.com/account/login/'),
					(40, 'Twitter', 7, 'twitter.tpl', 'https://twitter.com/login'),
					(41, 'MobileMe', 8, 'mobile-me.tpl', 'http://www.me.com/'),
					(42, 'OpenID', 8, 'open-id.tpl', NULL),
					(43, 'SSH', 8, 'ssh.tpl', NULL),
					(44, 'iChat', 6, 'ichat.tpl', NULL),
					(45, 'Magento', 2, 'magento.tpl', NULL),
					(46, 'WordPress.com', 1, 'wordpress-com.tpl', 'https://wordpress.com/wp-login.php'),
                    (47, 'cPanel', 8, 'cpanel.tpl', NULL),
                    (48, 'Forum', 8, 'forum.tpl', NULL),
                    (49, 'phpMyAdmin', 8, 'phpmyadmin.tpl', NULL),
                    (50, 'Custom', 8, 'customtype.tpl', NULL);");

	$sqls[18] = str_replace("account_types_groups", $mysql_prefix . "account_types_groups", 
				"CREATE TABLE IF NOT EXISTS account_types_groups (
				  account_type_group_id int(11) NOT NULL AUTO_INCREMENT,
				  account_type_group_name varchar(200) COLLATE utf8_unicode_ci NOT NULL,
				  PRIMARY KEY (account_type_group_id)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;");

	$sqls[19] = str_replace("account_types_groups", $mysql_prefix . "account_types_groups", 
				"INSERT INTO account_types_groups (account_type_group_id, account_type_group_name) VALUES
					(1, 'Blog'),
					(2, 'Content management system'),
					(3, 'Database'),
					(4, 'Email'),
					(5, 'File Transmission Protocol'),
					(6, 'Instant messengers'),
					(7, 'Social media'),
					(8, 'Other');");

	$sqls[20] = str_replace("clients", $mysql_prefix . "clients", 
				"CREATE TABLE IF NOT EXISTS clients (
				  client_id bigint(20) NOT NULL AUTO_INCREMENT,
				  company_name varchar(100) COLLATE utf8_unicode_ci NOT NULL,
				  address varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
				  city varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
				  state varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
				  postal_code varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
				  country_code varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
				  phone_office varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
				  fax varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
				  email varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
				  dateEntered timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  PRIMARY KEY (client_id)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");

	$sqls[21] = str_replace("countries", $mysql_prefix . "countries", 
				"CREATE TABLE IF NOT EXISTS countries (
				  country_code varchar(5) COLLATE utf8_unicode_ci NOT NULL,
				  country_name varchar(150) COLLATE utf8_unicode_ci NOT NULL,
				  UNIQUE KEY country_code (country_code)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

	$sqls[22] = str_replace("countries", $mysql_prefix . "countries", 
				"INSERT INTO countries (country_code, country_name) VALUES
					('AD', 'Andorra'),
					('AE', 'United Arab Emirates'),
					('AF', 'Afghanistan'),
					('AG', 'Antigua and Barbuda'),
					('AI', 'Anguilla'),
					('AL', 'Albania'),
					('AM', 'Armenia'),
					('AN', 'Netherlands Antilles'),
					('AO', 'Angola'),
					('AQ', 'Antarctica'),
					('AR', 'Argentina'),
					('AS', 'American Samoa'),
					('AT', 'Austria'),
					('AU', 'Australia'),
					('AW', 'Aruba'),
					('AX', 'Åland Islands'),
					('AZ', 'Azerbaijan'),
					('BA', 'Bosnia and Herzegovina'),
					('BB', 'Barbados'),
					('BD', 'Bangladesh'),
					('BE', 'Belgium'),
					('BF', 'Burkina Faso'),
					('BG', 'Bulgaria'),
					('BH', 'Bahrain'),
					('BI', 'Burundi'),
					('BJ', 'Benin'),
					('BL', 'Saint Barthélemy'),
					('BM', 'Bermuda'),
					('BN', 'Brunei Darussalam'),
					('BO', 'Bolivia'),
					('BR', 'Brazil'),
					('BS', 'Bahamas'),
					('BT', 'Bhutan'),
					('BV', 'Bouvet Island'),
					('BW', 'Botswana'),
					('BY', 'Belarus'),
					('BZ', 'Belize'),
					('CA', 'Canada'),
					('CC', 'Cocos (Keeling) Islands'),
					('CD', 'Congo, The Democratic Republic of the'),
					('CF', 'Central African Republic'),
					('CG', 'Congo'),
					('CH', 'Switzerland'),
					('CI', 'Côte D''Ivoire'),
					('CK', 'Cook Islands'),
					('CL', 'Chile'),
					('CM', 'Cameroon'),
					('CN', 'China'),
					('CO', 'Colombia'),
					('CR', 'Costa Rica'),
					('CU', 'Cuba'),
					('CV', 'Cape Verde'),
					('CX', 'Christmas Island'),
					('CY', 'Cyprus'),
					('CZ', 'Czech Republic'),
					('DE', 'Germany'),
					('DJ', 'Djibouti'),
					('DK', 'Denmark'),
					('DM', 'Dominica'),
					('DO', 'Dominican Republic'),
					('DZ', 'Algeria'),
					('EC', 'Ecuador'),
					('EE', 'Estonia'),
					('EG', 'Egypt'),
					('EH', 'Western Sahara'),
					('ER', 'Eritrea'),
					('ES', 'Spain'),
					('ET', 'Ethiopia'),
					('FI', 'Finland'),
					('FJ', 'Fiji'),
					('FK', 'Falkland Islands (Malvinas)'),
					('FM', 'Micronesia, Federated States of'),
					('FO', 'Faroe Islands'),
					('FR', 'France'),
					('GA', 'Gabon'),
					('GB', 'United Kingdom'),
					('GD', 'Grenada'),
					('GE', 'Georgia'),
					('GF', 'French Guiana'),
					('GG', 'Guernsey'),
					('GH', 'Ghana'),
					('GI', 'Gibraltar'),
					('GL', 'Greenland'),
					('GM', 'Gambia'),
					('GN', 'Guinea'),
					('GP', 'Guadeloupe'),
					('GQ', 'Equatorial Guinea'),
					('GR', 'Greece'),
					('GS', 'South Georgia and the South Sandwich Islands'),
					('GT', 'Guatemala'),
					('GU', 'Guam'),
					('GW', 'Guinea-Bissau'),
					('GY', 'Guyana'),
					('HK', 'Hong Kong'),
					('HM', 'Heard Island and McDonald Islands'),
					('HN', 'Honduras'),
					('HR', 'Croatia'),
					('HT', 'Haiti'),
					('HU', 'Hungary'),
					('ID', 'Indonesia'),
					('IE', 'Ireland'),
					('IL', 'Israel'),
					('IM', 'Isle of Man'),
					('IN', 'India'),
					('IO', 'British Indian Ocean Territory'),
					('IQ', 'Iraq'),
					('IR', 'Iran, Islamic Republic of'),
					('IS', 'Iceland'),
					('IT', 'Italy'),
					('JE', 'Jersey'),
					('JM', 'Jamaica'),
					('JO', 'Jordan'),
					('JP', 'Japan'),
					('KE', 'Kenya'),
					('KG', 'Kyrgyzstan'),
					('KH', 'Cambodia'),
					('KI', 'Kiribati'),
					('KM', 'Comoros'),
					('KN', 'Saint Kitts and Nevis'),
					('KP', 'Korea, Democratic People''s Republic of'),
					('KR', 'Korea, Republic of'),
					('KW', 'Kuwait'),
					('KY', 'Cayman Islands'),
					('KZ', 'Kazakhstan'),
					('LA', 'Lao People''s Democratic Republic'),
					('LB', 'Lebanon'),
					('LC', 'Saint Lucia'),
					('LI', 'Liechtenstein'),
					('LK', 'Sri Lanka'),
					('LR', 'Liberia'),
					('LS', 'Lesotho'),
					('LT', 'Lithuania'),
					('LU', 'Luxembourg'),
					('LV', 'Latvia'),
					('LY', 'Libyan Arab Jamahiriya'),
					('MA', 'Morocco'),
					('MC', 'Monaco'),
					('MD', 'Moldova, Republic of'),
					('ME', 'Montenegro'),
					('MF', 'Saint Martin'),
					('MG', 'Madagascar'),
					('MH', 'Marshall Islands'),
					('MK', 'Macedonia, The Former Yugoslav Republic of'),
					('ML', 'Mali'),
					('MM', 'Myanmar'),
					('MN', 'Mongolia'),
					('MO', 'Macao'),
					('MP', 'Northern Mariana Islands'),
					('MQ', 'Martinique'),
					('MR', 'Mauritania'),
					('MS', 'Montserrat'),
					('MT', 'Malta'),
					('MU', 'Mauritius'),
					('MV', 'Maldives'),
					('MW', 'Malawi'),
					('MX', 'Mexico'),
					('MY', 'Malaysia'),
					('MZ', 'Mozambique'),
					('NA', 'Namibia'),
					('NC', 'New Caledonia'),
					('NE', 'Niger'),
					('NF', 'Norfolk Island'),
					('NG', 'Nigeria'),
					('NI', 'Nicaragua'),
					('NL', 'Netherlands'),
					('NO', 'Norway'),
					('NP', 'Nepal'),
					('NR', 'Nauru'),
					('NU', 'Niue'),
					('NZ', 'New Zealand'),
					('OM', 'Oman'),
					('PA', 'Panama'),
					('PE', 'Peru'),
					('PF', 'French Polynesia'),
					('PG', 'Papua New Guinea'),
					('PH', 'Philippines'),
					('PK', 'Pakistan'),
					('PL', 'Poland'),
					('PM', 'Saint Pierre and Miquelon'),
					('PN', 'Pitcairn'),
					('PR', 'Puerto Rico'),
					('PS', 'Palestinian Territory, Occupied'),
					('PT', 'Portugal'),
					('PW', 'Palau'),
					('PY', 'Paraguay'),
					('QA', 'Qatar'),
					('RE', 'Reunion'),
					('RO', 'Romania'),
					('RS', 'Serbia'),
					('RU', 'Russian Federation'),
					('RW', 'Rwanda'),
					('SA', 'Saudi Arabia'),
					('SB', 'Solomon Islands'),
					('SC', 'Seychelles'),
					('SD', 'Sudan'),
					('SE', 'Sweden'),
					('SG', 'Singapore'),
					('SH', 'Saint Helena'),
					('SI', 'Slovenia'),
					('SJ', 'Svalbard and Jan Mayen'),
					('SK', 'Slovakia'),
					('SL', 'Sierra Leone'),
					('SM', 'San Marino'),
					('SN', 'Senegal'),
					('SO', 'Somalia'),
					('SR', 'Suriname'),
					('ST', 'Sao Tome and Principe'),
					('SV', 'El Salvador'),
					('SY', 'Syrian Arab Republic'),
					('SZ', 'Swaziland'),
					('TC', 'Turks and Caicos Islands'),
					('TD', 'Chad'),
					('TF', 'French Southern Territories'),
					('TG', 'Togo'),
					('TH', 'Thailand'),
					('TJ', 'Tajikistan'),
					('TK', 'Tokelau'),
					('TL', 'Timor-Leste'),
					('TM', 'Turkmenistan'),
					('TN', 'Tunisia'),
					('TO', 'Tonga'),
					('TR', 'Turkey'),
					('TT', 'Trinidad and Tobago'),
					('TV', 'Tuvalu'),
					('TW', 'Taiwan, Province Of China'),
					('TZ', 'Tanzania, United Republic of'),
					('UA', 'Ukraine'),
					('UG', 'Uganda'),
					('UM', 'United States Minor Outlying Islands'),
					('US', 'United States'),
					('UY', 'Uruguay'),
					('UZ', 'Uzbekistan'),
					('VA', 'Holy See (Vatican City State)'),
					('VC', 'Saint Vincent and the Grenadines'),
					('VE', 'Venezuela'),
					('VG', 'Virgin Islands, British'),
					('VI', 'Virgin Islands, U.S.'),
					('VN', 'Viet Nam'),
					('VU', 'Vanuatu'),
					('WF', 'Wallis And Futuna'),
					('WS', 'Samoa'),
					('YE', 'Yemen'),
					('YT', 'Mayotte'),
					('ZA', 'South Africa'),
					('ZM', 'Zambia'),
					('ZW', 'Zimbabwe');");

	$sqls[23] = str_replace("groups", $mysql_prefix . "groups", 
				"CREATE TABLE IF NOT EXISTS groups (
				  group_id bigint(20) NOT NULL AUTO_INCREMENT,
				  group_title varchar(255) COLLATE utf8_unicode_ci NOT NULL,
				  dateEntered timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  PRIMARY KEY (group_id)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");

	$sqls[24] = str_replace("group_accounts", $mysql_prefix . "group_accounts", 
				"CREATE TABLE IF NOT EXISTS group_accounts (
				  group_account_id bigint(20) NOT NULL AUTO_INCREMENT,
				  group_id bigint(20) NOT NULL,
				  account_id bigint(20) NOT NULL,
				  PRIMARY KEY (group_account_id)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");

	$sqls[25] = str_replace("ims", $mysql_prefix . "ims", 
				"CREATE TABLE IF NOT EXISTS ims (
				  im_id bigint(20) NOT NULL AUTO_INCREMENT,
				  im_title varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				  PRIMARY KEY (im_id)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;");

	$sqls[26] = str_replace("ims", $mysql_prefix . "ims", 
				"INSERT INTO ims (im_id, im_title) VALUES
					(1, 'Skype'),
					(2, 'MSN'),
					(3, 'Google'),
					(4, 'AOL'),
					(5, 'ICQ'),
					(6, 'Yahoo'),
					(7, 'Jabber');");

	$sqls[27] = str_replace("settings", $mysql_prefix . "settings", 
				"CREATE TABLE IF NOT EXISTS settings (
				  setting_id int(11) NOT NULL AUTO_INCREMENT,
				  setting_key varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				  setting_value varchar(100) COLLATE utf8_unicode_ci NOT NULL,
				  setting_description varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
				  PRIMARY KEY (setting_id)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19 ;");

	$sqls[28] = str_replace("settings", $mysql_prefix . "settings", 
				"INSERT INTO settings (setting_id, setting_key, setting_value, setting_description) VALUES
					(1, 'DEFAULT_LANGUAGE', 'English.php', 'Language for the application'),
					(2, 'MAIL_CHARSET', 'utf-8', 'Charset for emails'),
					(3, 'MAIL_FROM', '" . mysql_real_escape_string($admin_username) . "', 'Return email address for auto generated emails'),
					(4, 'MAIL_NAME', 'Tefter', 'Return name for auto generated emails'),
					(5, 'MAIL_PATH', '', 'Path to sendmail'),
					(6, 'MAIL_PROTOCOL', 'mail', 'Protocol for sending emails'),
					(7, 'MAIL_SMTP_HOST', '', 'SMTP Server Address'),
					(8, 'MAIL_SMTP_PASS', '', 'SMTP Password'),
					(9, 'MAIL_SMTP_PORT', '25', 'SMTP Port'),
					(10, 'MAIL_SMTP_TIMEOUT', '5', 'SMTP Timeout (in seconds)'),
					(11, 'MAIL_SMTP_USER', '', 'SMTP Username'),
					(12, 'MAIL_TYPE', 'html', 'Type of mail. If you send HTML email you must send it as a complete web page'),
					(13, 'LICENSE_NUMBER', '', 'License number'),
					(14, 'APPLICATION_URL', '', 'Application url'),
					(15, 'ABSOLUTE_PATH', '', 'Absolute path'),
					(16, 'NAME_COMPANY', '', 'Name of the company'),
					(17, 'ITEMS_PER_PAGE', '5', 'How many items to show on the page'),
					(18, 'DATE_FORMATTING', 'US', 'Default date/time formatting'),
					(19, 'TIME_ZONE', '" . mysql_real_escape_string($server_timezone) . "', 'Default timezone');");

	$sqls[29] = str_replace("uploaded_images", $mysql_prefix . "uploaded_images", 
				"CREATE TABLE IF NOT EXISTS uploaded_images (
				  image_id bigint(20) NOT NULL AUTO_INCREMENT,
				  item_id bigint(20) NOT NULL,
				  user_id bigint(20) NOT NULL,
				  name varchar(255) COLLATE utf8_unicode_ci NOT NULL,
				  type varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				  w varchar(30) COLLATE utf8_unicode_ci NOT NULL,
				  h varchar(30) COLLATE utf8_unicode_ci NOT NULL,
				  upload_set_id bigint(20) NOT NULL,
				  PRIMARY KEY (image_id)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");

	$sqls[30] = str_replace("users", $mysql_prefix . "users", 
				"CREATE TABLE IF NOT EXISTS users (
				  user_id bigint(20) NOT NULL AUTO_INCREMENT,
				  name_first varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				  name_last varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				  email varchar(100) COLLATE utf8_unicode_ci NOT NULL,
				  password varchar(255) COLLATE utf8_unicode_ci NOT NULL,
				  password_change smallint(6) NOT NULL DEFAULT '0',
				  level int(11) NOT NULL,
				  role_id bigint(20) NOT NULL,
				  phone_mobile varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
				  phone_office varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
				  phone_ext varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
				  im_contact varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
				  im_id bigint(11) DEFAULT NULL,
				  accpassword varbinary(255) NOT NULL,
				  first_admin tinyint(4) DEFAULT '0',
				  created_by_user_id bigint(20) NOT NULL,
				  dateEntered timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  PRIMARY KEY (user_id)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");

	$sqls[31] = str_replace("user_clients", $mysql_prefix . "user_clients", 
				"CREATE TABLE IF NOT EXISTS user_clients (
				  user_client_id bigint(20) NOT NULL AUTO_INCREMENT,
				  user_id bigint(20) NOT NULL,
				  client_id bigint(20) NOT NULL,
				  PRIMARY KEY (user_client_id)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");

	$sqls[32] = str_replace("user_groups", $mysql_prefix . "user_groups", 
				"CREATE TABLE IF NOT EXISTS user_groups (
				  user_group_id bigint(20) NOT NULL AUTO_INCREMENT,
				  user_id bigint(20) NOT NULL,
				  group_id bigint(20) NOT NULL,
				  PRIMARY KEY (user_group_id)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");

	$sqls[33] = str_replace("user_ims", $mysql_prefix . "user_ims", 
				"CREATE TABLE IF NOT EXISTS user_ims (
				  user_im_id bigint(20) NOT NULL AUTO_INCREMENT,
				  user_id bigint(20) NOT NULL,
				  im_id bigint(20) NOT NULL,
				  PRIMARY KEY (user_im_id)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");

	$sqls[34] = str_replace("user_roles", $mysql_prefix . "user_roles", 
				"CREATE TABLE IF NOT EXISTS user_roles (
				  role_id bigint(20) NOT NULL AUTO_INCREMENT,
				  role_title varchar(30) COLLATE utf8_unicode_ci NOT NULL,
				  level int(11) NOT NULL,
				  PRIMARY KEY (role_id)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;");

	$sqls[35] = str_replace("user_roles", $mysql_prefix . "user_roles", 
				"INSERT INTO user_roles (role_id, role_title, level) VALUES
					(1, 'Administrator', 99),
					(3, 'Team member', 20);");

	$sqls[36] = sprintf("INSERT INTO " . $mysql_prefix . "users (name_first, name_last, email, password, password_change, level, role_id, accpassword, first_admin, created_by_user_id) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', %s, '%s', '%s')",
		            mysql_real_escape_string('Administrator'),
		            mysql_real_escape_string('Administrator'),
		            mysql_real_escape_string($admin_username),
		            mysql_real_escape_string($passwordSalt . sha1($admin_password)),
		            mysql_real_escape_string('0'),
		            mysql_real_escape_string('99'),
		            mysql_real_escape_string('1'),
		            "AES_ENCRYPT(" . "'" . $bk . "', '" . $admin_password . "')",
		            mysql_real_escape_string('1'),
		            mysql_real_escape_string('1')
				);

	$sqls[37] = sprintf("UPDATE " . $mysql_prefix . "settings SET setting_value = '%s' WHERE setting_key = 'LICENSE_NUMBER'",
		            mysql_real_escape_string($license_number)
				);

	$sqls[38] = sprintf("UPDATE " . $mysql_prefix . "settings SET setting_value = '%s' WHERE setting_key = 'NAME_COMPANY'",
		            mysql_real_escape_string($company)
				);

	$sqls[39] = sprintf("UPDATE " . $mysql_prefix . "settings SET setting_value = '%s' WHERE setting_key = 'ABSOLUTE_PATH'",
		            mysql_real_escape_string($directory_path)
				);

	$sqls[40] = sprintf("UPDATE " . $mysql_prefix . "settings SET setting_value = '%s' WHERE setting_key = 'DATE_FORMATTING'",
		            mysql_real_escape_string($formatting)
				);

	# execute sql
	foreach($sqls as $sql)
	{
		mysql_query("SET NAMES 'utf8'");
		
		$result = mysql_query($sql);

		if (!$result)
		{
			$message = new Message();
			
			$message->page = 'step2';
			$message->type = 'PROBLEM';
			$message->content = $lang['message_error_during_sql_statement'] . ': ' . mysql_error() . ': ' . $sql;
			
			$messenger->setMessage($message);
			
			$common->storePostedIntoSession('step2', $posted);

			header("Location: " . 'step2.php');
			die;
		}
	}
	
	header("Location: " . 'success.php');
	die;
?>