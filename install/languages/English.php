<?php
    $lang = array();
    
    // step1
    $lang['title_step1'] = "Tefter Installation Wizard";
    $lang['label_license_agreement'] = "License Agreement";
    $lang['text_license_agreement'] = 'License Agreement for User

This license is a legal agreement between you and Vibrant Drive, LLC. for the use of Tefter software. By installing the software, you agree to be bound by the terms and conditions of this license. Vibrant Drive, LLC. reserves the right to alter this agreement at any time, for any reason, without notice.

PERMITTED USE
A purchased license is required for each installed instance of this software. One license grants the right to perform one installation. Each additional installation of this software requires an additional purchased license. Software designated as free or by donation is exempt from the paid license restrictions.

RESTRICTIONS
Unless you have been granted prior, written consent from Vibrant Drive, LLC., you may not:
- Reproduce, distribute, or transfer the software, or portions thereof, to any third party.
- Sell, rent, lease, assign, or sublet the software, or portions thereof.
- Grant rights to any other person.
- Use this software in violation of any international law or regulation.

DISPLAY OF COPYRIGHT NOTICES
All copyright and proprietary notices within the scripts must remain intact.

SOFTWARE MODIFICATION
You may alter, modify, or extend the software for your own use, or commission a third-party to perform modifications for you, but you may not resell, redistribute, transfer or rent the modified or derivative version without prior written consent from Vibrant Drive, LLC. Components from this software may not be extracted and used in other programs without prior written consent from Vibrant Drive, LLC.

TECHNICAL SUPPORT
Technical support is available only through the Online Support Forums at TefterApp.com. No representations or guarantees are made regarding the response time in which support questions are answered.

