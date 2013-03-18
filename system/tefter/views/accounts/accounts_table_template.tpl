{foreach from=$accounts item=account key=i}
<tr id="row{$account.account_id}">
	<td>
		<a id="datalink{$account.account_id}" href="javascript: loadData({$account.account_id});">{$account.account_name}</a>
		<span class="loading hide" id="loader_small_{$account.account_id}"></span>
	</td>
	<td>{$account.account_type_name}</td>
	{if $withClient eq 'yes'}
	<td><a href="{base_url}clients/details/{$account.client_id}">{$account.company_name}</a></td>
	{/if}
	<td>{$account.dateEntered}</td>
	<td>
		<ul class="group reset actions">
			<li><a href="javascript: loadData({$account.account_id});" title="{$lang.text_action_view}" class="view" id="view_{$account.account_id}">{$lang.text_action_view}</a></li>
			{if $user.level gt 20}
			<li><a href="#" class="send send-selector" id="send-{$account.account_id}" title="{$lang.text_action_send_via_email}">{$lang.text_action_send_via_email}</a></li>
			<li><a href="{base_url}accounts/edit/{$account.account_id}" class="edit" title="{$lang.text_action_edit}">{$lang.text_action_edit}</a></li>
			<li><a href="{base_url}accounts/actions/delete/{$account.account_id}" class="delete" title="{$lang.text_action_delete}" onclick="return confirm('{$lang.text_are_you_sure_you_want_to_delete_account}');">{$lang.text_action_delete}</a></li>
			{/if}
		</ul>
	</td>
</tr>
{/foreach}