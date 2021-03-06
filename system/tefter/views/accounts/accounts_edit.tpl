{include file='shared/head.tpl'}
<body id="accounts">

	{include file='shared/top_bar.tpl'}
	
	{include file='shared/header.tpl'}
	
	<section id="content">
		<div class="group block">
			<h1>{$lang.title_edit_account}</h1>
			
			<a href="{base_url}accounts" class="go-back">{$lang.text_back_to_accounts}</a>
			
			<section id="primary">
				<form action="{base_url}accounts/actions/edit" method="post">
				
					<input type="hidden" name="account_id" id="account_id" value="{$account.account_id}">
					<input type="hidden" name="base_url" id="base_url" value="{base_url}">
					<input type="hidden" name="call_script" id="call_script" value="accounts/typetemplate">
					<input type="hidden" name="mode" id="mode" value="edit">
					
					<fieldset>

            {if isset($m)}
                {$m->content}
            {/if}

						<div class="group">
							<label for="title" class="required">{$lang.text_table_title}</label>
							<input type="text" name="title" id="title" value="{if $posted.title neq ''}{$posted.title}{else}{$account.account_name}{/if}" autocomplete="off" />
						</div>
						
						<div class="group">
							<label for="client">{$lang.text_table_client}</label>
							<select name="client" id="client">
								{if $user.level eq 99}
								<option value="" 
								{if $posted.client eq ''}selected="selected"{/if}
								>{$lang.option_no_client}</option>
								{/if}
								<!-- add clients -->
								{foreach from=$clients item=client key=i}
									<option value="{$client.client_id}" 
									{if $posted.client neq ''}
										{if $posted.client eq $client.client_id}selected="selected"{/if}
									{else}
                    {if $account.client_id eq $client.client_id}
                        selected="selected"
                    {/if}
									{/if}
									>{$client.company_name}</option>
								{/foreach}
							</select>
							<a href="#" class="aside-link">{$lang.text_add_new_client}</a>
						</div>
						
						<div class="group">
							<label for="type" class="required">{$lang.text_table_type}</label>
							<select name="type" id="type">
								<option value="" 
								{if $posted.type eq ''}selected="selected"{/if}
								>{$lang.option_please_select}</option>
								
								{assign var=temp value='HG!QAD!@#@$'}

								{foreach from=$types item=type key=i}
									{if $type.account_type_group_name neq $temp}
										<optgroup label="{$type.account_type_group_name}">
										{assign var=temp value=$type.account_type_group_name}
									{/if}
									<option value="{$type.account_type_id}" 
									{if $posted.type neq ''}
										{if $posted.type eq $type.account_type_id}selected="selected"{/if}
									{else}
                                        {if $account.type_id eq $type.account_type_id}
                                            selected="selected"
                                        {/if}
									{/if}
									>{$type.account_type_name}</option>
								{/foreach}
							</select>
							<span class="loading hide" id="loading"></span>
						</div>
						<div id="account_type_fields" class="hide"></div>
						
					</fieldset>
					
					<button type="submit">{$lang.button_save_changes}</button>
					<a href="{base_url}accounts" class="cancel">{$lang.button_cancel}</a>
				</form>
			</section>
			
			<aside>
				<h5>{$lang.title_delete_this_account}</h5>
				<p>{$lang.text_delete_this_account}</p>
				<p><a href="{base_url}accounts/actions/delete/{$account.account_id}" class="delete" onclick="return confirm('{$lang.text_are_you_sure_you_want_to_delete_account}');">{$lang.text_delete} {$account.account_name}</a></p>
			</aside>
			
		</div>
	</section>
	
	<div id="add-new-client" class="hide">
		<h3>{$lang.text_new_client}</h3>
		
		<form action="#" method="post" onsubmit="return false;">
			<div class="message problem hide" id="messageproblem">
			</div>
			<input type="hidden" name="base_url_new_client" id="base_url_new_client" value="{base_url}">
			<input type="hidden" name="call_script_new_client" id="call_script_new_client" value="clients/addclientfromaccount">
			<div class="group">
				<label for="new_client" class="required">{$lang.text_enter_new_client_name}</label>
				<input type="text" name="new_client" id="new_client" maxlength="120" value="" />
			</div>
			
			<button type="submit" onclick="{literal}javascript: newClientClick($('#new_client').val());{/literal}">{$lang.button_add_client}</button>
			<a href="#" class="cancel">{$lang.button_cancel}</a>
		</form>
	</div>

	<script src="{base_url}assets/js/jquery-1.4.2.min.js"></script>
	<script src="{base_url}assets/js/jquery.blockUI.js"></script>
	<script src="{base_url}assets/js/global.js"></script>
	<script src="{base_url}assets/js/custom.js"></script>
    <script type="text/javascript">
	    {literal}
	        $(document).ready(function()
	        {
				if ($("#type").val() != "")
				{
					$("#type").change();
				}
	        });
	    {/literal}
    </script>
{include file='shared/foot.tpl'}
</body>
</html>