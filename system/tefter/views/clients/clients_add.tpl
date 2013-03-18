{include file='shared/head.tpl'}
<body id="cleints-add">

	{include file='shared/top_bar.tpl'}
	
	{include file='shared/header.tpl'}
	
	<section id="content">
		<div class="group block">
			<h1>{$lang.title_add_client}</h1>
			
			<a href="{base_url}clients" class="go-back">{$lang.text_back_to_clients}</a>
			
			<section id="primary">
				<form action="{base_url}clients/actions/add" method="post">
				
					<fieldset>

	                    {if isset($m)}
	                        {$m->content}
	                    {/if}

						<div class="group">
							<label for="client" class="required">{$lang.label_company_name}</label>
							<input type="text" name="client" id="client" autocomplete="off" value="{if $posted.client neq ''}{$posted.client}{/if}" />
						</div>
						
						<div class="group">
							<label for="client_address">{$lang.label_address}</label>
							<input type="text" name="client_address" id="client_address" autocomplete="off" value="{if $posted.client_address neq ''}{$posted.client_address}{/if}" />
						</div>
						
						<div class="group">
							<label for="client_city">{$lang.label_city}</label>
							<input type="text" name="client_city" id="client_city" autocomplete="off" value="{if $posted.client_city neq ''}{$posted.client_city}{/if}" />
						</div>
						
						<div class="group">
							<div class="half-width separator">
								<label for="client_state">{$lang.label_state}</label>
								<input type="text" name="client_state" id="client_state" autocomplete="off" value="{if $posted.client_state neq ''}{$posted.client_state}{/if}" />
							</div>
							
							<div class="half-width">
								<label for="client_zip">{$lang.label_zip}</label>
								<input type="text" name="client_zip" id="client_zip" autocomplete="off" value="{if $posted.client_zip neq ''}{$posted.client_zip}{/if}" />
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
								{if $posted.client_country eq $country.country_code}selected="selected"{/if}
								>{$country.country_name}</option>
								{/foreach}
							</select>
						</div>
						
						<div class="group">
							<div class="half-width separator">
								<label for="client_phone">{$lang.label_phone}</label>
								<input type="text" name="client_phone" id="client_phone" autocomplete="off" value="{if $posted.client_phone neq ''}{$posted.client_phone}{/if}" />
							</div>
							
							<div class="half-width">
								<label for="client_fax">{$lang.label_fax}</label>
								<input type="text" name="client_fax" id="client_fax" autocomplete="off" value="{if $posted.client_fax neq ''}{$posted.client_fax}{/if}" />
							</div>
						</div>
						
						<div class="group">
							<label for="client_email">{$lang.label_email}</label>
							<input type="text" name="client_email" id="client_email" autocomplete="off" value="{if $posted.client_email neq ''}{$posted.client_email}{/if}" />
						</div>
						
					</fieldset>
					
					<button type="submit">{$lang.button_add_client}</button>
					<a href="{base_url}clients" class="cancel" onclick="return confirm('{$lang.text_are_you_sure_you_want_to_cancel}');">{$lang.button_cancel}</a>
				</form>
			</section>
			
		</div>
	</section>
	
	<script src="{base_url}assets/js/jquery-1.4.2.min.js"></script>
	<script src="{base_url}assets/js/global.js"></script>
    <script type="text/javascript">
	    {literal}
	        $(document).ready(function()
	        {
	        	$('#client').focus();
			});
		{/literal}
	</script>
{include file='shared/foot.tpl'}
</body>
</html>