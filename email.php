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
  $message = $message . "<br />&nbsp;<br /><div style='font-size:80%'>Don't reply to this email...<br />Click
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

function text_to_members($condition, $subject, $message) {

  $result = mysql_query("SELECT name, phonenumber, phonecarrier, active FROM members WHERE " . $condition);
  while ($row = mysql_fetch_array($result)) {
    $name = $row["name"];
    $phonenumber = $row["phonenumber"];
    $phonecarrier = $row["phonecarrier"];
    $active = $row["active"];
    if (isset($name) && !empty($name) && isset($phonenumber) && !empty($phonenumber) && isset($phonecarrier) && !empty($phonecarrier) && isset($active) && $active == 1) {
      $textemailaddress = $phonenumber;
      $carriersmsgateways = array('ATT' => '@txt.att.net','Verizon'=>'@vtext.com', 'Sprint'=>'@messaging.sprintpcs.com', 'T-Mobile'=>'@tmomail.net', 'Consumer Cellular'=>'@txt.att.net', 'Boost'=>'@sms.myboostmobile.com','Metro PCS'=>'@mymetropcs.com');

      //construct the full sms gateway email address
      foreach ($carriersmsgateways as $id=> $value) {
        if($id == $phonecarrier) {
          $textemailaddress = $textemailaddress . $value;
        }
      }

      //send the message
      $headers  = 'MIME-Version: 1.0' . "\r\n";
      $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
      $headers .= 'To: ' . $name . ' <' . $email . '>' . "\r\n";
      $headers .= 'From: Calypso <calypso@stanford.edu>' . "\r\n";
      mail($textemailaddress, $subject, $message, $headers);

    }
  }
}

?>
