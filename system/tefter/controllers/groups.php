<?php
class Groups extends Controller {

    function Groups()
    {
        parent::Controller();    
        
        $this->load->library('common');
        $this->load->library('Smarty_Wrapper');
        $this->load->library('user');
        $this->load->library('messenger');
        $this->load->library('group');
        $this->load->library('account');
        $this->load->library('Group_Account');
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
        
        if ($user['level'] < 99)
        {
        	$groups = $this->group->fetchAll(0, 999999, 'ASC', 'name', null, null, $user['user_id']);
		}
		else
		{
        	$groups = $this->group->fetchAll(0, 999999, 'ASC', 'name', null, null);
		}
        
        $m = $this->messenger->getMessage('groups');
        
        $this->smarty_wrapper->assign('title', 'Groups');
        $this->smarty_wrapper->assign('title_fixed', $this->config->item('title_fixed'));
        $this->smarty_wrapper->assign('m', $m);
        $this->smarty_wrapper->assign('lang', $language);
        $this->smarty_wrapper->assign('settings', $settings);
        $this->smarty_wrapper->assign('user', $user);
        $this->smarty_wrapper->assign('groups', $groups);
        $this->smarty_wrapper->assign('selectMenuItem', 'groups');
        
        if (count($groups) > 0)
        {
        	$this->smarty_wrapper->assign('hasCount', true);
		}
		else
		{
			$this->smarty_wrapper->assign('hasCount', false);
		}

        $this->smarty_wrapper->display("groups/groups.tpl");
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
        
        $posted = $this->common->getPostedFromSession('groups_add');

        $m = $this->messenger->getMessage('groups_add');
        
        if ($user['level'] == 99)
        {
	        $groupUsers = $this->user->fetchAll(0, 999999, 'ASC', 'name', null, null, null, null, true);
	        $groupAccounts = $this->account->fetchAll(0, 999999, null, null, null, null, 'ASC', 'title');
		}
		else
		{
	        $groupUsers = $this->user->fetchAll(0, 999999, 'ASC', 'name', null, null, null, $user['user_id'], true);
	        $groupAccounts = $this->account->fetchAll(0, 999999, null, null, null, null, 'ASC', 'title', true);
		}
        
        $this->smarty_wrapper->assign('title', 'Add Group');
        $this->smarty_wrapper->assign('title_fixed', $this->config->item('title_fixed'));
        $this->smarty_wrapper->assign('m', $m);
        $this->smarty_wrapper->assign('lang', $language);
        $this->smarty_wrapper->assign('posted', $posted);
        $this->smarty_wrapper->assign('settings', $settings);
        $this->smarty_wrapper->assign('user', $user);
        $this->smarty_wrapper->assign('groupUsers', $groupUsers);
        $this->smarty_wrapper->assign('groupAccounts', $groupAccounts);
        $this->smarty_wrapper->assign('selectMenuItem', 'groups');

        $this->common->removePostedFromSession('groups_add');

        $this->smarty_wrapper->display("groups/groups_add.tpl");
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
	        
	        $message->page = 'groups';
	        $message->content = $language['message_field_not_integer'];
	        $message->type = 'PROBLEM';
	        $this->messenger->setMessage($message);

	        header("Location: " . $this->config->item('base_url') . 'groups');
	        die;
		}

        $group = $this->group->fetchOne($id);
        
        if ($group == null)
        {
            $message = new Message();
            
            $message->page = 'groups';
            $message->content = $language['message_group_not_found'];
            $message->type = 'NOTICE';
            $this->messenger->setMessage($message);

            header("Location: " . $this->config->item('base_url') . 'groups');
            die;
		}
        
        $canAccess = $this->group->canAccess($user, $group);
        
		if (!$canAccess)
		{
			show_404('page');
            die;
		}
        
        $set = $this->settings->fetchBaseAll();
        $settings = $this->settings->transformToArray($set);
        
        $posted = $this->common->getPostedFromSession('groups_edit');

        $m = $this->messenger->getMessage('groups_edit');
        
