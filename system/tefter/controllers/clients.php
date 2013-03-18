<?php
class Clients extends Controller {

    function Clients()
    {
        parent::Controller();    
        
        $this->load->library('common');
        $this->load->library('Smarty_Wrapper');
        $this->load->library('user');
        $this->load->library('messenger');
        $this->load->library('client');
        $this->load->library('countries');
        $this->load->library('User_Client');
        $this->load->library('account');
        $this->load->library('Group_Account');
        
        $this->load->helper('url');
    }
    
    function index()
    {
        # init session
        $this->common->init();
        
        $language = $this->multilanguage->loadLanguage();

        if (!$this->user->isAuthenticated())
        {
            $message = new Message();
            
            $message->page = 'login';
            $message->content = $language['message_login_required'];
            $message->type = 'PROBLEM';
            $this->messenger->setMessage($message);

            header("Location: " . $this->config->item('base_url') . 'login');
            die;
		}
        
        $userID = $this->common->getFromSession('TEFTER_USERID');
        $user = $this->user->fetchOne($userID);
        
        if (!$this->user->isMinimumLevel($user, 20))
        {
        	show_404('page');
        	die;
		}
        
        $set = $this->settings->fetchBaseAll();
        $settings = $this->settings->transformToArray($set);
        
        if ($user['level'] < 99)
        {
        	$clients = $this->client->fetchAll(0, 999999, 'ASC', 'name', null, null,  $user['user_id']);
		}
		else
		{
        	$clients = $this->client->fetchAll(0, 999999, 'ASC', 'name', null, null);
		}
        
        $m = $this->messenger->getMessage('clients');
        
        $this->smarty_wrapper->assign('title', 'Clients');
        $this->smarty_wrapper->assign('title_fixed', $this->config->item('title_fixed'));
        $this->smarty_wrapper->assign('m', $m);
        $this->smarty_wrapper->assign('lang', $language);
        $this->smarty_wrapper->assign('settings', $settings);
        $this->smarty_wrapper->assign('user', $user);
        $this->smarty_wrapper->assign('clients', $clients);
        $this->smarty_wrapper->assign('selectMenuItem', 'clients');
        
        if (count($clients) > 0)
        {
        	$this->smarty_wrapper->assign('hasCount', true);
		}
		else
		{
			$this->smarty_wrapper->assign('hasCount', false);
		}

        $this->smarty_wrapper->display("clients/clients.tpl");
    }

    function add()
    {
        # init session
        $this->common->init();

        $language = $this->multilanguage->loadLanguage();

        if (!$this->user->isAuthenticated())
        {
            $message = new Message();
            
            $message->page = 'login';
            $message->content = $language['message_login_required'];
            $message->type = 'PROBLEM';
            $this->messenger->setMessage($message);

            header("Location: " . $this->config->item('base_url') . 'login');
            die;
		}
        
        $userID = $this->common->getFromSession('TEFTER_USERID');
        $user = $this->user->fetchOne($userID);
        
        if (!$this->user->isMinimumLevel($user, 99))
        {
        	show_404('page');
        	die;
		}
        
        $set = $this->settings->fetchBaseAll();
        $settings = $this->settings->transformToArray($set);
        
        $countries = $this->countries->fetchAll();
        
        $posted = $this->common->getPostedFromSession('clients_add');

        $m = $this->messenger->getMessage('clients_add');
        
        $this->smarty_wrapper->assign('title', 'Add Client');
        $this->smarty_wrapper->assign('title_fixed', $this->config->item('title_fixed'));
        $this->smarty_wrapper->assign('m', $m);
        $this->smarty_wrapper->assign('lang', $language);
        $this->smarty_wrapper->assign('posted', $posted);
        $this->smarty_wrapper->assign('settings', $settings);
        $this->smarty_wrapper->assign('user', $user);
        $this->smarty_wrapper->assign('countries', $countries);
        $this->smarty_wrapper->assign('selectMenuItem', 'clients');

        $this->common->removePostedFromSession('clients_add');

        $this->smarty_wrapper->display("clients/clients_add.tpl");
	}
	
