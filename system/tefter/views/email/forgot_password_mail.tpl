<html lang="en">
<head>
  <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
  <title>Your new password</title>
</head>

<body style="color: #717171; font-family: Arial, Helvetica, sans-serif; font-size: 12px; background: #4b4b4b; margin: 0; padding: 0;">
	<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding: 35px 0; background: #4b4b4b;">
	  <tr>
	  	<td align="center">
	  	
		    <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" style="background: #426898;">
					<tr>
						<td width="20"style="font-size: 0px;">&nbsp;</td>
	        	<td width="580" align="left" style="padding: 18px 0;">
							<h1 style="color: #eeeeee; font-size: 32px; line-height: 40px; margin: 0; padding: 0;">Your password have been reseted</h1>
	        	</td>
	      	</tr>
				</table>

				<table cellpadding="0" cellspacing="0" border="0" align="center" width="600" style="background: #fff;" bgcolor="#fff">
					<tr>
		        <td width="600" valign="top" align="left" style="padding: 20px 0 0;">
		        
							<table cellpadding="0" cellspacing="0" border="0" width="600">
								<tr>
									<td width="21" style="font-size: 1px; line-height: 1px;"></td>
									<td align="left">			
										<h2 style="color:#646464; font-size: 18px; font-weight: bold; line-height: 26px; margin: 0 0 15px 0; padding: 0;">Hello {$user->name_first} {$user->name_last},</h2>
									</td>
									<td width="21" style="font-size: 1px; line-height: 1px;"></td>
								</tr>
								<tr>
									<td width="21" style="font-size: 1px; line-height: 1px;"></td>
									<td valign="top">
										<p style="font-size: 12px; line-height: 20px; margin: 0 0 15px 0; padding: 0;">{$editingUser.name_first} {$editingUser.name_last} reseted your sign in password for our login management system. All you need to do is to click on the following URL and sign in with new details.</p>
										
										<table cellpadding="0" cellspacing="0" border="0" width="100%" style="background: #FFFFCC; padding: 15px 15px 0 15px; border: 1px solid #FFD886;">
											<tr>
												<td>
													<p style="color: #333333; font-size: 12px; line-height: 20px; margin: 0 0 15px 0; padding: 0;">Sign in URL: <strong><a href="{base_url}" target="_blank" style="color: #456790;">{base_url}</a></strong></p>
													<p style="color: #333333; font-size: 12px; line-height: 20px; margin: 0 0 15px 0; padding: 0;">Email: <strong>{$user->email}</strong></p>
													<p style="color: #333333; font-size: 12px; line-height: 20px; margin: 0 0 15px 0; padding: 0;">Password: <strong>{$password}</strong></p>
												</td>
											</tr>
										</table>
									</td>
									<td width="21" style="font-size: 1px; line-height: 1px;"></td>
								</tr>
								<tr>
									<td width="21" style="font-size: 1px; line-height: 1px;"></td>
									<td style="padding: 15px 0 15px 0;" align="left">			
										<p style="color: #333333; font-size: 12px; line-height: 20px; margin: 0 0 15px 0; padding: 0;">Have questions? Contact {$editingUser.name_first} {$editingUser.name_last} at <a href="mailto:{$editingUser.email}" style="color: #456790;">{$editingUser.email}</a></p>
									</td>
									<td width="21" style="font-size: 1px; line-height: 1px;"></td>
								</tr>
								<tr>
									<td width="21" style="font-size: 1px; line-height: 1px;"></td>
									<td valign="top"></td>
									<td width="21" style="font-size: 1px; line-height: 1px;"></td>
								</tr>
							</table>	
						</td>
		      </tr>	
				</table>

	  	</td>
		</tr>
  </table>
</body>
</html>