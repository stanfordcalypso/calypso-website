<?php
function get_fact(){
  include_once 'dbcon.php';
  $base_url = 'http://www.reddit.com/r/TellMeAFact.json';
  $url = $base_url;
  $suffix = "";
  for ($i = 0; $i < 10; $i++) {
    echo $url.$suffix;
    echo '<br/>';
    $main_page = get_json_for_url($url.$suffix);
    $posts = $main_page['data']['children'];
    echo count($posts);
    $random_question = get_random_valid_post($posts, 'post_is_valid');
    if ($random_question == false) {
      //no more stuff for this page, look at the next one
      $after = $main_page['data']['after'];
      $suffix = '?after='.$after;
      continue;
    }
    $comments_url = $random_question['data']['url'];
    $comments = get_json_for_url($comments_url.'.json');
    if (count($comments) <= 1) {
      //got a post with empty comments
      mysql_query("INSERT INTO used_facts (id) VALUES ('".$random_question['data']['id']."')");
      continue;
    }
    echo_contents($comments[1]);
    $random_answer = get_random_valid_post($comments[1]['data']['children'], 'comment_is_valid');
    if ($random_answer == false) {
      //no more content in this post
      mysql_query("INSERT INTO used_facts (id) VALUES ('".$random_question['data']['id']."')");
      continue;
    }
    //echo_contents($random_answer);
    mysql_query("INSERT INTO used_facts (id) VALUES ('".$random_answer['data']['id']."')");
    return array($random_question['data']['title'], $random_answer['data']['body']);
  }
  //TODO: finish this, for the case that we run off the page
  mysql_close($con);
  return false;
}

function echo_contents($arr) {
  foreach ($arr as $thing) {
    echo $thing;
    echo '<br/>';
  }
}

function get_random_valid_post($posts, $is_valid) {
  $order = generate_random_perm(count($posts));
  foreach ($order as $i) {
    if (call_user_func($is_valid, $posts[$i])) {
      return $posts[$i];
    }
  }
  return false;
}

function get_json_for_url($url) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  $result = curl_exec($ch);
  echo strlen($result);
  echo '<br/>';
  curl_close($ch);
  return(json_decode($result, true));
}

function generate_random_perm($n) {
  $arr = range(0, $n - 1);  
  for ($i = 0; $i < $n; $i++) {
    $to_swap = rand($i, $n - 1);
    $tmp = $arr[$i];
    $arr[$i] = $arr[$to_swap];
    $arr[$to_swap] = $tmp;
  }
  return $arr;
}

function check_db($id) {
  $dups = mysql_query("SELECT * FROM used_facts WHERE id = '$id'");
  return(mysql_num_rows($dups) > 0);
}

function post_is_valid($post) {
  return !check_db($post['data']['id'])
    && $post['kind'] == 't3'
    && is_null($post['data']['link_flair_text'])
    && !is_null($post['data']['title'])
    && preg_match('/TMAF (.+)$/i', $post['data']['title']) > 0
    && intval($post['data']['ups']) >= 25;
}

function comment_is_valid($comment) {
  return !check_db($comment['data']['id'])
    && $comment['kind'] == 't1'
    && !is_null($comment['data']['body'])
    && $comment['data']['body'] != '[deleted]'
    && $comment['data']['body'] != '[removed]';
    //&& $comment['data']['ups'] >= 15;
}
//$fact = get_fact();
//echo $fact[0] . $fact[1];
?>