	function edit()
	{
        # init session
        $this->common->init();

        $language = $this->multilanguage->loadLanguage();
        
        $id = htmlspecialchars($this->uri->segment(3 + $this->config->item('segment_addition')));

        if (!$this->user->isAuthenticated())
        {
            $message = new Message();
            
            $message->page = 'login';
            $message->content = $language['message_login_required'];
            $message->type = 'PROBLEM';
            $this->messenger->setMessage($message);

            header("Location: " . $this->config->item('base_url') . 'login');
            die;
		}
        
        $userID = $this->common->getFromSession('TEFTER_USERID');
        $user = $this->user->fetchOne($userID);
        
        if (!$this->user->isMinimumLevel($user, 99))
        {
        	show_404('page');
        	die;
		}
        
		if (!$this->common->checkInteger($id))
		{
	        $message = new Message();
	        
	        $message->page = 'clients';
	        $message->content = $language['message_field_not_integer'];
	        $message->type = 'PROBLEM';
	        $this->messenger->setMessage($message);
	        
	        header("Location: " . $this->config->item('base_url') . 'clients');
	        die;
		}

        $client = $this->client->fetchOne($id);
        
        if ($client == null)
        {
            $message = new Message();
            
            $message->page = 'clients';
            $message->content = $language['message_client_not_found'];
            $message->type = 'NOTICE';
            $this->messenger->setMessage($message);

            header("Location: " . $this->config->item('base_url') . 'clients');
            die;
		}
        
        $set = $this->settings->fetchBaseAll();
        $settings = $this->settings->transformToArray($set);
        
        $countries = $this->countries->fetchAll();
        
        $posted = $this->common->getPostedFromSession('clients_edit');

        $m = $this->messenger->getMessage('clients_edit');
        
        $this->smarty_wrapper->assign('title', 'Edit client');
        $this->smarty_wrapper->assign('title_fixed', $this->config->item('title_fixed'));
        $this->smarty_wrapper->assign('m', $m);
        $this->smarty_wrapper->assign('lang', $language);
        $this->smarty_wrapper->assign('posted', $posted);
        $this->smarty_wrapper->assign('settings', $settings);
        $this->smarty_wrapper->assign('user', $user);
        $this->smarty_wrapper->assign('client', $client);
        $this->smarty_wrapper->assign('countries', $countries);
        $this->smarty_wrapper->assign('selectMenuItem', 'clients');

        $this->common->removePostedFromSession('clients_edit');

        $this->smarty_wrapper->display("clients/clients_edit.tpl");
	}
	
