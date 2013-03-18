<?php
    if (!defined('BASEPATH')) exit('No direct script access allowed');
    
    if (!class_exists('Empty_Class'))
    {
        class Empty_Class
        {
            function __construct(){}
        }
    }

    class Common 
    {
        function init() 
        {
            $obj =& get_instance();
            
            $installed = $obj->config->item('app_installed');
            
            if (!$installed)
            {
            	header("Location: " . $obj->config->item('base_url') . 'install/index.php');
            	die;
			}

			if (function_exists('date_default_timezone_set'))
			{
                error_reporting(0);

				date_default_timezone_set(date_default_timezone_get());
                
                error_reporting(E_ALL);
			}
            
            session_start();
        }
        
		function strip_magic_slashes($str)
		{
		    if (get_magic_quotes_gpc() == 0)
		    {
		    	return stripslashes($str);
			}
			else
			{
				return $str;
			}
		}

		function add_magic_slashes($str)
		{
		    if (get_magic_quotes_gpc() == 0)
		    {
		    	return addslashes($str);
			}
			else
			{
				return $str;
			}
		}

        function getParameter($value, $type='text')
        {
            $result = NULL;
            
            switch($type)
            {
                case 'text':
                    if (isset($_GET[$value]))
                    {
                        $result = $_GET[$value];
                    }
                    
                    if (isset($_POST[$value]))
                    {
                        $result = $_POST[$value];
                    }
                break;
                case 'html':
                    if (isset($_GET[$value]))
                    {
                        $result = $_GET[$value];
                    }
                    
                    if (isset($_POST[$value]))
                    {
                        $result = $_POST[$value];
                    }
                break;
                default:
                    if (isset($_GET[$value]))
                    {
                        $result = htmlspecialchars($_GET[$value]);
                    }
                    
                    if (isset($_POST[$value]))
                    {
                        $result = htmlspecialchars($_POST[$value]);
                    }
                break;
            }
            
            return $result;
        }
        
        function quote($value) 
        {
            $obj =& get_instance();
            
            return $obj->adodb->qstr($value, get_magic_quotes_gpc());
        }
        
        function crypt_password($password) 
        {
            $obj =& get_instance();
            
            return $obj->config->item('password_salt') . sha1($password);
        }
        
        function crypt_binary_password($password) 
        {
            $bk = $this->getFromSession('TEFTER_BK');
//            $bk = '567890';
            
            return "AES_ENCRYPT(" . $this->quote($password) . ", " . $this->quote($bk) . ")";
        }
        
        function crypt_binary_password_reverse($password) 
        {
            $bk = $this->getFromSession('TEFTER_BK');
            
            return "AES_ENCRYPT(" . $this->quote($bk) . ", " . $this->quote($password) . ")";
        }

		function getRandomPassword($length)
		{
		    $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
		    $string = "";

		    for ($p = 0; $p < $length; $p++)
		    {
		        $string .= $characters[mt_rand(0, strlen($characters) - 1)];
		    }

		    return $string;
		}

        function buildInsertString($tableName, $tableKey, $object, $fields, $includePrimaryKey = false)
        {
            $obj =& get_instance();
            
            $keys = "";
            $values = "";
            $separator = "";
            $sql = "";

            foreach ($fields as $key => $value) 
            {
                if (isset($object->$key)) {
                    if ($key == $tableKey) 
                    {
                        if (!$includePrimaryKey) 
                        {
                            continue;
                        }
                    }
                    
                    switch ($value) {
                        case 'text':
                        break;
                        case 'int':
                        break;
                        case 'html':
                        break;
                        case 'password':
                            $object->$key = $this->crypt_password($object->$key);
                        break;
                        case 'binarypassword':
                        	$object->$key = $this->crypt_binary_password($object->$key);
                        break;
                        case 'binarypasswordreverse':
                        	$object->$key = $this->crypt_binary_password_reverse($object->$key);
                        break;
                    }
                    
                    $keys = $keys . $separator . $key;
                    if ($value == 'binarypassword' || $value == 'binarypasswordreverse')
                    {
                    	$values = $values . $separator . $object->$key;
					}
					else
					{
                    	$values = $values . $separator . $this->quote($this->strip_magic_slashes(mysql_real_escape_string($object->$key)));
					}
                    $separator = ", ";
                }                
            }
            
            $sql = $sql . "INSERT INTO $tableName ($keys) VALUES ($values)";
            
            return $sql;
        }

        function buildUpdateString($tableName, $tableKey, $object, $fields, $condition)
        {
            $obj =& get_instance();
            
            $str = "";
            $separator = "";
            $sql = "";

            foreach ($fields as $key => $value) 
            {
                if (isset($object->$key)) {
                    if ($key == $tableKey) 
                    {
                        continue;
                    }

                    switch ($value) {
                        case 'text':
                        break;
                        case 'int':
                        break;
                        case 'html':
                        break;
                        case 'password':
                            $object->$key = $this->crypt_password($object->$key);
                        break;
                        case 'binarypassword':
                        	$object->$key = $this->crypt_binary_password($object->$key);
                        break;
                        case 'binarypasswordreverse':
                        	$object->$key = $this->crypt_binary_password_reverse($object->$key);
                        break;
                    }

                    if ($value == 'binarypassword' || $value == 'binarypasswordreverse')
                    {
						$str = $str . $separator . $key . " = " . $object->$key;
					}
					else
					{
						$str = $str . $separator . $key . " = " . $this->quote($this->strip_magic_slashes(mysql_real_escape_string($object->$key)));
					}

                    $separator = ", ";
                }
            }
            
            $sql = $sql . "UPDATE $tableName SET $str WHERE $tableKey = " . $this->quote(mysql_real_escape_string($condition));
            
            return $sql;
        }
        
        function check_email_address($email)
        {
			if (preg_match("/^(\w+((-\w+)|(\w.\w+))*)\@(\w+((\.|-)\w+)*\.\w+$)/", $email))
			{
				return true;
			}
			else 
			{
				return false;
			}
        	
/*
			$isValid = true;
			$atIndex = strrpos($email, "@");
		   	
		   	if (is_bool($atIndex) && !$atIndex)
		   	{
			  	$isValid = false;
			}
		   	else
		   	{
			  	$domain = substr($email, $atIndex+1);
			  	$local = substr($email, 0, $atIndex);
			  	$localLen = strlen($local);
			  	$domainLen = strlen($domain);
			  	
			  	if ($localLen < 1 || $localLen > 64)
			  	{
			     	// local part length exceeded
			     	$isValid = false;
				}
			  	else if ($domainLen < 1 || $domainLen > 255)
			  	{
			     	// domain part length exceeded
			     	$isValid = false;
				}
			  	else if ($local[0] == '.' || $local[$localLen-1] == '.')
			  	{
			     	// local part starts or ends with '.'
			     	$isValid = false;
			  	}
			  	else if (preg_match('/\\.\\./', $local))
			  	{
			     	// local part has two consecutive dots
			     	$isValid = false;
			  	}
			  	else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
			  	{
			     	// character not valid in domain part
			     	$isValid = false;
			  	}
			  	else if (preg_match('/\\.\\./', $domain))
			  	{
			     	// domain part has two consecutive dots
			     	$isValid = false;
			  	}
			  	else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local)))
			  	{
			     	// character not valid in local part unless 
			     	// local part is quoted
			     	if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local)))
			     	{
			        	$isValid = false;
			    	}
			  	}
			  	
			  	if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
			  	{
			     	// domain not found in DNS
			     	$isValid = false;
			  	}
		   	}
		   
		   	return $isValid;
*/
            // First, we check that there's one @ symbol, and that the lengths are right
            if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
            // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
                return false;
            }
            // Split it into sections to make life easier
            $email_array = explode("@", $email);
            $local_array = explode(".", $email_array[0]);
            for ($i = 0; $i < sizeof($local_array); $i++) {
                if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
                    return false;
                }
            }
            if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
                $domain_array = explode(".", $email_array[1]);
                if (sizeof($domain_array) < 2) {
                    return false; // Not enough parts to domain
                }
                for ($i = 0; $i < sizeof($domain_array); $i++) {
                    if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
                        return false;
                    }
                }
            }
            
            return true;
   		}
        
        public function smart_truncate($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true) 
        {
            if ($considerHtml) {
                // if the plain text is shorter than the maximum length, return the whole text
                if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                    return $text;
                }
                // splits all html-tags to scanable lines
                preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
                $total_length = strlen($ending);
                $open_tags = array();
                $truncate = '';
                foreach ($lines as $line_matchings) {
                    // if there is any html-tag in this line, handle it and add it (uncounted) to the output
                    if (!empty($line_matchings[1])) {
                        // if it's an "empty element" with or without xhtml-conform closing slash
                        if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                            // do nothing
                        // if tag is a closing tag
                        } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                            // delete tag from $open_tags list
                            $pos = array_search($tag_matchings[1], $open_tags);
                            if ($pos !== false) {
                            unset($open_tags[$pos]);
                            }
                        // if tag is an opening tag
                        } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                            // add tag to the beginning of $open_tags list
                            array_unshift($open_tags, strtolower($tag_matchings[1]));
                        }
                        // add html-tag to $truncate'd text
                        $truncate .= $line_matchings[1];
                    }
                    // calculate the length of the plain text part of the line; handle entities as one character
                    $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
                    if ($total_length+$content_length> $length) {
                        // the number of characters which are left
                        $left = $length - $total_length;
                        $entities_length = 0;
                        // search for html entities
                        if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                            // calculate the real length of all entities in the legal range
                            foreach ($entities[0] as $entity) {
                                if ($entity[1]+1-$entities_length <= $left) {
                                    $left--;
                                    $entities_length += strlen($entity[0]);
                                } else {
                                    // no more characters left
                                    break;
                                }
                            }
                        }
                        $truncate .= substr($line_matchings[2], 0, $left+$entities_length);
                        // maximum lenght is reached, so get off the loop
                        break;
                    } else {
                        $truncate .= $line_matchings[2];
                        $total_length += $content_length;
                    }
                    // if the maximum length is reached, get off the loop
                    if($total_length>= $length) {
                        break;
                    }
                }
            } else {
                if (strlen($text) <= $length) {
                    return $text;
                } else {
                    $truncate = substr($text, 0, $length - strlen($ending));
                }
            }
            // if the words shouldn't be cut in the middle...
            if (!$exact) {
                // ...search the last occurance of a space...
                $spacepos = strrpos($truncate, ' ');
                if (isset($spacepos)) {
                    // ...and cut the text in this position
                    $truncate = substr($truncate, 0, $spacepos);
                }
            }
            // add the defined ending to the text
            $truncate .= $ending;
            if($considerHtml) {
                // close all unclosed html-tags
                foreach ($open_tags as $tag) {
                    $truncate .= '</' . $tag . '>';
                }
            }
            return $truncate;
        }

        function getEncodedSafeString($value)
        {
            # replace empty spaces with dash
            $value = str_replace(' ', '-', $value);
            
            return urlencode($value);
        }
        
        function storeIntoSession($key, $object)
        {
        	try
        	{
        		$obj =& get_instance();

        		$serialized = serialize($object);
        		$crypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $obj->config->item('session_password'), $serialized, MCRYPT_MODE_ECB);
        		
        		$_SESSION[$key] = $crypted;
			}
			catch (Exception $e)
			{
				return false;
			}
			
			return true;
		}
        
        function getFromSession($key)
        {
            $result = null;
            
            if(isset($_SESSION[$key]))
            {
                try
                {
            		$obj =& get_instance();
                    
                    $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $obj->config->item('session_password'), $_SESSION[$key], MCRYPT_MODE_ECB);
                    $result = unserialize($decrypted);
                }
                catch (Exception $e)
                {
                    $result = null;
                }
            }
            
            return $result;
		}
		
		function removeFromSession($key)
		{
            $result = false;
            
            if(isset($_SESSION[$key]))
            {
                try
                {
                	unset($_SESSION[$key]);
                	
                	$result = true;
                }
                catch (Exception $e)
                {
                    $result = false;
                }
            }
            
            return $result;
		}
        
        function storePostedIntoSession($key, $object, $error_field = null)
        {
            try
            {
                if (!is_null($error_field))
                {
                    $object['error_field'] = $error_field;
                }

                $serialized = serialize($object);
        		$crypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->getFromSession('bk'), $serialized, MCRYPT_MODE_ECB);

                $_SESSION[$key] = $crypted;
            }
            catch (Exception $e)
            {
            	return false;
            }
            
            return true;
        }

        function getPostedFromSession($key)
        {
            $result = null;
            
            if(isset($_SESSION[$key]))
            {
                try
                {
					$decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->getFromSession('bk'), $_SESSION[$key], MCRYPT_MODE_ECB);
                    $result = unserialize($decrypted);
                }
                catch (Exception $e)
                {
                    $result = null;
                }
            }
            
            return $result;
        }

        function removePostedFromSession($key)
        {
            $result = false;
            
            if(isset($_SESSION[$key]))
            {
                try
                {
                	unset($_SESSION[$key]);
                	
                	$result = true;
                }
                catch (Exception $e)
                {
                	$result = false;
                }
            }
            
            return $result;
        }
        
        function convertDMY2YMD($value, $separator)
        {
            try
            {
                return substr($value, 6, 4) . $separator . substr($value, 3, 2) . $separator . substr($value, 0, 2);
            }
            catch (Exception $e)
            {
                return null;
            }
        }
        
        function check_dateDMY($value)
        {
            if (strlen($value) != 10)
            {
                return false;
            }
            
            try
            {
                return checkdate(substr($value, 3, 2), substr($value, 0, 2), substr($value, 6, 4));
            }
            catch (Exception $e)
            {
                return false;
            }
        }
        
        function checkInteger($value)
        {
			return preg_match( '/^\d*$/', $value) == 1;
		}

        function checkLimitedInteger($value, $lowLimit = null, $upLimit = null)
        {
        	$result = true;
        	
        	if (!$this->checkInteger($value))
        	{
        		$result = false;
			}
			
			if ($lowLimit != null)
			{
				if ($value < $lowLimit)
				{
					$result = false;
				}
			}
        	
			if ($upLimit != null)
			{
				if ($value > $upLimit)
				{
					$result = false;
				}
			}

        	return $result;
		}
		
		function checkLicense($license)
		{
			return preg_match('#([a-zA-Z0-9]+){6}\-([a-zA-Z0-9]+){6}\-([a-zA-Z0-9]+){6}\-([a-zA-Z0-9]+){6}#i', $license);
		}
    }
?>