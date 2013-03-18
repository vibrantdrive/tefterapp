{include file='shared/head.tpl'}
<body id="settings">

	{include file='shared/top_bar.tpl'}
	
	{include file='shared/header.tpl'}
	
	<section id="content">
		<div class="group block">
			<h1>{$lang.title_settings}</h1>
			
	        {if isset($m)}
	            {$m->content}
	        {/if}

			<section id="primary">
				<form action="{base_url}settings/email/action/save" method="post">
				
					<fieldset>

						<div class="group">
							<label for="return_email">{$lang.label_return_email_address}</label>
							<input type="text" name="return_email" id="return_email" value="{if $posted.return_email neq ''}{$posted.return_email}{else}{$settings.MAIL_FROM}{/if}" />
							<p class="hint">{$lang.label_hint_return_email_address}</p>
						</div>
						
						<div class="group">
							<label for="from_name">{$lang.label_from_email_name}</label>
							<input type="url" name="from_name" id="from_name" value="{if $posted.from_name neq ''}{$posted.from_name}{else}{if $settings.MAIL_NAME neq ''}{$settings.MAIL_NAME}{else}Tefter{/if}{/if}" />
						</div>
						
						<div class="group">
							<label for="email_protocol">{$lang.label_email_protocol}</label>
							<select name="email_protocol" id="email_protocol">
								<option value="mail" 
									{if $posted.email_protocol neq ''}
										{if $posted.email_protocol eq 'mail'}selected="selected"{/if}
									{else}
										{if $settings.MAIL_PROTOCOL eq 'mail'}selected="selected"{/if}
									{/if}
								>{$lang.option_php_mail}</option>
								<option value="smtp" 
									{if $posted.email_protocol neq ''}
										{if $posted.email_protocol eq 'smtp'}selected="selected"{/if}
									{else}
										{if $settings.MAIL_PROTOCOL eq 'smtp'}selected="selected"{/if}
									{/if}
								>{$lang.option_smtp}</option>
							</select>
							
							<a href="{base_url}settings/email/action/sendtestemail" class="aside-link">{$lang.text_test_mail}</a>
						</div>

						<div class="smtp 
							{if $posted.email_protocol neq ''}
								{if $posted.email_protocol eq 'smtp'}{/if}
							{else}
								{if $settings.MAIL_PROTOCOL eq 'smtp'}
								{else}
									hide
								{/if}
							{/if}
						">
							<div class="group">
								<label for="smtp_host">{$lang.label_smtp_host}</label>
								<input type="text" name="smtp_host" id="smtp_host" value="{if $posted.smtp_host neq ''}{$posted.smtp_host}{else}{$settings.MAIL_SMTP_HOST}{/if}" />
							</div>
							
							<div class="group">
								<div class="half-width separator">
									<label for="smtp_username">{$lang.label_username}</label>
									<input type="text" name="smtp_username" id="smtp_username" autocomplete="off" value="{if $posted.smtp_username neq ''}{$posted.smtp_username}{else}{$settings.MAIL_SMTP_USER}{/if}" />
								</div>
								
								<div class="half-width">
									<label for="smtp_password">{$lang.label_password}</label>
									<input type="password" name="smtp_password" id="smtp_password" value="{if $posted.smtp_password neq ''}{$posted.smtp_password}{else}{$settings.MAIL_SMTP_PASS}{/if}" />
								</div>
							</div>
						</div>
						
					</fieldset>
					
					<button type="submit">{$lang.button_save_changes}</button>
					<a href="{base_url}dashboard" class="cancel" onclick="return confirm('{$lang.text_are_you_sure_you_want_to_cancel}');">{$lang.button_cancel}</a>
				</form>
			</section>
			
			<aside>
				<ul class="reset">
					<li><a href="{base_url}settings/general">{$lang.text_general_configuration}</a></li>
					<li class="selected"><a href="{base_url}settings/email">{$lang.text_email_settings}</a></li>
				</ul>
			</aside>
			
		</div>
	</section>
	
	<script src="{base_url}assets/js/jquery-1.4.2.min.js"></script>
	<script src="{base_url}assets/js/global.js"></script>
{include file='shared/foot.tpl'}
</body>
</html>