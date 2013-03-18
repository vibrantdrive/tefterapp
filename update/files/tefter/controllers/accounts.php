<?php
class Accounts extends Controller {

    function Accounts()
    {
        parent::Controller();    
        
        $this->load->library('common');
        $this->load->library('Smarty_Wrapper');
        $this->load->library('user');
        $this->load->library('email');
        $this->load->library('messenger');
        $this->load->library('account');
        $this->load->library('client');
        $this->load->library('Account_Type');
        $this->load->library('Group_Account');
        $this->load->library('User_Client');
        
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
        
        $types = $this->account_type->fetchAll();

        if ($user['level'] < 99)
        {
        	$clients = $this->client->fetchAll(0, 999999, 'ASC', 'name', null, null,  $user['user_id']);
		}
		else
		{
        	$clients = $this->client->fetchAll(0, 999999, 'ASC', 'name', null, null);
		}
        
        $accounts = $this->account->fetchAll(0, 25, null, null, null, null, 'ASC', 'title');
        
        $m = $this->messenger->getMessage('accounts');
        
        $this->smarty_wrapper->assign('title', 'Accounts');
        $this->smarty_wrapper->assign('title_fixed', $this->config->item('title_fixed'));
        $this->smarty_wrapper->assign('m', $m);
        $this->smarty_wrapper->assign('lang', $language);
        $this->smarty_wrapper->assign('settings', $settings);
        $this->smarty_wrapper->assign('user', $user);
        $this->smarty_wrapper->assign('types', $types);
        $this->smarty_wrapper->assign('clients', $clients);
        $this->smarty_wrapper->assign('selectMenuItem', 'accounts');
        
        if (count($accounts) > 0)
        {
        	$this->smarty_wrapper->assign('hasCount', true);
		}
		else
		{
			$this->smarty_wrapper->assign('hasCount', false);
		}

        $this->smarty_wrapper->display("accounts/accounts.tpl");
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
        
        $types = $this->account_type->fetchAll();
        
        if ($user['level'] < 99)
        {
        	$clients = $this->client->fetchAll(0, 999999, 'ASC', 'name', null, null, $user['user_id']);
		}
		else
		{
        	$clients = $this->client->fetchAll(0, 999999, 'ASC', 'name', null, null);
		}

        $posted = $this->common->getPostedFromSession('accounts_add');

        $m = $this->messenger->getMessage('accounts_add');
        
        $selectClient = htmlspecialchars($this->uri->segment(3 + $this->config->item('segment_addition'), ''));
        
        if ($selectClient != '')
        {
	        if ($this->common->checkInteger($selectClient))
	        {
        		$this->smarty_wrapper->assign('selectClient', $selectClient);
			}
			else
			{
	            show_404('page');
	            die;
			}
		}
        
        $this->smarty_wrapper->assign('title', 'Add Account');
        $this->smarty_wrapper->assign('title_fixed', $this->config->item('title_fixed'));
        $this->smarty_wrapper->assign('m', $m);
        $this->smarty_wrapper->assign('lang', $language);
        $this->smarty_wrapper->assign('posted', $posted);
        $this->smarty_wrapper->assign('settings', $settings);
        $this->smarty_wrapper->assign('user', $user);
        $this->smarty_wrapper->assign('types', $types);
        $this->smarty_wrapper->assign('clients', $clients);
        $this->smarty_wrapper->assign('selectMenuItem', 'accounts');

      //  $this->common->removePostedFromSession('accounts_add');

        $this->smarty_wrapper->display("accounts/accounts_add.tpl");
	}
	
	function edit()
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
        
        $id = htmlspecialchars($this->uri->segment(3 + $this->config->item('segment_addition')));
        
        if (!$this->common->checkInteger($id))
        {
            $message = new Message();
            
            $message->page = 'accounts';
            $message->content = $language['message_field_not_integer'];
            $message->type = 'PROBLEM';
            $this->messenger->setMessage($message);

            header("Location: " . $this->config->item('base_url') . 'accounts');
            die;
		}
		
        $account = $this->account->fetchOne($id);
        
        if ($account == null)
        {
            $message = new Message();
            
            $message->page = 'accounts';
            $message->content = $language['message_account_not_found'];
            $message->type = 'NOTICE';
            $this->messenger->setMessage($message);

            header("Location: " . $this->config->item('base_url') . 'accounts');
            die;
		}
        
        $set = $this->settings->fetchBaseAll();
        $settings = $this->settings->transformToArray($set);
        
        $types = $this->account_type->fetchAll();
        
        if ($user['level'] < 99)
        {
        	$clients = $this->client->fetchAll(0, 999999, 'ASC', 'name', null, null, $user['user_id']);
		}
		else
		{
        	$clients = $this->client->fetchAll(0, 999999, 'ASC', 'name', null, null);
		}

        $posted = $this->common->getPostedFromSession('accounts_edit');

        $m = $this->messenger->getMessage('accounts_edit');
        
