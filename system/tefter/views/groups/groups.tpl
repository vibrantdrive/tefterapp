{include file='shared/head.tpl'}
<body id="groups">

	{include file='shared/top_bar.tpl'}
	
	{include file='shared/header.tpl'}
	
	<!-- content -->
	<section id="content"
		{if not $hasCount}
 			class="blank-page"
 		{/if}
	>
		<div class="group block">
		
			<h1>{$lang.title_groups}</h1>
			{if $user.level gt 20}
			<a href="{base_url}groups/add" class="btn add"><span>{$lang.button_add_group}</span></a>
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
							<button type="submit" onclick="{literal}javascript: searchGroupClick($('#search').val());{/literal}">{$lang.button_search}</button>
						</div>
						<input type="hidden" name="limit_from" id="limit_from" value="0">
						<input type="hidden" name="limit_to" id="limit_to" value="{$settings.ITEMS_PER_PAGE}">
						<input type="hidden" name="limit_default_from" id="limit_default_from" value="0">
						<input type="hidden" name="limit_default_to" id="limit_default_to" value="{$settings.ITEMS_PER_PAGE}">
						<input type="hidden" name="base_url" id="base_url" value="{base_url}">
						<input type="hidden" name="call_script_table" id="call_script_table" value="groups/table">
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
						<th>{$lang.text_table_total_accounts}</th>
						<th>{$lang.text_table_total_users}</th>
						<th id="last-column">{$lang.text_table_actions}</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
			<!-- data -->
			</div>

			<div id="loader" class="loader hide">{$lang.text_loading}...</div>

			{include file='shared/pagination.tpl'}
            {else}
				{include file='groups/groups_blank.tpl'}
            {/if}
		</div>
	</section>
	
	<script src="{base_url}assets/js/jquery-1.4.2.min.js"></script>
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

	        	loadGroupTable('', '', true);
	        });
	    {/literal}
    </script>
    {/if}
{include file='shared/foot.tpl'}
</body>
</html>