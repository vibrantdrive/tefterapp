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