	function details()
	{
        # init session
        $this->common->init();

        $language = $this->multilanguage->loadLanguage();

        if (!$this->user->isAuthenticated())
        {
            $message = new Message();
            
            $message->page = 'login';
            $message->content = $language['message_login_required'];
            $message->type = 'PROBLEM';
            $this->messenger->setMessage($message);

            header("Location: " . $this->config->item('base_url') . 'login');
            die;
		}
        
        $client_id = htmlspecialchars($this->uri->segment(3 + $this->config->item('segment_addition')));
        
        if (isset($client_id))
        {
            if (!$this->common->checkInteger($client_id))
            {
	            $message = new Message();
	            
	            $message->page = 'clients';
	            $message->content = $language['message_field_not_integer'];
	            $message->type = 'PROBLEM';
	            $this->messenger->setMessage($message);

	            header("Location: " . $this->config->item('base_url') . 'clients');
	            die;
			}
		}
		
        $userID = $this->common->getFromSession('TEFTER_USERID');
        $user = $this->user->fetchOne($userID);
        
        if (!$this->user->isMinimumLevel($user, 99))
        {
        	show_404('page');
        	die;
		}
        
        $set = $this->settings->fetchBaseAll();
        $settings = $this->settings->transformToArray($set);
        
        $posted = $this->common->getPostedFromSession('clients_details');

        $m = $this->messenger->getMessage('clients_details');
        
        $client = $this->client->fetchOne($client_id);
        
        if ($client == null)
        {
            $message = new Message();
            
            $message->page = 'clients';
            $message->content = $language['message_client_required'];
            $message->type = 'NOTICE';
            $this->messenger->setMessage($message);

            header("Location: " . $this->config->item('base_url') . 'clients');
            die;
		}
		
		$accounts = $this->account->fetchAll(0, 999999, null, null, null, $client['client_id'], 'ASC', 'title');

        $this->smarty_wrapper->assign('title', 'Client details');
        $this->smarty_wrapper->assign('title_fixed', $this->config->item('title_fixed'));
        $this->smarty_wrapper->assign('m', $m);
        $this->smarty_wrapper->assign('lang', $language);
        $this->smarty_wrapper->assign('posted', $posted);
        $this->smarty_wrapper->assign('settings', $settings);
        $this->smarty_wrapper->assign('user', $user);
        $this->smarty_wrapper->assign('client', $client);
        $this->smarty_wrapper->assign('selectMenuItem', 'clients');

        if (count($accounts) > 0)
        {
        	$this->smarty_wrapper->assign('hasCount', true);
		}
		else
		{
			$this->smarty_wrapper->assign('hasCount', false);
		}

        $this->smarty_wrapper->display("clients/clients_details.tpl");
	}
	
	function addclientfromaccount()
	{
        # init session
        $this->common->init();
        
        $language = $this->multilanguage->loadLanguage();

        $result = array();

        $company_name = $this->common->getParameter('company_name', 'int');
        
        if (!$this->user->isAuthenticated())
        {
            $message = new Message();
            
            $message->page = 'login';
            $message->content = $language['message_login_required'];
            $message->type = 'PROBLEM';
            $this->messenger->setMessage($message);

			$result['success'] = false;
			$result['data'] = null;
			$result['code'] = -200;
			$result['description'] = $language['message_login_required'];
			$result['client_id'] = 0;
			
			echo json_encode($result);
			die;
		}

        $userID = $this->common->getFromSession('TEFTER_USERID');
        $user = $this->user->fetchOne($userID);

        if (!$this->user->isMinimumLevel($user, 99))
        {
            $message = new Message();
            
            $message->page = 'login';
            $message->content = $language['message_login_required'];
            $message->type = 'PROBLEM';
            $this->messenger->setMessage($message);

			$result['success'] = false;
			$result['data'] = null;
			$result['code'] = -200;
			$result['description'] = $language['message_login_required'];
			$result['client_id'] = 0;
			
			echo json_encode($result);
			die;
		}
		
        if (empty($company_name))
        {
			$result['success'] = false;
			$result['data'] = null;
			$result['code'] = -100;
			$result['description'] = $language['message_company_name_required'];
			$result['client_id'] = 0;
			
			echo json_encode($result);
			die;
		}
		
		$client = new Client();
		
		$client->company_name = $company_name;
		
		$inserted = $this->client->insert($client);
		
		if ($inserted > 0)
		{
			$result['success'] = true;
			$result['data'] = $inserted;
			$result['code'] = 200;
			$result['description'] = $language['message_clients_add_success'];
			$result['client_id'] = $inserted;
			
			echo json_encode($result);
			die;
		}
		else
		{
			$result['success'] = false;
			$result['data'] = null;
			$result['code'] = -100;
			$result['description'] = $language['message_error_during_process'];
			$result['client_id'] = 0;
			
			echo json_encode($result);
			die;
		}
	}
	