        $this->smarty_wrapper->assign('title', 'Edit Account');
        $this->smarty_wrapper->assign('title_fixed', $this->config->item('title_fixed'));
        $this->smarty_wrapper->assign('m', $m);
        $this->smarty_wrapper->assign('lang', $language);
        $this->smarty_wrapper->assign('posted', $posted);
        $this->smarty_wrapper->assign('settings', $settings);
        $this->smarty_wrapper->assign('user', $user);
        $this->smarty_wrapper->assign('types', $types);
        $this->smarty_wrapper->assign('account', $account);
        $this->smarty_wrapper->assign('clients', $clients);
        $this->smarty_wrapper->assign('selectMenuItem', 'accounts');

        $this->common->removePostedFromSession('accounts_edit');

        $this->smarty_wrapper->display("accounts/accounts_edit.tpl");
	}
	
	function actions()
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

        switch (htmlspecialchars($this->uri->segment(3 + $this->config->item('segment_addition'))))
        {
            case 'add':
            	$title = $this->common->getParameter('title', 'text');
            	$client = $this->common->getParameter('client', 'text');
            	$type = $this->common->getParameter('type', 'text');
            	$server = $this->common->getParameter('server', 'text');
            	$host = $this->common->getParameter('host', 'text');
            	$username = $this->common->getParameter('username', 'text');
            	$password = $this->common->getParameter('password', 'text');
            	$port = $this->common->getParameter('port', 'text');
            	$passive_mode = $this->common->getParameter('passive_mode', 'int');
            	$root_url = $this->common->getParameter('root_url', 'text');
            	$remote_path = $this->common->getParameter('remote_path', 'text');
            	$login_url = $this->common->getParameter('login_url', 'text');
            	$note = $this->common->getParameter('note', 'text');
            	$name = $this->common->getParameter('name', 'text');
            	$email_account = $this->common->getParameter('email_account', 'text');
            	$email_account_type = $this->common->getParameter('email_account_type', 'text');
            	$incoming_mail_server = $this->common->getParameter('incoming_mail_server', 'text');
            	$incoming_mail_server_username = $this->common->getParameter('incoming_mail_server_username', 'text');
            	$incoming_mail_server_password = $this->common->getParameter('incoming_mail_server_password', 'text');
            	$outgoing_mail_server = $this->common->getParameter('outgoing_mail_server', 'text');
            	$outgoing_mail_server_username = $this->common->getParameter('outgoing_mail_server_username', 'text');
            	$outgoing_mail_server_password = $this->common->getParameter('outgoing_mail_server_password', 'text');
            	
            	$posted = $_POST;
            	
            	if (empty($title))
            	{
	                $message = new Message();
	                
	                $message->page = 'accounts_add';
	                $message->content = $language['message_title_required'];
	                $message->type = 'PROBLEM';
	                $this->messenger->setMessage($message);
	                
	                $this->common->storePostedIntoSession('accounts_add', $posted, 'title');

	                header("Location: " . $this->config->item('base_url') . 'accounts/add');
	                die;
				}

            	if (empty($type))
            	{
	                $message = new Message();
	                
	                $message->page = 'accounts_add';
	                $message->content = $language['message_type_required'];
	                $message->type = 'PROBLEM';
	                $this->messenger->setMessage($message);
	                
	                $this->common->storePostedIntoSession('accounts_add', $posted, 'type');

	                header("Location: " . $this->config->item('base_url') . 'accounts/add');
	                die;
				}
				
				// server field required
				if (in_array($type, array(18, 19, 20, 21, 22, 23, 24)))
				{
            		if (empty($server))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_add';
		                $message->content = $language['message_server_required'];
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_add', $posted, 'server');

		                header("Location: " . $this->config->item('base_url') . 'accounts/add');
		                die;
					}
				}

				// username and password fields required
				if (in_array($type, array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50)))
				{
            		if (empty($username))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_add';
		                if (in_array($type, array(24)))
		                {
		                	$message->content = $language['message_access_key_required'];
						}
						else
						{
		                	$message->content = $language['message_username_required'];
						}
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_add', $posted, 'username');

		                header("Location: " . $this->config->item('base_url') . 'accounts/add');
		                die;
					}

            		if (empty($password))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_add';
		                if (in_array($type, array(24)))
		                {
		                	$message->content = $language['message_secret_required'];
						}
						else
						{
		                	$message->content = $language['message_password_required'];
						}
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_add', $posted, 'password');

		                header("Location: " . $this->config->item('base_url') . 'accounts/add');
		                die;
					}
				}
				
				// login_url field required
				if (in_array($type, array(5, 6, 7, 8, 9, 10, 11, 42, 45, 47, 48, 49)))
				{
            		if (empty($login_url))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_add';
		                $message->content = $language['message_login_url_required'];
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_add', $posted, 'login_url');

		                header("Location: " . $this->config->item('base_url') . 'accounts/add');
		                die;
					}
				}
				
				// name and host fields required
				if (in_array($type, array(12, 13)))
				{
            		if (empty($name))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_add';
		                $message->content = $language['message_name_required'];
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_add', $posted, 'name');

		                header("Location: " . $this->config->item('base_url') . 'accounts/add');
		                die;
					}

            		if (empty($host))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_add';
		                $message->content = $language['message_host_required'];
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_add', $posted, 'host');

		                header("Location: " . $this->config->item('base_url') . 'accounts/add');
		                die;
					}
				}

				// note field required
