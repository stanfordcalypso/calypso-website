<?php

$SUNETID = getenv('WEBAUTH_USER');

$con = mysql_connect("mysql-user.stanford.edu","gcalypsomember0","iegoobei");
if (!$con) {
   die('Could not connect: ' . mysql_error());
}

mysql_select_db("g_calypso_members", $con);

?>