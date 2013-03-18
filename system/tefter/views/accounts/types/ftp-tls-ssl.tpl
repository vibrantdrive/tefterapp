<div class="group">
	<label for="server" class="required">{$lang.label_server}</label>
	<input type="text" name="server" id="server" autocomplete="off" value="{if $mode eq 'add'}{if isset($posted)}{$posted.server}{/if}{else}{if $mode eq 'edit'}{if $posted.server neq ''}{$posted.server}{else}{$account.server}{/if}{/if}{/if}" />
	<p class="hint">{$lang.text_server_hint}</p>
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

<a href="#" class="more-options">{$lang.text_more_options}</a>

<div class="type_ftp_options 
{if $mode eq 'edit' or isset($posted)}
{else}
	hide
{/if}
">
	<div class="group">
		<label for="port">{$lang.text_port}</label>
		<input type="text" name="port" id="port" autocomplete="off" value="{if $mode eq 'add'}{if isset($posted)}{$posted.port}{/if}{else}{if $mode eq 'edit'}{if $posted.port neq ''}{$posted.port}{else}{$account.port}{/if}{/if}{/if}" />
		
		<label for="passive_mode" class="passive">
			<input type="checkbox" name="passive_mode" id="passive_mode" value="Yes" 
			{if $mode eq 'add'}
				{if isset($posted)}
					{if $posted.passive_mode eq 'Yes'}checked="checked"{/if}
				{/if}
			{else}	
				{if $mode eq 'edit'}
	                {if isset($posted)}
	                    {if $posted.passive_mode eq 'Yes'}
	                        checked="checked"
	                    {/if}
	                {else}
	                    {if $account.passive_mode eq 1}
	                        checked="checked"
	                    {/if}    
	                {/if}
	            {/if}
			{/if}
			/> {$lang.text_use_passive_mode}</label>
	</div>
	
	<div class="group">
		<label for="root_url">{$lang.text_root_url}</label>
		<input type="text" name="root_url" id="root_url" autocomplete="off" value="{if $mode eq 'add'}{if isset($posted)}{$posted.root_url}{/if}{else}{if $mode eq 'edit'}{if $posted.root_url neq ''}{$posted.root_url}{else}{$account.root_url}{/if}{/if}{/if}" />
		<p class="hint">{$lang.text_root_url_hint}</p>
	</div>
	
	<div class="group">
		<label for="remote_path">{$lang.text_remote_path}</label>
		<input type="text" name="remote_path" id="remote_path" autocomplete="off" value="{if $mode eq 'add'}{if isset($posted)}{$posted.remote_path}{/if}{else}{if $mode eq 'edit'}{if $posted.remote_path neq ''}{$posted.remote_path}{else}{$account.remote_path}{/if}{/if}{/if}" />
		<p class="hint">{$lang.text_remote_path_hint}</p>
	</div>
</div>