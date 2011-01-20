<h2><?php echo $website['Website']['uri']?></h2>

<p><a href="http://<?php echo $website['Website']['uri']?>" style="color: blue" target="_new">Visit Website</a></p>

<dl>
<dt>FTP Host</dt>
<dd><?php echo $website['Website']['ftp_host'] ?></dd>
<dt>Username</dt>
<dd><?php echo $website['Website']['ftp_username'] ?></dd>
<dt>Password</dt>
<dd><?php echo $website['Website']['ftp_password'] ?></dd>
</dl>