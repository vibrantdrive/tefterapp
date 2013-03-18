{foreach from=$groups item=group key=i}
<tr>
	<td><a href="{base_url}groups/details/{$group.group_id}"><strong>{$group.group_title}</strong></a></td>
	<td>{$group.totalAccounts}</td>
	<td>{$group.totalUsers}</td>
	<td>
		<ul class="group reset actions">
			<li><a href="{base_url}groups/details/{$group.group_id}" class="view">{$lang.text_action_view}</a></li>
			{if $user.level gt 20}
			<li><a href="{base_url}groups/edit/{$group.group_id}" class="edit">{$lang.text_action_edit}</a></li>
			<li><a href="{base_url}groups/actions/delete/{$group.group_id}" class="delete" onclick="return confirm('{$lang.text_are_you_sure_you_want_to_delete_group}');">{$lang.text_action_delete}</a></li>
			{/if}
		</ul>
	</td>
</tr>
{/foreach}