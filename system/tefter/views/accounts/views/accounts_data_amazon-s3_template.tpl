<tr id="rowdata{$account.account_id}" class="active data amazon-s3">
	<td colspan="5">
		<div class="group">
			<ul class="reset">
				<li><span>{$lang.text_protocol}:</span> <pre>{$account.account_type_name}</pre></li>
				<li><span>{$lang.label_server}:</span> <pre>{$account.server}</pre></li>
				<li><span>{$lang.label_access_key}:</span> <pre>{$account.username}</pre></li>
				<li><span>{$lang.label_secret}:</span> <pre>{$account.password}</pre></li>
			</ul>
			
			<ul class="reset">
				{if $account.port neq ''}
					<li><span>{$lang.text_port}:</span> <pre>{$account.port}</pre></li>
				{/if}
				{if $account.passive_mode eq 1}<li><span>{$lang.text_use_passive_mode}:</span> <pre>Yes</pre></li>{/if}
				{if $account.root_url neq ''}
                <li><span>{$lang.text_root_url}:</span> <pre>{$account.root_url}</pre></li>
                {/if}
				{if $account.remote_path neq ''}
                <li><span>{$lang.text_remote_path}:</span> <pre>{$account.remote_path}</pre></li>
                {/if}
			</ul>
			<span class="pointer"></span>
		</div>
	</td>
</tr>