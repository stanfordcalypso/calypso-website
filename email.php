<?php
function send_email($name, $email, $subject, $message) {
  $to = '';
  $message = '
<html>
<head>
  <title>' . $subject . '</title>
</head>
<body>
' . $message . '
</body>
</html>
';

  $headers  = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
  $headers .= 'To: ' . $name . ' <' . $email . '>' . "\r\n";
  $headers .= 'From: Calypso <calypso@stanford.edu>' . "\r\n";

  mail($to, $subject, $message, $headers);
}

function send_email_with_reply_to($name, $email, $subject, $message, $replytoname, $replytoemail) {
  $to = '';
  $message = '
<html>
<head>
  <title>' . $subject . '</title>
</head>
<body>
' . $message . '
</body>
</html>
';

  $headers  = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
  $headers .= 'To: Calypso  <' . $email . '>' . "\r\n";
  $headers .= 'From: ' . $name . ' <calypso@stanford.edu>' . "\r\n";
  $headers .= 'Reply-To: ' . $replytoname . ' <' . $replytoemail . '>' . "\r\n";

  mail($to, $subject, $message, $headers);
}

function send_to_members($condition, $subject, $message) {
  $message = $message . "<br />&nbsp;<br /><div style='font-size:80%'>Don't reply this email...<br />Click 
<a href='https://www.stanford.edu/group/calypso/cgi-bin/members/?action=settings'>here</a> to change your email preferences.</div>";
  $result = mysql_query("SELECT name, email FROM members WHERE " . $condition);
  while ($row = mysql_fetch_array($result)) {
    $name = $row["name"];
    $email = $row["email"];
    if (isset($name) && !empty($name) && isset($email) && !empty($email)) {
      send_email($name, $email, $subject, $message);
    }
  }
}

?>