<?php
class Users extends Controller {

    function Users()
    {
        parent::Controller();    
        
        $this->load->library('common');
        $this->load->library('Smarty_Wrapper');
        $this->load->library('user');
        $this->load->library('messenger');
        $this->load->library('IMs');
        $this->load->library('email');
        $this->load->library('client');
        $this->load->library('User_Role');
        $this->load->library('User_Client');
        $this->load->library('User_Group');
        
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
        
        $user_roles = $this->user_role->fetchBaseAll();
        
        if ($user['level'] == 99)
        {
        	$users = $this->user->fetchAll(0, 25, 'ASC', 'name', null, null, null);
		}
		else
		{
        	$users = $this->user->fetchAll(0, 25, 'ASC', 'name', null, null, null, $user['user_id']);
		}
        
        $m = $this->messenger->getMessage('users');
        
        $this->smarty_wrapper->assign('title', 'Users');
        $this->smarty_wrapper->assign('title_fixed', $this->config->item('title_fixed'));
        $this->smarty_wrapper->assign('m', $m);
        $this->smarty_wrapper->assign('lang', $language);
        $this->smarty_wrapper->assign('settings', $settings);
        $this->smarty_wrapper->assign('user', $user);
        $this->smarty_wrapper->assign('user_roles', $user_roles);
        $this->smarty_wrapper->assign('selectMenuItem', 'users');

        if (count($users) > 0)
        {
        	$this->smarty_wrapper->assign('hasCount', true);
		}
		else
		{
			$this->smarty_wrapper->assign('hasCount', false);
		}

        $this->smarty_wrapper->display("users/users.tpl");
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
        
        $user_roles = $this->user_role->fetchBaseAll();
        $ims = $this->ims->fetchAll();

        if ($user['level'] < 99)
        {
        	$clients = $this->client->fetchAll(0, 999999, 'ASC', 'name', null, null, $user['user_id']);
		}
		else
		{
        	$clients = $this->client->fetchAll(0, 999999, 'ASC', 'name', null, null);
		}
        
        $posted = $this->common->getPostedFromSession('users_add');

        $m = $this->messenger->getMessage('users_add');
        
        $this->smarty_wrapper->assign('title', 'Add User');
        $this->smarty_wrapper->assign('title_fixed', $this->config->item('title_fixed'));
        $this->smarty_wrapper->assign('m', $m);
        $this->smarty_wrapper->assign('lang', $language);
        $this->smarty_wrapper->assign('posted', $posted);
        $this->smarty_wrapper->assign('settings', $settings);
        $this->smarty_wrapper->assign('user', $user);
        $this->smarty_wrapper->assign('user_roles', $user_roles);
        $this->smarty_wrapper->assign('ims', $ims);
        $this->smarty_wrapper->assign('clients', $clients);
        $this->smarty_wrapper->assign('selectMenuItem', 'users');

        $this->common->removePostedFromSession('users_add');

        $this->smarty_wrapper->display("users/users_add.tpl");
	}
	
