{include file='shared/head.tpl'}
<body id="accounts">

	{include file='shared/top_bar.tpl'}
	
	{include file='shared/header.tpl'}
	
	<!-- content -->
	<section id="content"
	{if not $hasCount}
		 class="blank-page"
	{/if}
	>
		<div class="group block">
		
			<h1>{$lang.title_accounts}</h1>
			{if $user.level gt 20}
			<a href="{base_url}accounts/add" class="btn add"><span>{$lang.button_add_account}</span></a>
			{/if}
			
	        {if isset($m)}
	            {$m->content}
	        {/if}

			{if $hasCount}
			<!-- tools -->
			<div id="tools">
				<ul class="group reset">
					<li id="letter_all" class="selected"><a href="#">{$lang.text_all}</a></li>
					<li id="letter_a"><a href="#">A</a></li>
					<li id="letter_b"><a href="#">B</a></li>
					<li id="letter_c"><a href="#">C</a></li>
					<li id="letter_d"><a href="#">D</a></li>
					<li id="letter_e"><a href="#">E</a></li>
					<li id="letter_f"><a href="#">F</a></li>
					<li id="letter_g"><a href="#">G</a></li>
					<li id="letter_h"><a href="#">H</a></li>
					<li id="letter_i"><a href="#">I</a></li>
					<li id="letter_j"><a href="#">J</a></li>
					<li id="letter_k"><a href="#">K</a></li>
					<li id="letter_l"><a href="#">L</a></li>
					<li id="letter_m"><a href="#">M</a></li>
					<li id="letter_n"><a href="#">N</a></li>
					<li id="letter_o"><a href="#">O</a></li>
					<li id="letter_p"><a href="#">P</a></li>
					<li id="letter_q"><a href="#">Q</a></li>
					<li id="letter_r"><a href="#">R</a></li>
					<li id="letter_s"><a href="#">S</a></li>
					<li id="letter_t"><a href="#">T</a></li>
					<li id="letter_u"><a href="#">U</a></li>
					<li id="letter_v"><a href="#">V</a></li>
					<li id="letter_w"><a href="#">W</a></li>
					<li id="letter_x"><a href="#">X</a></li>
					<li id="letter_y"><a href="#">Y</a></li>
					<li id="letter_z"><a href="#">Z</a></li>
					<li id="letter_asterix"><a href="#">#</a></li>
				</ul>
				
				<div id="filters">
					<form action="#" method="post" class="group" onSubmit="return false;">
						<div class="group">
							<label for="search">{$lang.label_search_for}</label>
							<input type="text" name="search" id="search" maxlength="100" />
							<button type="submit" onclick="{literal}javascript: searchClick($('#search').val());{/literal}">{$lang.button_search}</button>
						</div>
						
						<div class="group filter-client 
							{if $user.level eq 20}
								hide
							{/if}	
						">
							<label for="types">{$lang.label_filter_by_client}</label>
							<select name="clients" id="clients" onchange="{literal}javascript: clientOnChange();{/literal}">
								<option value="">{$lang.option_all}</option>
								{foreach from=$clients item=client key=i}
									<option value="{$client.client_id}">{$client.company_name}</option>
								{/foreach}
							</select>
						</div>
						
						<div class="group filter-type">
							<label for="clients">{$lang.label_filter_by_type}</label>
							<select name="types" id="types" onchange="{literal}javascript: typeOnChange();{/literal}">
								<option value="" 
								{if $posted.types eq ''}selected="selected"{/if}
								>{$lang.option_all}</option>
								
								{assign var=temp value='HG!QAD!@#@$'}

								{foreach from=$types item=type key=i}
									{if $type.account_type_group_name neq $temp}
										<optgroup label="{$type.account_type_group_name}">
										{assign var=temp value=$type.account_type_group_name}
									{/if}
									<option value="{$type.account_type_id}" 
									{if $posted.types neq ''}
										{if $posted.types eq $type.account_type_id}selected="selected"{/if}
									{/if}
									>{$type.account_type_name}</option>
								{/foreach}
							</select>
						</div>
						<input type="hidden" name="limit_from" id="limit_from" value="0">
						<input type="hidden" name="limit_to" id="limit_to" value="{$settings.ITEMS_PER_PAGE}">
						<input type="hidden" name="limit_default_from" id="limit_default_from" value="0">
						<input type="hidden" name="limit_default_to" id="limit_default_to" value="{$settings.ITEMS_PER_PAGE}">
						<input type="hidden" name="base_url" id="base_url" value="{base_url}">
						<input type="hidden" name="call_script_table" id="call_script_table" value="accounts/table">
						<input type="hidden" name="call_script_data" id="call_script_data" value="accounts/data">
						<input type="hidden" name="for_group" id="for_group" value="yes">
						<input type="hidden" name="for_group_id" id="for_group_id" value="0">
					</form>
				</div>
				
			</div>
			<!-- / tools -->

			<!-- data -->
			<div id="data" class="hide">
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
			<!-- data -->
			</div>

			<div id="loader" class="loader" class="hide">{$lang.text_loading}...</div>

			{include file='shared/pagination.tpl'}
            {else}
				{include file='accounts/accounts_blank.tpl'}
            {/if}
		</div>
	</section>
	
	<div id="send-via-email" class="hide">
		<h3>{$lang.text_email_account_data}</h3>
		
		<form action="#" method="post" onsubmit="return false;" id="sendform">
			<div class="message problem hide" id="sendmessageproblem">
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
				$('#search').keypress(function(key) {
				  if (key.keyCode == 13)
				  {
				  	  searchClick(this.value);
				  	  
				  	  return false;
				  }
				});

	        	if ({/literal}{$user.level}{literal} != 20)
	        	{
	        		loadTable('', '', '', $('#clients').val(), true, 'yes');
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