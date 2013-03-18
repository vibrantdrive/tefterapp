<?php
    if (!defined('BASEPATH')) exit('No direct script access allowed');
    
    class Client extends Basedata
    {
    	function Client()
    	{
    		$obj =& get_instance();
    		
    		$this->table_name = $obj->config->item('table_prefix') . 'clients';
    		$this->table_key = 'client_id';
    		$this->fields = array(
		        'client_id' => 'int',
		        'company_name' => 'text',
		        'address' => 'text',
		        'city' => 'text',
		        'state' => 'text',
		        'city' => 'text',
		        'postal_code' => 'text',
		        'country_code' => 'text',
		        'phone_office' => 'text',
		        'fax' => 'text',
		        'email' => 'text'
			);
		}
		
		function fetchAll($from, $limit, $direction = 'DESC', $orderBy = 'name', $letterFilter = null, $keyword = null, $forUserID = null)
		{
            $obj =& get_instance();

            $sql = "SELECT DISTINCT c.*, co.country_code, co.country_name, (SELECT COUNT(*) FROM " . $obj->config->item('table_prefix') . "accounts aa WHERE c.client_id = aa.client_id) AS total  
            		FROM $this->table_name c ";
            		
            if ($forUserID != null)
            {
            	$sql = $sql . " JOIN " . $obj->config->item('table_prefix') . "user_clients uc ON c.client_id = uc.client_id AND uc.user_id = '$forUserID' ";
			}
            		
	        $sql = $sql . " LEFT OUTER JOIN " . $obj->config->item('table_prefix') . "accounts a ON c.client_id = a.client_id 
	        				LEFT OUTER JOIN " . $obj->config->item('table_prefix') . "countries co ON c.country_code = co.country_code 
	        				WHERE c.client_id > 0 ";
            
            if ($letterFilter != null)
            {
            	if ($letterFilter == 'asterix')
            	{
            		$sql = $sql . " AND (c.company_name LIKE '1%' 
            							OR c.company_name LIKE '2%' 
            							OR c.company_name LIKE '3%' 
            							OR c.company_name LIKE '4%' 
            							OR c.company_name LIKE '5%' 
            							OR c.company_name LIKE '6%' 
            							OR c.company_name LIKE '7%' 
            							OR c.company_name LIKE '8%' 
            							OR c.company_name LIKE '9%' 
            							OR c.company_name LIKE '0%' 
            							OR c.company_name LIKE '!%' 
            							OR c.company_name LIKE '#%' 
            							OR c.company_name LIKE '&%' )
            							";
				}
				else
				{
            		$sql .= " AND c.company_name LIKE '$letterFilter%' ";
				}
			}
			
            if ($keyword != null)
            {
            	$sql .= " AND c.company_name LIKE " . $obj->common->quote('%' . mysql_real_escape_string($keyword) . '%');
			}

            switch ($orderBy)
            {
                case 'date':
                    $sql = $sql . " ORDER BY c.dateEntered ";
                break;
                case 'name':
                    $sql = $sql . " ORDER BY c.company_name ";
                break;
                default:
                    $sql = $sql . " ORDER BY c.company_name ";
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

		function fetchOne($id)
		{
            $obj =& get_instance();

            $sql = "SELECT DISTINCT c.*, co.country_name, (SELECT COUNT(*) FROM " . $obj->config->item('table_prefix') . "accounts aa WHERE c.client_id = aa.client_id) AS total 
            		FROM $this->table_name c ";
            		
	        $sql = $sql . " LEFT OUTER JOIN " . $obj->config->item('table_prefix') . "accounts a ON c.client_id = a.client_id 
	        				LEFT OUTER JOIN " . $obj->config->item('table_prefix') . "countries co ON c.country_code = co.country_code 
	        				WHERE c.client_id = '$id' ";
            
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

		function countAll($letterFilter = null, $keyword = null, $forUserID = null)
		{
            $obj =& get_instance();

            $sql = "SELECT COUNT(*) AS total FROM (SELECT DISTINCT c.*, co.country_name, (SELECT COUNT(*) FROM " . $obj->config->item('table_prefix') . "accounts aa WHERE c.client_id = aa.client_id) AS total  
            		FROM $this->table_name c ";
            		
            if ($forUserID != null)
            {
            	$sql = $sql . " JOIN " . $obj->config->item('table_prefix') . "user_clients uc ON c.client_id = uc.client_id AND uc.user_id = '$forUserID' ";
			}
            		
	        $sql = $sql . " LEFT OUTER JOIN " . $obj->config->item('table_prefix') . "accounts a ON c.client_id = a.client_id 
	        				LEFT OUTER JOIN " . $obj->config->item('table_prefix') . "countries co ON c.country_code = co.country_code 
	        				WHERE c.client_id > 0 ";
            
            if ($letterFilter != null)
            {
            	if ($letterFilter == 'asterix')
            	{
            		$sql = $sql . " AND (c.company_name LIKE '1%' 
            							OR c.company_name LIKE '2%' 
            							OR c.company_name LIKE '3%' 
            							OR c.company_name LIKE '4%' 
            							OR c.company_name LIKE '5%' 
            							OR c.company_name LIKE '6%' 
            							OR c.company_name LIKE '7%' 
            							OR c.company_name LIKE '8%' 
            							OR c.company_name LIKE '9%' 
            							OR c.company_name LIKE '0%' 
            							OR c.company_name LIKE '!%' 
            							OR c.company_name LIKE '#%' 
            							OR c.company_name LIKE '&%' )
            							";
				}
				else
				{
            		$sql .= " AND c.company_name LIKE '$letterFilter%' ";
				}
			}
			
            if ($keyword != null)
            {
            	$sql .= " AND c.company_name LIKE " . $obj->common->quote('%' . mysql_real_escape_string($keyword) . '%');
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
	}
?>