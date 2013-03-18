<tr id="rowdata{$account.account_id}" class="active data email">
	<td colspan="5">
		<div class="group">
			<ul class="reset">
				<li><span>{$lang.label_login_url}:</span> <a href="{if $account.login_url|strpos:'http://' === false and $account.login_url|strpos:'https://' === false}http://{$account.login_url}{else}{$account.login_url}{/if}" rel="external" target="_blank"><pre>{if $account.login_url|strpos:'http://' === false and $account.login_url|strpos:'https://' === false}http://{$account.login_url}{else}{$account.login_url}{/if}</pre></a></li>
				<li><span>{$lang.label_username}:</span> <pre>{$account.username}</pre></li>
				<li><span>{$lang.label_password}:</span> <pre>{$account.password}</pre></li>
			</ul>
			<span class="pointer"></span>
		</div>
	</td>
</tr>