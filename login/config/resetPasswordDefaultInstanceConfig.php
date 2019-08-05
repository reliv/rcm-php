<?php
$hostname = 'localhost';

if (!empty($_SERVER['HTTP_HOST'])) {
    $hostname = $_SERVER['HTTP_HOST'];
}

return [
    'top' => '
        <h1>Reset Password</h1>
        <div>
        Enter your user name.  An email to reset your password will be sent to your email address on file.
        </div>
    ',
    'passwordLabel' => 'New Password:',
    'passwordTwoLabel' => 'Re-enter new password:',
    'userIdLabel' => 'User Name:',
    'button' => 'Submit',
    'prospectEmail' => [
        'fromEmail' => 'no-reply@'.$hostname,
        'fromName' => $hostname.' Password Reset',
        'subject' => 'Reset Password',
        'body' => '<html>
<body marginheight="0">
    <table cellpadding="0" cellspacing="0" height="300" width="600" border="0">
      <tbody>
        <tr>
          <td valign="top"><blockquote><font face="Verdana, Arial, Helvetica, sans-serif"
              size="2"><br>
              Click on the link below to reset your password:<br><br>
             <strong>RCN</strong>: {userId}<br><br>
             <a href="{url}">Reset your password.</a><br>

            </font></blockquote></td>
        </tr>
      </tbody>
    </table>
</body>
</html>
'
    ]
];
