<?php
    $folder = htmlspecialchars($_POST['folder']);
    
    if (trim($folder) == '')
    {
        die("System folder not set.");
    }
    
    require_once('../../' . $folder . '/tefter/config/settings.php');
    
    $host = $db['default']['hostname'];
    $usr = $db['default']['username'];
    $pwd = $db['default']['password'];
    $dbname = $db['default']['database'];
    
    $link = mysql_connect($host, $usr, $pwd);
    
    error_reporting(E_ALL);
    
    if (!$link)
    {
        die("Could not connect to database.");
    }
    
    if (mysql_select_db($dbname, $link) === false)
    {
        die("Could not select database.");
    }
    
    $pref = $config['table_prefix'];
    
    $sql = "INSERT INTO " . $pref . "account_types (account_type_id, account_type_name, account_type_group_id, account_type_template_name, account_type_login_url) VALUES (50, 'Custom', 8, 'customtype.tpl', NULL);";
    
    $result = mysql_query($sql, $link);
    
    if (!$result)
    {
        die('There was an error during process.');
    }
    else
    {
        echo 'Database update successfully completed.';
    }
?>