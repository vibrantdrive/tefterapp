<?php
    if (!defined('BASEPATH')) exit('No direct script access allowed');
    
    class Group extends Basedata
    {
    	function Group()
    	{
            $obj =& get_instance();

    		$this->table_name = $obj->config->item('table_prefix') . 'groups';
    		$this->table_key = 'group_id';
    		$this->fields = array(
		        'group_id' => 'int',
		        'group_title' => 'text'
			);
		}
		
		function fetchAll($from, $limit, $direction = 'DESC', $orderBy = 'name', $letterFilter = null, $keyword = null, $forUserID = null)
		{
            $obj =& get_instance();

            $sql = " SELECT DISTINCT g.*, (SELECT COUNT(*) FROM " . $obj->config->item('table_prefix') . "group_accounts ga WHERE ga.group_id = g.group_id) AS totalAccounts, 
                     (SELECT count(*) FROM " . $obj->config->item('table_prefix') . "user_groups ugg WHERE ugg.group_id = g.group_id) AS totalUsers 
            		 FROM $this->table_name g ";
            		
            if ($forUserID != null)
            {
            	$sql = $sql . " JOIN " . $obj->config->item('table_prefix') . "user_groups ug ON g.group_id = ug.group_id AND ug.user_id = '$forUserID' ";
			}
            		
	        $sql = $sql . " WHERE g.group_id > 0 ";
            
            if ($letterFilter != null)
            {
            	if ($letterFilter == 'asterix')
            	{
            		$sql = $sql . " AND (g.group_title LIKE '1%' 
            							OR g.group_title LIKE '2%' 
            							OR g.group_title LIKE '3%' 
            							OR g.group_title LIKE '4%' 
            							OR g.group_title LIKE '5%' 
            							OR g.group_title LIKE '6%' 
            							OR g.group_title LIKE '7%' 
            							OR g.group_title LIKE '8%' 
            							OR g.group_title LIKE '9%' 
            							OR g.group_title LIKE '0%' 
            							OR g.group_title LIKE '!%' 
            							OR g.group_title LIKE '#%' 
            							OR g.group_title LIKE '&%' )
            							";
				}
				else
				{
            		$sql .= " AND g.group_title LIKE '$letterFilter%' ";
				}
			}
			
            if ($keyword != null)
            {
            	$sql .= " AND g.group_title LIKE " . $obj->common->quote('%' . mysql_real_escape_string($keyword) . '%');
			}

            switch ($orderBy)
            {
                case 'date':
                    $sql = $sql . " ORDER BY g.dateEntered ";
                break;
                case 'name':
                    $sql = $sql . " ORDER BY g.group_title ";
                break;
                default:
                    $sql = $sql . " ORDER BY g.group_title ";
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

        public function fetchOne($id)
        {
            $obj =& get_instance();
            
            $sql = " SELECT g.*, (SELECT COUNT(*) FROM " . $obj->config->item('table_prefix') . "group_accounts ga WHERE ga.group_id = g.group_id) AS totalAccounts, 
                     		(SELECT count(*) FROM " . $obj->config->item('table_prefix') . "user_groups ugg WHERE ugg.group_id = g.group_id) AS totalUsers 
            		 FROM $this->table_name g 
            		 WHERE g.group_id = '$id' ";
            		
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

		function countAll($letterFilter = null, $keyword = null, $forUserID = null)
		{
            $obj =& get_instance();

            $sql = " SELECT COUNT(*) AS total FROM (SELECT DISTINCT g.*, (SELECT COUNT(*) FROM " . $obj->config->item('table_prefix') . "group_accounts ga WHERE ga.group_id = g.group_id) AS totalAccounts, 
                     (SELECT count(*) FROM " . $obj->config->item('table_prefix') . "user_groups ugg WHERE ugg.group_id = g.group_id) AS totalUsers 
            		 FROM $this->table_name g ";
            		
            if ($forUserID != null)
            {
            	$sql = $sql . " JOIN " . $obj->config->item('table_prefix') . "user_groups ug ON g.group_id = ug.group_id AND ug.user_id = '$forUserID' ";
			}
            		
	        $sql = $sql . " WHERE g.group_id > 0 ";
            
            if ($letterFilter != null)
            {
            	if ($letterFilter == 'asterix')
            	{
            		$sql = $sql . " AND (g.group_title LIKE '1%' 
            							OR g.group_title LIKE '2%' 
            							OR g.group_title LIKE '3%' 
            							OR g.group_title LIKE '4%' 
            							OR g.group_title LIKE '5%' 
            							OR g.group_title LIKE '6%' 
            							OR g.group_title LIKE '7%' 
            							OR g.group_title LIKE '8%' 
            							OR g.group_title LIKE '9%' 
            							OR g.group_title LIKE '0%' 
            							OR g.group_title LIKE '!%' 
            							OR g.group_title LIKE '#%' 
            							OR g.group_title LIKE '&%' )
            							";
				}
				else
				{
            		$sql .= " AND g.group_title LIKE '$letterFilter%' ";
				}
			}
			
            if ($keyword != null)
            {
            	$sql .= " AND g.group_title LIKE " . $obj->common->quote('%' . mysql_real_escape_string($keyword) . '%');
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
		
		function canAccess($user, $group)
		{
			$result = false;
			
			if ($user['level'] == 99)
			{
				$result = true;
			}
			else
			{
				if ($user['level'] <= 20)
				{
					# check if this group is seen by this user
					$obj =& get_instance();
					
					if ($obj->user_group->countGroupsForUser($user['user_id'], $group['group_id']) > 0)
					{
						$result = true;
					}
				}
			}
			
			return $result;
		}

		function canEdit($userObject, $group)
        {
            $result = false;
            
            if ($userObject['level'] == 99)
            {
                $result = true;
            }
            
            return $result;
        }
        
        function canRemoveFromGroup($loggedUser, $userObject, $group)
		{
			$result = false;
			
			if ($loggedUser['level'] == 99)
			{
				$result = true;
			}
			
			return $result;
		}
	}
?>