/*
				if (in_array($type, array(43)))
				{
            		if (empty($note))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_add';
		                $message->content = $language['message_note_required'];
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_add', $posted, 'note');

		                header("Location: " . $this->config->item('base_url') . 'accounts/add');
		                die;
					}
				}
*/				
				// pop3 / imap
				if (in_array($type, array(16)))
				{
            		if (empty($email_account))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_add';
		                $message->content = $language['message_email_required'];
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_add', $posted, 'email_account');

		                header("Location: " . $this->config->item('base_url') . 'accounts/add');
		                die;
					}
					
            		if (!$this->common->check_email_address($email_account))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_add';
		                $message->content = $language['message_email_format'];
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_add', $posted, 'email_account');

		                header("Location: " . $this->config->item('base_url') . 'accounts/add');
		                die;
					}

            		if (empty($email_account_type))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_add';
		                $message->content = $language['message_email_type_required'];
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_add', $posted, 'email_account_type');

		                header("Location: " . $this->config->item('base_url') . 'accounts/add');
		                die;
					}

            		if (empty($incoming_mail_server))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_add';
		                $message->content = $language['message_incoming_server_required'];
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_add', $posted, 'incoming_mail_server');

		                header("Location: " . $this->config->item('base_url') . 'accounts/add');
		                die;
					}

            		if (empty($incoming_mail_server_username))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_add';
		                $message->content = $language['message_incoming_username_required'];
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_add', $posted, 'incoming_mail_server_username');

		                header("Location: " . $this->config->item('base_url') . 'accounts/add');
		                die;
					}

            		if (empty($incoming_mail_server_password))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_add';
		                $message->content = $language['message_incoming_password_required'];
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_add', $posted, 'incoming_mail_server_password');

		                header("Location: " . $this->config->item('base_url') . 'accounts/add');
		                die;
					}

            		if (empty($outgoing_mail_server))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_add';
		                $message->content = $language['message_outgoing_server_required'];
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_add', $posted, 'outgoing_mail_server');

		                header("Location: " . $this->config->item('base_url') . 'accounts/add');
		                die;
					}

            		if (empty($outgoing_mail_server_username))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_add';
		                $message->content = $language['message_outgoing_username_required'];
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_add', $posted, 'outgoing_mail_server_username');

		                header("Location: " . $this->config->item('base_url') . 'accounts/add');
		                die;
					}

            		if (empty($outgoing_mail_server_password))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_add';
		                $message->content = $language['message_outgoing_password_required'];
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_add', $posted, 'outgoing_mail_server_password');

		                header("Location: " . $this->config->item('base_url') . 'accounts/add');
		                die;
					}
				}

				$account = new Account();
				
				$account->account_name = $title;
				$account->type_id = $type;
				
				if (!empty($client))
				{
					$account->client_id = $client;
				}
				
				if (!empty($server))
				{
					$account->server = $server;
				}

				if (!empty($host))
				{
					$account->server = $host;
				}

				if (!empty($username))
				{
					$account->username = $username;
				}

				if (!empty($password))
				{
					$account->password = $password;
				}

				if (!empty($port))
				{
					$account->port = $port;
				}

				if (!empty($passive_mode))
				{
					if ($passive_mode == "Yes")
					{
						$account->passive_mode = 1;
					}
					else
					{
						$account->passive_mode = 0;
					}
				}
				else
				{
					$account->passive_mode = 0;
				}
				
				if (!empty($root_url))
				{
					$account->root_url = $root_url;
				}
				
				if (!empty($remote_path))
				{
					$account->remote_path = $remote_path;
				}
				
				if (!empty($login_url))
				{
					$account->login_url = $login_url;
				}
				else
				{
					$account_type = $this->account_type->fetchBaseOne($type);
					
					$account->login_url = $account_type['account_type_login_url'];
				}

				if (!empty($note))
				{
					$account->note = $note;
				}

				if (!empty($name))
				{
					$account->name = $name;
				}

				if (!empty($email_account))
				{
					$account->email = $email_account;
				}

				if (!empty($email_account_type))
				{
					$account->email_type = $email_account_type;
				}

				if (!empty($incoming_mail_server))
				{
					$account->incoming_mail_server = $incoming_mail_server;
				}

				if (!empty($incoming_mail_server_username))
				{
					$account->incoming_username = $incoming_mail_server_username;
				}

				if (!empty($incoming_mail_server_password))
				{
					$account->incoming_password = $incoming_mail_server_password;
				}

				if (!empty($outgoing_mail_server))
				{
					$account->outgoing_mail_server = $outgoing_mail_server;
				}

				if (!empty($outgoing_mail_server_username))
				{
					$account->outgoing_username = $outgoing_mail_server_username;
				}

				if (!empty($outgoing_mail_server_password))
				{
					$account->outgoing_password = $outgoing_mail_server_password;
				}
				
				$result = $this->account->insert($account);
				
				if ($result > 0)
				{
		            $message = new Message();
		            
		            $message->page = 'accounts';
		            $message->content = $language['message_accounts_add_success'];
		            $message->type = 'SUCCESS';
		            $this->messenger->setMessage($message);

		            header("Location: " . $this->config->item('base_url') . 'accounts');
		            die;
				}
				else
				{
		            $message = new Message();
		            
		            $message->page = 'accounts_add';
		            $message->content = $language['message_error_during_process'];
		            $message->type = 'PROBLEM';
		            $this->messenger->setMessage($message);
		            
		            $this->common->storePostedIntoSession('accounts_add', $posted);

		            header("Location: " . $this->config->item('base_url') . 'accounts/add');
		            die;
				}
            break;
            
            case 'edit':
            	$account_id = $this->common->getParameter('account_id', 'int');
            	$title = $this->common->getParameter('title', 'text');
            	$client = $this->common->getParameter('client', 'text');
            	$type = $this->common->getParameter('type', 'text');
            	$server = $this->common->getParameter('server', 'text');
            	$host = $this->common->getParameter('host', 'text');
            	$username = $this->common->getParameter('username', 'text');
            	$password = $this->common->getParameter('password', 'text');
            	$port = $this->common->getParameter('port', 'text');
            	$passive_mode = $this->common->getParameter('passive_mode', 'int');
            	$root_url = $this->common->getParameter('root_url', 'text');
            	$remote_path = $this->common->getParameter('remote_path', 'text');
            	$login_url = $this->common->getParameter('login_url', 'text');
            	$note = $this->common->getParameter('note', 'text');
            	$name = $this->common->getParameter('name', 'text');
            	$email_account = $this->common->getParameter('email_account', 'text');
            	$email_account_type = $this->common->getParameter('email_account_type', 'text');
            	$incoming_mail_server = $this->common->getParameter('incoming_mail_server', 'text');
            	$incoming_mail_server_username = $this->common->getParameter('incoming_mail_server_username', 'text');
            	$incoming_mail_server_password = $this->common->getParameter('incoming_mail_server_password', 'text');
            	$outgoing_mail_server = $this->common->getParameter('outgoing_mail_server', 'text');
            	$outgoing_mail_server_username = $this->common->getParameter('outgoing_mail_server_username', 'text');
            	$outgoing_mail_server_password = $this->common->getParameter('outgoing_mail_server_password', 'text');
            	
            	$posted = $_POST;
            	
		        if (!$this->common->checkInteger($account_id))
		        {
	                $message = new Message();
	                
	                $message->page = 'accounts_edit';
	                $message->content = $language['message_field_not_integer'];
	                $message->type = 'PROBLEM';
	                $this->messenger->setMessage($message);
	                
	                header("Location: " . $this->config->item('base_url') . 'accounts/edit/' . $account_id);
	                die;
				}

            	if (empty($title))
            	{
	                $message = new Message();
	                
	                $message->page = 'accounts_edit';
	                $message->content = $language['message_title_required'];
	                $message->type = 'PROBLEM';
	                $this->messenger->setMessage($message);
	                
	                $this->common->storePostedIntoSession('accounts_edit', $posted, 'title');

	                header("Location: " . $this->config->item('base_url') . 'accounts/edit/' . $account_id);
	                die;
				}

            	if (empty($type))
            	{
	                $message = new Message();
	                
	                $message->page = 'accounts_edit';
	                $message->content = $language['message_type_required'];
	                $message->type = 'PROBLEM';
	                $this->messenger->setMessage($message);
	                
	                $this->common->storePostedIntoSession('accounts_edit', $posted, 'type');

	                header("Location: " . $this->config->item('base_url') . 'accounts/edit/' . $account_id);
	                die;
				}
				
				// server field required
				if (in_array($type, array(18, 19, 20, 21, 22, 23, 24)))
				{
            		if (empty($server))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_edit';
		                $message->content = $language['message_server_required'];
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_edit', $posted, 'server');

		                header("Location: " . $this->config->item('base_url') . 'accounts/edit/' . $account_id);
		                die;
					}
				}
            		
				// username and password fields required
				if (in_array($type, array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50)))
				{
            		if (empty($username))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_edit';
		                if (in_array($type, array(24)))
		                {
		                	$message->content = $language['message_access_key_required'];
						}
						else
						{
		                	$message->content = $language['message_username_required'];
						}
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_edit', $posted, 'username');

		                header("Location: " . $this->config->item('base_url') . 'accounts/edit/' . $account_id);
		                die;
					}

            		if (empty($password))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_edit';
		                if (in_array($type, array(24)))
		                {
		                	$message->content = $language['message_secret_required'];
						}
						else
						{
		                	$message->content = $language['message_password_required'];
						}
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_edit', $posted, 'password');

		                header("Location: " . $this->config->item('base_url') . 'accounts/edit/' . $account_id);
		                die;
					}
				}
				
				// login_url field required
				if (in_array($type, array(5, 6, 7, 8, 9, 10, 11, 42, 45, 47, 48, 49)))
				{
            		if (empty($login_url))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_edit';
		                $message->content = $language['message_login_url_required'];
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_edit', $posted, 'login_url');

		                header("Location: " . $this->config->item('base_url') . 'accounts/edit/' . $account_id);
		                die;
					}
				}
				
				// name and host fields required
				if (in_array($type, array(12, 13)))
				{
            		if (empty($name))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_edit';
		                $message->content = $language['message_name_required'];
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_edit', $posted, 'name');

		                header("Location: " . $this->config->item('base_url') . 'accounts/edit/' . $account_id);
		                die;
					}

            		if (empty($host))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_edit';
		                $message->content = $language['message_host_required'];
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_edit', $posted, 'host');

		                header("Location: " . $this->config->item('base_url') . 'accounts/edit/' . $account_id);
		                die;
					}
				}

				// note field required
