{include file='shared/head.tpl'}

<body id="edit-group">

	{include file='shared/top_bar.tpl'}
	
	{include file='shared/header.tpl'}
	
	<section id="content">
		<div class="group block">
			<h1>{$lang.title_edit_group}</h1>
			
			<a href="{base_url}groups" class="go-back">{$lang.text_back_to_groups}</a>
			
			<section id="primary">
				<form action="{base_url}groups/actions/edit" method="post">
				
					<input type="hidden" name="group_id" id="group_id" value="{$group.group_id}">

					<fieldset>

	                    {if isset($m)}
	                        {$m->content}
	                    {/if}

						<div class="group">
							<label for="group_title" class="required">{$lang.label_title}</label>
							<input type="text" name="group_title" id="group_title" value="{if $posted.group_title neq ''}{$posted.group_title}{else}{$group.group_title}{/if}" autocomplete="off" />
						</div>
						
						<div class="group inline-checkboxes">
							<p class="label required">{$lang.label_enable_access_to_groups}</p>
							{if $groupUsers|@count gt 0}
							<label for="select_all" class="inline-label"><input type="checkbox" name="select_all" id="select_all" value="all" /> {$lang.text_all}</label>
                            {else}
                                <p>No available users</p>
							{/if}

							{foreach from=$groupUsers item=groupUser key=i}
								{if $groupUser.level neq 99}
		                            {assign var=pref value='user_id'}
		                            {assign var=suf value=$groupUser.user_id}
		                            {assign var=che value=$pref$suf}

									<label for="user_id{$groupUser.user_id}" class="inline-label"><input type="checkbox" name="user_id{$groupUser.user_id}" id="user_id{$groupUser.user_id}" value="{$groupUser.user_id}" class="selector" 
		                                {if $posted.$che neq ''}
		                                    checked="checked"
		                                {else}
		                                    {if in_array($suf, $sortedUsers)}
		                                        checked="checked"
		                                    {/if}    
		                                {/if}
									/> {$groupUser.name_first} {$groupUser.name_last}</label>
								{/if}
							{/foreach}
						</div>
						
						<div class="group inline-checkboxes">
							<p class="label required">{$lang.label_assign_accounts_to_group}</p>
							{if $groupAccounts|@count gt 0}
							<label for="select_all_accounts" class="inline-label"><input type="checkbox" name="select_all_accounts" id="select_all_accounts" value="all" /> {$lang.text_all}</label>
							{/if}

							{foreach from=$groupAccounts item=groupAccount key=i}

                            {assign var=pref value='account_id'}
                            {assign var=suf value=$groupAccount.account_id}
                            {assign var=che value=$pref$suf}

							<label for="account_id{$groupAccount.account_id}" class="inline-label"><input type="checkbox" name="account_id{$groupAccount.account_id}" id="account_id{$groupAccount.account_id}" value="{$groupAccount.account_id}" class="account_selector" 
                                {if $posted.$che neq ''}
                                    checked="checked"
                                {else}
                                    {if in_array($suf, $sortedAccounts)}
                                        checked="checked"
                                    {/if}    
                                {/if}
							/> {$groupAccount.account_name}</label>
							{/foreach}
						</div>
					</fieldset>
					
					<button type="submit">{$lang.button_save_changes}</button>
					<a href="{base_url}groups" class="cancel">{$lang.button_cancel}</a>
				</form>
			</section>
			
			<aside>
				<h5>{$lang.title_delete_this_group}</h5>
				<p>{$lang.text_delete_this_group_part1} {$group.group_title} {$lang.text_delete_this_group_part2}</p>
				<p><a href="{base_url}groups/actions/delete/{$group.group_id}" class="delete" onclick="return confirm('{$lang.text_are_you_sure_you_want_to_delete_group}');">{$lang.text_aside_delete} {$group.group_title}</a></p>
			</aside>
			
		</div>
	</section>
	
	<script src="{base_url}assets/js/jquery-1.4.2.min.js"></script>
	<script src="{base_url}assets/js/global.js"></script>
{include file='shared/foot.tpl'}
</body>
</html>