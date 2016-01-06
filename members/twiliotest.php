<?php
 
require "twilio-php-master/Services/Twilio.php";

//Lawrence's Account Info
$AccountSid = "ACb9703cf0b46e3ca312947e566ecd6d3c"; 
$AuthToken  = "decdc42f285e05feef9840da552d12a7"; 
//Calypso's account info
//$AccountSid = "AC1da30f815f640ebba16030229aeda812";
//$AuthToken  = "ffb044cd8a904ac3b8916408a48d58c7"; 

$client = new Services_Twilio($AccountSid, $AuthToken);
//Lawrence's
$from_number = "310-846-8172";
$my_number = "970-779-1536";
//Calypso's
//$from_number = "+19704260379";
//$my_number = "+19707791536";
echo "<br/> Sending Message...";
$message = $client->account->messages->create(array(
    "From" => $from_number,
    "To" => $my_number,
    "Body" => "Sent via Twilio, yo!",
));
 
// Display a confirmation message on the screen
echo "<br/> Sent message {$message->sid}";
?>
