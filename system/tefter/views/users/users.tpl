{include file='shared/head.tpl'}
<body id="users">

	{include file='shared/top_bar.tpl'}
	
	{include file='shared/header.tpl'}
	
	<!-- content -->
	<section id="content">
		<div class="group block">
		
			<h1>{$lang.title_users}</h1>
			{if $user.level gt 20}
			<a href="{base_url}users/add" class="btn add"><span>{$lang.button_add_user}</span></a>
			{/if}
			
	        {if isset($m)}
	            {$m->content}
	        {/if}

			{if $user.level eq 99}
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
					<form action="#" method="post" class="group">
						
						<div class="group filter-role">
							<label for="types">{$lang.text_filter_by_role}</label>
							<select name="roles" id="roles" onchange="{literal}javascript: roleOnChange();{/literal}">
								<option value="" selected="selected">{$lang.option_all}</option>
								{foreach from=$user_roles item=user_role key=i}
									<option value="{$user_role.role_id}">{$user_role.role_title}</option>
								{/foreach}
							</select>
						</div>
						<input type="hidden" name="limit_from" id="limit_from" value="0">
						<input type="hidden" name="limit_to" id="limit_to" value="{$settings.ITEMS_PER_PAGE}">
						<input type="hidden" name="limit_default_from" id="limit_default_from" value="0">
						<input type="hidden" name="limit_default_to" id="limit_default_to" value="{$settings.ITEMS_PER_PAGE}">
						<input type="hidden" name="base_url" id="base_url" value="{base_url}">
						<input type="hidden" name="call_script_table" id="call_script_table" value="users/table">
					</form>
				</div>
				
			</div>
			<!-- / tools -->
			{else}
				<form action="#" method="post" class="group hide">
					<input type="hidden" name="limit_from" id="limit_from" value="0">
					<input type="hidden" name="limit_to" id="limit_to" value="{$settings.ITEMS_PER_PAGE}">
					<input type="hidden" name="limit_default_from" id="limit_default_from" value="0">
					<input type="hidden" name="limit_default_to" id="limit_default_to" value="{$settings.ITEMS_PER_PAGE}">
					<input type="hidden" name="base_url" id="base_url" value="{base_url}">
					<input type="hidden" name="call_script_table" id="call_script_table" value="users/table">
				</form>
			{/if}
			
			<!-- data -->
			<div id="data" class="hide">
			<ol class="group reset users-list">
			</ol>
			<!-- data -->
			</div>

			<div id="loader" class="loader hide">{$lang.text_loading}...</div>

			{include file='shared/pagination.tpl'}
		</div>
	</section>
	
	<script src="{base_url}assets/js/jquery-1.4.2.min.js"></script>
	<script src="{base_url}assets/js/global.js"></script>
	<script src="{base_url}assets/js/custom.js"></script>
    {if $hasCount}
    <script type="text/javascript">
	    {literal}
	        $(function()
	        {
	        	loadUserTable('', '', true);
	        });
	    {/literal}
    </script>
    {/if}
{include file='shared/foot.tpl'}
</body>
</html>