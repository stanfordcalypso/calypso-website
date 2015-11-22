<tr>
<td colspan='2'>
<p>Recommended songs for 
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

?>
</p>
<select size="1" id="tableselect" style="width:140px" onChange="updateTable(this)">
  <option value="playerVpart" selected>Player vs. Part</option>
  <option value="songVpart">Songs vs. Part</option>
</select>
<div id='javascript_test'></div>
</td></tr>

<script type="text/javascript">
 "use strict"; 
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

  function saveResults(sql_result) {
    window.results = [];
    var rows = sql_result.split(bigdivider);  
    for (var i = 0, sz = rows.length - 1; i < sz; ++i) {
      var row = rows[i].split(smalldivider);
      var elem = {
        "song": row[0],
        "instrument": row[1],
        "member": row[2],
        "skill": row[3]
      }
      window.results.push(elem);
    }
  }

  function parseResultAsSongVPart(results) {
    var colnames = {};
    var rows = {};
    results.forEach(function(elem) {
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
  function parseResultAsPlayerVPart(results) {
    var colnames = {};
    var rows = {};
    results.forEach(function(elem) {
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

  function makePartTable(results, resultsParser, rowsName) {
    //grrrr need destructuring assignment
    var parsedResults = resultsParser(results);
    Object.keys(parsedResults[0]).forEach(function(colname) { console.log(colname);});
    var content = getTableHtml(parsedResults[0], parsedResults[1], rowsName);
    id("javascript_test").innerHTML = content;
  }

  function makePlayerVPartTable(results) {
    console.log("playerVPart");
    dumpresults(results);
  }
  function getTableObject(name, skill) {
    return {"name": name, "skill": skill};
  }
  
  function onPageLoad() {
    doajax("allpartsforactivemembers", initializePage);
  }

  function sayHi(dropdown) {
    console.log("Hello!");
    console.log(dropdown.selectedIndex);
  }

  function updateTable(dropdown) {
    if (!window.results) {
      console.error("SQL results not found");
      return;
    }
    switch(dropdown.options[dropdown.selectedIndex].value) {
    case "songVpart":
      makePartTable(window.results, parseResultAsSongVPart, "Song");
      break;
    case "playerVpart":
      makePartTable(window.results, parseResultAsPlayerVPart, "Player");
      break;
    default:
      console.error("Unknown dropdown value: " + dropdown.options[dropdown.selectedIndex].value);
    }
  }

  function initializePage(sql_result) {
    saveResults(sql_result);
    //alert(window.results.length);
    //dumpresults(window.results);
    updateTable(document.getElementById("tableselect"));
  }

  onPageLoad();

</script>


