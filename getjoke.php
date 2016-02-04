<?php
function get_joke(){
  $url = 'https://www.reddit.com/r/jokes.json';

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  $result = curl_exec($ch);
  echo strlen($result);
  curl_close($ch);
  return($result);
}

function write_to_file($contents,$new_filename){
    $fp = fopen($new_filename, 'w+');
    fwrite($fp, $contents);
    fclose($fp);
}

function downloadcat($new_file_name) {
  $api_key = "NTkxNjk";
  //$new_file_name = "temp/cat.jpg";
  $url = "http://thecatapi.com/api/images/get?api_key=" . $api_key . "&format=src&type=jpg";
  $referer = "http://thecatapi.com";

  echo "Getting cat...";
  $fp = fopen($url, 'r');
  file_put_contents($new_file_name, $fp);
  fclose($fp);
  echo "Done!";

  //$temp_file_contents = collect_cat($url, $referer);
  //write_to_file($temp_file_contents,$new_file_name);
}

//downloadcat('temp/cat.jpg');
?>
