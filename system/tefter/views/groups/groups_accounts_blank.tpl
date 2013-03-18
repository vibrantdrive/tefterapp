{if $user.level eq 99}
	<h2>{$lang.message_havent_added_accounts_yet}</h2>
	<h5><a href="{base_url}groups/edit/{$group.group_id}">{$lang.link_edit_group}</a></h5>
{else}
	<p>{$lang.message_no_accounts_to_show}</p>
{/if}