{foreach from=$users item=userLoop key=i}
				<li>
					<ul class="reset">
						<li class="name"><strong>{$userLoop.name_first} {$userLoop.name_last}</strong></li>
						<li class="email">{$userLoop.email}</li>
						<li class="role">{$userLoop.role_title}</li>
						<li class="tools">
						{if $user.level eq 99}
							{if $user.first_admin eq 1}
								<a href="{base_url}users/edit/{$userLoop.user_id}">{$lang.text_action_edit}</a>{if $userLoop.user_id neq $user.user_id} | <a href="{base_url}users/actions/delete/{$userLoop.user_id}" class="delete" onclick="return confirm('{$lang.text_are_you_sure_you_want_to_delete_user}');">{$lang.text_action_delete}</a>{/if}
							{else}
								{if $userLoop.first_admin neq 1}
									<a href="{base_url}users/edit/{$userLoop.user_id}">{$lang.text_action_edit}</a> | <a href="{base_url}users/actions/delete/{$userLoop.user_id}" class="delete" onclick="return confirm('{$lang.text_are_you_sure_you_want_to_delete_user}');">{$lang.text_action_delete}</a>
								{/if}
							{/if}
						{else}
							{if $user.user_id eq $userLoop.user_id}
								<a href="{base_url}users/edit/{$userLoop.user_id}">{$lang.text_action_edit}</a> | <a href="{base_url}users/actions/delete/{$userLoop.user_id}" class="delete" onclick="return confirm('{$lang.text_are_you_sure_you_want_to_delete_user}');">{$lang.text_action_delete}</a>
							{/if}
						{/if}
						</li>
					</ul>
				</li>
{/foreach}				