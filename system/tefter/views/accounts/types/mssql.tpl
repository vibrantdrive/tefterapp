<div class="group">
	<label for="name" class="required">{$lang.label_database_name}</label>
	<input type="text" name="name" id="name" autocomplete="off" value="{if $mode eq 'add'}{if isset($posted)}{$posted.name}{/if}{else}{if $mode eq 'edit'}{if $posted.name neq ''}{$posted.name}{else}{$account.name}{/if}{/if}{/if}" />
</div>

<div class="group">
	<div class="half-width separator">
		<label for="username" class="required">{$lang.label_username}</label>
		<input type="text" name="username" id="username" autocomplete="off" value="{if $mode eq 'add'}{if isset($posted)}{$posted.username}{/if}{else}{if $mode eq 'edit'}{if $posted.username neq ''}{$posted.username}{else}{$account.username}{/if}{/if}{/if}" />
	</div>
	
	<div class="half-width">
		<label for="password" class="required">{$lang.label_password}</label>
		<input type="text" name="password" id="password" autocomplete="off" value="{if $mode eq 'add'}{if isset($posted)}{$posted.password}{/if}{else}{if $mode eq 'edit'}{if $posted.password neq ''}{$posted.password}{else}{$account.password}{/if}{/if}{/if}" />
	</div>
</div>

<div class="group">
	<label for="host" class="required">{$lang.label_host}</label>
	<input type="text" name="host" id="host" autocomplete="off" value="{if $mode eq 'add'}{if isset($posted)}{$posted.host}{/if}{else}{if $mode eq 'edit'}{if $posted.host neq ''}{$posted.host}{else}{$account.server}{/if}{/if}{/if}" />
	<p class="hint">{$lang.text_host_hint}</p>
</div>