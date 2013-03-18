{include file='shared/head.tpl'}
<body>

	<h1 id="heading">{if $settings.NAME_COMPANY neq ''}{$settings.NAME_COMPANY}{else}Tefter{/if}</h1>
	<section id="sign-in">
		<h2>{$lang.title_sign_in}</h2>
		<form action="{base_url}login/action" method="post">

        	{if isset($m) }
            	{$m->content}
            {/if}
			
			<div class="group">
				<label for="email">{$lang.label_email}</label>
				<input type="email" name="email" id="email" maxlength="100" {if not $enab}disabled{/if} />
			</div>
			
			<div class="group">
				<label for="password">{$lang.label_password}</label>
				<input type="password" name="password" id="password" {if not $enab}disabled{/if} />
				<p class="forgot"><a href="{base_url}amnesia">{$lang.text_forgot_your_password}</a></p>
			</div>
			
			<div class="group">
				<label for="remember-me" class="inline"><input type="checkbox" name="remember-me" id="remember-me" /> {$lang.option_remember_me}</label>
			</div>
			
			<button type="submit" {if not $enab}disabled="disabled"{/if}>{$lang.button_sign_in}</button>
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