        if ($user['level'] == 99)
        {
	        $groupUsers = $this->user->fetchAll(0, 999999, 'ASC', 'name', null, null, null, null, true);
	        $groupAccounts = $this->account->fetchAll(0, 999999, null, null, null, null, 'ASC', 'title');
		}
		else
		{
	        $groupUsers = $this->user->fetchAll(0, 999999, 'ASC', 'name', null, null, null, $user['user_id'], true);
	        $groupAccounts = $this->account->fetchAll(0, 999999, null, null, null, null, 'ASC', 'title', true);
		}

        $usersGroups = $this->user_group->fetchAll($id);
        
        $sortedUsers = array();
        foreach ($usersGroups as $item)
        {
            array_push($sortedUsers, $item['user_id']);
        }
        
        $groupsAccounts = $this->group_account->fetchAll($id);
        
        $sortedAccounts = array();
        foreach ($groupsAccounts as $item)
        {
            array_push($sortedAccounts, $item['account_id']);
        }

        $this->smarty_wrapper->assign('title', 'Edit group');
        $this->smarty_wrapper->assign('title_fixed', $this->config->item('title_fixed'));
        $this->smarty_wrapper->assign('m', $m);
        $this->smarty_wrapper->assign('lang', $language);
        $this->smarty_wrapper->assign('posted', $posted);
        $this->smarty_wrapper->assign('settings', $settings);
        $this->smarty_wrapper->assign('user', $user);
        $this->smarty_wrapper->assign('group', $group);
        $this->smarty_wrapper->assign('sortedUsers', $sortedUsers);
        $this->smarty_wrapper->assign('sortedAccounts', $sortedAccounts);
        $this->smarty_wrapper->assign('groupUsers', $groupUsers);
        $this->smarty_wrapper->assign('groupAccounts', $groupAccounts);
        $this->smarty_wrapper->assign('selectMenuItem', 'groups');

        $this->common->removePostedFromSession('groups_edit');

        $this->smarty_wrapper->display("groups/groups_edit.tpl");
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
        
        $group_id = htmlspecialchars($this->uri->segment(3 + $this->config->item('segment_addition')));
        
