<?php

function splicearr($arr, $pos) {
  $newarr = array();
  $found = false;
  for ($i = 0; $i < count($arr); $i++) {
    if ($i < $pos)
      $newarr[$i] = $arr[$i];
    if ($i > $pos)
      $newarr[$i - 1] = $arr[$i];
  }
  return $newarr;
}

function get_recommended_songs($tgigid) {
$result = mysql_query("SELECT members.sunetid, members.name, parts.instrumentid, instruments.name, songs.songid, songs.name, parts.skillid
FROM parts, responses, members, songs, instruments
WHERE responses.gigid = '" . $tgigid . "' AND responses.sunetid = members.sunetid AND responses.playing = '1'
AND parts.sunetid = members.sunetid AND parts.songid = songs.songid AND parts.instrumentid = instruments.instrumentid
ORDER BY songs.songid, instruments.instrumentid, members.name");

$skilltocolor = array("#008800", "#888800", "#880000");
$r2 = mysql_query("SELECT name FROM instruments");
$instnames = array();
$i = 0;
while ($row = mysql_fetch_array($r2)) {
  $instnames[$i] = $row['name'];
  $i++;
}

$resp = array();
while ($row = mysql_fetch_array($result)) {
  $count = count($row);
  $thisresp = array();
  for ($i = 0; $i < $count; $i++) {
    $thisresp[$i] = $row[$i];
  }
  $resp[count($resp)] = $thisresp;
}

$currentsong = -1;
$allsongs = array();
$songobj = array();
for ($i = 0; $i < count($resp); $i++) {
  if ($resp[$i][4] != $currentsong) {
    if ($currentsong != -1)
      $allsongs[count($allsongs)] = $songobj;
    $songobj = array();
    $songobj["id"] = $resp[$i][4];
    $songobj["name"] = $resp[$i][5];
    $songobj["parts"] = array();
    for ($j = 0; $j < 6; $j++) {
      $songobj["parts"][$j] = array();
    }
    $currentsong = $resp[$i][4];
  }
  
  $partind = $resp[$i][2] - 1;
  $part = array();
  $part["id"] = $resp[$i][0];
  $part["name"] = $resp[$i][1];
  $part["skill"] = $resp[$i][6];
  
  $songobj["parts"][$partind][count($songobj["parts"][$partind])] = $part;
}
if ($currentsong != -1)
  $allsongs[count($allsongs)] = $songobj;

/*
for ($i = 0; $i < count($allsongs); $i++) {
  echo "<br/> " . $allsongs[$i]["name"] . " ";
  for ($j = 0; $j < 6; $j++) {
    echo $j . " ";
    for ($k = 0; $k < count($allsongs[$i]["parts"][$j]); $k++) {
      echo $allsongs[$i]["parts"][$j][$k]["id"] . "/" . $allsongs[$i]["parts"][$j][$k]["name"] . " ";
    }
  }
  }

echo "<br />&nbsp;<br />";
*/

//findbestassignments();
//showbestassignments();

$assign = array();
for ($i = 0; $i < count($allsongs); $i++) {
  $assign[$i] = array();
  $assign[$i]["parts"] = array();
  $assign[$i]["name"] = $allsongs[$i]["name"];
  for ($j = 0; $j < 6; $j++) {
    $assign[$i]["parts"][$j] = array();
    $assign[$i]["parts"][$j]["people"] = array();
  }
  
  /* Find people who only play one part */
  $singlepart = array();
  for ($j = 0; $j < 6; $j++ ) {
    for ($k = 0; $k < count($allsongs[$i]["parts"][$j]); $k++) {
      $part = $allsongs[$i]["parts"][$j][$k];
      $foundother = false;
      for ($l = 0; $l < count($singlepart); $l++) {
	if ($singlepart[$l]["id"] == $part["id"]) {
	  $foundother = true;
	  $singlepart[$l]["other"] = true;
	  break;
	}
      }
      if (!$foundother) {
	$single = array();
	$single["id"] = $part["id"];
	$single["name"] = $part["name"];
	$single["skill"] = $part["skill"];
	$single["inst"] = $j;
	$single["other"] = false;
	$singlepart[count($singlepart)] = $single;
      }
    }
  }
  
  for ($j = 0; $j < count($singlepart); $j++) {
    if (!$singlepart[$j]["other"]) {
      $inst = $singlepart[$j]["inst"];
      for ($k = 0; $k < count($allsongs[$i]["parts"][$inst]); $k++) {
	if ($allsongs[$i]["parts"][$inst][$k]["id"] == $singlepart[$j]["id"]) {
	  $allsongs[$i]["parts"][$inst] = splicearr($allsongs[$i]["parts"][$inst], $k);
	  break;
	}
      }
      $part = array();
      $part["name"] = $singlepart[$j]["name"];
      $part["skill"] = $singlepart[$j]["skill"];
      $assign[$i]["parts"][$singlepart[$j]["inst"]]["people"][count($assign[$i]["parts"][$singlepart[$j]["inst"]]["people"])] = $part;
    }
  }

  /* Find parts that only have one person */
  $partimportance = array(0,3,4,2,1);
  /* Most important to least important:
     lead, triple, set, second, tenors
     (bass is not needed) */
  $addedone = true;
  while ($addedone) {
    $addedone = false;
    $personneeded = "";
    for ($j = 0; $j < count($partimportance); $j++) {
      $inst = $partimportance[$j];
      if (count($allsongs[$i]["parts"][$inst]) == 1 &&
	  count($assign[$i]["parts"][$inst]["people"]) == 0) {
	$part = $allsongs[$i]["parts"][$inst][0];
	$personneeded = $part["id"];
	$mypart = array();
	$mypart["name"] = $part["name"];
	$mypart["skill"] = $part["skill"];
	$assign[$i]["parts"][$inst]["people"][count($assign[$i]["parts"][$inst]["people"])] = $mypart;
	$addedone = true;
	break;
      }
    }
    
    if ($addedone) {
      for ($k = 0; $k < 6; $k++) {
	for ($l = 0; $l < count($allsongs[$i]["parts"][$k]); $l++) {
	  if ($allsongs[$i]["parts"][$k][$l]["id"] == $personneeded) {
	    $allsongs[$i]["parts"][$k] = splicearr($allsongs[$i]["parts"][$k], $l);
	    break;
	  }
	}
      }
    }
  }

  /* Try to give each part a green person */
  $greenskill = 1;
  $yellowskill = 2;
  $redskill = 3;
  $greenweight = 3;
  $yellowweight = 2;
  $redweight = 0.5;
  $nobodyweight = -0.5;
  $numtenors = 5;
  $numdoubletenors = 2;
  $numdoubleseconds = 4;
  $numcellos = 1;
  
  /* NOT USING THIS RIGHT NOW */
  $addedone = true;
  while ($addedone) {
    $addedone = false;
    $greenperpartassigned = array();
    $greenperpartleft = array();
    for ($j = 0; $j < 6; $j++) {
      $p = array();
      $p["count"] = 0;
      $p["inst"] = $j;
      $greenperpartassigned[$j] = 0;
      $greenperpartleft[$j] = $p;
    }
    for ($j = 0; $j < 6; $j++) {
      for ($k = 0; $k < count($assign[$i]["parts"][$j]["people"]); $k++) {
	if ($assign[$i]["parts"][$j]["people"][$k]["skill"] == $greenskill)
	  $greenperpartassigned[$j]++;
      }
      for ($k = 0; $k < count($allsongs[$i]["parts"][$j]); $k++) {
	if ($allsongs[$i]["parts"][$j][$k]["skill"] == $greenskill)
	  $greenperpartleft[$j]["count"]++;
      }
    }
    $sortarr = array();
    for ($j = 0; $j < count($greenperpartleft); $j++)
      $sortarr[$j] = $greenperpartleft[$j]["count"];
    array_multisort($sortarr, $greenperpartleft);

    $personadded = "";
    for ($j = 0; $j < 6; $j++) {
      $inst = $greenperpartleft[$j]["inst"];
      $count = $greenperpartleft[$j]["count"];
      if ($greenperpartassigned[$inst] == 0 && $count > 0) {
	for ($k = 0; $k < count($allsongs[$i]["parts"][$inst]); $k++) {
	  if ($allsongs[$i]["parts"][$inst][$k]["skill"] == $greenskill) {
	    $part = $allsongs[$i]["parts"][$inst][$k];
	    $personadded = $part["id"];
	    $mypart = array();
	    $mypart["name"] = $part["name"];
	    $mypart["skill"] = $part["skill"];
	    $assign[$i]["parts"][$inst]["people"][count($assign[$i]["parts"][$inst]["people"])] = $mypart;
	    $addedone = true;
	    break;
	  }
	}
	
	break;
      }
    }
    
    if ($addedone) {
      for ($k = 0; $k < 6; $k++) {
	for ($l = 0; $l < count($allsongs[$i]["parts"][$k]); $l++) {
	  if ($allsongs[$i]["parts"][$k][$l]["id"] == $personadded) {
	    $allsongs[$i]["parts"][$k] = splicearr($allsongs[$i]["parts"][$k], $l);
	    break;
	  }
	}
      }
    }
  }
  
  
  /* Solidify each part */
  $importancebyinst = array(1,5,4,2,3,6);
  $totalscore = 0.0;
  $addedone = true;
  while ($addedone) {
    $totalscore = 0.0;
    $addedone = false;
    $sectionscores = array();
    for ($j = 0; $j < 6; $j++) {
      $p = array();
      $p["score"] = 0.0;
      $p["count"] = 0;
      $p["done"] = false;
      $p["inst"] = $j;
      $sectionscores[$j] = $p;
    }
    $worstsectionscore = 10000.0;
    for ($j = 0; $j < 6; $j++) {
      for ($k = 0; $k < count($assign[$i]["parts"][$j]["people"]); $k++) {
	$sectionscores[$j]["count"]++;
	
	if ($assign[$i]["parts"][$j]["people"][$k]["skill"] == $greenskill)
	  $sectionscores[$j]["score"] += $greenweight;
	else if ($assign[$i]["parts"][$j]["people"][$k]["skill"] == $yellowskill)
	  $sectionscores[$j]["score"] += $yellowweight;
	else
	  $sectionscores[$j]["score"] += $redweight;
	
	if ($j == 0 && $sectionscores[$j]["count"] == $numtenors)
	  $sectionscores[$j]["done"] = true;
	if ($j == 1 && $sectionscores[$j]["count"] == $numdoubletenors)
	  $sectionscores[$j]["done"] = true;
	if ($j == 2 && $sectionscores[$j]["count"] == $numdoubleseconds)
	  $sectionscores[$j]["done"] = true;
	if ($j == 3 && $sectionscores[$j]["count"] == $numcellos)
	  $sectionscores[$j]["done"] = true;
	if (($j == 4 || $j == 5) && $sectionscores[$j]["count"] == 1)
	  $sectionscores[$j]["done"] = true;/* Don't need two bassists or drummers */
      }
      if (count($assign[$i]["parts"][$j]["people"]) == 0) {
	$sectionscores[$j]["score"] = $nobodyweight;
      }
      
      $totalscore += $sectionscores[$j]["score"];
      /* Don't worry if we don't have a bass */
      $badscore = $sectionscores[$j]["score"];
      if ($j == 4 && $badscore > 0.2)
	$badscore = 10000;
      else if ($j == 4 && $badscore < 0.2)
	$badscore = -1;
      if ($badscore < $worstsectionscore && $j != 5)
	$worstsectionscore = $badscore;
    }
    $totalscore += $worstsectionscore*1.5;
    

    $sortarr = array();
    for ($j = 0; $j < count($sectionscores); $j++)
      $sortarr[$j] = $sectionscores[$j]["score"]*1000 + $importancebyinst[$sectionscores[$j]["inst"]];
    array_multisort($sortarr, $sectionscores);
    
    $personadded = "";
    for ($j = 0; $j < 6; $j++) {
      if ($sectionscores[$j]["done"])
	continue;
      $inst = $sectionscores[$j]["inst"];
      $lowestskill = 10; /* Lower is better... */
      for ($k = 0; $k < count($allsongs[$i]["parts"][$inst]); $k++) {
	$skill = $allsongs[$i]["parts"][$inst][$k]["skill"];
	if ($skill < $lowestskill) {
	  $lowestskill = $skill;
	  if ($lowestskill == $greenskill)
	    break;
	}
      }
      for ($k = 0; $k < count($allsongs[$i]["parts"][$inst]); $k++) {
	if ($allsongs[$i]["parts"][$inst][$k]["skill"] == $lowestskill) {
	  $part = $allsongs[$i]["parts"][$inst][$k];
	  $personadded = $part["id"];
	  $mypart = array();
	  $mypart["name"] = $part["name"];
	  $mypart["skill"] = $part["skill"];
	  $assign[$i]["parts"][$inst]["people"][count($assign[$i]["parts"][$inst]["people"])] = $mypart;
	  $addedone = true;
	  break;
	}
      }
      
      if ($addedone)
	break;
    }
    
    if ($addedone) {
      for ($k = 0; $k < 6; $k++) {
	for ($l = 0; $l < count($allsongs[$i]["parts"][$k]); $l++) {
	  if ($allsongs[$i]["parts"][$k][$l]["id"] == $personadded) {
	    $allsongs[$i]["parts"][$k] = splicearr($allsongs[$i]["parts"][$k], $l);
	    break;
	  }
	}
      }
    }
  }
  
  $assign[$i]["score"] = $totalscore;

  /*
  echo $allsongs[$i]["name"] . "<br />";
  for ($j = 0; $j < count($assign[$i]["parts"]); $j++) {
    echo "<br/> " . $j . " ";
    for ($k = 0; $k < count($assign[$i]["parts"][$j]["people"]); $k++) {
      echo $assign[$i]["parts"][$j]["people"][$k]["name"] . " ";
    }
  }
  echo "<br />&nbsp;<br/>";
}
  */
    
    /*
    for (var j = 0; j < assign[i].parts.length; j++) {
      txt += "<br/> " + j + " ";
      for (var k = 0; k < assign[i].parts[j].people.length; k++) {
	txt += assign[i].parts[j].people[k].name + " ";
      }
    }
    txt += "<br/ ><br />";*/
}
  
  /*
  txt += "<br/><br/>";
  for (var i = 0; i < allsongs.length; i++) {
    txt += "<br/> " + allsongs[i].name + " ";
    for (var j = 0; j < 6; j++) {
      txt += j + " ";
      for (var k = 0; k < allsongs[i].parts[j].length; k++) {
	txt += allsongs[i].parts[j][k].id + "/" + allsongs[i].parts[j][k].name + " ";
      }
    }
  }
  id("recommendations").innerHTML = txt;*/

$sortarr = array();
for ($j = 0; $j < count($assign); $j++)
  $sortarr[$j] = 10000.0 - $assign[$j]["score"];
array_multisort($sortarr, $assign);

$txt = "";
for ($i = 0; $i < count($assign); $i++) {
  if ($assign[$i]["score"] < 8.0)
    break;
  //$txt = $txt . $assign[$i]["score"];
  $txt = $txt . "<div style='border:1px black solid;width:450px'>";
  $txt = $txt . $assign[$i]["name"] . "<br />";
  $txt = $txt . "<table style='width:450px'>";
  for ($j = 0; $j < 6; $j++) {
    $txt = $txt . "<tr><td><div style='width:130px;font-size:90%'>" . $instnames[$j];
    $txt = $txt . ":</div></td><td><div style='width:320px;font-size:80%'>";
    for ($k = 0; $k < count($assign[$i]["parts"][$j]["people"]); $k++) {
      $txt = $txt . "<span style='color:" . $skilltocolor[$assign[$i]["parts"][$j]["people"][$k]["skill"] - 1];
      $txt = $txt . "'>" . $assign[$i]["parts"][$j]["people"][$k]["name"] . "</span>";
      if ($k < count($assign[$i]["parts"][$j]["people"]) - 1)
	$txt = $txt . ", ";
    }
    $txt = $txt . "</div></td></tr>\n";
  }
  $txt = $txt . "</table></div><br />\n\n";
}
return $txt;
}

?>