<?php
    if (!defined('BASEPATH')) exit('No direct script access allowed');
    
    class User extends Basedata
    {
    	function User()
    	{
            $obj =& get_instance();

    		$this->table_name = $obj->config->item('table_prefix') . 'users';
    		$this->table_key = 'user_id';
    		$this->fields = array(
		        'user_id' => 'int',
		        'name_first' => 'text',
		        'name_last' => 'text',
		        'email' => 'text',
		        'password' => 'password',
		        'password_change' => 'int',
		        'role_id' => 'int',
		        'level' => 'int',
		        'phone_mobile' => 'text',
		        'phone_office' => 'text',
		        'phone_ext' => 'text',
		        'im_contact' => 'text',
		        'im_id' => 'int',
		        'accpassword' => 'binarypasswordreverse',
		        'first_admin' => 'int',
		        'created_by_user_id' => 'int'
			);
		}
		
		function fetchAll($from, $limit, $direction = 'ASC', $orderBy = 'name', $letterFilter = null, $keywordFilter = null, $roleFilter = null, $forUserID = null, $excludeSelf = false)
		{
            $obj =& get_instance();
            
            $uploadPath = $obj->config->item('users_upload_path');
            $bk = $obj->common->getFromSession('TEFTER_UP');
            
            $user_id = $obj->common->getFromSession('TEFTER_USERID');
            $user = $obj->user->fetchOne($user_id);
            
            $sql = " SELECT DISTINCT u.*, AES_DECRYPT(u.accpassword, " . $obj->common->quote($bk) . ") AS keydecrypted, CONCAT('$uploadPath', ui.name) AS image_path, ui.image_id, ur.role_title 
                    FROM $this->table_name u 
                        LEFT OUTER JOIN " . $obj->config->item('table_prefix') . "uploaded_images ui ON u.$this->table_key = ui.item_id AND ui.type = 'users' AND ui.w = '42' AND ui.h = '42' 
                        LEFT OUTER JOIN " . $obj->config->item('table_prefix') . "user_roles ur ON u.role_id = ur.role_id ";

            $sql = $sql . " WHERE u.user_id > 0 ";
            
			if ($excludeSelf)
			{
				$sql = $sql . " AND u.user_id <> '$user_id' ";
			}

            if ($letterFilter != null)
            {
            	if ($letterFilter == 'asterix')
            	{
            		$sql = $sql . " AND (u.name_first LIKE '1%' 
            							OR u.name_first LIKE '2%' 
            							OR u.name_first LIKE '3%' 
            							OR u.name_first LIKE '4%' 
            							OR u.name_first LIKE '5%' 
            							OR u.name_first LIKE '6%' 
            							OR u.name_first LIKE '7%' 
            							OR u.name_first LIKE '8%' 
            							OR u.name_first LIKE '9%' 
            							OR u.name_first LIKE '0%' 
            							OR u.name_first LIKE '!%' 
            							OR u.name_first LIKE '#%' 
            							OR u.name_first LIKE '&%' )
            							";
				}
				else
				{
            		$sql .= " AND u.name_first LIKE '$letterFilter%' ";
				}
			}
			
            if ($keywordFilter != null)
            {
            	$sql = $sql . " AND (u.name_first LIKE " . $obj->common->quote('%' . mysql_real_escape_string($keywordFilter) . '%') . " OR u.name_last LIKE " . $obj->common->quote('%' . mysql_real_escape_string($keywordFilter) . '%') . ") ";
			}

            if ($roleFilter != null)
            {
            	$sql = $sql . " AND u.role_id = '$roleFilter' ";
			}

            if ($forUserID != null && $user['level'] < 99)
            {
            	$sql = $sql . " AND u.user_id = '$forUserID' ";
			}

            switch ($orderBy)
            {
                case 'date':
                    $sql = $sql . " ORDER BY u.dateEntered ";
                break;
                case 'name':
                    $sql = $sql . " ORDER BY u.name_first, u.name_last ";
                break;
                default:
                    $sql = $sql . " ORDER BY u.name_first, u.name_last ";
                break;
            }

            if ($direction != null)
            { 
                $sql .= $direction;
            }
                    
            $sql .= " LIMIT $from, $limit";
            
            $recordSet = $obj->adodb->Execute($sql);
            $records = array();

            if ($recordSet != false) 
            {
                while (!$recordSet->EOF) 
                {
                    $records[] = $recordSet->fields;
                    $recordSet->MoveNext();
                }
                
                return $records;
            }
            else
            {
                return null;
            }
		}
		
		public function fetchAllPasswordReset($userID, $level)
		{
            $obj =& get_instance();
            
            $sql = " SELECT u.user_id, u.name_first, u.name_last, u.email, u.password_change, u.level, u.role_id, u.first_admin, ur.role_title 
                    FROM $this->table_name u 
                        LEFT OUTER JOIN " . $obj->config->item('table_prefix') . "user_roles ur ON u.role_id = ur.role_id 
                    WHERE u.user_id > 0 
                    	AND u.password_change = 2 ";
                    	
            $sql = $sql . " ORDER BY u.name_first, u.name_last ";

            $recordSet = $obj->adodb->Execute($sql);
            $records = array();

            if ($recordSet != false) 
            {
                while (!$recordSet->EOF) 
                {
                    $records[] = $recordSet->fields;
                    $recordSet->MoveNext();
                }
                
                return $records;
            }
            else
            {
                return null;
            }
		}

        public function fetchOne($id)
        {
            $obj =& get_instance();
            $uploadPath = $obj->config->item('users_upload_path');
            $bk = $obj->common->getFromSession('TEFTER_UP');
            
            $sql = "SELECT u.*, AES_DECRYPT(u.accpassword, " . $obj->common->quote($bk) . ") AS keydecrypted, CONCAT('$uploadPath', ui.name) AS image_path, ui.image_id, ur.role_title 
                    FROM $this->table_name u 
                        LEFT OUTER JOIN " . $obj->config->item('table_prefix') . "uploaded_images ui ON u.$this->table_key = ui.item_id AND ui.type = 'users' AND ui.w = '42' AND ui.h = '42' 
                        LEFT OUTER JOIN " . $obj->config->item('table_prefix') . "user_roles ur ON u.role_id = ur.role_id 
                    WHERE u.$this->table_key = $id";
            
            $recordSet = $obj->adodb->Execute($sql);
            $records = array();
            
            if ($recordSet != false) 
            {
                while (!$recordSet->EOF) 
                {
                    $records[] = $recordSet->fields;
                    $recordSet->MoveNext();
                }
                
                if (count($records) > 0) 
                {
                    $result = $records[0];
                    
                    if ($result[$this->table_key] > 0)
                    {
                        return $result;
                    }
                    else
                    {
                        return NULL;
                    }
                }
                else 
                {
                    return NULL;
                }
            } 
            else 
            {
                return NULL;
            }
        }

		function countAll($letterFilter = null, $keyword = null, $roleFilter = null, $forUserID = null)
		{
            $obj =& get_instance();
            
            $uploadPath = $obj->config->item('users_upload_path');
            $bk = $obj->common->getFromSession('TEFTER_UP');
            
            $user_id = $obj->common->getFromSession('TEFTER_USERID');
            $user = $obj->user->fetchOne($user_id);
            
            $sql = " SELECT COUNT(*) AS total FROM (SELECT DISTINCT u.*, AES_DECRYPT(u.accpassword, " . $obj->common->quote($bk) . ") AS keydecrypted, CONCAT('$uploadPath', ui.name) AS image_path, ui.image_id, ur.role_title 
                    FROM $this->table_name u 
                        LEFT OUTER JOIN " . $obj->config->item('table_prefix') . "uploaded_images ui ON u.$this->table_key = ui.item_id AND ui.type = 'users' AND ui.w = '42' AND ui.h = '42' 
                        LEFT OUTER JOIN " . $obj->config->item('table_prefix') . "user_roles ur ON u.role_id = ur.role_id ";

            $sql = $sql . " WHERE u.user_id > 0 ";
            
            if ($letterFilter != null)
            {
            	if ($letterFilter == 'asterix')
            	{
            		$sql = $sql . " AND (u.name_first LIKE '1%' 
            							OR u.name_first LIKE '2%' 
            							OR u.name_first LIKE '3%' 
            							OR u.name_first LIKE '4%' 
            							OR u.name_first LIKE '5%' 
            							OR u.name_first LIKE '6%' 
            							OR u.name_first LIKE '7%' 
            							OR u.name_first LIKE '8%' 
            							OR u.name_first LIKE '9%' 
            							OR u.name_first LIKE '0%' 
            							OR u.name_first LIKE '!%' 
            							OR u.name_first LIKE '#%' 
            							OR u.name_first LIKE '&%' )
            							";
				}
				else
				{
            		$sql .= " AND u.name_first LIKE '$letterFilter%' ";
				}
			}
			
            if ($keyword != null)
            {
            	$sql = $sql . " AND (u.name_first LIKE " . $obj->common->quote('%' . mysql_real_escape_string($keyword) . '%') . " OR u.name_last LIKE " . $obj->common->quote('%' . mysql_real_escape_string($keyword) . '%') . ") ";
			}

            if ($roleFilter != null)
            {
            	$sql = $sql . " AND u.role_id = '$roleFilter' ";
			}

            if ($forUserID != null && $user['level'] < 99)
            {
            	$sql = $sql . " AND u.user_id = '$forUserID' ";
			}
			
			$sql = $sql . " ) AS x ";

            $recordSet = $obj->adodb->Execute($sql);

            if ($recordSet != false) 
            {
                while (!$recordSet->EOF) 
                {
                    $records[] = $recordSet->fields;
                    $recordSet->MoveNext();
                }
                
                return $records[0]['total'];
            }
            else
            {
                return 0;
            }
		}

        public function fetchOneByEmailAddress($email)
        {
            $obj =& get_instance();
            $uploadPath = $obj->config->item('users_upload_path');
            $pass = $obj->common->getFromSession('TEFTER_UP');
            
            $sql = "SELECT u.*, AES_DECRYPT(u.accpassword, " . $obj->common->quote($pass) . ") AS keydecrypted, CONCAT('$uploadPath', ui.name) AS image_path, ui.image_id 
                    FROM $this->table_name u 
                        LEFT OUTER JOIN " . $obj->config->item('table_prefix') . "uploaded_images ui ON u.$this->table_key = ui.item_id AND ui.type = 'users' AND ui.w = '42' AND ui.h = '42' 
                    WHERE u.email = '$email' ";
            
            $recordSet = $obj->adodb->Execute($sql);
            $records = array();
            
            if ($recordSet != false) 
            {
                while (!$recordSet->EOF) 
                {
                    $records[] = $recordSet->fields;
                    $recordSet->MoveNext();
                }
                
                if (count($records) > 0) 
                {
                    $result = $records[0];
                    
                    if ($result[$this->table_key] > 0)
                    {
                        return $result;
                    }
                    else
                    {
                        return NULL;
                    }
                } 
                else 
                {
                    return NULL;
                }
            }
            else 
            {
                return NULL;
            }
        }

        public function login($username, $password)
        {
            $obj =& get_instance();

        	$language = $obj->multilanguage->loadLanguage();
        	
	        $set = $obj->settings->fetchBaseAll();
	        $settings = $obj->settings->transformToArray($set);
            
            if (trim($settings['LICENSE_NUMBER']) == '')
            {
	            $message = new Message();

	            $message->page = 'login';
	            $message->content = $language['message_license_number_missing'];
	            $message->type = 'PROBLEM';
	            $obj->messenger->setMessage($message);

	            header("Location: " . $obj->config->item('base_url') . 'login');
	            die;
			}
            
            $valid = $obj->common->checkLicense($settings['LICENSE_NUMBER']);
            
            if (!$valid)
            {
	            $message = new Message();

	            $message->page = 'login';
	            $message->content = $language['message_license_number_invalid'];
	            $message->type = 'PROBLEM';
	            $obj->messenger->setMessage($message);

	            header("Location: " . $obj->config->item('base_url') . 'login');
	            die;
			}

	        $installed = $obj->config->item('app_installed');
	        
	        if ($installed)
	        {
        		if (file_exists($obj->config->item('home_path') . 'install'))
        		{
        			$message = new Message();

		            $message->page = 'login';
		            $message->content = $language['message_install_folder_not_removed'];
		            $message->type = 'NOTICE';
		            $obj->messenger->setMessage($message);

		            header("Location: " . $obj->config->item('base_url') . 'login');
		            die;
				}
			}

            $result = false;
            $records = array();
            $passwordsha = $obj->common->crypt_password($password);
            
            # initiate login parameters against database and return user row
            $sql = "SELECT *, AES_DECRYPT(accpassword, " . $obj->common->quote($password) . ") AS keydecrypted FROM $this->table_name WHERE email = '$username' AND password = '$passwordsha'";
            
            $recordSet = $obj->adodb->Execute($sql);
            
            if ($recordSet != false) 
            {
                while (!$recordSet->EOF) 
                {
                    $records[] = $recordSet->fields;
                    $recordSet->MoveNext();
                }

                if (count($records) > 0) 
                {
                    $user = $records[0];
                    
                    if ($user[$this->table_key] > 0)
                    {
                        # store session data
                        $obj->common->storeIntoSession('TEFTER_BK', $user['keydecrypted']);
                        $obj->common->storeIntoSession('TEFTER_UP', $password);
                        $obj->common->storeIntoSession('TEFTER_USERID', $user['user_id']);
                        
                        $result = true;
                    }
                }
            }
            
            return $result;
        }

        public function doAutomaticLogin()
        {
	        $result = false;
	        
	        if (isset($_COOKIE['TEFTER_USER_USERNAME']) && isset($_COOKIE['TEFTER_USER_PASSWORD']))
	        {
        		# login user
        		$username = $_COOKIE['TEFTER_USER_USERNAME'];
        		$password = $_COOKIE['TEFTER_USER_PASSWORD'];
        		
        		$result = $this->login($username, $password);
			}
			
			return $result;
		}
        
        public function canEdit($user, $userEditObject)
        {
        	// prvi admin moze da menja sve 
        	// admin moze da sve, ali ne prvog admina
        	// team leader moze da brise sebe i one koje je on kreirao
        	// member moze da brise samo sebe
        	$result = false;
        	
        	if ($user['level'] == 99)
        	{
        		if ($user['first_admin'] == 1)
        		{
        			$result = true;
				}
				else
				{
					if ($userEditObject['first_admin'] != 1)
					{
						$result = true;
					}
				}
			}
			else
			{
				if ($user['user_id'] == $userEditObject['user_id'])
				{
					$result = true;
				}
			}
			
			return $result;
		}
        
        public function canPasswordReset($user, $userEditObject)
        {
        	// admin moze sve
        	// team leader moze da resetuje iz svoje grupe
        	// member ne moze nista
        	$result = false;
        	
        	if ($user['level'] == 99)
        	{
        		$result = true;
			}
			else
			{
			    $result = false;
			}
			
			return $result;
		}

        public function canDelete($user, $userEditObject)
        {
        	// prvi admin moze da brise sve, osim sebe
        	// admin moze da brise sve, ali ne prvog admina
        	// team leader moze da menja sebe i one koje je on kreirao
        	// member moze da menja samo sebe
        	$result = false;
        	
        	if ($user['level'] == 99)
        	{
        		if ($user['first_admin'] == 1)
        		{
        			if ($user['user_id'] == $userEditObject['user_id'])
        			{
        				$result = false;
					}
					else
					{
						$result = true;
					}
				}
				else
				{
					if ($userEditObject['first_admin'] != 1)
					{
						$result = true;
					}
				}
			}
			else
			{
				if ($user['user_id'] == $userEditObject['user_id'])
				{
					$result = true;
				}
			}
			
			return $result;
		}

        public function storeLoginCookie($email, $password)
        {
        	if (isset($email) && isset($password))
        	{
        		// expire in 2 weeks
        		$resultU = setcookie("TEFTER_USER_USERNAME", $email, time() + 1209600, '/');
        		$resultP = setcookie("TEFTER_USER_PASSWORD", $password, time() + 1209600, '/');
			}
		}
        
        public function isEmailExist($email, $userId = null)
        {
            $obj =& get_instance();
            
            $sql = "SELECT COUNT(*) AS total FROM $this->table_name WHERE email = '$email' ";
            
            if (!is_null($userId))
            {
                $sql = $sql . " AND $this->table_key <> '$userId'";
            }
            
            $recordSet = $obj->adodb->Execute($sql);

            if ($recordSet != false) 
            {
                while (!$recordSet->EOF) 
                {
                    $records[] = $recordSet->fields;
                    $recordSet->MoveNext();
                }
                
                if ($records[0]['total'] > 0)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                # return like email exist if we encounter an error
                return true;
            }
        }
        
        public function isAuthenticated()
        {
            $result = false;
            
            if(isset($_SESSION["TEFTER_USERID"]))
            {
                $result = true;
            }
            
            return $result;
        }

        public function isMinimumLevel($user, $level)
        {
            $result = false;
            
            if ($user['level'] >= $level)
            {
            	$result = true;
			}
            	
            return $result;
        }

        public function logout()
        {
	        if (isset($_COOKIE['TEFTER_USER_USERNAME']) && isset($_COOKIE['TEFTER_USER_PASSWORD']))
	        {
				$resultU = setcookie("TEFTER_USER_USERNAME", "", time() - 3600, '/');	        	
        		$resultP = setcookie("TEFTER_USER_PASSWORD", "", time() - 3600, '/');
			}
			
            session_destroy();
        }

        function getIP()
        {
            $result = 'n/a';
  
            if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
                $result = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } elseif (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
                $result = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (array_key_exists('REMOTE_ADDR', $_SERVER)) {
                $result = $_SERVER['REMOTE_ADDR'];
            } else {
                $result = 'n/a';
            }
        
            if (strstr($result, ',')) {
                $result = strtok($result, ',');
            }
    
            if ($result == "" || strlen($result) == 0){
                $result = 'n/a';
            }

            return $result;
        }
	}
?>