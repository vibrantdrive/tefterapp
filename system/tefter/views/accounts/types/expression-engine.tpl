<div class="group">								
	<div class="half-width separator">
		<label for="username">{$lang.label_username}</label>
		<input type="text" name="username" id="username" autocomplete="off" value="{if $mode eq 'add'}{if isset($posted)}{$posted.username}{/if}{else}{if $mode eq 'edit'}{if $posted.username neq ''}{$posted.username}{else}{$account.username}{/if}{/if}{/if}" />
	</div>
	
	<div class="half-width">
		<label for="password">{$lang.label_password}</label>
		<input type="text" name="password" id="password" autocomplete="off" value="{if $mode eq 'add'}{if isset($posted)}{$posted.password}{/if}{else}{if $mode eq 'edit'}{if $posted.password neq ''}{$posted.password}{else}{$account.password}{/if}{/if}{/if}"/>
	</div>
</div>

<div class="group">
	<label for="login_url">{$lang.label_login_url}</label>
	<input type="text" name="login_url" id="login_url" autocomplete="off" value="{if $mode eq 'add'}{if isset($posted)}{$posted.login_url}{/if}{else}{if $mode eq 'edit'}{if $posted.login_url neq ''}{$posted.login_url}{else}{$account.login_url}{/if}{/if}{/if}" />
	<p class="hint">{$lang.text_login_url_hint}</p>
</div>