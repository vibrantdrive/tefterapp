{include file='shared/head.tpl'}

<body id="amnesia">

	<h1 id="heading">{if $settings.NAME_COMPANY neq ''}{$settings.NAME_COMPANY}{else}Tefter{/if}</h1>
	<section id="sign-in">
		<h2>{$lang.title_forgot_password}</h2>
			
		<div class="message success">
			<p>{$lang.message_password_sent}</p>
		</div>
		
		<p>{$lang.text_admin_reset}</p>
		
		<a href="{base_url}login" class="btn">{$lang.text_back_to_login_page}</strong></a>
	</section>
	
	<script src="{base_url}assets/js/jquery-1.4.2.min.js"></script>
	<script src="{base_url}assets/js/global.js"></script>
</body>
</html>