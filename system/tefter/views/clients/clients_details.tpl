{include file='shared/head.tpl'}
<body id="client-details">

	{include file='shared/top_bar.tpl'}
	
	{include file='shared/header.tpl'}
	
	<!-- content -->
	<section id="content">
		<div class="group block">
		
			<h1>{$client.company_name}</h1>
			<a href="{base_url}clients" class="go-back">{$lang.text_back_to_clients}</a>
			
	        {if isset($m)}
	            {$m->content}
	        {/if}

			<section id="client-info" class="group">
				<figure><img src="{base_url}assets/images/default-client-image.png" alt="{$client.company_name}" /></figure>

				<dl>
					<dt>{$lang.label_address}</dt>
					{if $client.address neq '' or $client.city neq '' or $client.state neq '' or $client.postal_code neq '' or $client.country_code neq ''}
					<dd class="address">{$client.address}</dd>
					<dd><span class="city">{$client.city}</span>{if $client.city neq ''},{/if} <span class="state">{$client.state}</span> <span class="zip">{$client.postal_code}</span>{if $client.state neq '' or $client.postal_code neq ''},{/if} <span class="country">{$client.country_code}</span></dd>
					{else}
						{$lang.text_no_address}
					{/if}
				</dl>

				<dl>
					<dt>{$lang.label_contact_details}</dt>
					{if $client.phone_office neq '' or $client.email neq '' or $client.phone_office neq '' or $client.fax neq ''}
					<dd>Tel: <span class="tel">{$client.phone_office}</span></dd>
					<dd>Fax: <span class="fax">{$client.fax}</span></dd>
					<dd><span class="email">{$client.email}</span></dd>
 					{else}
						{$lang.text_no_contact_details}
					{/if}
				</dl>
				
				{if $user.level eq 99}
				<p class="edit-client"><a href="{base_url}clients/edit/{$client.client_id}"><strong>{$lang.title_edit_client}</strong></a></p>
				{/if}
			</section>
			
			{if $hasCount}
			<!-- data -->
			<div id="data" class="hide">
			<p class="info-text">{$lang.text_info_text_part1} <span id="details_count">0</span> {$lang.text_info_text_part2}</p>
			
			<p class="add-account"><a href="{base_url}accounts/add/{$client.client_id}"><strong>{$lang.title_add_account}</strong></a></p>
			<table width="100%" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th>{$lang.text_table_title}</th>
						<th>{$lang.text_table_type}</th>
						<th>{$lang.text_table_date_added}</th>
						<th>{$lang.text_table_actions}</th>
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
				{include file='clients/clients_accounts_blank.tpl'}
		</div>		
            {/if}

		<input type="hidden" name="limit_from" id="limit_from" value="0" />
		<input type="hidden" name="limit_to" id="limit_to" value="999999" />
		<input type="hidden" name="limit_default_from" id="limit_default_from" value="0" />
		<input type="hidden" name="limit_default_to" id="limit_default_to" value="9999999" />
		<input type="hidden" name="base_url" id="base_url" value="{base_url}" />
		<input type="hidden" name="call_script_table" id="call_script_table" value="accounts/table" />
		<input type="hidden" name="call_script_data" id="call_script_data" value="accounts/data" />
		<input type="hidden" name="for_group" id="for_group" value="yes" />
		<input type="hidden" name="for_group_id" id="for_group_id" value="0" />
	</section>
	
    <div id="send-via-email" class="hide">
        <h3>{$lang.text_email_account_data}</h3>
        
        <form action="#" method="post" onsubmit="return false;">
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
	        	loadTable('', '', '', '{/literal}{$client.client_id}{literal}', true, 'no');
	        });
	    {/literal}
    </script>
    {/if}
{include file='shared/foot.tpl'}
</body>
</html>