        if (isset($group_id))
        {
            if (!$this->common->checkInteger($group_id))
            {
	            $message = new Message();
	            
	            $message->page = 'groups';
	            $message->content = $language['message_field_not_integer'];
	            $message->type = 'PROBLEM';
	            $this->messenger->setMessage($message);

	            header("Location: " . $this->config->item('base_url') . 'groups');
	            die;
			}
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
        
        $posted = $this->common->getPostedFromSession('groups_details');

        $m = $this->messenger->getMessage('groups_details');
        
        $group = $this->group->fetchOne($group_id);
        
        if ($group == null)
        {
            $message = new Message();
            
            $message->page = 'groups';
            $message->content = $language['message_group_required'];
            $message->type = 'NOTICE';
            $this->messenger->setMessage($message);

            header("Location: " . $this->config->item('base_url') . 'groups');
            die;
		}
		
        $canAccess = $this->group->canAccess($user, $group);

		if (!$canAccess)
		{
			show_404('page');
            die;
		}

        $canEdit = $this->group->canEdit($user, $group);

		$accounts = $this->account->fetchAll(0, 999999, null, null, null, null, 'ASC', 'title', true, $group_id);
		$usersGroups = $this->user_group->fetchAll($group_id);

        $this->smarty_wrapper->assign('title', 'Group details');
        $this->smarty_wrapper->assign('title_fixed', $this->config->item('title_fixed'));
        $this->smarty_wrapper->assign('m', $m);
        $this->smarty_wrapper->assign('lang', $language);
        $this->smarty_wrapper->assign('posted', $posted);
        $this->smarty_wrapper->assign('settings', $settings);
        $this->smarty_wrapper->assign('user', $user);
        $this->smarty_wrapper->assign('canEdit', $canEdit);
        $this->smarty_wrapper->assign('group', $group);
        $this->smarty_wrapper->assign('usersGroups', $usersGroups);
        $this->smarty_wrapper->assign('selectMenuItem', 'groups');

        if (count($accounts) > 0)
        {
        	$this->smarty_wrapper->assign('hasCount', true);
		}
		else
		{
			$this->smarty_wrapper->assign('hasCount', false);
		}
		
        $this->smarty_wrapper->display("groups/groups_details.tpl");
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
				$group_title = $this->common->getParameter('group_title', 'text');

            	$posted = $_POST;
            	
            	if (empty($group_title))
            	{
	                $message = new Message();
	                
	                $message->page = 'groups_add';
	                $message->content = $language['message_title_required'];
	                $message->type = 'PROBLEM';
	                $this->messenger->setMessage($message);
	                
	                $this->common->storePostedIntoSession('groups_add', $posted, 'group_title');

	                header("Location: " . $this->config->item('base_url') . 'groups/add');
	                die;
				}
				
	            $users_array = $this->user->fetchBaseAll();
	            $usersForInsert = array();

	            foreach ($users_array as $off)
	            {
	                if (isset($_POST['user_id' . $off['user_id']]))
	                {
	                    array_push($usersForInsert, $off['user_id']);
	                }
	            }
	            
	            $accounts_array = $this->account->fetchBaseAll();
	            $accountsForInsert = array();

	            foreach ($accounts_array as $off)
	            {
	                if (isset($_POST['account_id' . $off['account_id']]))
	                {
	                    array_push($accountsForInsert, $off['account_id']);
	                }
	            }
	            
	            $group = new Group();
	            
	            $group->group_title = $group_title;
	            
	            $result = $this->group->insert($group);
	            
	            if ($result > 0)
	            {
	                foreach ($usersForInsert as $key => $value)
	                {
	                    $newUserGroup = new User_Group();
	                    
	                    $newUserGroup->user_id = $value;
	                    $newUserGroup->group_id = $result;
	                    
	                    $resultNewUserGroup = $this->user_group->insert($newUserGroup);
	                }
	                
	            	foreach($accountsForInsert as $key => $value)
	            	{
	            		$newGroupAccount = new Group_Account();
	            		
	            		$newGroupAccount->group_id = $result;
	            		$newGroupAccount->account_id = $value;
	            		
	            		$resultNewGroupAccount = $this->group_account->insert($newGroupAccount);
					}
					
			        $message = new Message();
			        
			        $message->page = 'groups';
			        $message->content = $language['message_groups_add_success'];
			        $message->type = 'SUCCESS';
			        $this->messenger->setMessage($message);

			        header("Location: " . $this->config->item('base_url') . 'groups');
			        die;
				}
				else
				{
		            $message = new Message();
		            
		            $message->page = 'groups_add';
		            $message->content = $language['message_error_during_process'];
		            $message->type = 'PROBLEM';
		            $this->messenger->setMessage($message);
		            
		            $this->common->storePostedIntoSession('groups_add', $posted);

		            header("Location: " . $this->config->item('base_url') . 'groups/add');
		            die;
				}
            break;
            
            case 'edit':
				$group_title = $this->common->getParameter('group_title', 'text');
				$group_id = $this->common->getParameter('group_id', 'int');

		        if (!$this->common->checkInteger($group_id))
		        {
			        $message = new Message();
			        
			        $message->page = 'groups_edit';
			        $message->content = $language['message_field_not_integer'];
			        $message->type = 'PROBLEM';
			        $this->messenger->setMessage($message);

			        header("Location: " . $this->config->item('base_url') . 'groups/edit/' . $group_id);
			        die;
				}

            	$posted = $_POST;
            	
            	if (empty($group_title))
            	{
	                $message = new Message();
	                
	                $message->page = 'groups_edit';
	                $message->content = $language['message_title_required'];
	                $message->type = 'PROBLEM';
	                $this->messenger->setMessage($message);
	                
	                $this->common->storePostedIntoSession('groups_edit', $posted, 'group_title');

	                header("Location: " . $this->config->item('base_url') . 'groups/edit/' . $group_id);
	                die;
				}
				
	            $users_array = $this->user->fetchBaseAll();
	            $usersForInsert = array();

	            foreach ($users_array as $off)
	            {
	                if (isset($_POST['user_id' . $off['user_id']]))
	                {
	                    array_push($usersForInsert, $off['user_id']);
	                }
	            }

	            $accounts_array = $this->account->fetchBaseAll();
	            $accountsForInsert = array();

	            foreach ($accounts_array as $off)
	            {
	                if (isset($_POST['account_id' . $off['account_id']]))
	                {
	                    array_push($accountsForInsert, $off['account_id']);
	                }
	            }
	            
	            $group = new Group();
	            
	            $group->group_id = $group_id;
	            $group->group_title = $group_title;
	            
	            $result = $this->group->update($group);
	            
	            if ($result)
	            {
	                # remove all user groups links
	                $this->user_group->removeAllForGroupID($group_id);

	                foreach ($usersForInsert as $key => $value)
	                {
	                    $newUserGroup = new User_Group();
	                    
	                    $newUserGroup->user_id = $value;
	                    $newUserGroup->group_id = $group_id;
	                    
	                    $resultNewUserGroup = $this->user_group->insert($newUserGroup);
	                }

	                # remove all group accounts links
	                $this->group_account->removeAllForGroupID($group_id);

	            	foreach($accountsForInsert as $key => $value)
	            	{
	            		$newGroupAccount = new Group_Account();
	            		
	            		$newGroupAccount->group_id = $group_id;
	            		$newGroupAccount->account_id = $value;
	            		
	            		$resultNewGroupAccount = $this->group_account->insert($newGroupAccount);
					}
					
			        $message = new Message();
			        
			        $message->page = 'groups';
			        $message->content = $language['message_groups_edit_success'];
			        $message->type = 'SUCCESS';
			        $this->messenger->setMessage($message);

			        header("Location: " . $this->config->item('base_url') . 'groups');
			        die;
				}
				else
				{
		            $message = new Message();
		            
		            $message->page = 'groups_edit';
		            $message->content = $language['message_error_during_process'];
		            $message->type = 'PROBLEM';
		            $this->messenger->setMessage($message);
		            
		            $this->common->storePostedIntoSession('groups_edit', $posted);

		            header("Location: " . $this->config->item('base_url') . 'groups/edit/' . $group_id);
		            die;
				}
            break;
            
            case 'delete':
            	$group_id = htmlspecialchars($this->uri->segment(4 + $this->config->item('segment_addition')));
            	
            	if (isset($group_id))
            	{
            		if ($this->common->checkInteger($group_id))
            		{
            			# fetch group that should be deleted
            			$group = $this->group->fetchOne($group_id);
            			
            			$canAccess = $this->group->canAccess($user, $group);
            			
            			if ($canAccess)
            			{
            				$deletedGroup = $this->group->remove($group_id);
            				$deletedLinks = $this->user_group->removeAllForGroupID($group_id);
            				$this->group_account->removeAllForGroupID($group_id);
            				
            				if ($deletedGroup > 0)
            				{
					            $message = new Message();
					            
					            $message->page = 'groups';
					            $message->content = $language['message_group_successfully_deleted'];
					            $message->type = 'SUCCESS';
					            $this->messenger->setMessage($message);
					            
					            header("Location: " . $this->config->item('base_url') . 'groups');
					            die;
							}
							else
							{
					            $message = new Message();
					            
					            $message->page = 'groups';
					            $message->content = $language['message_error_during_process'];
					            $message->type = 'PROBLEM';
					            $this->messenger->setMessage($message);
					            
					            header("Location: " . $this->config->item('base_url') . 'groups');
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
				        
				        $message->page = 'groups';
				        $message->content = $language['message_field_not_integer'];
				        $message->type = 'PROBLEM';
				        $this->messenger->setMessage($message);
				        
				        header("Location: " . $this->config->item('base_url') . 'groups');
				        die;
					}
				}
				else
				{
				    $message = new Message();
				    
				    $message->page = 'groups';
				    $message->content = $language['message_group_required'];
				    $message->type = 'PROBLEM';
				    $this->messenger->setMessage($message);
				    
				    header("Location: " . $this->config->item('base_url') . 'groups');
				    die;
				}
            break;
            
            case 'removeuserfromgroup':
            	$user_id = $this->uri->segment(4 + $this->config->item('segment_addition'));
            	$group_id = $this->uri->segment(5 + $this->config->item('segment_addition'));
            	$return_group_id = $this->uri->segment(6 + $this->config->item('segment_addition'));
            	
            	if (isset($user_id) && isset($group_id) && isset($return_group_id))
            	{
            		if ($this->common->checkInteger($user_id) && $this->common->checkInteger($group_id))
            		{
            			# fetch User
            			$userObject = $this->user->fetchOne($user_id);

            			# fetch Group user should be removed from
            			$group = $this->group->fetchOne($group_id);
            			
            			$canRemoveFromGroup = $this->group->canRemoveFromGroup($user, $userObject, $group);
            			
            			if ($canRemoveFromGroup)
            			{
            				$deletedLink = $this->user_group->removeLink($user_id, $group_id);
            				
            				if ($deletedLink > 0)
            				{
					            $message = new Message();
					            
					            $message->page = 'groups_details';
					            $message->content = $language['message_usergroup_link_successfully_deleted'];
					            $message->type = 'SUCCESS';
					            $this->messenger->setMessage($message);
					            
					            header("Location: " . $this->config->item('base_url') . 'groups/details/' . $return_group_id);
					            die;
							}
							else
							{
					            $message = new Message();
					            
					            $message->page = 'groups_details';
					            $message->content = $language['message_error_during_process'];
					            $message->type = 'PROBLEM';
					            $this->messenger->setMessage($message);
					            
					            header("Location: " . $this->config->item('base_url') . 'groups/details/' . $return_group_id);
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
				        
				        $message->page = 'groups_details';
				        $message->content = $language['message_field_not_integer'];
				        $message->type = 'PROBLEM';
				        $this->messenger->setMessage($message);
				        
				        header("Location: " . $this->config->item('base_url') . 'groups/details/' . $return_group_id);
				        die;
					}
				}
				else
				{
				    $message = new Message();
				    
				    $message->page = 'groups_details';
				    $message->content = $language['message_required_fields'];
				    $message->type = 'PROBLEM';
				    $this->messenger->setMessage($message);
				    
				    header("Location: " . $this->config->item('base_url') . 'groups/details/' . $return_group_id);
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
			$result['totalGroups'] = 0;
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
			$result['totalGroups'] = 0;
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
        	$groups = $this->group->fetchAll($limit_from, $limit_to - $limit_from, $direction, $sort, $letterFilter, $keyword, $user['user_id']);
			$totalGroups = $this->group->countAll($letterFilter, $keyword, $user['user_id']);
		}
		else
		{
        	$groups = $this->group->fetchAll($limit_from, $limit_to - $limit_from, $direction, $sort, $letterFilter, $keyword);
			$totalGroups = $this->group->countAll($letterFilter, $keyword);
		}

		$this->smarty_wrapper->assign('groups', $groups);
		$this->smarty_wrapper->assign('lang', $language);
		$this->smarty_wrapper->assign('user', $user);
		
        $data = $this->smarty_wrapper->fetch("groups/groups_table_template.tpl");
        
		$result['success'] = true;
		if ($totalGroups == 0)
		{
			$result['data'] = '<tr><td colspan="5">' . $language['message_no_matches_found'] . '</td></tr>';
		}
		else
		{
			$result['data'] = $data;
		}
		$result['code'] = 200;
		$result['description'] = 'OK';
		$result['total'] = count($groups);
		$result['totalGroups'] = $totalGroups;
		$result['limit_from'] = $limit_from;
		$result['limit_to'] = $limit_to;
		
		echo json_encode($result);
	}
}
?>