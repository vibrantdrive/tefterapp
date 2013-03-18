<tr id="rowdata{$account.account_id}" class="active data icq">
	<td colspan="5">
		<div class="group">
			<ul class="reset">
				<li><span>{$lang.label_username}:</span> <pre>{$account.username}</pre></li>
				<li><span>{$lang.label_password}:</span> <pre>{$account.password}</pre></li>
				{if $account.note neq ''}<li><span>{$lang.label_instructions}:</span> <pre>{$account.note}</pre></li>{/if}
			</ul>
			<span class="pointer"></span>
		</div>
	</td>
</tr>