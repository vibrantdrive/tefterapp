<tr id="rowdata{$account.account_id}" class="active data pop3-imap">
	<td colspan="5">
		<div class="group">
			<ul class="reset single-data">
				<li><span>{$lang.label_email}:</span> <pre>{$account.email}</pre></li>
				<li><span>{$lang.label_email_type}:</span> <pre>{$account.email_type}</pre></li>
			</ul>

			<ul class="reset new-row">
				<li><span>{$lang.label_incoming_mail_server}:</span> <pre>{$account.incoming_mail_server}</pre></li>
				<li><span>{$lang.label_username}:</span> <pre>{$account.incoming_username}</pre></li>
				<li><span>{$lang.label_password}:</span> <pre>{$account.incoming_password}</pre></li>
			</ul>
			
			<ul class="reset">
				<li><span>{$lang.label_outgoing_mail_server}:</span> <pre>{$account.outgoing_mail_server}</pre></li>
				<li><span>{$lang.label_username}:</span> <pre>{$account.outgoing_username}</pre></li>
				<li><span>{$lang.label_password}:</span> <pre>{$account.outgoing_password}</pre></li>
			</ul>
			<span class="pointer"></span>
		</div>
	</td>
</tr>