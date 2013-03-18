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
				<form action="{base_url}settings/general/action/save" method="post">
				
					<fieldset>

						<div class="group">
							<label for="license" class="required">{$lang.label_license_number}</label>
							<input type="text" name="license" id="license" value="{if $posted.license neq ''}{$posted.license}{else}{$settings.LICENSE_NUMBER}{/if}" />
						</div>
						
						<div class="group">
							<label for="company">{$lang.label_name_company}</label>
							<input type="text" name="company" id="company" value="{if $posted.company neq ''}{$posted.company}{else}{$settings.NAME_COMPANY}{/if}" />
						</div>
						
						<div class="group">
							<label for="default_lang">{$lang.label_default_language}</label>
							<select name="default_lang" id="default_lang">
								{section name=language loop=$languages}
                                <option value="{$languages[language]->file}" 
                                    {if $posted.default_lang neq ''}
                                        {if $posted.default_lang eq $languages[language]->file}
                                            selected="selected"
                                        {/if}
                                    {else}
                                        {if $settings.DEFAULT_LANGUAGE eq $languages[language]->file}
                                            selected="selected"
                                        {/if}
                                    {/if}>{$languages[language]->title}</option>
								{/section}
							</select>
						</div>
						
						<div class="group">
							<label for="date_time_format">{$lang.label_default_date_formatting}</label>
							<select name="date_time_format" id="date_time_format">
								<option value="US" 
									{if $posted.date_time_format neq ''}
										{if $posted.date_time_format eq 'US'}selected="selected"{/if}
									{else}
										{if $settings.DATE_FORMATTING eq 'US'}selected="selected"{/if}
									{/if}
								>United States</option>	
								<option value="EU" 
									{if $posted.date_time_format neq ''}
										{if $posted.date_time_format eq 'EU'}selected="selected"{/if}
									{else}
										{if $settings.DATE_FORMATTING eq 'EU'}selected="selected"{/if}
									{/if}	
								>European</option>
							</select>
						</div>
						
						<div class="group">
							<label for="items_per_page">{$lang.label_number_items_per_page}</label>
							<input type="text" name="items_per_page" id="items_per_page" value="{if $posted.items_per_page neq ''}{$posted.items_per_page}{else}{$settings.ITEMS_PER_PAGE}{/if}" />
						</div>
						
						<div class="group">
							<label for="server_timezone">{$lang.label_server_timezone}</label>
							<select name="server_timezone" id="server_timezone" class="select">
								<option value="UM12" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UM12'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UM12'}selected="selected"{/if}
									{/if}	
								>(UTC -12:00) Baker/Howland Island</option>
								<option value="UM11" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UM11'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UM11'}selected="selected"{/if}
									{/if}	
								>(UTC -11:00) Samoa Time Zone, Niue</option>
								<option value="UM10" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UM10'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UM10'}selected="selected"{/if}
									{/if}	
								>(UTC -10:00) Hawaii-Aleutian Standard Time, Cook Islands, Tahiti</option>
								<option value="UM95" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UM95'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UM95'}selected="selected"{/if}
									{/if}	
								>(UTC -9:30) Marquesas Islands</option>
								<option value="UM9" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UM9'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UM9'}selected="selected"{/if}
									{/if}	
								>(UTC -9:00) Alaska Standard Time, Gambier Islands</option>
								<option value="UM8" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UM8'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UM8'}selected="selected"{/if}
									{/if}	
								>(UTC -8:00) Pacific Standard Time, Clipperton Island</option>
								<option value="UM7" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UM7'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UM7'}selected="selected"{/if}
									{/if}	
								>(UTC -7:00) Mountain Standard Time</option>
								<option value="UM6" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UM6'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UM6'}selected="selected"{/if}
									{/if}	
								>(UTC -6:00) Central Standard Time</option>
								<option value="UM5" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UM5'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UM5'}selected="selected"{/if}
									{/if}	
								>(UTC -5:00) Eastern Standard Time, Western Caribbean Standard Time</option>
								<option value="UM45" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UM45'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UM45'}selected="selected"{/if}
									{/if}	
								>(UTC -4:30) Venezuelan Standard Time</option>
								<option value="UM4" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UM4'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UM4'}selected="selected"{/if}
									{/if}	
								>(UTC -4:00) Atlantic Standard Time, Eastern Caribbean Standard Time</option>
								<option value="UM35" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UM35'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UM35'}selected="selected"{/if}
									{/if}	
								>(UTC -3:30) Newfoundland Standard Time</option>
								<option value="UM3" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UM3'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UM3'}selected="selected"{/if}
									{/if}	
								>(UTC -3:00) Argentina, Brazil, French Guiana, Uruguay</option>
								<option value="UM2" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UM2'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UM2'}selected="selected"{/if}
									{/if}	
								>(UTC -2:00) South Georgia/South Sandwich Islands</option>
								<option value="UM1" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UM1'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UM1'}selected="selected"{/if}
									{/if}	
								>(UTC -1:00) Azores, Cape Verde Islands</option>
								<option value="UTC" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UTC'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UTC'}selected="selected"{/if}
									{/if}	
								>(UTC) Greenwich Mean Time, Western European Time</option>
								<option value="UP1" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP1'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UP1'}selected="selected"{/if}
									{/if}	
								>(UTC +1:00) Central European Time, West Africa Time</option>
								<option value="UP2" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP2'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UP2'}selected="selected"{/if}
									{/if}	
								>(UTC +2:00) Central Africa Time, Eastern European Time, Kaliningrad Time</option>
								<option value="UP3" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP3'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UP3'}selected="selected"{/if}
									{/if}	
								>(UTC +3:00) Moscow Time, East Africa Time</option>
								<option value="UP35" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP35'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UP35'}selected="selected"{/if}
									{/if}	
								>(UTC +3:30) Iran Standard Time</option>
								<option value="UP4" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP4'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UP4'}selected="selected"{/if}
									{/if}	
								>(UTC +4:00) Azerbaijan Standard Time, Samara Time</option>
								<option value="UP45" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP45'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UP45'}selected="selected"{/if}
									{/if}	
								>(UTC +4:30) Afghanistan</option>
								<option value="UP5" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP5'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UP5'}selected="selected"{/if}
									{/if}	
								>(UTC +5:00) Pakistan Standard Time, Yekaterinburg Time</option>
								<option value="UP55" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP55'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UP55'}selected="selected"{/if}
									{/if}	
								>(UTC +5:30) Indian Standard Time, Sri Lanka Time</option>
								<option value="UP575" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP575'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UP575'}selected="selected"{/if}
									{/if}	
								>(UTC +5:45) Nepal Time</option>
								<option value="UP6" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP6'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UP6'}selected="selected"{/if}
									{/if}	
								>(UTC +6:00) Bangladesh Standard Time, Bhutan Time, Omsk Time</option>
								<option value="UP65" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP65'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UP65'}selected="selected"{/if}
									{/if}	
								>(UTC +6:30) Cocos Islands, Myanmar</option>
								<option value="UP7" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP7'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UP7'}selected="selected"{/if}
									{/if}	
								>(UTC +7:00) Krasnoyarsk Time, Cambodia, Laos, Thailand, Vietnam</option>
								<option value="UP8" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP8'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UP8'}selected="selected"{/if}
									{/if}	
								>(UTC +8:00) Australian Western Standard Time, Beijing Time, Irkutsk Time</option>
								<option value="UP875" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP875'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UP875'}selected="selected"{/if}
									{/if}	
								>(UTC +8:45) Australian Central Western Standard Time</option>
								<option value="UP9" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP9'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UP9'}selected="selected"{/if}
									{/if}	
								>(UTC +9:00) Japan Standard Time, Korea Standard Time, Yakutsk Time</option>
								<option value="UP95" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP95'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UP95'}selected="selected"{/if}
									{/if}	
								>(UTC +9:30) Australian Central Standard Time</option>
								<option value="UP10" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP10'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UP10'}selected="selected"{/if}
									{/if}	
								>(UTC +10:00) Australian Eastern Standard Time, Vladivostok Time</option>
								<option value="UP105" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP105'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UP105'}selected="selected"{/if}
									{/if}	
								>(UTC +10:30) Lord Howe Island</option>
								<option value="UP11" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP11'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UP11'}selected="selected"{/if}
									{/if}	
								>(UTC +11:00) Magadan Time, Solomon Islands, Vanuatu</option>
								<option value="UP115" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP115'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UP115'}selected="selected"{/if}
									{/if}	
								>(UTC +11:30) Norfolk Island</option>
								<option value="UP12" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP12'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UP12'}selected="selected"{/if}
									{/if}	
								>(UTC +12:00) Fiji, Gilbert Islands, Kamchatka Time, New Zealand Standard Time</option>
								<option value="UP1275" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP1275'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UP1275'}selected="selected"{/if}
									{/if}	
								>(UTC +12:45) Chatham Islands Standard Time</option>
								<option value="UP13" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP13'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UP13'}selected="selected"{/if}
									{/if}	
								>(UTC +13:00) Phoenix Islands Time, Tonga</option>
								<option value="UP14" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP14'}
											selected="selected"
										{/if}
									{else}
										{if $settings.TIME_ZONE eq 'UP14'}selected="selected"{/if}
									{/if}	
								>(UTC +14:00) Line Islands</option>
							</select>
						</div>
					</fieldset>
					
					<button type="submit">{$lang.button_save_changes}</button>
					<a href="{base_url}dashboard" class="cancel" onclick="return confirm('{$lang.text_are_you_sure_you_want_to_cancel}');">{$lang.button_cancel}</a>
				</form>
			</section>
			
			<aside>
				<ul class="reset">
					<li class="selected"><a href="{base_url}settings/general">{$lang.text_general_configuration}</a></li>
					<li><a href="{base_url}settings/email">{$lang.text_email_settings}</a></li>
				</ul>
			</aside>
			
		</div>
	</section>
	
	<script src="{base_url}assets/js/jquery-1.4.2.min.js"></script>
	<script src="{base_url}assets/js/global.js"></script>
{include file='shared/foot.tpl'}
</body>
</html>