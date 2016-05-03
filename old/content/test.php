<tr>
<td colspan='2'>
<p>View Songs by part and player
<?php
////$link_identifier = mysqli_connect();
////$result = mysqli_query($link_identifier, "SELECT members.sunetid, members.name, parts.instrumentid, instruments.name, songs.songid, songs.name, parts.skillid FROM parts, members, songs, instruments");
////if ($row = mysqli_fetch_array($result))
  ////echo $row[0];

////$link_identifier = mysql_connect();
////$result = mysql_query("SELECT members.sunetid, members.name, parts.instrumentid, instruments.name, songs.songid, songs.name, parts.skillid FROM parts, members, songs, instruments", $link_identifier);
////echo $result;
////if ($row = mysql_fetch_array($result))
  ////echo $row[0];

//$smalldivider = "^";
//$bigdivider = "%";

//function echorow($a) {
  //global $smalldivider;
  //global $bigdivider;

  //$count = count($a);
  //for ($i = 0; $i < $count; $i++) {
    //echo stripslashes($a[$i]);
    //if ($i < $count - 1)
      //echo $smalldivider;
  //}

  //echo $bigdivider;
//}

//function echoresult($r) {
  //while ($row = mysql_fetch_array($r)) {
    //echorow($row);
  //}
//}

//$result = mysql_query("SELECT * FROM members");
//echoresult($result);
//include "./dbcon.php";
include(dirname(__FILE__) . "/../dbcon.php");
?>
</p>
<select size="1" id="typeselect" style="width:140px" onChange="updateTable(this)">
  <option value="playerVpart">Player vs. Part</option>
  <option value="songVpart" selected>Songs vs. Part</option>
</select>

<div id='gigselectoptions'></div>
<!-- <select size="1" id="gigselect" style="width:140px" onChange="updateMembers(this)">
  <span id="gigselectoptions"></span>
</select> -->

<div id='javascript_test'></div>
</td></tr>