	function actions()
	{
        # init session
        $this->common->init();

        $language = $this->multilanguage->loadLanguage();

        $userID = $this->common->getFromSession('TEFTER_USERID');
        $user = $this->user->fetchOne($userID);

        if (!$this->user->isAuthenticated())
        {
            $message = new Message();
            
            $message->page = 'login';
            $message->content = $language['message_login_required'];
            $message->type = 'PROBLEM';
            $this->messenger->setMessage($message);

            header("Location: " . $this->config->item('base_url') . 'login');
            die;
		}

        if (!$this->user->isMinimumLevel($user, 99))
        {
        	show_404('page');
        	die;
		}

        $set = $this->settings->fetchBaseAll();
        $settings = $this->settings->transformToArray($set);

        switch (htmlspecialchars($this->uri->segment(3 + $this->config->item('segment_addition'))))
        {
            case 'add':
            	$company_name = $this->common->getParameter('client', 'text');
            	$address = $this->common->getParameter('client_address', 'text');
            	$city = $this->common->getParameter('client_city', 'text');
            	$state = $this->common->getParameter('client_state', 'text');
            	$postal_code = $this->common->getParameter('client_zip', 'text');
            	$country_code = $this->common->getParameter('client_country', 'text');
            	$phone_office = $this->common->getParameter('client_phone', 'text');
            	$fax = $this->common->getParameter('client_fax', 'text');
            	$email = $this->common->getParameter('client_email', 'text');
            	
            	$posted = $_POST;
            	
            	if (empty($company_name))
            	{
	                $message = new Message();
	                
	                $message->page = 'clients_add';
	                $message->content = $language['message_company_name_required'];
	                $message->type = 'PROBLEM';
	                $this->messenger->setMessage($message);
	                
	                $this->common->storePostedIntoSession('clients_add', $posted, 'client');

	                header("Location: " . $this->config->item('base_url') . 'clients/add');
	                die;
				}
				
				if (!empty($email))
				{
					if (!$this->common->check_email_address($email))
					{
			            $message = new Message();
			            
			            $message->page = 'clients_add';
			            $message->content = $language['message_email_format'];
			            $message->type = 'PROBLEM';
			            $this->messenger->setMessage($message);
			            
			            $this->common->storePostedIntoSession('clients_add', $posted, 'client_email');

			            header("Location: " . $this->config->item('base_url') . 'clients/add');
			            die;
					}
				} 
				
				$client = new Client();
				
				$client->company_name = $company_name;
				
				if (!empty($address))
				{
					$client->address = $address;
				}
				
				if (!empty($city))
				{
					$client->city = $city;
				}

				if (!empty($state))
				{
					$client->state = $state;
				}

				if (!empty($postal_code))
				{
					$client->postal_code = $postal_code;
				}

				if (!empty($country_code))
				{
					$client->country_code = $country_code;
				}

				if (!empty($phone_office))
				{
					$client->phone_office = $phone_office;
				}
				
				if (!empty($fax))
				{
					$client->fax = $fax;
				}
				
				if (!empty($email))
				{
					$client->email = $email;
				}
				
				$result = $this->client->insert($client);
				
				if ($result > 0)
				{
		            $message = new Message();
		            
		            $message->page = 'clients';
		            $message->content = $language['message_clients_add_success'];
		            $message->type = 'SUCCESS';
		            $this->messenger->setMessage($message);

		            header("Location: " . $this->config->item('base_url') . 'clients');
		            die;
				}
				else
				{
		            $message = new Message();
		            
		            $message->page = 'clients_add';
		            $message->content = $language['message_error_during_process'];
		            $message->type = 'PROBLEM';
		            $this->messenger->setMessage($message);
		            
		            $this->common->storePostedIntoSession('clients_add', $posted);

		            header("Location: " . $this->config->item('base_url') . 'clients/add');
		            die;
				}
			break;
			
			case 'edit':
            	$client_id = $this->common->getParameter('client_id', 'int');
            	$company_name = $this->common->getParameter('client', 'text');
            	$address = $this->common->getParameter('client_address', 'text');
            	$city = $this->common->getParameter('client_city', 'text');
            	$state = $this->common->getParameter('client_state', 'text');
            	$postal_code = $this->common->getParameter('client_zip', 'text');
            	$country_code = $this->common->getParameter('client_country', 'text');
            	$phone_office = $this->common->getParameter('client_phone', 'text');
            	$fax = $this->common->getParameter('client_fax', 'text');
            	$email = $this->common->getParameter('client_email', 'text');
            	
            	$posted = $_POST;
            	
		        if (!$this->common->checkInteger($client_id))
		        {
	                $message = new Message();
	                
	                $message->page = 'clients_edit';
	                $message->content = $language['message_field_not_integer'];
	                $message->type = 'PROBLEM';
	                $this->messenger->setMessage($message);
	                
	                header("Location: " . $this->config->item('base_url') . 'clients/edit/' . $client_id);
	                die;
				}

            	if (empty($company_name))
            	{
	                $message = new Message();
	                
	                $message->page = 'clients_edit';
	                $message->content = $language['message_company_name_required'];
	                $message->type = 'PROBLEM';
	                $this->messenger->setMessage($message);
	                
	                $this->common->storePostedIntoSession('clients_edit', $posted, 'client');

	                header("Location: " . $this->config->item('base_url') . 'clients/edit/' . $client_id);
	                die;
				}
				
				if (!empty($email))
				{
					if (!$this->common->check_email_address($email))
					{
			            $message = new Message();
			            
			            $message->page = 'clients_edit';
			            $message->content = $language['message_email_format'];
			            $message->type = 'PROBLEM';
			            $this->messenger->setMessage($message);
			            
			            $this->common->storePostedIntoSession('clients_edit', $posted, 'client_email');

			            header("Location: " . $this->config->item('base_url') . 'clients/edit/' . $client_id);
			            die;
					}
				} 
				
				$client = new Client();
				
				$client->client_id = $client_id;
				$client->company_name = $company_name;
				$client->address = $address;
				$client->city = $city;
				$client->state = $state;
				$client->postal_code = $postal_code;
				$client->country_code = $country_code;
				$client->phone_office = $phone_office;
				$client->fax = $fax;
				$client->email = $email;
				
				$result = $this->client->update($client);
				
				if ($result)
				{
		            $message = new Message();
		            
		            $message->page = 'clients';
		            $message->content = $language['message_clients_edit_success'];
		            $message->type = 'SUCCESS';
		            $this->messenger->setMessage($message);

		            header("Location: " . $this->config->item('base_url') . 'clients');
		            die;
				}
				else
				{
		            $message = new Message();
		            
		            $message->page = 'clients_edit';
		            $message->content = $language['message_error_during_process'];
		            $message->type = 'PROBLEM';
		            $this->messenger->setMessage($message);
		            
		            $this->common->storePostedIntoSession('clients_edit', $posted);

		            header("Location: " . $this->config->item('base_url') . 'accounts/edit/' . $client_id);
		            die;
				}
			break;
			
			case 'delete':
            	$client_id = $this->uri->segment(4 + $this->config->item('segment_addition'));
            	
            	if (isset($client_id))
            	{
            		if ($this->common->checkInteger($client_id))
            		{
            			$result = $this->client->remove($client_id);
            			
            			if ($result > 0)
            			{
					        // fetch all accounts for client
					        $clientAccounts = $this->account->fetchAll(0, 999999, null, null, null, $client_id, null, null, false, null);
					        
					        $this->user_client->removeAllForClientID($client_id);
					        $this->account->removeAllForClientID($client_id);
					        
					        if (count($clientAccounts) > 0)
					        {
					        	foreach ($clientAccounts as $clientAccount)
					        	{
					        		$this->group_account->removeAllForAccountID($clientAccount['account_id']);
								}
							}
					        
					        $message = new Message();
					        
					        $message->page = 'clients';
					        $message->content = $language['message_client_successfully_deleted'];
					        $message->type = 'SUCCESS';
					        $this->messenger->setMessage($message);
					        
					        header("Location: " . $this->config->item('base_url') . 'clients');
					        die;
						}
						else
						{
					        $message = new Message();
					        
					        $message->page = 'clients';
					        $message->content = $language['message_error_during_process'];
					        $message->type = 'PROBLEM';
					        $this->messenger->setMessage($message);
					        
					        header("Location: " . $this->config->item('base_url') . 'clients');
					        die;
						}
					}
					else
					{
				        $message = new Message();
				        
				        $message->page = 'clients';
				        $message->content = $language['message_field_not_integer'];
				        $message->type = 'PROBLEM';
				        $this->messenger->setMessage($message);
				        
				        header("Location: " . $this->config->item('base_url') . 'clients');
				        die;
					}
				}
				else
				{
				    $message = new Message();
				    
				    $message->page = 'clients';
				    $message->content = $language['message_client_required'];
				    $message->type = 'PROBLEM';
				    $this->messenger->setMessage($message);
				    
				    header("Location: " . $this->config->item('base_url') . 'clients');
				    die;
				}
			break;
		}
	}
    
