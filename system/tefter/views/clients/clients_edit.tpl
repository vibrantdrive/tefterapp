{include file='shared/head.tpl'}
<body id="cleints-add">

	{include file='shared/top_bar.tpl'}
	
	{include file='shared/header.tpl'}
	
	<section id="content">
		<div class="group block">
			<h1>{$lang.title_edit_client}</h1>
			
			<a href="{base_url}clients" class="go-back">{$lang.text_back_to_clients}</a>
			
			<section id="primary">
				<form action="{base_url}clients/actions/edit" method="post">
				
					<input type="hidden" name="client_id" id="client_id" value="{$client.client_id}">

					<fieldset>

	                    {if isset($m)}
	                        {$m->content}
	                    {/if}

						<div class="group">
							<label for="client" class="required">{$lang.label_company_name}</label>
							<input type="text" name="client" id="client" value="{if $posted.client neq ''}{$posted.client}{else}{$client.company_name}{/if}" autocomplete="off" />
						</div>
						
						<div class="group">
							<label for="client_address">{$lang.label_address}</label>
							<input type="text" name="client_address" id="client_address" value="{if $posted.client_address neq ''}{$posted.client_address}{else}{$client.address}{/if}" autocomplete="off" />
						</div>
						
						<div class="group">
							<label for="client_city">{$lang.label_city}</label>
							<input type="text" name="client_city" id="client_city" value="{if $posted.client_city neq ''}{$posted.client_city}{else}{$client.city}{/if}" autocomplete="off" />
						</div>
						
						<div class="group">
							<div class="half-width separator">
								<label for="client_state">{$lang.label_state}</label>
								<input type="text" name="client_state" id="client_state" value="{if $posted.client_state neq ''}{$posted.client_state}{else}{$client.state}{/if}" autocomplete="off" />
							</div>
							
							<div class="half-width">
								<label for="client_zip">{$lang.label_zip}</label>
								<input type="text" name="client_zip" id="client_zip" value="{if $posted.client_zip neq ''}{$posted.client_zip}{else}{$client.postal_code}{/if}" autocomplete="off" />
							</div>
						</div>
						
						<div class="group">
							<label for="client_country">{$lang.label_country}</label>
							<select name="client_country" id="client_country">
								<option value="" 
								{if $posted.client_country eq ''}selected="selected"{/if}
								>{$lang.option_please_select}</option>
								{foreach from=$countries item=country key=i}
								<option value="{$country.country_code}" 
								{if $posted.client_country eq $country.country_code}
									selected="selected"
								{else}
									{if $client.country_code eq $country.country_code}
										selected="selected"
									{/if}										
								{/if}
								>{$country.country_name}</option>
								{/foreach}
							</select>
						</div>
						
						<div class="group">
							<div class="half-width separator">
								<label for="client_phone">{$lang.label_phone}</label>
								<input type="text" name="client_phone" id="client_phone" value="{if $posted.client_phone neq ''}{$posted.client_phone}{else}{$client.phone_office}{/if}" autocomplete="off" />
							</div>
							
							<div class="half-width">
								<label for="client_fax">{$lang.label_fax}</label>
								<input type="text" name="client_fax" id="client_fax" value="{if $posted.client_fax neq ''}{$posted.client_fax}{else}{$client.fax}{/if}" autocomplete="off" />
							</div>
						</div>
						
						<div class="group">
							<label for="client_email">{$lang.label_email}</label>
							<input type="text" name="client_email" id="client_email" value="{if $posted.client_email neq ''}{$posted.client_email}{else}{$client.email}{/if}" autocomplete="off" />
						</div>
						
					</fieldset>
					
					<button type="submit">{$lang.button_save_changes}</button>
					<a href="{base_url}clients" class="cancel">{$lang.button_cancel}</a>
				</form>
			</section>
			
			<aside>
				<h5>{$lang.title_delete_this_client}</h5>
				<p>{$lang.text_delete_this_client_part1} {$client.company_name} {$lang.text_delete_this_client_part2}</p>
				<p><a href="{base_url}clients/actions/delete/{$client.client_id}" class="delete" onclick="return confirm('{$lang.text_are_you_sure_you_want_to_delete_client}');">{$lang.text_aside_delete} {$client.company_name}</a></p>
			</aside>
			
		</div>
	</section>
	
	<script src="{base_url}assets/js/jquery-1.4.2.min.js"></script>
	<script src="{base_url}assets/js/global.js"></script>
{include file='shared/foot.tpl'}
</body>
</html>