<script type="text/javascript">
  "use strict";
  var null_gigid = "none";
  var gig_ajax_done = false;
  var song_ajax_done = false;
  function dumpsqlresult(sql_result) {
    var rows = sql_result.split(bigdivider);
    var txt = "<ul>\n";
    for (var i = 0, sz = rows.length - 1; i < sz; ++i) {
      txt += "<li>" + rows[i] + "</li>\n";
    }
   txt += "</ul>";
   id("javascript_test").innerHTML = txt;
  }

  function dumpresults(results) {
    var txt = "<ul>\n";
    for (var i = 0, sz = results.length; i < sz; ++i) {
      txt += "<li>" + JSON.stringify(results[i]) + "</li>\n";
    }
   txt += "</ul>";
   id("javascript_test").innerHTML = txt;
  }

  function addResponsesForGig(sql_result) {
    var members = {}
    var rows = sql_result.split(bigdivider);
    if (rows.length < 1) return;
    var curgig = rows[0].split(smalldivider).slice(7, 9); //id, name
    for (var i = 0, sz = rows.length - 1; i < sz; ++i) {
      var row = rows[i].split(smalldivider);
      members[row[0]] = true;
    }
    window.responses[curgig[0]] = {
      "name": curgig[1],
      "members": members
    };
  }

  function findMembersForResponses(sql_result) {
    window.responses = {};
    var rows = sql_result.split(bigdivider);
    for (var i = 0, sz = rows.length - 1; i < sz; ++i) {
      var row = rows[i].split(smalldivider);
      // window.curgig = row; //id, name, comments
      doajax("gigresponses&gigid=" + row[0], addResponsesForGig);
      // delete window.curgig;
    }
    wait_for_members_ajax(rows.length - 1);
  }

  function wait_for_members_ajax(size) {
    if (Object.keys(window.responses).length < size) {
      window.setTimeout(wait_for_members_ajax, 10, size);
    } else {
      gig_ajax_done = true;
    }
  }


  function setAllSongs(sql_result) {
    window.allSongs = [];
    var rows = sql_result.split(bigdivider);
    for (var i = 0, sz = rows.length - 1; i < sz; ++i) {
      var row = rows[i].split(smalldivider);
      var elem = {
        "song": row[0],
        "instrument": row[1],
        "member": row[2],
        "sunetid": row[6],
        "skill": row[3]
      }
      window.allSongs.push(elem);
    }
    song_ajax_done = true;
  }

  function parseResultAsSongVPart(songs) {
    var colnames = {};
    var rows = {};
    songs.forEach(function(elem) {
      colnames[elem.instrument] = true;
      if (rows.hasOwnProperty(elem.song)) {
        if (rows[elem.song].hasOwnProperty(elem.instrument)) {
          rows[elem.song][elem.instrument].push(getTableObject(elem.member, elem.skill));
        } else {
          rows[elem.song][elem.instrument] = [getTableObject(elem.member, elem.skill)];
        }
      } else {
        rows[elem.song] = {};
        rows[elem.song][elem.instrument] = [getTableObject(elem.member, elem.skill)];
      }
    });
    return [Object.keys(colnames).sort(), rows];
  }
  function parseResultAsPlayerVPart(songs) {
    var colnames = {};
    var rows = {};
    songs.forEach(function(elem) {
      colnames[elem.instrument] = true;
      if (rows.hasOwnProperty(elem.member)) {
        if (rows[elem.member].hasOwnProperty(elem.instrument)) {
          rows[elem.member][elem.instrument].push(getTableObject(elem.song, elem.skill));
        } else {
          rows[elem.member][elem.instrument] = [getTableObject(elem.song, elem.skill)];
        }
      } else {
        rows[elem.member] = {};
        rows[elem.member][elem.instrument] = [getTableObject(elem.song, elem.skill)];
      }
    });
    colnames = Object.keys(colnames);
    colnames.sort();
    return [colnames, rows];
  }

  function getTableHtml(colnames, rows, rowsName) {
    var txt = "<table border=1>";
    var colwidth = 100/(colnames.length + 1);
    txt += "<tr>";
    txt +='<td style="width:' + colwidth + '%;" class="row-header"><span class="column-name placeholder"></span></td>';
    console.log(colnames);
    colnames.forEach(function(colname) {
     txt += '<td style="width:' + colwidth + '%;"><span class="column-name">' + colname + '</span></td>';
    });
    txt += "</tr>";
    Object.keys(rows).forEach(function(rowname) {
      txt += "<tr>";
      txt += '<td style="width:' + colwidth + '%;" class="row-header"><span class="row-name">' + rowname + '</span></td>';
      console.log(rowname, rows[rowname]);
      var row = rows[rowname];
      colnames.forEach(function(colname) {
        txt += '<td style="width:' + colwidth + '%;"><span class="table-entry">';
        if (colname in row) {
          for (var i = 0, sz = row[colname].length; i < sz; ++i) {
            var elem = row[colname][i];
            txt += '<div style="color:' + skilltocolor[elem.skill - 1] + ';font-size:90%">';
            txt += elem.name;
            if (i < sz - 1) txt += ",";
            txt += '</div>';
          }
        }
        txt += '</span></td>';
      });
      txt += "</tr>";
    });
    txt += "</table>";
    return txt;
   /* for (var i = 0; i < x.length - 1; i++) {*/
      //var thisSong = x[i].split(smalldivider)[0];
      //if (thisSong != currentSong) {
         //if (currentSong != "")
            //txt += "</div></td></tr>";
         //txt += "<tr><td><span style='font-size:90%'>" + thisSong + "</span></td><td>";
         //txt += "<div style='width:250px'>";
         //currentSong = thisSong;
      //}
      //else {
         //txt += ", ";
      //}
      //txt += "<span style='color:" + skilltocolor[x[i].split(smalldivider)[2]-1] + ";font-size:90%'>";
      //txt += x[i].split(smalldivider)[1];
      //txt += "</span> ";
      //txt += "<span style='font-size:80%'>(<a href='#' onclick='deletepart(" + x[i].split(smalldivider)[3] + "," + x[i].split(smalldivider)[4] + ");return false'>x</a>)</span>";
    //}
    //if (currentSong != "")
      //txt += "</div></td></tr>";
    //txt += "</table>";
    /*id("partsiknow").innerHTML = txt;*/
  }

  function makePartTable(songs, resultsParser, rowsName) {
    //grrrr need destructuring assignment
    var parsedResults = resultsParser(songs);
    Object.keys(parsedResults[0]).forEach(function(colname) { console.log(colname);});
    var content = getTableHtml(parsedResults[0], parsedResults[1], rowsName);
    id("javascript_test").innerHTML = content;
  }

  function makePlayerVPartTable(songs) {
    console.log("playerVPart");
    dumpresults(songs);
  }
  function getTableObject(name, skill) {
    return {"name": name, "skill": skill};
  }

  function onPageLoad() {
    doajax("allgigs", findMembersForResponses);
    doajax("allpartsforactivemembers", setAllSongs);
    tryInitializePage();
  }

  function sayHi(dropdown) {
    console.log("Hello!");
    console.log(dropdown.selectedIndex);
  }

  function updateTable(dropdown) {
    if (!window.playableSongs) {
      console.error("SQL songs not found");
      return;
    }
    switch(dropdown.options[dropdown.selectedIndex].value) {
    case "songVpart":
      makePartTable(window.playableSongs, parseResultAsSongVPart, "Song");
      break;
    case "playerVpart":
      makePartTable(window.playableSongs, parseResultAsPlayerVPart, "Player");
      break;
    default:
      console.error("Unknown dropdown value: " + dropdown.options[dropdown.selectedIndex].value);
    }
  }

  function setGigDropDown(gigs_and_members) {
    var dropdownHtml = '<select size="1" id="gigselect" style="width:140px" onChange="updateMembers(this)">'
    dropdownHtml += '<option value="' + null_gigid + '" selected>--</option>\n';
    for (var gigid in gigs_and_members) {
      if (gigs_and_members.hasOwnProperty(gigid)) {
        dropdownHtml += '<option value="' + gigid + '">' + gigs_and_members[gigid].name + '</option>\n';
      }
    }
    id("gigselectoptions").innerHTML = dropdownHtml;
  }

  function setPlayableSongs(allsongs, dropdown) {
    window.playableSongs = [];
    var gigid = dropdown.options[dropdown.selectedIndex].value;
    if (gigid === null_gigid) {
      window.playableSongs = allsongs;
      return;
    } else {
      var giggingMembers = window.responses[gigid].members;
      allsongs.forEach(function(song) {
        if (giggingMembers.hasOwnProperty(song.sunetid)) {
          window.playableSongs.push(song);
        }
      });
    }
  }

  function updateMembers(dropdown) {
    setPlayableSongs(window.allSongs, dropdown);
    updateTable(document.getElementById("typeselect"));
  }

  function tryInitializePage() {
    if (!(gig_ajax_done && song_ajax_done)) {
      window.setTimeout(tryInitializePage, 10);
    } else {
      initializePage();
    }
  }

  function initializePage() {
    setGigDropDown(window.responses);
    setPlayableSongs(window.allSongs, document.getElementById("gigselect"));
    //alert(window.songs.length);
    //dumpresults(window.songs);
    updateTable(document.getElementById("typeselect"));
  }

  onPageLoad();

</script>