/*
				if (in_array($type, array(43)))
				{
            		if (empty($note))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_edit';
		                $message->content = $language['message_note_required'];
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_edit', $posted, 'note');

		                header("Location: " . $this->config->item('base_url') . 'accounts/edit/' . $account_id);
		                die;
					}
				}
*/
				// pop3 / imap
				if (in_array($type, array(16)))
				{
            		if (empty($email_account))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_edit';
		                $message->content = $language['message_email_required'];
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_edit', $posted, 'email_account');

		                header("Location: " . $this->config->item('base_url') . 'accounts/edit/' . $account_id);
		                die;
					}
					
            		if (!$this->common->check_email_address($email_account))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_edit';
		                $message->content = $language['message_email_format'];
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_edit', $posted, 'email_account');

		                header("Location: " . $this->config->item('base_url') . 'accounts/edit/' . $account_id);
		                die;
					}

            		if (empty($email_account_type))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_edit';
		                $message->content = $language['message_email_type_required'];
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_edit', $posted, 'email_account_type');

		                header("Location: " . $this->config->item('base_url') . 'accounts/edit/' . $account_id);
		                die;
					}

            		if (empty($incoming_mail_server))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_edit';
		                $message->content = $language['message_incoming_server_required'];
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_edit', $posted, 'incoming_mail_server');

		                header("Location: " . $this->config->item('base_url') . 'accounts/edit/' . $account_id);
		                die;
					}

            		if (empty($incoming_mail_server_username))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_edit';
		                $message->content = $language['message_incoming_username_required'];
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_edit', $posted, 'incoming_mail_server_username');

		                header("Location: " . $this->config->item('base_url') . 'accounts/edit/' . $account_id);
		                die;
					}

            		if (empty($incoming_mail_server_password))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_edit';
		                $message->content = $language['message_incoming_password_required'];
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_edit', $posted, 'incoming_mail_server_password');

		                header("Location: " . $this->config->item('base_url') . 'accounts/edit/' . $account_id);
		                die;
					}

            		if (empty($outgoing_mail_server))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_edit';
		                $message->content = $language['message_outgoing_server_required'];
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_edit', $posted, 'outgoing_mail_server');

		                header("Location: " . $this->config->item('base_url') . 'accounts/edit/' . $account_id);
		                die;
					}

            		if (empty($outgoing_mail_server_username))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_edit';
		                $message->content = $language['message_outgoing_username_required'];
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_edit', $posted, 'outgoing_mail_server_username');

		                header("Location: " . $this->config->item('base_url') . 'accounts/edit/' . $account_id);
		                die;
					}

            		if (empty($outgoing_mail_server_password))
            		{
		                $message = new Message();
		                
		                $message->page = 'accounts_edit';
		                $message->content = $language['message_outgoing_password_required'];
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('accounts_edit', $posted, 'outgoing_mail_server_password');

		                header("Location: " . $this->config->item('base_url') . 'accounts/edit/' . $account_id);
		                die;
					}
				}

				$account = new Account();
				
				$account->account_id = $account_id;
				$account->account_name = $title;
				$account->type_id = $type;
				$account->client_id = $client;
				
				$account->server = $server;
			
				$account->server = $host;

				$account->username = $username;

				$account->password = $password;

				$account->port = $port;

				if (!empty($passive_mode))
				{
					if ($passive_mode == "Yes")
					{
						$account->passive_mode = 1;
					}
					else
					{
						$account->passive_mode = 0;
					}
				}
				else
				{
					$account->passive_mode = 0;
				}
				
				$account->root_url = $root_url;
			
				$account->remote_path = $remote_path;
				
				if (!empty($login_url))
				{
					$account->login_url = $login_url;
				}
				else
				{
					$account_type = $this->account_type->fetchBaseOne($type);
					
					$account->login_url = $account_type['account_type_login_url'];
				}

				$account->note = $note;

				$account->name = $name;

				$account->email = $email_account;

				$account->email_type = $email_account_type;

				$account->incoming_mail_server = $incoming_mail_server;

				$account->incoming_username = $incoming_mail_server_username;

				$account->incoming_password = $incoming_mail_server_password;

				$account->outgoing_mail_server = $outgoing_mail_server;

				$account->outgoing_username = $outgoing_mail_server_username;

				$account->outgoing_password = $outgoing_mail_server_password;

				$result = $this->account->update($account);
				
				if ($result)
				{
		            $message = new Message();
		            
		            $message->page = 'accounts';
		            $message->content = $language['message_accounts_edit_success'];
		            $message->type = 'SUCCESS';
		            $this->messenger->setMessage($message);

		            header("Location: " . $this->config->item('base_url') . 'accounts');
		            die;
				}
				else
				{
		            $message = new Message();
		            
		            $message->page = 'accounts_edit';
		            $message->content = $language['message_error_during_process'];
		            $message->type = 'PROBLEM';
		            $this->messenger->setMessage($message);
		            
		            $this->common->storePostedIntoSession('accounts_edit', $posted);

		            header("Location: " . $this->config->item('base_url') . 'accounts/edit/' . $account_id);
		            die;
				}
            break;
            
            case 'delete':
            	$account_id = htmlspecialchars($this->uri->segment(4 + $this->config->item('segment_addition')));
            	
            	if (isset($account_id))
            	{
            		if ($this->common->checkInteger($account_id))
            		{
            			// fetch account
            			$account = $this->account->fetchOne($account_id);

            			$canDelete = $this->account->canDelete($user, $account);
            			
            			if (!$canDelete)
            			{
            				show_404('page');
            				die;
						}
						
            			$result = $this->account->remove($account_id);
            			
            			if ($result > 0)
            			{
				            $deletedLinks = $this->group_account->removeAllForAccountID($account_id);
				            
				            $message = new Message();
				            
				            $message->page = 'accounts';
				            $message->content = $language['message_account_successfully_deleted'];
				            $message->type = 'SUCCESS';
				            $this->messenger->setMessage($message);
				            
				            header("Location: " . $this->config->item('base_url') . 'accounts');
				            die;
						}
						else
						{
				            $message = new Message();
				            
				            $message->page = 'accounts';
				            $message->content = $language['message_error_during_process'];
				            $message->type = 'PROBLEM';
				            $this->messenger->setMessage($message);
				            
				            header("Location: " . $this->config->item('base_url') . 'accounts');
				            die;
						}
					}
					else
					{
				        $message = new Message();
				        
				        $message->page = 'accounts';
				        $message->content = $language['message_field_not_integer'];
				        $message->type = 'PROBLEM';
				        $this->messenger->setMessage($message);
				        
				        header("Location: " . $this->config->item('base_url') . 'accounts');
				        die;
					}
				}
				else
				{
				    $message = new Message();
				    
				    $message->page = 'accounts';
				    $message->content = $language['message_account_required'];
				    $message->type = 'PROBLEM';
				    $this->messenger->setMessage($message);
				    
				    header("Location: " . $this->config->item('base_url') . 'accounts');
				    die;
				}
            break;
		}
	}
	
	function sendaccount()
	{
        # init session
        $this->common->init();
        
        $language = $this->multilanguage->loadLanguage();

        $result = array();

        $email = $this->common->getParameter('email', 'text');
        $send_id = $this->common->getParameter('send_id', 'int');
        
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
			
			echo json_encode($result);
			die;
		}

        if (empty($email))
        {
			$result['success'] = false;
			$result['data'] = null;
			$result['code'] = -100;
			$result['description'] = $language['message_email_required'];
			
			echo json_encode($result);
			die;
		}
		
		if (!$this->common->check_email_address($email))
		{
			$result['success'] = false;
			$result['data'] = null;
			$result['code'] = -100;
			$result['description'] = $language['message_email_format'];
			
			echo json_encode($result);
			die;
		}

		if (empty($send_id))
		{
			$result['success'] = false;
			$result['data'] = null;
			$result['code'] = -100;
			$result['description'] = $language['message_account_required'];
			
			echo json_encode($result);
			die;
		}
        
        if (!$this->common->checkInteger($send_id))
        {
			$result['success'] = false;
			$result['data'] = null;
			$result['code'] = -100;
			$result['description'] = $language['message_field_not_integer'];
			
			echo json_encode($result);
			die;
		}
        
		$account = $this->account->fetchOne($send_id);
		
        if ($account == null)
        {
			$result['success'] = false;
			$result['data'] = null;
			$result['code'] = -100;
			$result['description'] = $language['message_account_not_found'];
			
			echo json_encode($result);
			die;
		}
        
        $set = $this->settings->fetchBaseAll();
        $settings = $this->settings->transformToArray($set);
        
        $sendingUser = $this->user->fetchOneByEmailAddress($email);

        $this->smarty_wrapper->assign('lang', $language);
        $this->smarty_wrapper->assign('account', $account);
        $this->smarty_wrapper->assign('user', $sendingUser);
        $this->smarty_wrapper->assign('editingUser', $user);

		switch ($account['type_id'])
		{
			case '2':
			case '3':
			case '4':
			case '5':
			case '6':
			case '7':
			case '8':
			case '9':
			case '10':
			case '11':
			case '14':
			case '15':
			case '17':
			case '29':
			case '30':
			case '31':
			case '32':
			case '33':
			case '34':
			case '35':
			case '36':
			case '37':
			case '38':
			case '39':
			case '40':
			case '41':
			case '42':
			case '45':
			case '46':
            case '47':
            case '48':
            case '49':
        		$sending = $this->smarty_wrapper->fetch("email/accounts_email_email-social-cms_template.tpl");
			break;
			case '12':
			case '13':
        		$sending = $this->smarty_wrapper->fetch("email/accounts_email_sql_template.tpl");
			break;
			case '18':
			case '19':
			case '20':
			case '21':
			case '22':
			case '23':
        		$sending = $this->smarty_wrapper->fetch("email/accounts_email_ftp_template.tpl");
			break;
			case '24':
        		$sending = $this->smarty_wrapper->fetch("email/accounts_email_amazon-s3_template.tpl");
			break;
			case '25':
        		$sending = $this->smarty_wrapper->fetch("email/accounts_email_icq_template.tpl");
			break;
			case '26':
			case '27':
			case '28':
        		$sending = $this->smarty_wrapper->fetch("email/accounts_email_skype_template.tpl");
			break;
			case '16':
        		$sending = $this->smarty_wrapper->fetch("email/accounts_email_pop3-imap_template.tpl");
			break;
			case '43':
        		$sending = $this->smarty_wrapper->fetch("email/accounts_email_ssh_template.tpl");
			break;
			case '44':
        		$sending = $this->smarty_wrapper->fetch("email/accounts_email_ichat_template.tpl");
			break;
            case '50':
                $sending = $this->smarty_wrapper->fetch("email/accounts_email_customtype_template.tpl");
            break;
		}
		
        # load email parameters
        $config = $this->settings->loadEmailParameters();
        
        # send email
        error_reporting(0);
        
	    # initialize email settings
	    $this->email->initialize($config);
	    
        if ($settings['MAIL_NAME'] != '')
        {
	        $this->email->from($settings['MAIL_FROM'], $settings['MAIL_NAME']);
        }
        else
        {
            $this->email->from($settings['MAIL_FROM'], 'Tefter');
        }
	    $this->email->to($email);
	    
	    $this->email->subject($account['account_name'] . ' ' . $language['subject_mail_send_account_details']);
	    $this->email->message($sending);

        $sent = $this->email->send();
        
        error_reporting(E_ALL);

        if ($sent)
        {
			$result['success'] = true;
			$result['data'] = null;
			$result['code'] = 200;
			$result['description'] = $language['message_account_details_sent'];
			
			echo json_encode($result);
			die;
        }
        else
        {
			$result['success'] = false;
			$result['data'] = null;
			$result['code'] = -100;
			$result['description'] = $language['message_error_during_process'];
			
			echo json_encode($result);
			die;
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
        $typeFilter = $this->common->getParameter('typeFilter', 'int');
        $clientFilter = $this->common->getParameter('clientFilter', 'int');
        $forGroup = $this->common->getParameter('forGroup', 'text');
        $forGroupID = $this->common->getParameter('forGroupID', 'int');
        $withClient = $this->common->getParameter('withClient', 'text');
        
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
			$result['totalAccounts'] = 0;
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
			$result['totalAccounts'] = 0;
			$result['limit_from'] = $limit_from;
			$result['limit_to'] = $limit_to;
			
			echo json_encode($result);
			die;
		}
		
		// define sorting
		$sort = 'title';
		$direction = 'ASC';

		if ($letterFilter == '')
		{
			$sort = 'date';
			$direction = 'DESC';
		}

		if ($forGroupID != '0')
		{
			$accounts = $this->account->fetchAll($limit_from, $limit_to - $limit_from, $letterFilter, $keyword, $typeFilter, $clientFilter, $direction, $sort, $forGroup == 'yes', $forGroupID);
		}
		else
		{
			$accounts = $this->account->fetchAll($limit_from, $limit_to - $limit_from, $letterFilter, $keyword, $typeFilter, $clientFilter, $direction, $sort, $forGroup == 'yes', null);
		}

		if ($forGroupID != '0')
		{
			$totalAccounts = $this->account->countAll($letterFilter, $keyword, $typeFilter, $clientFilter, $forGroup == 'yes', $forGroupID);
		}
		else
		{
			$totalAccounts = $this->account->countAll($letterFilter, $keyword, $typeFilter, $clientFilter, $forGroup == 'yes', null);
		}

		$this->smarty_wrapper->assign('accounts', $accounts);
		$this->smarty_wrapper->assign('lang', $language);
		$this->smarty_wrapper->assign('user', $user);
		$this->smarty_wrapper->assign('withClient', $withClient);
		
        $data = $this->smarty_wrapper->fetch("accounts/accounts_table_template.tpl");
        
		$result['success'] = true;
		if ($totalAccounts == 0)
		{
			$result['data'] = '<tr><td colspan="5">' . $language['message_no_matches_found'] . '</td></tr>';
		}
		else
		{
			$result['data'] = $data;
		}
		$result['code'] = 200;
		$result['description'] = 'OK';
		$result['total'] = count($accounts);
		$result['totalAccounts'] = $totalAccounts;
		$result['limit_from'] = $limit_from;
		$result['limit_to'] = $limit_to;
		
		echo json_encode($result);
	}
	
	function data()
	{
        # init session
        $this->common->init();
        
        $language = $this->multilanguage->loadLanguage();

        $result = array();

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
			
			echo json_encode($result);
			die;
		}
		
        $id = $this->common->getParameter('id', 'int');
        
        if (!$this->common->checkInteger($id))
        {
            $message = new Message();
            
            $message->page = 'login';
            $message->content = $language['message_field_not_integer'];
            $message->type = 'PROBLEM';
            $this->messenger->setMessage($message);

			$result['success'] = false;
			$result['data'] = null;
			$result['code'] = -200;
			$result['description'] = $language['message_field_not_integer'];
			
			echo json_encode($result);
			die;
		}
        
		$account = $this->account->fetchOne($id);
		
		$this->smarty_wrapper->assign('account', $account);
		$this->smarty_wrapper->assign('lang', $language);
		
		switch ($account['type_id'])
		{
			case '2':
			case '3':
			case '4':
			case '5':
			case '6':
			case '7':
			case '8':
			case '9':
			case '10':
			case '11':
			case '14':
			case '15':
			case '17':
			case '29':
			case '30':
			case '31':
			case '32':
			case '33':
			case '34':
			case '35':
			case '36':
			case '37':
			case '38':
			case '39':
			case '40':
			case '41':
			case '42':
			case '45':
			case '46':
            case '47':
            case '48':
            case '49':
        		$data = $this->smarty_wrapper->fetch("accounts/views/accounts_data_email-social-cms_template.tpl");
			break;
			case '12':
			case '13':
        		$data = $this->smarty_wrapper->fetch("accounts/views/accounts_data_sql_template.tpl");
			break;
			case '18':
			case '19':
			case '20':
			case '21':
			case '22':
			case '23':
        		$data = $this->smarty_wrapper->fetch("accounts/views/accounts_data_ftp_template.tpl");
			break;
			case '24':
        		$data = $this->smarty_wrapper->fetch("accounts/views/accounts_data_amazon-s3_template.tpl");
			break;
			case '25':
        		$data = $this->smarty_wrapper->fetch("accounts/views/accounts_data_icq_template.tpl");
			break;
			case '26':
			case '27':
			case '28':
        		$data = $this->smarty_wrapper->fetch("accounts/views/accounts_data_skype_template.tpl");
			break;
			case '16':
        		$data = $this->smarty_wrapper->fetch("accounts/views/accounts_data_pop3-imap_template.tpl");
			break;
			case '43':
        		$data = $this->smarty_wrapper->fetch("accounts/views/accounts_data_ssh_template.tpl");
			break;
			case '44':
        		$data = $this->smarty_wrapper->fetch("accounts/views/accounts_data_ichat_template.tpl");
			break;
            case '50':
                $data = $this->smarty_wrapper->fetch("accounts/views/accounts_data_customtype_template.tpl");
            break;
		}
		
		$result['success'] = true;
		$result['data'] = $data;
		$result['code'] = 200;
		$result['description'] = 'OK';
		
		echo json_encode($result);
	}
	
	function typetemplate()
	{
        # init session
        $this->common->init();
        
        $language = $this->multilanguage->loadLanguage();

        $result = array();

        $id = $this->common->getParameter('id', 'int');
        $mode = $this->common->getParameter('mode', 'text');
        $account_id = $this->common->getParameter('account_id', 'int');

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
			
			echo json_encode($result);
			die;
		}
		
        if (!$this->common->checkInteger($id))
        {
            $message = new Message();
            
            $message->page = 'login';
            $message->content = $language['message_field_not_integer'];
            $message->type = 'PROBLEM';
            $this->messenger->setMessage($message);

			$result['success'] = false;
			$result['data'] = null;
			$result['code'] = -200;
			$result['description'] = $language['message_field_not_integer'];
			
			echo json_encode($result);
			die;
		}
		
        if (!$this->common->checkInteger($account_id))
        {
            $message = new Message();
            
            $message->page = 'login';
            $message->content = $language['message_field_not_integer'];
            $message->type = 'PROBLEM';
            $this->messenger->setMessage($message);

			$result['success'] = false;
			$result['data'] = null;
			$result['code'] = -200;
			$result['description'] = $language['message_field_not_integer'];
			
			echo json_encode($result);
			die;
		}
		
		$account_type = $this->account_type->fetchBaseOne($id);
		
        $posted = $this->common->getPostedFromSession('accounts_add');
		
		if ($account_id != 0)
		{
			$account = $this->account->fetchOne($account_id);
			$this->smarty_wrapper->assign('account', $account);
		}

		$this->smarty_wrapper->assign('account_type', $account_type);
		$this->smarty_wrapper->assign('lang', $language);
		$this->smarty_wrapper->assign('mode', $mode);
		$this->smarty_wrapper->assign('posted', $posted);
		
        $data = $this->smarty_wrapper->fetch("accounts/types/" . $account_type['account_type_template_name']);
		
        $this->common->removePostedFromSession('accounts_add');

		$result['success'] = true;
		$result['data'] = $data;
		$result['code'] = 200;
		$result['description'] = 'OK';
		
		echo json_encode($result);
	}
}
?>