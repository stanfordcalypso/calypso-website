<tr>
<td colspan='2'>
<?php
//include "../cron.php";
include_once(dirname(__FILE__) . "/ajax.php");
?>
<select size="1" id="typeselect" style="width:140px" onChange="updateTable(this)" hidden>
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

  function addSongsForGig(sql_result) {
    window.playableSongs = [];
    var rows = sql_result.split(bigdivider);
    for (var i = 0, sz = rows.length - 1; i < sz; ++i) {
      var row = rows[i].split(smalldivider);
      if (row[3].substring(0, 7) == "Triples")
        row[3] = "Triples";
      var elem = {
        "sunetid": row[0],
        "member": row[1],
        "instrument": row[3],      
        "song": row[5],
        "skill": row[6]
      }
      window.playableSongs.push(elem);
    }
    song_ajax_done = true;
  }

  function onPageLoad() {
    <?php echo 'doajax("partsforgig&gigid=' . $_GET[gigid] . '", addSongsForGig);' ?>
    tryInitializePage();
  }

  function tryInitializePage() {
    if (!song_ajax_done) {
      window.setTimeout(tryInitializePage, 5);
    } else {
      updateTable(document.getElementById("typeselect"));
    }
  }

  onPageLoad();

</script>
