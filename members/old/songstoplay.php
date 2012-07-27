<tr>
<td colspan='2'>
<h1>Recommended songs for <?php
$gigid = $_GET['gigid'];

$result = mysql_query("SELECT name FROM gigs WHERE gigid = '$gigid'");
if ($row = mysql_fetch_array($result))
  echo $row['name'];


?></h1>
<br />
<?php

$result = mysql_query("SELECT members.name FROM responses, members WHERE responses.gigid = '$gigid' AND responses.sunetid = members.sunetid AND responses.playing = '1'");
$count = mysql_num_rows($result);
if ($count > 0) {
echo "People playing: ";
$i = 0;
while ($row = mysql_fetch_array($result)) {
  echo $row['name'];
  if ($i++ < $count - 1)
    echo ", ";
}
}
else {
  echo "No one has signed up to play yet.";
  exit();
}


?>

<script type="text/javascript">
  var instnames = new Array(<?php
			    $result = mysql_query("SELECT name FROM instruments");
			    $count = mysql_num_rows($result);
			    $i = 0;
			    while ($row = mysql_fetch_array($result)) {
			      echo "'" . $row['name'] . "'";
			      if ($i++ < $count - 1)
				echo ",";
			    }
			    ?>);
</script>
&nbsp;<br />&nbsp;<br />
<div id="recommendations"></div>

<script type="text/javascript">
var allsongs;
var assign;

