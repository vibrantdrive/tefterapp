<ul class="group reset">
	<li {if $selectMenuItem eq 'dashboard'}class="current"{/if}><a href="{base_url}dashboard">{$lang.text_menu_dashboard}</a></li>
	{if $user.level eq 99}
	<li {if $selectMenuItem eq 'clients'}class="current"{/if}><a href="{base_url}clients">{$lang.text_menu_clients}</a></li>
	{/if}
	<li {if $selectMenuItem eq 'accounts'}class="current"{/if}><a href="{base_url}accounts">{$lang.text_menu_accounts}</a></li>
	<li {if $selectMenuItem eq 'groups'}class="current"{/if}><a href="{base_url}groups">{$lang.text_menu_groups}</a></li>
	<li {if $selectMenuItem eq 'users'}class="current"{/if}><a href="{base_url}users">{$lang.text_menu_users}</a></li>
	{if $user.level eq 99}
	<li {if $selectMenuItem eq 'settings'}class="current"{/if}><a href="{base_url}settings/general">{$lang.text_menu_settings}</a></li>
	{/if}
</ul>