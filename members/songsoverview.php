<tr>
<td colspan='2'>
<p>View Songs by part and player
<?php
//include "../cron.php";
include(dirname(__FILE__) . "/../dbcon.php");
?>
</p>
<select size="1" id="typeselect" style="width:140px" onChange="updateTable(this)">
  <option value="playerVpart">Player vs. Part</option>
  <option value="songVpart" selected>Songs vs. Part</option>
</select>

<div id='table'><p style="color:blue;">Waiting for server response...</p></div>
</td></tr>

<script type="text/javascript">
  "use strict";

  var song_ajax_done = false;

  function dumpsqlresult(sql_result) {
    var rows = sql_result.split(bigdivider);
    var txt = "<ul>\n";
    for (var i = 0, sz = rows.length - 1; i < sz; ++i) {
      txt += "<li>" + rows[i] + "</li>\n";
    }
   txt += "</ul>";
   id("table").innerHTML = txt;
  }

  function dumpresults(results) {
    var txt = "<ul>\n";
    for (var i = 0, sz = results.length; i < sz; ++i) {
      txt += "<li>" + JSON.stringify(results[i]) + "</li>\n";
    }
   txt += "</ul>";
   id("table").innerHTML = txt;
  }

  function setAllSongs(sql_result) {
    window.allSongs = [];
    var rows = sql_result.split(bigdivider);
    for (var i = 0, sz = rows.length - 1; i < sz; ++i) {
      var row = rows[i].split(smalldivider);
      if (row[1].substring(0, 7) == "Triples")
        row[1] = "Triples";
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

  function onPageLoad() {
    doajax("allpartsforactivemembers", setAllSongs);
    tryInitializePage();
  }

  function sayHi(dropdown) {
    console.log("Hello!");
    console.log(dropdown.selectedIndex);
  }

  function tryInitializePage() {
    if (!song_ajax_done) {
      window.setTimeout(tryInitializePage, 5);
    } else {
      initializePage();
    }
  }

  function initializePage() {
    window.playableSongs = window.allSongs;
    updateTable(document.getElementById("typeselect"));
  }

  onPageLoad();

</script>
