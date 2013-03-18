{foreach from=$clients item=client key=i}
				<li>
					<ul class="reset">
						<li class="name"><strong><a href="{base_url}clients/details/{$client.client_id}" class="view">{$client.company_name}</a></strong></li>
						<li class="total-accounts">{$client.total} {$lang.text_accounts}</li>
						<li class="tools">
							{if $user.level eq 99}
							<a href="{base_url}clients/edit/{$client.client_id}" class="edit">{$lang.text_action_edit}</a> | 
							<a href="{base_url}clients/actions/delete/{$client.client_id}" class="delete" onclick="return confirm('{$lang.text_are_you_sure_you_want_to_delete_client}');">{$lang.text_action_delete}</a>
							{/if}
						</li>
					</ul>
				</li>
{/foreach}