{include file='shared/head.tpl'}
<body>

	<h1 id="heading">{if $settings.NAME_COMPANY neq ''}{$settings.NAME_COMPANY}{else}Tefter{/if}</h1>
	<section id="sign-in">
		<h2>{$lang.title_forgot_password}</h2>
		<form action="{base_url}amnesia/actions/send" method="post">
			
        	{if isset($m) }
            	{$m->content}
            {/if}

			<p>{$lang.text_forgot_password}</p>
			
			<div class="group">
				<label for="email">{$lang.label_enter_email}</label>
				<input type="email" name="email" id="email" maxlength="100" />
			</div>
			
			<button type="submit">{$lang.button_reset_my_password}</button>
			<a href="{base_url}login" class="cancel">{$lang.button_cancel}</a>
		</form>
	</section>
	
	<script src="{base_url}assets/js/jquery-1.4.2.min.js"></script>
	<script src="{base_url}assets/js/global.js"></script>
	<script type="text/javascript">
	    {literal}
	        $(document).ready(function()
	        {
	        	$('#email').focus();
			});
		{/literal}
	</script>
{include file='shared/foot.tpl'}
</body>
</html>