==========================================
Installation Instructions
==========================================

1. MySQL Settings

First, you’ll need to either create your MySQL database or have an existing database before you install tefter. This can be done via phpMyAdmin or through web hosting control panel.

   1. MySQL Database Name
   2. MySQL Server Address
   3. MySQL Username
   4. MySQL Password

2. Rename the “system” folder

This is an optional, but recommended step that increases security. To perform this step: Rename the directory called “system”. Choose a name that is not easily guessed.

Open /index.php and update the $system_root_folder to point to your renamed system directory.

3. Upload the Files

With FTP program such as Transmit upload the Tefter files to a web accessible directory on your server.

4. Set File Permissions

You must set the following file to 644 (or equivalent write permissions on your server):

    * /system/tefter/config/settings.php

And the follow directory to 755 (or equivalent write permissions on your server):

    * /system/tefter/views/cache

5. Run installation wizard

When you’re done with setting up file permissions, run Tefter installation wizard typing http://your-domain.com/install and follow installation instructions.