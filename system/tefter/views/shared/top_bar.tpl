	<!-- top bar -->
	<section id="top-bar">
		<div class="group block">
			<h2>{if $settings.NAME_COMPANY neq ''}{$settings.NAME_COMPANY}{else}Tefter{/if}</h2>
			<ul class="group reset user-info">
				<li>{$lang.text_welcome_back}, {$user.name_first}!</li>
				<li class="profile"><a href="{base_url}users/profile">{$lang.text_profile}</a></li>
				<li class="sign-out"><a href="{base_url}logout" onclick="return confirm('{$lang.text_are_you_sure_you_want_to_logout}');">{$lang.text_sign_out}</a></li>
			</ul>
		</div>
	</section>
	<!-- / top bar -->