	function table()
	{
        # init session
        $this->common->init();
        
        $language = $this->multilanguage->loadLanguage();

        $result = array();

        $limit_from = $this->common->getParameter('limit_from', 'int');
        $limit_to = $this->common->getParameter('limit_to', 'int');
        $letterFilter = $this->common->getParameter('letterFilter', 'text');
        $keyword = $this->common->getParameter('keyword', 'text');
        
        if (!$this->user->isAuthenticated())
        {
            $message = new Message();
            
            $message->page = 'login';
            $message->content = $language['message_login_required'];
            $message->type = 'PROBLEM';
            $this->messenger->setMessage($message);

			$result['success'] = false;
			$result['data'] = null;
			$result['code'] = -200;
			$result['description'] = $language['message_login_required'];
			$result['total'] = 0;
			$result['totalClients'] = 0;
			$result['limit_from'] = $limit_from;
			$result['limit_to'] = $limit_to;
			
			echo json_encode($result);
			die;
		}

        $userID = $this->common->getFromSession('TEFTER_USERID');
        $user = $this->user->fetchOne($userID);

        if (!$this->user->isMinimumLevel($user, 20))
        {
            $message = new Message();
            
            $message->page = 'login';
            $message->content = $language['message_login_required'];
            $message->type = 'PROBLEM';
            $this->messenger->setMessage($message);

			$result['success'] = false;
			$result['data'] = null;
			$result['code'] = -200;
			$result['description'] = $language['message_login_required'];
			$result['total'] = 0;
			$result['totalClients'] = 0;
			$result['limit_from'] = $limit_from;
			$result['limit_to'] = $limit_to;
			
			echo json_encode($result);
			die;
		}

		// define sorting
		$sort = 'name';
		$direction = 'ASC';

		if ($letterFilter == '')
		{
			$sort = 'date';
			$direction = 'DESC';
		}

        if ($user['level'] < 99)
        {
        	$clients = $this->client->fetchAll($limit_from, $limit_to - $limit_from, $direction, $sort, $letterFilter, $keyword, $user['user_id']);
			$totalClients = $this->client->countAll($letterFilter, $keyword, $user['user_id']);
		}
		else
		{
        	$clients = $this->client->fetchAll($limit_from, $limit_to - $limit_from, $direction, $sort, $letterFilter, $keyword);
			$totalClients = $this->client->countAll($letterFilter, $keyword);
		}
		
		$this->smarty_wrapper->assign('clients', $clients);
		$this->smarty_wrapper->assign('lang', $language);
		$this->smarty_wrapper->assign('user', $user);
		
        $data = $this->smarty_wrapper->fetch("clients/clients_table_template.tpl");
        
		$result['success'] = true;
		if ($totalClients == 0)
		{
			$result['data'] = '<p class="no-matches">' . $language['message_no_matches_found'] . '</p>';
		}
		else
		{
			$result['data'] = $data;
		}
		$result['code'] = 200;
		$result['description'] = 'OK';
		$result['total'] = count($clients);
		$result['totalClients'] = $totalClients;
		$result['limit_from'] = $limit_from;
		$result['limit_to'] = $limit_to;
		
		echo json_encode($result);
	}
}
?>