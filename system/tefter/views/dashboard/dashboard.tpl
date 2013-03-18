{include file='shared/head.tpl'}

<body id="dashboard">

	{include file='shared/top_bar.tpl'}
	
	{include file='shared/header.tpl'}
	
	<!-- content -->
	<section id="content">
		<div class="group block">
					
	        {if isset($m)}
	            {$m->content}
	        {/if}

			{if $user.password_change eq 1}
			<section id="update-password">
				<form action="{base_url}dashboard/actions/changepassword" method="post">
					<fieldset>
						<h2>{$lang.title_update_password_important_notice}</h2>
						<p>{$lang.text_update_password_important_notice}</p>
						
						<div class="group">
							<div class="half-width separator">
								<label for="new_password">{$lang.label_new_password}</label>
								<input type="password" name="new_password" id="new_password" />
							</div>
							
							<div class="half-width">
								<label for="confirm_new_password">{$lang.label_confirm_new_password}</label>
								<input type="password" name="confirm_new_password" id="confirm_new_password" />
							</div>
						</div>
						
						<button type="submit">{$lang.button_change_password}</button>
						<a href="{base_url}dashboard/actions/dontchangepassword" class="cancel">{$lang.text_dont_change_password}</a>
					</fieldset>
				</form>
			</section>
			{/if}
			
			{if $user.level gt 20 and $hasCount}
			<section id="request-password-reset">
				<h4>{$lang.text_these_users_requested_password_change}:</h4>

				<ol class="group reset users-list">
					{foreach from=$usersPasswordReset item=userLoop key=i}
					<li>
						<ul class="reset">
							<li class="name"><strong>{$userLoop.name_first} {$userLoop.name_last}</strong></li>
							<li class="email">{$userLoop.email}</li>
							<li class="role">{$userLoop.role_title}</li>
							<li class="tools">
								<a href="{base_url}users/actions/passwordreset/{$userLoop.user_id}">{$lang.text_action_reset}</a> | 
								<a href="{base_url}users/actions/passwordignore/{$userLoop.user_id}" class="delete" onclick="return confirm('{$lang.text_are_you_sure_you_want_to_ignore}');">{$lang.text_action_ignore}</a>
							</li>
						</ul>
					</li>
					{/foreach}
				</ol>
			</section>
			{/if}
			
			<section id="get-started">
				<h4>{$lang.title_get_started}</h4>
				
				<ul class="group reset">
					{if $user.level eq 99}
						<li class="new-client"><a href="{base_url}clients/add">{$lang.button_add_client}</a></li>
						<li class="new-account"><a href="{base_url}accounts/add">{$lang.button_add_account}</a></li>
						<li class="new-group"><a href="{base_url}groups/add">{$lang.button_add_group}</a></li>
						<li class="new-user"><a href="{base_url}users/add">{$lang.button_add_user}</a></li>
						<li class="edit-settings"><a href="{base_url}settings/general">{$lang.title_settings}</a></li>
					{else}
						<li class="view-accounts"><a href="{base_url}accounts">{$lang.text_view_accounts}</a></li>
						<li class="view-groups"><a href="{base_url}groups">{$lang.text_view_groups}</a></li>
						<li class="view-users"><a href="{base_url}users">{$lang.text_view_users}</a></li>
					{/if}
				</ul>
			</section>

		</div>
	</section>
	
	<script src="{base_url}assets/js/jquery-1.4.2.min.js"></script>
	<script src="{base_url}assets/js/global.js"></script>
{include file='shared/foot.tpl'}
</body>
</html>