	function edit()
	{
        # init session
        $this->common->init();

        $language = $this->multilanguage->loadLanguage();
        
        $id = htmlspecialchars($this->uri->segment(3 + $this->config->item('segment_addition'), ''));

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
        
        if ($id == '')
        {
			$userEditObject = $user;
        	$this->smarty_wrapper->assign('mode', 'profile');
		}
        else
        {
	        if (!$this->common->checkInteger($id))
	        {
		        $message = new Message();
		        
		        $message->page = 'users';
		        $message->content = $language['message_field_not_integer'];
		        $message->type = 'PROBLEM';
		        $this->messenger->setMessage($message);

		        header("Location: " . $this->config->item('base_url') . 'users');
		        die;
			}

        	$userEditObject = $this->user->fetchOne($id);
        	$this->smarty_wrapper->assign('mode', 'edit');
		}
		
		if ($userEditObject == null)
		{
            $message = new Message();
            
            $message->page = 'users';
            $message->content = $language['message_user_not_found'];
            $message->type = 'NOTICE';
            $this->messenger->setMessage($message);

            header("Location: " . $this->config->item('base_url') . 'users');
            die;
		}

        if (!$this->user->isMinimumLevel($user, 20))
        {
        	show_404('page');
        	die;
		}
        
        if ($user == null)
        {
            $message = new Message();
            
            $message->page = 'login';
            $message->content = $language['message_login_required'];
            $message->type = 'NOTICE';
            $this->messenger->setMessage($message);

            header("Location: " . $this->config->item('base_url') . 'login');
            die;
		}
		
		# check if user can edit or delete object
		$canEdit = $this->user->canEdit($user, $userEditObject);
		$canDelete = $this->user->canDelete($user, $userEditObject);
		
		if (!$canEdit)
		{
			show_404('page');
            die;
		}
        
        $set = $this->settings->fetchBaseAll();
        $settings = $this->settings->transformToArray($set);
        
        $user_roles = $this->user_role->fetchBaseAll();
        $ims = $this->ims->fetchAll();

        if ($user['level'] < 99)
        {
        	$clients = $this->client->fetchAll(0, 999999, 'ASC', 'name', null, null, $user['user_id']);
		}
		else
		{
        	$clients = $this->client->fetchAll(0, 999999, 'ASC', 'name', null, null);
		}

		$usersClients = $this->user_client->fetchAll($userEditObject['user_id']);
		
        $sortedClients = array();
        foreach ($usersClients as $item)
        {
            array_push($sortedClients, $item['client_id']);
        }
        
        $posted = $this->common->getPostedFromSession('users_edit');

        $m = $this->messenger->getMessage('users_edit');
        
        $this->smarty_wrapper->assign('title', 'Edit User');
        $this->smarty_wrapper->assign('title_fixed', $this->config->item('title_fixed'));
        $this->smarty_wrapper->assign('m', $m);
        $this->smarty_wrapper->assign('lang', $language);
        $this->smarty_wrapper->assign('posted', $posted);
        $this->smarty_wrapper->assign('settings', $settings);
        $this->smarty_wrapper->assign('user', $user);
        $this->smarty_wrapper->assign('userEditObject', $userEditObject);
        $this->smarty_wrapper->assign('user_roles', $user_roles);
        $this->smarty_wrapper->assign('ims', $ims);
        $this->smarty_wrapper->assign('clients', $clients);
        $this->smarty_wrapper->assign('usersClients', $usersClients);
        $this->smarty_wrapper->assign('sortedClients', $sortedClients);
        $this->smarty_wrapper->assign('canDelete', $canDelete);
        $this->smarty_wrapper->assign('selectMenuItem', 'users');

        $this->common->removePostedFromSession('users_edit');

        $this->smarty_wrapper->display("users/users_edit.tpl");
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

        if (!$this->user->isMinimumLevel($user, 20))
        {
        	show_404('page');
        	die;
		}

        $set = $this->settings->fetchBaseAll();
        $settings = $this->settings->transformToArray($set);

        switch (htmlspecialchars($this->uri->segment(3 + $this->config->item('segment_addition'))))
        {
            case 'add':
            	$name_first = $this->common->getParameter('user_first_name', 'text');
            	$name_last = $this->common->getParameter('user_last_name', 'text');
            	$email = $this->common->getParameter('user_email', 'text');
            	$role_id = $this->common->getParameter('user_role', 'int');
            	$phone_mobile = $this->common->getParameter('user_mobile', 'text');
            	$phone_office = $this->common->getParameter('user_office_phone', 'text');
            	$phone_ext = $this->common->getParameter('user_ext', 'text');
            	$im_contact = $this->common->getParameter('user_im', 'text');
            	$im_id = $this->common->getParameter('user_im_client', 'text');
            	
            	$posted = $_POST;
            	
		        if (!$this->user->isMinimumLevel($user, 99))
		        {
        			show_404('page');
        			die;
				}
            	
            	if (empty($name_first))
            	{
	                $message = new Message();
	                
	                $message->page = 'users_add';
	                $message->content = $language['message_first_name_required'];
	                $message->type = 'PROBLEM';
	                $this->messenger->setMessage($message);
	                
	                $this->common->storePostedIntoSession('users_add', $posted, 'user_first_name');

	                header("Location: " . $this->config->item('base_url') . 'users/add');
	                die;
				}
            	
            	if (empty($name_last))
            	{
	                $message = new Message();
	                
	                $message->page = 'users_add';
	                $message->content = $language['message_last_name_required'];
	                $message->type = 'PROBLEM';
	                $this->messenger->setMessage($message);
	                
	                $this->common->storePostedIntoSession('users_add', $posted, 'user_last_name');

	                header("Location: " . $this->config->item('base_url') . 'users/add');
	                die;
				}

            	if (empty($email))
            	{
	                $message = new Message();
	                
	                $message->page = 'users_add';
	                $message->content = $language['message_email_required'];
	                $message->type = 'PROBLEM';
	                $this->messenger->setMessage($message);
	                
	                $this->common->storePostedIntoSession('users_add', $posted, 'user_email');

	                header("Location: " . $this->config->item('base_url') . 'users/add');
	                die;
				}

            	if (!$this->common->check_email_address($email))
            	{
		            $message = new Message();
		            
		            $message->page = 'users_add';
		            $message->content = $language['message_email_format'];
		            $message->type = 'PROBLEM';
		            $this->messenger->setMessage($message);
		            
		            $this->common->storePostedIntoSession('users_add', $posted, 'user_email');

		            header("Location: " . $this->config->item('base_url') . 'users/add');
		            die;
				}

            	if ($this->user->isEmailExist($email))
            	{
		            $message = new Message();
		            
		            $message->page = 'users_add';
		            $message->content = $language['message_email_exist'];
		            $message->type = 'PROBLEM';
		            $this->messenger->setMessage($message);
		            
		            $this->common->storePostedIntoSession('users_add', $posted, 'user_email');

		            header("Location: " . $this->config->item('base_url') . 'users/add');
		            die;
				}

            	if (empty($role_id))
            	{
	                $message = new Message();
	                
	                $message->page = 'users_add';
	                $message->content = $language['message_role_required'];
	                $message->type = 'PROBLEM';
	                $this->messenger->setMessage($message);
	                
	                $this->common->storePostedIntoSession('users_add', $posted, 'user_role');

	                header("Location: " . $this->config->item('base_url') . 'users/add');
	                die;
				}

            	# count clients for insert only if it is team leader
            	if ($role_id == 2)
            	{
	                $clients_array = $this->client->fetchBaseAll();
	                $clientsForInsert = array();

	                foreach ($clients_array as $off)
	                {
	                    if (isset($_POST['client_id' . $off['client_id']]))
	                    {
	                        array_push($clientsForInsert, $off['client_id']);
	                    }
	                }
	                
	                if (count($clientsForInsert) == 0)
	                {
	                    $message = new Message();
	                    
	                    $message->page = 'users_add';
	                    $message->content = $language['message_at_least_one_client'];
	                    $message->type = 'PROBLEM';
	                    $this->messenger->setMessage($message);
	                    
	                    $this->common->storePostedIntoSession('users_add', $posted, 'user_role');

	                    header("Location: " . $this->config->item('base_url') . 'users/add');
	                    die;
	                }
				}
				
				$user_role = $this->user_role->fetchBaseOne($role_id);
				$randomPass = $this->common->getRandomPassword(10);
				
				$newUser = new User();
				
				$newUser->name_first = $name_first;
				$newUser->name_last = $name_last;
				$newUser->email = $email;
				$newUser->password = $randomPass;

				# 0 - no need for password change, 1 - need password change, 2 - requested change
				$newUser->password_change = 1;

				$newUser->role_id = $role_id;
				$newUser->level = $user_role['level'];
				
				if (!empty($phone_mobile))
				{
					$newUser->phone_mobile = $phone_mobile;
				}
				
				if (!empty($phone_office))
				{
					$newUser->phone_office = $phone_office;
				}
				
				if (!empty($phone_ext))
				{
					$newUser->phone_ext = $phone_ext;
				}
				
				if (!empty($im_contact))
				{
					$newUser->im_contact = $im_contact;
					$newUser->im_id = $im_id;
				}
				
				$newUser->first_admin = 0;
				$newUser->created_by_user_id = $user['user_id'];
				$newUser->accpassword = $randomPass;
				
				$inserted = $this->user->insert($newUser);
				
				if ($inserted > 0)
				{
                    if ($role_id == 2)
                    {
	                    foreach ($clientsForInsert as $key => $value)
	                    {
	                        $newUserClient = new User_Client();
	                        
	                        $newUserClient->user_id = $inserted;
	                        $newUserClient->client_id = $value;
	                        
	                        $resultNewUserClient = $this->user_client->insert($newUserClient);
	                    }
					}

	                # fetch mail template
	                $this->smarty_wrapper->assign('user', $newUser);
	                $this->smarty_wrapper->assign('editingUser', $user);
	                $this->smarty_wrapper->assign('lang', $language);
	                $this->smarty_wrapper->assign('password', $randomPass);
	                $sending = $this->smarty_wrapper->fetch("email/users_new_user_mail.tpl");
	                
	                # load email parameters
	                $config = $this->settings->loadEmailParameters();
	                
	                # initialize email settings
	                $this->email->initialize($config);
	                
                    $tempmailname = $this->settings->getValueForKey($set, 'MAIL_NAME');
                    if ($tempmailname != '')
                    {
	                    $this->email->from($this->settings->getValueForKey($set, 'MAIL_FROM'), $this->settings->getValueForKey($set, 'MAIL_NAME'));
                    }
                    else
                    {
                        $this->email->from($this->settings->getValueForKey($set, 'MAIL_FROM'), 'Tefter');
                    }
	                $this->email->to($newUser->email);
	                
	                $this->email->subject($language['subject_mail_new_user']);
	                $this->email->message($sending);

	                # send email
	                error_reporting(0);
	                
	                $result = $this->email->send();
		            
	                error_reporting(E_ALL);

		            if ($result)
		            {
			            // everything OK
			            $message = new Message();
			            
			            $message->page = 'users';
			            $message->content = $language['message_users_add_success'];
			            $message->type = 'SUCCESS';
			            $this->messenger->setMessage($message);

			            header("Location: " . $this->config->item('base_url') . 'users');
			            die;
					}
					else
					{
						# inserted, but not sent
			            $message = new Message();
			            
			            $message->page = 'users';
			            $message->content = $language['message_users_add_success_not_sent'];
			            $message->type = 'NOTICE';
			            $this->messenger->setMessage($message);

			            header("Location: " . $this->config->item('base_url') . 'users');
			            die;
					}
				}
				else
				{
					# not inserted
			        $message = new Message();
			        
			        $message->page = 'users_add';
			        $message->content = $language['message_error_during_process'];
			        $message->type = 'PROBLEM';
			        $this->messenger->setMessage($message);

	                $this->common->storePostedIntoSession('users_add', $posted, 'user_first_name');

			        header("Location: " . $this->config->item('base_url') . 'users/add');
			        die;
				}
            break;
            
            case 'edit':
            	$name_first = $this->common->getParameter('user_first_name', 'text');
            	$name_last = $this->common->getParameter('user_last_name', 'text');
            	$new_password = $this->common->getParameter('password', 'text');
            	$confirm_new_password = $this->common->getParameter('confirm_password', 'text');
            	$email = $this->common->getParameter('user_email', 'text');
            	$role_id = $this->common->getParameter('user_role', 'int');
            	$phone_mobile = $this->common->getParameter('user_mobile', 'text');
            	$phone_office = $this->common->getParameter('user_office_phone', 'text');
            	$phone_ext = $this->common->getParameter('user_ext', 'text');
            	$im_contact = $this->common->getParameter('user_im', 'text');
            	$im_id = $this->common->getParameter('user_im_client', 'text');
            	$user_id = $this->common->getParameter('user_id', 'int');
            	$mode = $this->common->getParameter('mode', 'text');
            	
            	$posted = $_POST;
            	
		        if (!$this->common->checkInteger($user_id))
		        {
			        $message = new Message();
			        
			        $message->page = 'users_edit';
			        $message->content = $language['message_field_not_integer'];
			        $message->type = 'PROBLEM';
			        $this->messenger->setMessage($message);

			        header("Location: " . $this->config->item('base_url') . 'users/edit/' . $user_id);
			        die;
				}

            	$userEdit = $this->user->fetchOne($user_id);
            	$canEdit = $this->user->canEdit($user, $userEdit);
            	
		        if (!$canEdit)
		        {
					show_404('page');
		            die;
				}
				
            	if (empty($name_first))
            	{
	                $message = new Message();
	                
	                $message->page = 'users_edit';
	                $message->content = $language['message_first_name_required'];
	                $message->type = 'PROBLEM';
	                $this->messenger->setMessage($message);
	                
	                $this->common->storePostedIntoSession('users_edit', $posted, 'user_first_name');

	                if ($mode == 'profile')
	                {
	                	header("Location: " . $this->config->item('base_url') . 'users/profile');
					}
					else
					{
	                	header("Location: " . $this->config->item('base_url') . 'users/edit/' . $user_id);
					}
	                die;
				}
            	
            	if (empty($name_last))
            	{
	                $message = new Message();
	                
	                $message->page = 'users_edit';
	                $message->content = $language['message_last_name_required'];
	                $message->type = 'PROBLEM';
	                $this->messenger->setMessage($message);
	                
	                $this->common->storePostedIntoSession('users_edit', $posted, 'user_last_name');

	                if ($mode == 'profile')
	                {
	                	header("Location: " . $this->config->item('base_url') . 'users/profile');
					}
					else
					{
	                	header("Location: " . $this->config->item('base_url') . 'users/edit/' . $user_id);
					}
	                die;
				}

				if (!empty($new_password))
				{
            		if (empty($confirm_new_password))
            		{
		                $message = new Message();
		                
		                $message->page = 'users_edit';
		                $message->content = $language['message_confirm_password_required'];
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('users_edit', $posted, 'user_last_name');

		                if ($mode == 'profile')
		                {
	                		header("Location: " . $this->config->item('base_url') . 'users/profile');
						}
						else
						{
	                		header("Location: " . $this->config->item('base_url') . 'users/edit/' . $user_id);
						}
		                die;
					}
					
					if ($new_password != $confirm_new_password)
					{
		                $message = new Message();
		                
		                $message->page = 'users_edit';
		                $message->content = $language['message_passwords_do_not_match'];
		                $message->type = 'PROBLEM';
		                $this->messenger->setMessage($message);
		                
		                $this->common->storePostedIntoSession('users_edit', $posted, 'user_last_name');

		                if ($mode == 'profile')
		                {
	                		header("Location: " . $this->config->item('base_url') . 'users/profile');
						}
						else
						{
	                		header("Location: " . $this->config->item('base_url') . 'users/edit/' . $user_id);
						}
		                die;
					}
				}
				
            	if (empty($email))
            	{
	                $message = new Message();
	                
	                $message->page = 'users_edit';
	                $message->content = $language['message_email_required'];
	                $message->type = 'PROBLEM';
	                $this->messenger->setMessage($message);
	                
	                $this->common->storePostedIntoSession('users_edit', $posted, 'user_email');

	                if ($mode == 'profile')
	                {
	                	header("Location: " . $this->config->item('base_url') . 'users/profile');
					}
					else
					{
	                	header("Location: " . $this->config->item('base_url') . 'users/edit/' . $user_id);
					}
	                die;
				}

            	if (!$this->common->check_email_address($email))
            	{
		            $message = new Message();
		            
		            $message->page = 'users_edit';
		            $message->content = $language['message_email_format'];
		            $message->type = 'PROBLEM';
		            $this->messenger->setMessage($message);
		            
		            $this->common->storePostedIntoSession('users_edit', $posted, 'user_email');

	                if ($mode == 'profile')
	                {
	                	header("Location: " . $this->config->item('base_url') . 'users/profile');
					}
					else
					{
	                	header("Location: " . $this->config->item('base_url') . 'users/edit/' . $user_id);
					}
		            die;
				}

            	if ($this->user->isEmailExist($email, $user_id))
            	{
		            $message = new Message();
		            
		            $message->page = 'users_edit';
		            $message->content = $language['message_email_exist'];
		            $message->type = 'PROBLEM';
		            $this->messenger->setMessage($message);
		            
		            $this->common->storePostedIntoSession('users_edit', $posted, 'user_email');

	                if ($mode == 'profile')
	                {
	                	header("Location: " . $this->config->item('base_url') . 'users/profile');
					}
					else
					{
	                	header("Location: " . $this->config->item('base_url') . 'users/edit/' . $user_id);
					}
		            die;
				}

            	if (empty($role_id))
            	{
	                $message = new Message();
	                
	                $message->page = 'users_edit';
	                $message->content = $language['message_role_required'];
	                $message->type = 'PROBLEM';
	                $this->messenger->setMessage($message);
	                
	                $this->common->storePostedIntoSession('users_edit', $posted, 'user_role');

	                if ($mode == 'profile')
	                {
	                	header("Location: " . $this->config->item('base_url') . 'users/profile');
					}
					else
					{
	                	header("Location: " . $this->config->item('base_url') . 'users/edit/' . $user_id);
					}
	                die;
				}

            	# count clients for insert only if it is team leader
            	if ($role_id == 2)
            	{
	                $clients_array = $this->client->fetchBaseAll();
	                $clientsForInsert = array();

	                foreach ($clients_array as $off)
	                {
	                    if (isset($_POST['client_id' . $off['client_id']]))
	                    {
	                        array_push($clientsForInsert, $off['client_id']);
	                    }
	                }
	                
	                if (count($clientsForInsert) == 0)
	                {
	                    $message = new Message();
	                    
	                    $message->page = 'users_edit';
	                    $message->content = $language['message_at_least_one_client'];
	                    $message->type = 'PROBLEM';
	                    $this->messenger->setMessage($message);
	                    
	                    $this->common->storePostedIntoSession('users_edit', $posted, 'user_role');

		                if ($mode == 'profile')
		                {
	                		header("Location: " . $this->config->item('base_url') . 'users/profile');
						}
						else
						{
	                		header("Location: " . $this->config->item('base_url') . 'users/edit/' . $user_id);
						}
	                    die;
	                }
				}
				
				$user_role = $this->user_role->fetchBaseOne($role_id);

				$editUser = new User();
				
				$editUser->user_id = $user_id;
				$editUser->name_first = $name_first;
				$editUser->name_last = $name_last;
				$editUser->email = $email;
				$editUser->role_id = $role_id;
				$editUser->level = $user_role['level'];
				
					$editUser->phone_mobile = $phone_mobile;
				
					$editUser->phone_office = $phone_office;
				
					$editUser->phone_ext = $phone_ext;
				
					$editUser->im_contact = $im_contact;
					$editUser->im_id = $im_id;
				
				if (!empty($new_password))
				{
					$editUser->password = $new_password;
					$editUser->accpassword = $new_password;
					$editUser->password_change = 0;
				}
				
				$updated = $this->user->update($editUser);
				
				if ($updated)
				{
                    if ($role_id == 2)
                    {
	                    # remove all user clients links
	                    $this->user_client->removeAllForUserID($user_id);

	                    foreach ($clientsForInsert as $key => $value)
	                    {
	                        $newUserClient = new User_Client();
	                        
	                        $newUserClient->user_id = $user_id;
	                        $newUserClient->client_id = $value;
	                        
	                        $resultNewUserClient = $this->user_client->insert($newUserClient);
	                    }
					}
					
					// send email
					if (!empty($new_password))
					{
		                # fetch mail template
		                $this->smarty_wrapper->assign('user', $editUser);
		                $this->smarty_wrapper->assign('lang', $language);
		                $this->smarty_wrapper->assign('password', $new_password);
	                	$this->smarty_wrapper->assign('editingUser', $user);
		                $sending = $this->smarty_wrapper->fetch("email/users_edit_user_mail.tpl");
		                
		                # load email parameters
		                $config = $this->settings->loadEmailParameters();
		                
		                # initialize email settings
		                $this->email->initialize($config);
		                
                        $tempmailname = $this->settings->getValueForKey($set, 'MAIL_NAME');
                        if ($tempmailname != '')
                        {
		                    $this->email->from($this->settings->getValueForKey($set, 'MAIL_FROM'), $this->settings->getValueForKey($set, 'MAIL_NAME'));
                        }
                        else
                        {
                            $this->email->from($this->settings->getValueForKey($set, 'MAIL_FROM'), 'Tefter');
                        }
		                $this->email->to($editUser->email);
		                
		                $this->email->subject($language['subject_mail_edit_user']);
		                $this->email->message($sending);

		                # send email
	                	error_reporting(0);
		                
		                $result = $this->email->send();
	                	
	                	error_reporting(E_ALL);
					}
					
			        // everything OK
			        $message = new Message();
			        
			        $message->page = 'users';
			        $message->content = $language['message_users_edit_success'];
			        $message->type = 'SUCCESS';
			        $this->messenger->setMessage($message);

			        header("Location: " . $this->config->item('base_url') . 'users');
			        die;
				}
				else
				{
					# error during process
			        $message = new Message();
			        
			        $message->page = 'users_edit';
			        $message->content = $language['message_error_during_process'];
			        $message->type = 'PROBLEM';
			        $this->messenger->setMessage($message);

		            if ($mode == 'profile')
		            {
	                	header("Location: " . $this->config->item('base_url') . 'users/profile');
					}
					else
					{
	                	header("Location: " . $this->config->item('base_url') . 'users/edit/' . $user_id);
					}
			        die;
				}
            break;
            
            case 'delete':
            	$user_id = htmlspecialchars($this->uri->segment(4 + $this->config->item('segment_addition')));
            	
            	if (isset($user_id))
            	{
            		if ($this->common->checkInteger($user_id))
            		{
            			# fetch user that should be deleted
            			$userEditObject = $this->user->fetchOne($user_id);
            			
            			$canDelete = $this->user->canDelete($user, $userEditObject);
            			
            			if ($canDelete)
            			{
            				$deletedUser = $this->user->remove($user_id);
            				$deletedLinks = $this->user_client->removeAllForUserID($user_id);
            				$this->user_group->removeAllForUserID($user_id);
            				
            				if ($deletedUser > 0)
            				{
					            $message = new Message();
					            
					            $message->page = 'users';
					            $message->content = $language['message_user_successfully_deleted'];
					            $message->type = 'SUCCESS';
					            $this->messenger->setMessage($message);
					            
					            header("Location: " . $this->config->item('base_url') . 'users');
					            die;
							}
							else
							{
					            $message = new Message();
					            
					            $message->page = 'users';
					            $message->content = $language['message_error_during_process'];
					            $message->type = 'PROBLEM';
					            $this->messenger->setMessage($message);
					            
					            header("Location: " . $this->config->item('base_url') . 'users');
					            die;
							}
						}
						else
						{
							show_404('page');
				            die;
						}
					}
					else
					{
				        $message = new Message();
				        
				        $message->page = 'users';
				        $message->content = $language['message_field_not_integer'];
				        $message->type = 'PROBLEM';
				        $this->messenger->setMessage($message);
				        
				        header("Location: " . $this->config->item('base_url') . 'users');
				        die;
					}
				}
				else
				{
				    $message = new Message();
				    
				    $message->page = 'users';
				    $message->content = $language['message_user_required'];
				    $message->type = 'PROBLEM';
				    $this->messenger->setMessage($message);
				    
				    header("Location: " . $this->config->item('base_url') . 'users');
				    die;
				}
            break;
            
            case 'passwordignore':
            	$user_id = htmlspecialchars($this->uri->segment(4 + $this->config->item('segment_addition')));
            	
            	if (isset($user_id))
            	{
            		if ($this->common->checkInteger($user_id))
            		{
            			# fetch user that should be edited
            			$userEditObject = $this->user->fetchOne($user_id);
            			
            			$canPasswordReset = $this->user->canPasswordReset($user, $userEditObject);
            			
            			if ($canPasswordReset)
            			{
            				$editUser = new User();
            				
            				$editUser->user_id = $userEditObject['user_id'];
            				$editUser->password_change = 0;
            				
            				$updated = $this->user->update($editUser);
            				
            				if ($updated)
            				{
					            $message = new Message();
					            
					            $message->page = 'dashboard';
					            $message->content = $language['message_password_reset_successfully_ignored'];
					            $message->type = 'SUCCESS';
					            $this->messenger->setMessage($message);
					            
					            header("Location: " . $this->config->item('base_url') . 'dashboard');
					            die;
							}
							else
							{
					            $message = new Message();
					            
					            $message->page = 'dashboard';
					            $message->content = $language['message_error_during_process'];
					            $message->type = 'PROBLEM';
					            $this->messenger->setMessage($message);
					            
					            header("Location: " . $this->config->item('base_url') . 'dashboard');
					            die;
							}
						}
						else
						{
							show_404('page');
				            die;
						}
					}
					else
					{
				        $message = new Message();
				        
				        $message->page = 'dashboard';
				        $message->content = $language['message_field_not_integer'];
				        $message->type = 'PROBLEM';
				        $this->messenger->setMessage($message);
				        
				        header("Location: " . $this->config->item('base_url') . 'dashboard');
				        die;
					}
				}
				else
				{
				    $message = new Message();
				    
				    $message->page = 'dashboard';
				    $message->content = $language['message_user_required'];
				    $message->type = 'PROBLEM';
				    $this->messenger->setMessage($message);
				    
				    header("Location: " . $this->config->item('base_url') . 'dashboard');
				    die;
				}
            break;

            case 'passwordreset':
            	$user_id = htmlspecialchars($this->uri->segment(4 + $this->config->item('segment_addition')));
            	
            	if (isset($user_id))
            	{
            		if ($this->common->checkInteger($user_id))
            		{
            			# fetch user that should be edited
            			$userEditObject = $this->user->fetchOne($user_id);
            			
            			$canPasswordReset = $this->user->canPasswordReset($user, $userEditObject);
            			
            			if ($canPasswordReset)
            			{
            				$randomPass = $this->common->getRandomPassword(10);
            				
            				$editUser = new User();
            				
            				$editUser->user_id = $userEditObject['user_id'];
            				$editUser->email = $userEditObject['email'];
            				$editUser->password = $randomPass;
            				$editUser->accpassword = $randomPass;
            				$editUser->password_change = 1;
            				
            				$updated = $this->user->update($editUser);
            				
            				if ($updated)
            				{
					            // send email
				                # fetch mail template
				                $this->smarty_wrapper->assign('user', $editUser);
				                $this->smarty_wrapper->assign('lang', $language);
	                			$this->smarty_wrapper->assign('password', $randomPass);
	                			$this->smarty_wrapper->assign('editingUser', $user);
				                $sending = $this->smarty_wrapper->fetch("email/forgot_password_mail.tpl");
				                
				                # load email parameters
				                $config = $this->settings->loadEmailParameters();
				                
				                # initialize email settings
				                $this->email->initialize($config);
				                
                                $tempmailname = $this->settings->getValueForKey($set, 'MAIL_NAME');
                                if ($tempmailname != '')
                                {
				                    $this->email->from($this->settings->getValueForKey($set, 'MAIL_FROM'), $this->settings->getValueForKey($set, 'MAIL_NAME'));
                                }
                                else
                                {
                                    $this->email->from($this->settings->getValueForKey($set, 'MAIL_FROM'), 'Tefter');
                                }
				                $this->email->to($userEditObject['email']);
				                
				                $this->email->subject($language['subject_mail_forgot_password']);
				                $this->email->message($sending);

				                # send email
	                			error_reporting(0);
				                
				                $result = $this->email->send();
					            
	                			error_reporting(E_ALL);
	                			
					            if ($result)
					            {
						            $message = new Message();
						            
						            $message->page = 'dashboard';
						            $message->content = $language['message_password_reset_successfully_stored'];
						            $message->type = 'SUCCESS';
						            $this->messenger->setMessage($message);
						            
						            header("Location: " . $this->config->item('base_url') . 'dashboard');
						            die;
								}
								else
								{
						            $message = new Message();
						            
						            $message->page = 'dashboard';
						            $message->content = $language['message_password_reset_successfully_not_sent'];
						            $message->type = 'PROBLEM';
						            $this->messenger->setMessage($message);
						            
						            header("Location: " . $this->config->item('base_url') . 'dashboard');
						            die;
								}
							}
							else
							{
					            $message = new Message();
					            
					            $message->page = 'dashboard';
					            $message->content = $language['message_error_during_process'];
					            $message->type = 'PROBLEM';
					            $this->messenger->setMessage($message);
					            
					            header("Location: " . $this->config->item('base_url') . 'dashboard');
					            die;
							}
						}
						else
						{
							show_404('page');
				            die;
						}
					}
					else
					{
				        $message = new Message();
				        
				        $message->page = 'dashboard';
				        $message->content = $language['message_field_not_integer'];
				        $message->type = 'PROBLEM';
				        $this->messenger->setMessage($message);
				        
				        header("Location: " . $this->config->item('base_url') . 'dashboard');
				        die;
					}
				}
				else
				{
				    $message = new Message();
				    
				    $message->page = 'dashboard';
				    $message->content = $language['message_user_required'];
				    $message->type = 'PROBLEM';
				    $this->messenger->setMessage($message);
				    
				    header("Location: " . $this->config->item('base_url') . 'dashboard');
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

        $limit_from = $this->common->getParameter('limit_from');
        $limit_to = $this->common->getParameter('limit_to');
        $letterFilter = $this->common->getParameter('letterFilter');
        $roleFilter = $this->common->getParameter('roleFilter');
        
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
			$result['totalUsers'] = 0;
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
			$result['totalUsers'] = 0;
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

		if ($user['level'] == 99)
		{
			$users = $this->user->fetchAll($limit_from, $limit_to - $limit_from, $direction, $sort, $letterFilter, null, $roleFilter);
			$totalUsers = $this->user->countAll($letterFilter, null, $roleFilter);
		}
		else
		{
			$users = $this->user->fetchAll($limit_from, $limit_to - $limit_from, $direction, $sort, $letterFilter, null, $roleFilter, $user['user_id']);
			$totalUsers = $this->user->countAll($letterFilter, null, $roleFilter, $user['user_id']);
		}
		
		$this->smarty_wrapper->assign('user', $user);
		$this->smarty_wrapper->assign('users', $users);
		$this->smarty_wrapper->assign('lang', $language);
		
        $data = $this->smarty_wrapper->fetch("users/users_table_template.tpl");
        
		$result['success'] = true;
		if ($totalUsers == 0)
		{
			$result['data'] = '<p class="no-matches">' . $language['message_no_matches_found'] . '</p>';
		}
		else
		{
			$result['data'] = $data;
		}
		$result['code'] = 200;
		$result['description'] = 'OK';
		$result['total'] = count($users);
		$result['totalUsers'] = $totalUsers;
		$result['limit_from'] = $limit_from;
		$result['limit_to'] = $limit_to;
		
		echo json_encode($result);
	}
}
?>