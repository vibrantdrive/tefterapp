<?php
    if (!defined('BASEPATH')) exit('No direct script access allowed');
    
    class Account extends Basedata
    {
    	function Account()
    	{
            $obj =& get_instance();

    		$this->table_name = $obj->config->item('table_prefix') . 'accounts';
    		$this->table_key = 'account_id';
    		$this->fields = array(
		        'account_id' => 'int',
		        'account_name' => 'text',
		        'client_id' => 'int',
		        'type_id' => 'int',
		        'server' => 'binarypassword',
		        'username' => 'binarypassword',
		        'password' => 'binarypassword',
		        'port' => 'text',
		        'passive_mode' => 'int',
		        'root_url' => 'text',
		        'remote_path' => 'text',
		        'login_url' => 'binarypassword',
		        'note' => 'binarypassword',
		        'name' => 'binarypassword', // database name
		        'email' => 'binarypassword',
		        'email_type' => 'binarypassword',
		        'incoming_mail_server' => 'binarypassword',
		        'incoming_username' => 'binarypassword',
		        'incoming_password' => 'binarypassword',
		        'outgoing_mail_server' => 'binarypassword',
		        'outgoing_username' => 'binarypassword',
		        'outgoing_password' => 'binarypassword'
			);
		}
		
		function fetchAll($from, $limit, $letterFilter = null, $keyword = null, $typeFilter = null, $clientFilter = null, $direction = null, $orderBy = null, $forGroup = false, $forGroupID = null)
		{
            $obj =& get_instance();
            
            $bk = $obj->common->getFromSession('TEFTER_BK');
            $user_id = $obj->common->getFromSession('TEFTER_USERID');
            $user = $obj->user->fetchOne($user_id);
			
            $sql = "SELECT DISTINCT a.account_id, a.account_name, a.client_id, a.type_id, 
                           AES_DECRYPT(a.server, " . $obj->common->quote($bk) . ") AS server, 
                           AES_DECRYPT(a.username, " . $obj->common->quote($bk) . ") AS username, 
                           AES_DECRYPT(a.password, " . $obj->common->quote($bk) . ") AS password, 
                           a.port, a.passive_mode, a.root_url, a.remote_path, 
                           AES_DECRYPT(a.login_url, " . $obj->common->quote($bk) . ") AS login_url, 
                           AES_DECRYPT(a.note, " . $obj->common->quote($bk) . ") AS note, 
                           AES_DECRYPT(a.name, " . $obj->common->quote($bk) . ") AS name, 
                           AES_DECRYPT(a.email, " . $obj->common->quote($bk) . ") AS email, 
                           AES_DECRYPT(a.email_type, " . $obj->common->quote($bk) . ") AS email_type, 
                           AES_DECRYPT(a.incoming_mail_server, " . $obj->common->quote($bk) . ") AS incoming_mail_server, 
                           AES_DECRYPT(a.incoming_username, " . $obj->common->quote($bk) . ") AS incoming_username, 
                           AES_DECRYPT(a.incoming_password, " . $obj->common->quote($bk) . ") AS incoming_password, 
                           AES_DECRYPT(a.outgoing_mail_server, " . $obj->common->quote($bk) . ") AS outgoing_mail_server, 
                           AES_DECRYPT(a.outgoing_username, " . $obj->common->quote($bk) . ") AS outgoing_username, 
                           AES_DECRYPT(a.outgoing_password, " . $obj->common->quote($bk) . ") AS outgoing_password, 
                           c.company_name, at.account_type_name, ";
			
        	$set = $obj->settings->fetchBaseAll();
        	$dateFormat = $obj->settings->getValueForKey($set, 'DATE_FORMATTING');
        	
        	if ($dateFormat == 'EU')
        	{
        		$sql = $sql . " DATE_FORMAT(a.dateEntered, '%d/%m/%Y') AS dateEntered ";
			}
			else
			{
				$sql = $sql . " DATE_FORMAT(a.dateEntered, '%m/%d/%Y') AS dateEntered ";
			}
			                           
			$sql = $sql . " 
            		FROM $this->table_name a 
            			LEFT OUTER JOIN " . $obj->config->item('table_prefix') . "clients c ON a.client_id = c.client_id 
            			LEFT JOIN " . $obj->config->item('table_prefix') . "account_types at ON a.type_id = at.account_type_id ";
			
	        // groups
	        if ($user['level'] == 99)
	        {
		        # sees all accounts, all client accounts, all group accounts
		        if ($forGroup)
		        {
			        if ($forGroupID != null)
			        {
						$sql = $sql . " JOIN " . $obj->config->item('table_prefix') . "group_accounts ga ON a.account_id = ga.account_id AND ga.group_id = '$forGroupID' ";
//										JOIN " . $obj->config->item('table_prefix') . "user_groups ug ON ga.group_id = ug.group_id AND ug.group_id = '$forGroupID' ";
					}
				}
			}
			else
			{
		        if ($user['level'] == 20)
		        {
			        if ($forGroup)
			        {
						$sql = $sql . " JOIN " . $obj->config->item('table_prefix') . "group_accounts ga ON a.account_id = ga.account_id 
										JOIN " . $obj->config->item('table_prefix') . "user_groups ug ON ga.group_id = ug.group_id AND ug.user_id = '$user_id' ";
				        
				        if ($forGroupID != null)
				        {
							$sql = $sql . " AND ug.group_id = '$forGroupID' ";
						}
					}
				}
			}
            			
            $sql = $sql . " WHERE a.account_id > 0 ";
            
            if ($letterFilter != null)
            {
            	if ($letterFilter == 'asterix')
            	{
            		$sql = $sql . " AND (a.account_name LIKE '1%' 
            							OR a.account_name LIKE '2%' 
            							OR a.account_name LIKE '3%' 
            							OR a.account_name LIKE '4%' 
            							OR a.account_name LIKE '5%' 
            							OR a.account_name LIKE '6%' 
            							OR a.account_name LIKE '7%' 
            							OR a.account_name LIKE '8%' 
            							OR a.account_name LIKE '9%' 
            							OR a.account_name LIKE '0%' 
            							OR a.account_name LIKE '!%' 
            							OR a.account_name LIKE '#%' 
            							OR a.account_name LIKE '&%' )
            							";
				}
				else
				{
            		$sql .= " AND a.account_name LIKE '$letterFilter%' ";
				}
			}
			
            if ($keyword != null)
            {
            	$sql .= " AND a.account_name LIKE " . $obj->common->quote('%' . mysql_real_escape_string($keyword) . '%');
			}

			if ($typeFilter != null)
			{
				$sql .= " AND a.type_id = '$typeFilter' ";
			}
			
			if ($clientFilter != null)
			{
				$sql .= " AND a.client_id = '$clientFilter' ";
			}

            if ($orderBy != null)
            {
	            switch ($orderBy)
	            {
	                case 'date':
	                    $sql = $sql . " ORDER BY a.dateEntered ";
	                break;
	                case 'title':
	                    $sql = $sql . " ORDER BY a.account_name ";
	                break;
	                default:
	                    $sql = $sql . " ORDER BY a.account_name ";
	                break;
	            }
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
                return NULL;
            }
		}
		
		function fetchOne($id)
		{
            $obj =& get_instance();
            
            $bk = $obj->common->getFromSession('TEFTER_BK');
			
            $sql = "SELECT a.account_id, a.account_name, a.client_id, a.type_id, 
                           AES_DECRYPT(a.server, " . $obj->common->quote($bk) . ") AS server, 
                           AES_DECRYPT(a.username, " . $obj->common->quote($bk) . ") AS username, 
                           AES_DECRYPT(a.password, " . $obj->common->quote($bk) . ") AS password, 
                           a.port, a.passive_mode, a.root_url, a.remote_path, 
                           AES_DECRYPT(a.login_url, " . $obj->common->quote($bk) . ") AS login_url, 
                           AES_DECRYPT(a.note, " . $obj->common->quote($bk) . ") AS note, 
                           AES_DECRYPT(a.name, " . $obj->common->quote($bk) . ") AS name, 
                           AES_DECRYPT(a.email, " . $obj->common->quote($bk) . ") AS email, 
                           AES_DECRYPT(a.email_type, " . $obj->common->quote($bk) . ") AS email_type, 
                           AES_DECRYPT(a.incoming_mail_server, " . $obj->common->quote($bk) . ") AS incoming_mail_server, 
                           AES_DECRYPT(a.incoming_username, " . $obj->common->quote($bk) . ") AS incoming_username, 
                           AES_DECRYPT(a.incoming_password, " . $obj->common->quote($bk) . ") AS incoming_password, 
                           AES_DECRYPT(a.outgoing_mail_server, " . $obj->common->quote($bk) . ") AS outgoing_mail_server, 
                           AES_DECRYPT(a.outgoing_username, " . $obj->common->quote($bk) . ") AS outgoing_username, 
                           AES_DECRYPT(a.outgoing_password, " . $obj->common->quote($bk) . ") AS outgoing_password, 
                           c.company_name, at.account_type_name, ";
			
        	$set = $obj->settings->fetchBaseAll();
        	$dateFormat = $obj->settings->getValueForKey($set, 'DATE_FORMATTING');
        	
        	if ($dateFormat == 'EU')
        	{
        		$sql = $sql . " DATE_FORMAT(a.dateEntered, '%d/%m/%Y') AS dateEntered ";
			}
			else
			{
				$sql = $sql . " DATE_FORMAT(a.dateEntered, '%m/%d/%Y') AS dateEntered ";
			}
			                           
			$sql = $sql . " 
            		FROM $this->table_name a 
            			LEFT OUTER JOIN " . $obj->config->item('table_prefix') . "clients c ON a.client_id = c.client_id 
            			LEFT JOIN " . $obj->config->item('table_prefix') . "account_types at ON a.type_id = at.account_type_id 
            		WHERE a.account_id = '$id' ";
			
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
                    return $records[0];
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

		function countAll($letterFilter = null, $keyword = null, $typeFilter = null, $clientFilter = null, $forGroup = false, $forGroupID = null)
		{
            $obj =& get_instance();
            
            $bk = $obj->common->getFromSession('TEFTER_BK');
            $user_id = $obj->common->getFromSession('TEFTER_USERID');
            $user = $obj->user->fetchOne($user_id);
			
            $sql = "SELECT COUNT(*) AS total FROM (SELECT DISTINCT a.account_id, a.account_name, a.client_id, a.type_id, 
                           AES_DECRYPT(a.server, " . $obj->common->quote($bk) . ") AS server, 
                           AES_DECRYPT(a.username, " . $obj->common->quote($bk) . ") AS username, 
                           AES_DECRYPT(a.password, " . $obj->common->quote($bk) . ") AS password, 
                           a.port, a.passive_mode, a.root_url, a.remote_path, 
                           AES_DECRYPT(a.login_url, " . $obj->common->quote($bk) . ") AS login_url, 
                           AES_DECRYPT(a.note, " . $obj->common->quote($bk) . ") AS note, 
                           AES_DECRYPT(a.name, " . $obj->common->quote($bk) . ") AS name, 
                           AES_DECRYPT(a.email, " . $obj->common->quote($bk) . ") AS email, 
                           AES_DECRYPT(a.email_type, " . $obj->common->quote($bk) . ") AS email_type, 
                           AES_DECRYPT(a.incoming_mail_server, " . $obj->common->quote($bk) . ") AS incoming_mail_server, 
                           AES_DECRYPT(a.incoming_username, " . $obj->common->quote($bk) . ") AS incoming_username, 
                           AES_DECRYPT(a.incoming_password, " . $obj->common->quote($bk) . ") AS incoming_password, 
                           AES_DECRYPT(a.outgoing_mail_server, " . $obj->common->quote($bk) . ") AS outgoing_mail_server, 
                           AES_DECRYPT(a.outgoing_username, " . $obj->common->quote($bk) . ") AS outgoing_username, 
                           AES_DECRYPT(a.outgoing_password, " . $obj->common->quote($bk) . ") AS outgoing_password, 
                           c.company_name, at.account_type_name, ";
			
        	$set = $obj->settings->fetchBaseAll();
        	$dateFormat = $obj->settings->getValueForKey($set, 'DATE_FORMATTING');
        	
        	if ($dateFormat == 'EU')
        	{
        		$sql = $sql . " DATE_FORMAT(a.dateEntered, '%d/%m/%Y') AS dateEntered ";
			}
			else
			{
				$sql = $sql . " DATE_FORMAT(a.dateEntered, '%m/%d/%Y') AS dateEntered ";
			}
			                           
			$sql = $sql . " 
            		FROM $this->table_name a 
            			LEFT OUTER JOIN " . $obj->config->item('table_prefix') . "clients c ON a.client_id = c.client_id 
            			LEFT JOIN " . $obj->config->item('table_prefix') . "account_types at ON a.type_id = at.account_type_id ";
			
	        // groups
	        if ($user['level'] == 99)
	        {
		        # sees all accounts, all client accounts, all group accounts
		        if ($forGroup)
		        {
			        if ($forGroupID != null)
			        {
						$sql = $sql . " JOIN " . $obj->config->item('table_prefix') . "group_accounts ga ON a.account_id = ga.account_id AND ga.group_id = '$forGroupID' ";
//										JOIN " . $obj->config->item('table_prefix') . "user_groups ug ON ga.group_id = ug.group_id AND ug.group_id = '$forGroupID' ";
					}
				}
			}
			else
			{
		        if ($user['level'] == 20)
		        {
			        if ($forGroup)
			        {
						$sql = $sql . " JOIN " . $obj->config->item('table_prefix') . "group_accounts ga ON a.account_id = ga.account_id 
										JOIN " . $obj->config->item('table_prefix') . "user_groups ug ON ga.group_id = ug.group_id AND ug.user_id = '$user_id' ";
				        
				        if ($forGroupID != null)
				        {
							$sql = $sql . " AND ug.group_id = '$forGroupID' ";
						}
					}
				}
			}
            			
            $sql = $sql . " WHERE a.account_id > 0 ";
            
            if ($letterFilter != null)
            {
            	if ($letterFilter == 'asterix')
            	{
            		$sql = $sql . " AND (a.account_name LIKE '1%' 
            							OR a.account_name LIKE '2%' 
            							OR a.account_name LIKE '3%' 
            							OR a.account_name LIKE '4%' 
            							OR a.account_name LIKE '5%' 
            							OR a.account_name LIKE '6%' 
            							OR a.account_name LIKE '7%' 
            							OR a.account_name LIKE '8%' 
            							OR a.account_name LIKE '9%' 
            							OR a.account_name LIKE '0%' 
            							OR a.account_name LIKE '!%' 
            							OR a.account_name LIKE '#%' 
            							OR a.account_name LIKE '&%' )
            							";
				}
				else
				{
            		$sql .= " AND a.account_name LIKE '$letterFilter%' ";
				}
			}
			
            if ($keyword != null)
            {
            	$sql .= " AND a.account_name LIKE " . $obj->common->quote('%' . mysql_real_escape_string($keyword) . '%');
			}

			if ($typeFilter != null)
			{
				$sql .= " AND a.type_id = '$typeFilter' ";
			}
			
			if ($clientFilter != null)
			{
				$sql .= " AND a.client_id = '$clientFilter' ";
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
		
		function canDelete($user, $account)
		{
			$result = false;
			
			// admin can delete all accounts
			if ($user['level'] == 99)
			{
				$result = true;
			}
			
			return $result;
		}
		
		function removeAllForClientID($clientID)
		{
            $obj =& get_instance();
            
            $sql = "DELETE FROM $this->table_name WHERE client_id = '$clientID' ";
            
            $recordSet = $obj->adodb->Execute($sql);
            
            return $obj->adodb->Affected_Rows();
		}
	}
?>