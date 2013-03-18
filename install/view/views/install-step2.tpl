{include file='head.tpl'}

<body id="install">
	
	<section id="content">
		<div class="group block">
			<h1>{$lang.title_step2}</h1>
			
	        {if isset($m)}
	            {$m->content}
	        {/if}

			<h3>{$lang.text_enter_settings}</h3>
			
			<section id="primary">
				<form action="../install/install-actions.php" method="post">
					
					<input type="hidden" name="license" id="license" value="{if isset($posted)}{$posted.license}{else}{$license}{/if}">
					
					<fieldset>
						<div class="group">
							<label for="license_number" class="required">{$lang.label_license_number}</label>
							<input type="text" name="license_number" id="license_number" maxlength="50" value="{if isset($posted)}{$posted.license_number}{/if}" autocomplete="off" />
							<p class="hint">{$lang.text_available_on_your} <a href="http://tefterapp.com/account/" target="_blank">{$lang.text_profile_page}</a> {$lang.text_of_the_domain}</a></p>
						</div>
						
						<div class="group">
							<label for="company">{$lang.label_company_name}</label>
							<input type="text" name="company" id="company" maxlength="50" value="{if isset($posted)}{$posted.company}{/if}" autocomplete="off" />
							<p class="hint">{$lang.label_company_name_hint}</p>
						</div>
					</fieldset>
					
					<fieldset>
						<h4>{$lang.text_server_and_database_settings}</h4>
						
						<p>{$lang.text_server_and_database_settings_help}</p>
						
						<div class="group">
							<label for="base_url" class="required">{$lang.label_base_url}</label>
							<input type="text" name="base_url" id="base_url" maxlength="200" value="{if isset($posted)}{$posted.base_url}{/if}" autocomplete="off" />
							<p class="hint">{$lang.label_base_url_hint}</p>
						</div>

						<div class="group">
							<label for="directory_path" class="required">{$lang.label_path_to_the_root}</label>
							<input type="text" name="directory_path" id="directory_path" maxlength="100" value="{if isset($posted)}{$posted.directory_path}{else}{$cwd}{/if}" autocomplete="off" />
							<p class="hint">{$lang.label_path_to_the_root_hint}</p>
						</div>
						
						<div class="group">
							<label for="application_path" class="required">{$lang.label_path_to_the_application}</label>
							<input type="text" name="application_path" id="application_path" maxlength="100" value="{if isset($posted)}{$posted.application_path}{/if}" autocomplete="off" />
							<p class="hint">{$lang.label_path_to_the_application_hint}</p>
						</div>

						<div class="group">
							<label for="mysql_host" class="required">{$lang.label_database_host}</label>
							<input type="text" name="mysql_host" id="mysql_host" maxlength="50" value="{if isset($posted)}{$posted.mysql_host}{/if}" autocomplete="off" />
							<p class="hint">{$lang.label_database_host_hint1} <strong>{$lang.text_localhost}</strong>, {$lang.label_database_host_hint2}</p>
						</div>
						
						<div class="group">
							<label for="mysql_username" class="required">{$lang.label_database_username}</label>
							<input type="text" name="mysql_username" id="mysql_username" maxlength="50" value="{if isset($posted)}{$posted.mysql_username}{/if}" autocomplete="off" />
							<p class="hint">{$lang.label_database_username_hint}</p>
						</div>
						
						<div class="group">
							<label for="mysql_password" class="required">{$lang.label_database_password}</label>
							<input type="password" name="mysql_password" id="mysql_password" maxlength="50" value="{if isset($posted)}{$posted.mysql_password}{/if}" autocomplete="off" />
							<p class="hint">{$lang.label_database_password_hint}</p>
						</div>
						
						<div class="group">
							<label for="mysql_name" class="required">{$lang.label_database_name}</label>
							<input type="text" name="mysql_name" id="mysql_name" maxlength="50" value="{if isset($posted)}{$posted.mysql_name}{/if}" autocomplete="off" />
							<p class="hint">{$lang.label_database_name_hint1} <strong>{$lang.text_not}</strong> {$lang.label_database_name_hint2}</p>
						</div>
						
						<div class="group">
							<label for="mysql_prefix" class="required">{$lang.label_database_prefix}</label>
							<input type="text" name="mysql_prefix" id="mysql_prefix" value="{if isset($posted)}{$posted.mysql_prefix}{else}tef_{/if}" maxlength="50" autocomplete="off" />
							<p class="hint">{$lang.text_use} <strong>{$lang.text_database_prefix_example}</strong> {$lang.label_database_prefix_hint}</p>
						</div>
					</fieldset>
					
					<fieldset>
						<h4>{$lang.text_create_admin_account}</h4>
						
						<p>{$lang.text_create_admin_account_help}</p>
						
						<div class="group">
							<label for="admin_username" class="required">{$lang.label_username}</label>
							<input type="text" name="admin_username" id="admin_username" maxlength="100" value="{if isset($posted)}{$posted.admin_username}{/if}" autocomplete="off" />
							<p class="hint">{$lang.label_username_hint}</p>
						</div>
						
						<div class="group">
							<label for="admin_password" class="required">{$lang.label_password}</label>
							<input type="password" name="admin_password" id="admin_password" maxlength="50" value="{if isset($posted)}{$posted.admin_password}{/if}" autocomplete="off" />
							<p class="hint">{$lang.label_password_hint}</p>
						</div>
						
						<div class="group">
							<label for="admin_password_again" class="required">{$lang.label_password_check}</label>
							<input type="password" name="admin_password_again" id="admin_password_again" maxlength="50" value="{if isset($posted)}{$posted.admin_password_again}{/if}" autocomplete="off" />
						</div>
					</fieldset>
					
					<fieldset>
						<h4>{$lang.label_localization_settings}</h4>
						
						<p>{$lang.label_localization_settings_help}</p>
						
						<div class="group">
							<label for="server_timezone">{$lang.label_server_timezone}</label>
							<select name="server_timezone" id="server_timezone" class="select">
								<option value="UM12" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UM12'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC -12:00) Baker/Howland Island</option>
								<option value="UM11" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UM11'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC -11:00) Samoa Time Zone, Niue</option>
								<option value="UM10" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UM10'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC -10:00) Hawaii-Aleutian Standard Time, Cook Islands, Tahiti</option>
								<option value="UM95" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UM95'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC -9:30) Marquesas Islands</option>
								<option value="UM9" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UM9'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC -9:00) Alaska Standard Time, Gambier Islands</option>
								<option value="UM8" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UM8'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC -8:00) Pacific Standard Time, Clipperton Island</option>
								<option value="UM7" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UM7'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC -7:00) Mountain Standard Time</option>
								<option value="UM6" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UM6'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC -6:00) Central Standard Time</option>
								<option value="UM5" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UM5'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC -5:00) Eastern Standard Time, Western Caribbean Standard Time</option>
								<option value="UM45" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UM45'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC -4:30) Venezuelan Standard Time</option>
								<option value="UM4" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UM4'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC -4:00) Atlantic Standard Time, Eastern Caribbean Standard Time</option>
								<option value="UM35" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UM35'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC -3:30) Newfoundland Standard Time</option>
								<option value="UM3" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UM3'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC -3:00) Argentina, Brazil, French Guiana, Uruguay</option>
								<option value="UM2" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UM2'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC -2:00) South Georgia/South Sandwich Islands</option>
								<option value="UM1" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UM1'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC -1:00) Azores, Cape Verde Islands</option>
								<option value="UTC" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UTC'}
											selected="selected"
										{/if}
									{else}
										selected="selected"
									{/if}	
								>(UTC) Greenwich Mean Time, Western European Time</option>
								<option value="UP1" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP1'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC +1:00) Central European Time, West Africa Time</option>
								<option value="UP2" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP2'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC +2:00) Central Africa Time, Eastern European Time, Kaliningrad Time</option>
								<option value="UP3" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP3'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC +3:00) Moscow Time, East Africa Time</option>
								<option value="UP35" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP35'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC +3:30) Iran Standard Time</option>
								<option value="UP4" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP4'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC +4:00) Azerbaijan Standard Time, Samara Time</option>
								<option value="UP45" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP45'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC +4:30) Afghanistan</option>
								<option value="UP5" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP5'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC +5:00) Pakistan Standard Time, Yekaterinburg Time</option>
								<option value="UP55" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP55'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC +5:30) Indian Standard Time, Sri Lanka Time</option>
								<option value="UP575" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP575'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC +5:45) Nepal Time</option>
								<option value="UP6" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP6'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC +6:00) Bangladesh Standard Time, Bhutan Time, Omsk Time</option>
								<option value="UP65" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP65'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC +6:30) Cocos Islands, Myanmar</option>
								<option value="UP7" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP7'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC +7:00) Krasnoyarsk Time, Cambodia, Laos, Thailand, Vietnam</option>
								<option value="UP8" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP8'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC +8:00) Australian Western Standard Time, Beijing Time, Irkutsk Time</option>
								<option value="UP875" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP875'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC +8:45) Australian Central Western Standard Time</option>
								<option value="UP9" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP9'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC +9:00) Japan Standard Time, Korea Standard Time, Yakutsk Time</option>
								<option value="UP95" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP95'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC +9:30) Australian Central Standard Time</option>
								<option value="UP10" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP10'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC +10:00) Australian Eastern Standard Time, Vladivostok Time</option>
								<option value="UP105" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP105'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC +10:30) Lord Howe Island</option>
								<option value="UP11" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP11'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC +11:00) Magadan Time, Solomon Islands, Vanuatu</option>
								<option value="UP115" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP115'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC +11:30) Norfolk Island</option>
								<option value="UP12" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP12'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC +12:00) Fiji, Gilbert Islands, Kamchatka Time, New Zealand Standard Time</option>
								<option value="UP1275" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP1275'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC +12:45) Chatham Islands Standard Time</option>
								<option value="UP13" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP13'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC +13:00) Phoenix Islands Time, Tonga</option>
								<option value="UP14" 
									{if isset($posted)}
										{if $posted.server_timezone eq 'UP14'}
											selected="selected"
										{/if}
									{/if}	
								>(UTC +14:00) Line Islands</option>
							</select>
						</div>
						
						<div class="group">
							<label for="formatting">{$lang.label_date_time_formatting}</label>
							<select name="formatting" id="formatting">
								<option value="US" 
								{if isset($posted)}
									{if $posted.formatting eq 'US'}
										selected="selected"
									{/if}
								{/if}
								>United States</option>
								<option value="EU" 
								{if isset($posted)}
									{if $posted.formatting eq 'EU'}
										selected="selected"
									{/if}
								{/if}
								>European</option>
							</select>
						</div>
					</fieldset>
					
					<button type="submit">{$lang.button_install}</button>
				</form>
			</section>
			
		</div>
	</section>
    
    <script>
        {literal}
            document.getElementById('license_number').focus();
        {/literal}
    </script>

</body>
</html>