function showsongs(x) {
  x = splitresponse(x);
  var currentsong = -1;
  allsongs = new Array();
  var songobj;
  for (var i = 0; i < x.length - 1; i++ ) {
    if (x[i][4] != currentsong) {
      if (currentsong != -1)
	allsongs.push(songobj);
      songobj = new Object();
      songobj.id = x[i][4];
      songobj.name = x[i][5];
      songobj.parts = new Array();
      for (var j = 0; j < 6; j++) {
	songobj.parts[j] = new Array();
      }
      currentsong = x[i][4];
    }

    var partind = x[i][2] - 1;//Math.round((x[i][2] - 1)/2);
    var part = new Object();
    part.id = x[i][0];
    part.name = x[i][1];
    part.skill = x[i][6];

    songobj.parts[partind].push(part);
  }
  if (currentsong != -1)
    allsongs.push(songobj);

  /*  
  var txt = "";
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
  findbestassignments();
  showbestassignments();
}

function findbestassignments() {
  //var txt = "";
  assign = new Array();
  for (var i = 0; i < allsongs.length; i++) {
    assign[i] = new Object();
    assign[i].parts = new Array();
    assign[i].name = allsongs[i].name;
    for (var j = 0; j < 6; j++) {
      assign[i].parts[j] = new Object();
      assign[i].parts[j].people = new Array();
    }
    
    /* Find people who only play one part */
    var singlepart = new Array();
    for (var j = 0; j < 6; j++ ) {
      for (var k = 0; k < allsongs[i].parts[j].length; k++) {
	var part = allsongs[i].parts[j][k];
	var foundother = false;
	for (var l = 0; l < singlepart.length; l++) {
	  if (singlepart[l].id == part.id) {
	    foundother = true;
	    singlepart[l].other = true;
	    break;
	  }
	}
	if (!foundother) {
	  var single = new Object();
	  single.id = part.id;
	  single.name = part.name;
	  single.skill = part.skill;
	  single.inst = j;
	  single.other = false;
	  singlepart.push(single);
	}
      }
    }

    for (var j = 0; j < singlepart.length; j++) {
      if (!singlepart[j].other) {
	var inst = singlepart[j].inst;
	for (var k = 0; k < allsongs[i].parts[inst].length; k++) {
	  if (allsongs[i].parts[inst][k].id == singlepart[j].id) {
	    allsongs[i].parts[inst].splice(k, 1);
	    break;
	  }
	}
	var part = new Object();
	part.name = singlepart[j].name;
	part.skill = singlepart[j].skill;
	assign[i].parts[singlepart[j].inst].people.push(part);
      }
    }

    /* Find parts that only have one person */
    var partimportance = new Array(0,3,4,2,1);
    /* Most important to least important:
       lead, triple, set, second, tenors
       (bass is not needed) */
    var addedone = true;
    while (addedone) {
      addedone = false;
      var personneeded = "";
      for (var j = 0; j < partimportance.length; j++) {
	var inst = partimportance[j];
	if (allsongs[i].parts[inst].length == 1 &&
	    assign[i].parts[inst].people.length == 0) {
	  var part = allsongs[i].parts[inst][0];
	  personneeded = part.id;
	  var mypart = new Object();
	  mypart.name = part.name;
	  mypart.skill = part.skill;
	  assign[i].parts[inst].people.push(mypart);
	  addedone = true;
	  break;
	}
      }
      
      if (addedone) {
	for (var k = 0; k < 6; k++) {
	  for (var l = 0; l < allsongs[i].parts[k].length; l++) {
	    if (allsongs[i].parts[k][l].id == personneeded) {
	      allsongs[i].parts[k].splice(l, 1);
	      break;
	    }
	  }
	}
      }
    }

    /* Try to give each part a green person */
    var greenskill = 1;
    var yellowskill = 2;
    var redskill = 3;
    var greenweight = 3;
    var yellowweight = 2;
    var redweight = 0.5;
    var numtenors = 5;
    var numdoubletenors = 2;
    var numdoubleseconds = 4;
    var numcellos = 1;

    addedone = true;
    while (addedone) {
      addedone = false;
      var greenperpartassigned = new Array();
      var greenperpartleft = new Array();
      for (var j = 0; j < 6; j++) {
	var p = new Object();
	p.count = 0;
	p.inst = j;
	greenperpartassigned.push(0);
	greenperpartleft.push(p);
      }
      for (var j = 0; j < 6; j++) {
	for (var k = 0; k < assign[i].parts[j].people.length; k++) {
	  if (assign[i].parts[j].people[k].skill == greenskill)
	    greenperpartassigned[j]++;
	}
	for (var k = 0; k < allsongs[i].parts[j].length; k++) {
	  if (allsongs[i].parts[j][k].skill == greenskill)
	    greenperpartleft[j].count++;
	}
      }
      greenperpartleft.sort(function(a,b){return a.count-b.count;});
      
      var personadded = "";
      for (var j = 0; j < 6; j++) {
	var inst = greenperpartleft[j].inst;
	var count = greenperpartleft[j].count;
	if (greenperpartassigned[inst] == 0 && count > 0) {
	  for (var k = 0; k < allsongs[i].parts[inst].length; k++) {
	    if (allsongs[i].parts[inst][k].skill == greenskill) {
	      var part = allsongs[i].parts[inst][k];
	      personadded = part.id;
	      var mypart = new Object();
	      mypart.name = part.name;
	      mypart.skill = part.skill;
	      assign[i].parts[inst].people.push(mypart);
	      addedone = true;
	      break;
	    }
	  }
	  
	  break;
	}
      }

      if (addedone) {
	for (var k = 0; k < 6; k++) {
	  for (var l = 0; l < allsongs[i].parts[k].length; l++) {
	    if (allsongs[i].parts[k][l].id == personadded) {
	      allsongs[i].parts[k].splice(l, 1);
	      break;
	    }
	  }
	}
      }
    }
    
    /* Solidify each part */
    var importancebyinst = new Array(1,5,4,2,3,6);
    var totalscore = 0.0;
    addedone = true;
    while (addedone) {
      totalscore = 0.0;
      addedone = false;
      var sectionscores = new Array();
      for (var j = 0; j < 6; j++) {
	var p = new Object();
	p.score = 0.0;
	p.count = 0;
	p.done = false;
	p.inst = j;
	sectionscores.push(p);
      }
      var worstsectionscore = 10000;
      for (var j = 0; j < 6; j++) {
	for (var k = 0; k < assign[i].parts[j].people.length; k++) {
	  sectionscores[j].count++;
	  
	  if (assign[i].parts[j].people[k].skill == greenskill)
	    sectionscores[j].score += greenweight;
	  else if (assign[i].parts[j].people[k].skill == yellowskill)
	    sectionscores[j].score += yellowweight;
	  else
	    sectionscores[j].score += redweight;
	  
	  if (j == 0 && sectionscores[j].count == numtenors)
	    sectionscores[j].done = true;
	  if (j == 1 && sectionscores[j].count == numdoubletenors)
	    sectionscores[j].done = true;
	  if (j == 2 && sectionscores[j].count == numdoubleseconds)
	    sectionscores[j].done = true;
	  if (j == 3 && sectionscores[j].count == numcellos)
	    sectionscores[j].done = true;
	  if ((j == 4 || j == 5) && sectionscores[j].count == 1)
	    sectionscores[j].done = true;/* Don't need two bassists or drummers */
	}

	totalscore += sectionscores[j].score;
	/* Don't worry if we don't have a bass */
	var badscore = sectionscores[j].score;
	if (j == 4 && badscore > 0.2)
	  badscore = 10000;
	else if (j == 4 && badscore < 0.2)
	  badscore = -1;
	if (badscore < worstsectionscore && j != 5)
	  worstsectionscore = badscore;
      }
      totalscore += worstsectionscore*1.5;

      sectionscores.sort(function(a,b){if (a.score-b.score != 0){return a.score-b.score;}else{return importancebyinst[a.inst] - importancebyinst[b.inst];}});
      
      var personadded = "";
      for (var j = 0; j < 6; j++) {
	if (sectionscores[j].done)
	  continue;
	var inst = sectionscores[j].inst;
	var lowestskill = 10; /* Lower is better... */
	for (var k = 0; k < allsongs[i].parts[inst].length; k++) {
	  var skill = allsongs[i].parts[inst][k].skill;
	  if (skill < lowestskill) {
	    lowestskill = skill;
	    if (lowestskill == greenskill)
	      break;
	  }
	}
	for (var k = 0; k < allsongs[i].parts[inst].length; k++) {
	  if (allsongs[i].parts[inst][k].skill == lowestskill) {
	    var part = allsongs[i].parts[inst][k];
	    personadded = part.id;
	    var mypart = new Object();
	    mypart.name = part.name;
	    mypart.skill = part.skill;
	    assign[i].parts[inst].people.push(mypart);
	    addedone = true;
	    break;
	  }
	}

	if (addedone)
	  break;
      }

      if (addedone) {
	for (var k = 0; k < 6; k++) {
	  for (var l = 0; l < allsongs[i].parts[k].length; l++) {
	    if (allsongs[i].parts[k][l].id == personadded) {
	      allsongs[i].parts[k].splice(l, 1);
	      break;
	    }
	  }
	}
      }
    }

    assign[i].score = totalscore;
    
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
}

function showbestassignments() {
  var txt = "";
  assign.sort(function(a,b){return b.score - a.score;});
  for (var i = 0; i < assign.length; i++) {
    if (assign[i].score < 10.0)
      break;
    txt += "<div style='border:1px black solid;width:450px'>";
    txt += assign[i].name + "<br />";
    txt += "<table style='width:450px'>";
    for (var j = 0; j < 6; j++) {
      txt += "<tr><td><div style='width:130px;font-size:90%'>" + instnames[j];
      txt += ":</div></td><td><div style='width:320px;font-size:80%'>";
      for (var k = 0; k < assign[i].parts[j].people.length; k++) {
	txt += "<span style='color:" + skilltocolor[assign[i].parts[j].people[k].skill - 1];
	txt += "'>" + assign[i].parts[j].people[k].name + "</span>";
	if (k < assign[i].parts[j].people.length - 1)
	  txt += ", ";
      }
      txt += "</div></td>";
    }
    txt += "</table></div><br />";
  }

  id("recommendations").innerHTML = txt;
}

doajax("partsforgig&gigid=<?php echo $gigid; ?>", showsongs);
</script>


</td></tr>




