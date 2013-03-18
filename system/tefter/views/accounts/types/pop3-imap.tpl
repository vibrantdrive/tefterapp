<div class="group">
	<label for="email_account" class="required">{$lang.label_email_address}</label>
	<input type="text" name="email_account" id="email_account" value="{if $mode eq 'add'}{if isset($posted)}{$posted.email_account}{/if}{else}{if $mode eq 'edit'}{if $posted.email_account neq ''}{$posted.email_account}{else}{$account.email}{/if}{/if}{/if}" />
	<p class="hint">{$lang.text_pop3imap_email_hint}</p>
</div>

<div class="group">
	<label for="email_account_type" class="required">{$lang.text_table_type}</label>
	<select name="email_account_type" id="email_account_type">
		<option value="POP3" 
		{if $mode eq 'add'}
			{if isset($posted)}
				{if $posted.email_account_type eq 'POP3'}
					selected="selected"
				{/if}
			{/if}
		{else}
			{if $mode eq 'edit'}
				{if $posted.email_account_type eq 'POP3'}
					selected="selected"
				{else}
					{if $account.email_type eq 'POP3'}
						selected="selected"
					{/if}
				{/if}
			{/if}
		{/if}
		>POP3</option>
		<option value="IMAP" 
		{if $mode eq 'add'}
			{if isset($posted)}
				{if $posted.email_account_type eq 'IMAP'}
					selected="selected"
				{/if}
			{/if}
		{else}
			{if $mode eq 'edit'}
				{if $posted.email_account_type eq 'IMAP'}
					selected="selected"
				{else}
					{if $account.email_type eq 'IMAP'}
						selected="selected"
					{/if}
				{/if}
			{/if}
		{/if}
		>IMAP</option>
	</select>
</div>

<div class="group">
	<label for="incoming_mail_server" class="required">{$lang.label_incoming_mail_server}</label>
	<input type="text" name="incoming_mail_server" id="incoming_mail_server" value="{if $mode eq 'add'}{if isset($posted)}{$posted.incoming_mail_server}{/if}{else}{if $mode eq 'edit'}{if $posted.incoming_mail_server neq ''}{$posted.incoming_mail_server}{else}{$account.incoming_mail_server}{/if}{/if}{/if}" />
	<p class="hint">{$lang.text_pop3imap_server_hint}</p>
</div>

<div class="group">
	<div class="half-width separator">
		<label for="incoming_mail_server_username" class="required">{$lang.label_username}</label>
		<input type="text" name="incoming_mail_server_username" id="incoming_mail_server_username" autocomplete="off" value="{if $mode eq 'add'}{if isset($posted)}{$posted.incoming_mail_server_username}{/if}{else}{if $mode eq 'edit'}{if $posted.incoming_mail_server_username neq ''}{$posted.incoming_mail_server_username}{else}{$account.incoming_username}{/if}{/if}{/if}" />
	</div>
	
	<div class="half-width">
		<label for="incoming_mail_server_password" class="required">{$lang.label_password}</label>
		<input type="text" name="incoming_mail_server_password" id="incoming_mail_server_password" autocomplete="off" value="{if $mode eq 'add'}{if isset($posted)}{$posted.incoming_mail_server_password}{/if}{else}{if $mode eq 'edit'}{if $posted.incoming_mail_server_password neq ''}{$posted.incoming_mail_server_password}{else}{$account.incoming_password}{/if}{/if}{/if}" />
	</div>
</div>

<div class="group">
	<label for="outgoing_mail_server" class="required">{$lang.label_outgoing_mail_server}</label>
	<input type="text" name="outgoing_mail_server" id="outgoing_mail_server" value="{if $mode eq 'add'}{if isset($posted)}{$posted.outgoing_mail_server}{/if}{else}{if $mode eq 'edit'}{if $posted.outgoing_mail_server neq ''}{$posted.outgoing_mail_server}{else}{$account.outgoing_mail_server}{/if}{/if}{/if}" />
	<p class="hint">{$lang.text_pop3imap_server_hint}</p>
</div>

<div class="group">
	<div class="half-width separator">
		<label for="outgoing_mail_server_username" class="required">{$lang.label_username}</label>
		<input type="text" name="outgoing_mail_server_username" id="outgoing_mail_server_username" autocomplete="off" value="{if $mode eq 'add'}{if isset($posted)}{$posted.outgoing_mail_server_username}{/if}{else}{if $mode eq 'edit'}{if $posted.outgoing_mail_server_username neq ''}{$posted.outgoing_mail_server_username}{else}{$account.outgoing_username}{/if}{/if}{/if}" />
	</div>
	
	<div class="half-width">
		<label for="outgoing_mail_server_password" class="required">{$lang.label_password}</label>
		<input type="text" name="outgoing_mail_server_password" id="outgoing_mail_server_password" autocomplete="off" value="{if $mode eq 'add'}{if isset($posted)}{$posted.outgoing_mail_server_password}{/if}{else}{if $mode eq 'edit'}{if $posted.outgoing_mail_server_password neq ''}{$posted.outgoing_mail_server_password}{else}{$account.outgoing_password}{/if}{/if}{/if}" />
	</div>
</div>