REFUNDS
We offer a 30 day, money back Refund Policy (http://tefterapp.com/support/refund-policy/). If for any reason Tefter doesnÕt meet your needs, simply email sales (http://tefterapp.com/support/) within 30 days of purchase for a full refund.

INDEMNITY
You agree to indemnify and hold harmless Vibrant Drive, LLC., for any third-party claims, actions or suits, as well as any related expenses, liabilities, damages, settlements or fees arising from your use or misuse of the software, or a violation of any terms of this license.

DISCLAIMER OF WARRANTY
THE SOFTWARE IS PROVIDED ÒAS ISÓ, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, WARRANTIES OF QUALITY, PERFORMANCE, NON-INFRINGEMENT, MERCHANTABILITY, OR FITNESS FOR A PARTICULAR PURPOSE. FURTHER, VIBRANT DRIVE, LLC. DOES NOT WARRANT THAT THE SOFTWARE OR ANY RELATED SERVICE WILL ALWAYS BE AVAILABLE.

LIMITATIONS OF LIABILITY
YOU ASSUME ALL RISK ASSOCIATED WITH THE INSTALLATION AND USE OF THE SOFTWARE. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS OF THE SOFTWARE BE LIABLE FOR CLAIMS, DAMAGES OR OTHER LIABILITY ARISING FROM, OUT OF, OR IN CONNECTION WITH THE SOFTWARE. LICENSE HOLDERS ARE SOLELY RESPONSIBLE FOR DETERMINING THE APPROPRIATENESS OF USE AND ASSUME ALL RISKS ASSOCIATED WITH ITS USE, INCLUDING BUT NOT LIMITED TO THE RISKS OF PROGRAM ERRORS, DAMAGE TO EQUIPMENT, LOSS OF DATA OR SOFTWARE PROGRAMS, OR UNAVAILABILITY OR INTERRUPTION OF OPERATIONS.';
	$lang['text_i_agree'] = "I agree to abide by the license Terms and Conditions as stated above";
	$lang['text_i_dont_agree'] = "I do NOT agree to abide by the license Terms and Conditions as stated above";
	$lang['button_submit'] = "Submit";
	$lang['message_not_agreed'] = "Cannot proceed. You must agree with the T&C";
	
	
	
	// step2
	$lang['title_step2'] = "Tefter Installation Wizard";
	$lang['text_enter_settings'] = "Enter your settings";
	$lang['label_license_number'] = "Tefter License Number";
	$lang['text_available_on_your'] = "Available on your";
	$lang['text_profile_page'] = "profile page";
	$lang['text_of_the_domain'] = "on TefterApp.com";
	$lang['label_company_name'] = "Name of your company/agency/studio";
	$lang['label_company_name_hint'] = "This name appears in top left corner of application";
	$lang['text_server_and_database_settings'] = "Server and Database Settings";
	$lang['text_server_and_database_settings_help'] = "If you are not sure what any of these settings should be, please contact your hosting provider and ask them.";
	$lang['label_base_url'] = "URL to the application";
	$lang['label_base_url_hint'] = "Ex. http://www.domain.com/";
	$lang['label_path_to_the_root'] = "Absolute path of root Tefter directory on your server";
	$lang['label_path_to_the_root_hint'] = "Ex. /home/www/webapps/public/";
	$lang['label_path_to_the_application'] = "Name of Tefter system folder";
	$lang['label_path_to_the_application_hint'] = "Default is <em>system</em>";
	$lang['label_database_host'] = "MySQL database host";
	$lang['label_database_host_hint1'] = "Usually you will use";
	$lang['text_localhost'] = "localhost";
	$lang['label_database_host_hint2'] = "but your hosting provider may require something else";
	$lang['label_database_username'] = "MySQL username";
	$lang['label_database_username_hint'] = "The username you use to access your MySQL database";
	$lang['label_database_password'] = "MySQL password";
	$lang['label_database_password_hint'] = "The password you use to access your MySQL database";
	$lang['label_database_name'] = "MySQL database name";
	$lang['label_database_name_hint1'] = "Please make sure that the database exists, Tefter will";
	$lang['text_not'] = "not";
	$lang['label_database_name_hint2'] = "create it for you.";
	$lang['label_database_prefix'] = "MySQL database prefix";
	$lang['text_use'] = "Use";
	$lang['text_database_prefix_example'] = "tef_";
	$lang['label_database_prefix_hint'] = "unless you need to use a different prefix";
	$lang['text_create_admin_account'] = "Create Your Admin Account";
	$lang['text_create_admin_account_help'] = "You'll use these settings to access your Tefter control panel.";
	$lang['label_username'] = "Your email address";
	$lang['label_username_hint'] = "You'll use this email for sign in";
	$lang['label_password'] = "Password";
	$lang['label_password_hint'] = "Must be at least 5 characters in length";
	$lang['label_password_check'] = "Please retype your password";
	$lang['label_localization_settings'] = "Localization Settings";
	$lang['label_localization_settings_help'] = "Enter your local settings.";
	$lang['label_server_timezone'] = "Your timezone";
	$lang['label_date_time_formatting'] = "Default date/time formatting";
	$lang['button_install'] = "Install Tefter";
	
	
	// actions
    $lang['message_mcrypt_required'] = "mcrypt functions cannot be found. mcrypt extension is required.";
	$lang['message_license_number_required'] = "License number is required.";
	$lang['message_license_format_incorrect'] = "License format incorrect.";
	$lang['message_base_url_required'] = "Application URL required.";
	$lang['message_directory_path_required'] = "Root path is required.";
	$lang['message_application_path_required'] = "Please enter system folder name.";
	$lang['message_mysql_host_required'] = "MySQL host is required.";
	$lang['message_mysql_username_required'] = "MySQL username is required.";
	$lang['message_mysql_name_required'] = "MySQL database name is required.";
	$lang['message_admin_username_required'] = "Admin username is required.";
	$lang['message_email_format_error'] = "Email format is not correct.";
	$lang['message_admin_password_required'] = "Admin password is required.";
	$lang['message_passwords_do_not_match'] = "Admin password and confirmation password do not match.";
	$lang['message_admin_password_again_required'] = "Admin password confirmation is required.";
	$lang['message_server_timezone_required'] = "Server timezone is required.";
	$lang['message_formatting_required'] = "Default fomratting is required.";
	$lang['message_file_is_not_writable'] = "File does not exist or is not writable";
	$lang['message_could_not_connect'] = "Could not connect to the MySQL server";
	$lang['message_could_not_select_database'] = "Could not select database";
	$lang['message_could_not_open_file'] = "Could not open file";
	$lang['message_could_not_write_file'] = "Error during file write";
	$lang['message_sql_file_not_found'] = "SQL install file not found: install/install.sql";
	$lang['message_error_during_sql_statement'] = "Error during sql statement";
	$lang['message_mysql_prefix_allowed_characters'] = "MySQL prefix: allowed characters are alphanumeric, underscore and dash.";
	$lang['message_license_number_allowed_characters'] = "License number: allowed characters are alphanumeric, underscore and dash.";
	$lang['message_settings_permissions'] = "File does not have sufficient permissions (644) or higher";
	$lang['message_cache_permissions'] = "Folder does not have sufficient permissions (755) or higher";
	
	
	// success
	$lang['title_success'] = "Tefter Installation Wizard";
	$lang['message_success_install'] = "Tefter has been successfully installed!";
	$lang['text_line1'] = 'Very important: Using your FTP program, please find the folder named "install" and delete it from your server.';
	$lang['text_line2'] = "You'll not be permitted to sign into your Tefter account until you do.";
	$lang['text_line3'] = "Install folder is located in root of your application folder.";
	$lang['text_line4a'] = "You can now proceed to";
	$lang['text_line4b'] = "sign in page";
?>