{include file='shared/head.tpl'}
<body id="users">

	{include file='shared/top_bar.tpl'}
	
	{include file='shared/header.tpl'}
	
	<section id="content">
		<div class="group block">
			<h1>{$lang.title_edit_user}</h1>
			
			<a href="{base_url}users" class="go-back">{$lang.text_back_to_users}</a>
			
			<section id="primary">
				<form action="{base_url}users/actions/edit" method="post" id="edit-user">
				
					<input type="hidden" name="user_id" id="user_id" value="{$userEditObject.user_id}">
					<input type="hidden" name="mode" id="mode" value="{$mode}">

					<fieldset>

			            {if isset($m)}
			                {$m->content}
			            {/if}

						<div class="group">
							<div class="half-width separator">
								<label for="user_first_name" class="required">{$lang.label_first_name}</label>
								<input type="text" name="user_first_name" id="user_first_name" value="{if $posted.user_first_name neq ''}{$posted.user_first_name}{else}{$userEditObject.name_first}{/if}" autocomplete="off" />
							</div>
							
							<div class="half-width">
								<label for="user_last_name" class="required">{$lang.label_last_name}</label>
								<input type="text" name="user_last_name" id="user_last_name" value="{if $posted.user_last_name neq ''}{$posted.user_last_name}{else}{$userEditObject.name_last}{/if}" autocomplete="off" />
							</div>
						</div>
						
						<div class="group">
							<div class="half-width separator">
								<label for="password">{$lang.label_new_password}</label>
								<input type="password" name="password" id="password" value="" />
							</div>
							
							<div class="half-width">
								<label for="confirm_password">{$lang.label_confirm_new_password}</label>
								<input type="password" name="confirm_password" id="confirm_password" value="" />
							</div>
							<p class="hint">{$lang.text_hint_change_password}</p>
						</div>

						<div class="group">
							<label for="user_email" class="required">{$lang.label_email}</label>
							<input type="email" name="user_email" id="user_email" value="{if $posted.user_email neq ''}{$posted.user_email}{else}{$userEditObject.email}{/if}" autocomplete="off" />
						</div>
						
						{if $user.level eq 99}
						<div class="group">
							<label for="user_role" class="required">{$lang.label_role}</label>
							<select name="user_role" id="user_role">
								{if $user.level neq 40}
								<option value="" 
								{if $posted.user_role eq ''}
									selected="selected"
								{/if}
								>{$lang.option_please_select}</option>
								{/if}
								{foreach from=$user_roles item=user_role key=i}
									{if $userEditObject.first_admin eq 1}
		                                {if $user_role.role_id eq 1}
											<option value="{$user_role.role_id}" 
											{if $posted.user_role neq ''}
												{if $posted.user_role eq $user_role.role_id}
													selected="selected"
												{/if}
											{else}
												{if $user_role.role_id eq $userEditObject.role_id}selected="selected"{/if}
											{/if}
											>{$user_role.role_title}</option>
										{/if}
									{else}
										<option value="{$user_role.role_id}" 
										{if $posted.user_role neq ''}
											{if $posted.user_role eq $user_role.role_id}
												selected="selected"
											{/if}
										{else}
											{if $user_role.role_id eq $userEditObject.role_id}selected="selected"{/if}
										{/if}
										>{$user_role.role_title}</option>
									{/if}
								{/foreach}
							</select>
							{if $user.level gt 20}
							<a href="#" class="help" id="roles" title="<p><strong>{$lang.text_administrator}</strong> {$lang.text_administrator_description}.</p><p><strong>{$lang.text_team_member}</strong> {$lang.text_team_member_description}.</p>">{$lang.text_help}</a>
							{/if}
						</div>
						
						<div class="group user-access
						{if isset($posted)}
							{if $posted.user_role eq '' or $posted.user_role neq '2'}
								hide
							{/if}
						{else}
							{if $userEditObject.role_id neq '2'}
								hide
							{/if}
						{/if}
						">
							<p class="label required">{$lang.label_enable_access_to_clients}</p>
							<div class="group inline-checkboxes" id="checkboxes">
							{if $clients|@count gt 0}
							<label for="select_all" class="inline-label"><input type="checkbox" name="select_all" id="select_all" value="all" /> {$lang.text_all}</label>
							
							{foreach from=$clients item=client key=i}

                            {assign var=pref value='client_id'}
                            {assign var=suf value=$client.client_id}
                            {assign var=che value=$pref$suf}

							<label for="client_id{$client.client_id}" class="inline-label"><input type="checkbox" name="client_id{$client.client_id}" id="client_id{$client.client_id}" value="{$client.client_id}" class="selector" 
                                {if $posted.$che neq ''}
                                    checked="checked"
                                {else}
                                    {if in_array($suf, $sortedClients)}
                                        checked="checked"
                                    {/if}    
                                {/if}
 							/> {$client.company_name}</label>
							{/foreach}
							{else}
								<p class="no-clients">{$lang.text_no_clients}</p>
							{/if}
							</div>
							<a href="#" class="aside-link">{$lang.text_add_new_client}</a>
						</div>
						{else}
							<input type="hidden" name="user_role" id="user_role" value="{$userEditObject.role_id}">
						{/if}
						
						<div class="group">
							<div class="half-width separator">
								<label for="user_mobile">{$lang.label_mobile_phone}</label>
								<input type="text" name="user_mobile" id="user_mobile" value="{if $posted.user_mobile neq ''}{$posted.user_mobile}{else}{$userEditObject.phone_mobile}{/if}" autocomplete="off" />
							</div>
							
							<div class="half-width separator">
								<label for="user_office_phone">{$lang.label_office_phone}</label>
								<input type="text" name="user_office_phone" id="user_office_phone" autocomplete="off" value="{if $posted.user_office_phone neq ''}{$posted.user_office_phone}{else}{$userEditObject.phone_office}{/if}" />
							</div>
							
							<div class="quartern-width">
								<label for="user_ext">{$lang.label_ext}</label>
								<input type="text" name="user_ext" id="user_ext" autocomplete="off" value="{if $posted.user_ext neq ''}{$posted.user_ext}{else}{$userEditObject.phone_ext}{/if}" />
							</div>
						</div>
						
						<div class="group">
							<div class="half-width separator">
								<label for="user_im">{$lang.label_im_contact}</label>
								<input type="text" name="user_im" id="user_im" value="{if $posted.user_im neq ''}{$posted.user_im}{else}{$userEditObject.im_contact}{/if}" autocomplete="off" />
							</div>
							
							<div class="group half-width im-clients">
								<span>{$lang.text_on}</span>
								<select name="user_im_client" id="user_im_client">
									{foreach from=$ims item=im key=i}
										<option value="{$im.im_id}" 
										{if $posted.user_im_client neq ''}
											{if $posted.user_im_client eq $im.im_id}
												selected="selected"
											{/if}
										{else}
											{if $userEditObject.im_id eq $im.im_id}
												selected="selected"
											{/if}
										{/if}
										>{$im.im_title}</option>
									{/foreach}
								</select>
							</div>

						</div>
					</fieldset>
					
					<button type="submit">{$lang.button_save_changes}</button>
					<a href="{base_url}users" class="cancel">{$lang.button_cancel}</a>
				</form>
			</section>
			
			<aside>
				{if $canDelete}
				<h5>{$lang.title_delete_this_user}</h5>
				<p>{$lang.text_delete_this_user_part1} {$userEditObject.name_first} {$userEditObject.name_last} {$lang.text_delete_this_user_part2}</p>
				<p><a href="{base_url}users/actions/delete/{$userEditObject.user_id}" class="delete" onclick="return confirm('{$lang.text_are_you_sure_you_want_to_delete_user}');">{$lang.text_aside_delete} {if $user.user_id eq $userEditObject.user_id}{$lang.text_yourself}{else}{$userEditObject.name_first} {$userEditObject.name_last}{/if}</a></p>
				{/if}
			</aside>
			
		</div>
	</section>
	
	<div id="add-new-client" class="hide">
		<h3>{$lang.text_new_client}</h3>
		
		<form action="#" method="post" onsubmit="return false;">
			<div class="message problem hide">
			</div>
			<input type="hidden" name="base_url_new_client" id="base_url_new_client" value="{base_url}">
			<input type="hidden" name="call_script_new_client" id="call_script_new_client" value="clients/addclientfromaccount">
			<div class="group">
				<label for="new_client" class="required">{$lang.text_enter_new_client_name}</label>
				<input type="text" name="new_client" id="new_client" maxlength="120" value="" />
			</div>
			
			<button type="submit" onclick="{literal}javascript: newUserClientClick($('#new_client').val());{/literal}">{$lang.button_add_client}</button>
			<a href="#" class="cancel">{$lang.button_cancel}</a>
		</form>
	</div>

	<script src="{base_url}assets/js/jquery-1.4.2.min.js"></script>
	<script src="{base_url}assets/js/jquery.tipsy.js"></script>
	<script src="{base_url}assets/js/jquery.blockUI.js"></script>
	<script src="{base_url}assets/js/global.js"></script>
	<script src="{base_url}assets/js/custom.js"></script>
	<script>
		{literal}
	  $(function() {
	    $("#roles").tipsy({
	    	fade: true,
	    	gravity: 'w',
	    	html: true
	    	});
	  	});
	  {/literal}
	</script>
	{include file='shared/foot.tpl'}
</body>
</html>