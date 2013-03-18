{include file='shared/head.tpl'}
<body id="group-details">

	{include file='shared/top_bar.tpl'}
	
	{include file='shared/header.tpl'}
	
	<!-- content -->
	<section id="content"
	{if not $hasCount}
		 class="blank-page"
	{/if}
	>
		<div class="group block">
		
			<h1>{$group.group_title}</h1>
			<a href="{base_url}groups" class="go-back">{$lang.text_back_to_groups}</a>
			
	        {if isset($m)}
	            {$m->content}
	        {/if}

			<ol class="group reset users-list">
				{foreach from=$usersGroups item=userGroup key=i}
				<li>
					<ul class="reset">
						<li class="name"><strong>{$userGroup.name_first} {$userGroup.name_last}</strong></li>
						<li class="email">{$userGroup.email}</li>
						<li class="role">{$userGroup.role_title}</li>
						<li class="tools">
							{if $user.level eq 99}
							<a href="{base_url}groups/actions/removeuserfromgroup/{$userGroup.user_id}/{$userGroup.group_id}/{$group.group_id}" onclick="return confirm('{$lang.text_are_you_sure_you_want_to_remove_user}');">{$lang.text_remove_from_group}</a>
							{/if}
						</li>
					</ul>
				</li>
				{/foreach}
			</ol>
			
			{if $hasCount}
			<!-- data -->
			<div id="data" class="hide">

			<p class="info-text">{$lang.text_info_text_part1} <span id="details_count">0</span> {$lang.text_info_text_part3}</p>
			
            {if $user.level gt 20 and $canEdit}
			<p class="edit-group"><a href="{base_url}groups/edit/{$group.group_id}"><strong>Edit group</strong></a></p>
            {/if}

			<table width="100%" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th>{$lang.text_table_title}</th>
						<th>{$lang.text_table_type}</th>
						{if $user.level neq 20}
						<th>{$lang.text_table_client}</th>
						{/if}
						<th>{$lang.text_table_date_added}</th>
						<th id="last-column">{$lang.text_table_actions}</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
			</div>

			<div id="loader" class="loader hide">{$lang.text_loading}...</div>
			<!-- data -->
		</div>
            {else}
        </div>    
		<div class="group block">
				{include file='groups/groups_accounts_blank.tpl'}
		</div>		
            {/if}

		<input type="hidden" name="limit_from" id="limit_from" value="0">
		<input type="hidden" name="limit_to" id="limit_to" value="999999">
		<input type="hidden" name="limit_default_from" id="limit_default_from" value="0">
		<input type="hidden" name="limit_default_to" id="limit_default_to" value="9999999">
		<input type="hidden" name="base_url" id="base_url" value="{base_url}">
		<input type="hidden" name="call_script_table" id="call_script_table" value="accounts/table">
		<input type="hidden" name="call_script_data" id="call_script_data" value="accounts/data">
		<input type="hidden" name="for_group" id="for_group" value="yes">
		<input type="hidden" name="for_group_id" id="for_group_id" value="{$group.group_id}">
	</section>
	
	<div id="send-via-email" class="hide">
		<h3>{$lang.text_email_account_data}</h3>
		
		<form action="#" method="post" onsubmit="return false;">
			<div class="message problem hide">
			</div>
			<div class="message success hide">
			</div>
			<div class="group">
				<label for="email" class="required">{$lang.text_where_to_send_account_data}</label>
				<input type="email" name="email" id="email" maxlength="120" value="" />
				<input type="hidden" name="send_id" id="send_id" value="">
			</div>
			
			<input type="hidden" name="base_url_send" id="base_url_send" value="{base_url}">
			<input type="hidden" name="call_script_send" id="call_script_send" value="accounts/sendaccount">

			<button type="submit" onclick="{literal}javascript: sendAccount($('#email').val());{/literal}">{$lang.button_send}</button>
			<a href="#" class="cancel">{$lang.button_cancel}</a>
		</form>
	</div>

	<script src="{base_url}assets/js/jquery-1.4.2.min.js"></script>
	<script src="{base_url}assets/js/jquery.blockUI.js"></script>
	<script src="{base_url}assets/js/global.js"></script>
	<script src="{base_url}assets/js/custom.js"></script>
    {if $hasCount}
    <script type="text/javascript">
	    {literal}
	        $(document).ready(function()
	        {
	        	if ({/literal}{$user.level}{literal} != 20)
	        	{
	        		loadTable('', '', '', '', true, 'yes');
				}
				else
				{
	        		loadTable('', '', '', '', true, 'no');
				}
	        });
	    {/literal}
    </script>
    {/if}
{include file='shared/foot.tpl'}
</body>
</html>