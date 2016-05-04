//HOW TO USE
//1. populate window.playableSongs with the songs you wish to have displayed
//2. make sure you have a div named "table" to put the table in and a dropdown menu 
//   to feed to updateTable

"use strict";

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
  return [Object.keys(colnames).sort(), rows];
}

function getTableHtml(colnames, rowNames, rows, rowsName) {
  var txt = "<table border=1>";
  var colwidth = 100/(colnames.length + 1);
  txt += "<tr>";
  txt +='<td style="width:' + colwidth + '%;" class="row-header"><span class="column-name placeholder"></span></td>';
  // console.log(colnames);
  colnames.forEach(function(colname) {
   txt += '<td style="width:' + colwidth + '%;"><span class="column-name">' + colname + '</span></td>';
  });
  txt += "</tr>";
  for (var i = 0; i < rowNames.length; i++) {
    const rowname = rowNames[i];
    // console.log(rowname, rows[rowname]);
    var row = rows[rowname];
    txt += "<tr>";
    txt += '<td style="width:' + colwidth + '%;" class="row-header"><span class="row-name">' + rowname + '</span></td>';
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
  }
  txt += "</table>";
  return txt;
}

//naive, does not account for the fact that one player can only play one part
function score(song) {
  var score = 0;
  for (const part in song) {
    if (!song.hasOwnProperty(part)) continue;
    var numGoodPlayers = 0;
    song[part].forEach(function(player) {
      switch (player.skill) {
      case "1":
        numGoodPlayers++;
        score++;
        break;
      case "2":
        score += 0.5;
        break;
      default:
        break;
      }
    });
    //at least one good player most important
    if (numGoodPlayers > 0)
      score += 10;
  }
  return score;
}

function getRowOrderer(rows) {
  //order by the minimum number of not-bad players in a given section
  const comp = function(a, b) { 
    return -(score(rows[a]) - score(rows[b]));
  };
  return comp;
}

function makePartTable(songs, resultsParser, rowsName) {
  //grrrr need destructuring assignment
  var colsAndRows = resultsParser(songs);
  // const temp = Object.keys(colsAndRows[1]);
  // const rowNames = temp.sort(getRowOrderer(colsAndRows[1]));
  const rowNames = Object.keys(colsAndRows[1]).sort(getRowOrderer(colsAndRows[1]));
  console.log(rowNames);
  var content = getTableHtml(colsAndRows[0], rowNames, colsAndRows[1], rowsName);
  id("table").innerHTML = content;
}

function getTableObject(name, skill) {
  return {"name": name, "skill": skill};
}

// function onPageLoad() {
//   doajax("allpartsforactivemembers", setAllSongs);
//   tryInitializePage();
// }

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

// function updateMembers(dropdown) {
//   setPlayableSongs(window.allSongs, dropdown);
//   updateTable(document.getElementById("typeselect"));
// }

// function tryInitializePage() {
//   if (!song_ajax_done) {
//     window.setTimeout(tryInitializePage, 5);
//   } else {
//     initializePage();
//   }
// }

// function initializePage() {
//   window.playableSongs = window.allSongs;
//   updateTable(document.getElementById("typeselect"));
// }

// onPageLoad();
