<tr><td class='half'>
<h1>Songs</h1>
<br />
<div id="allsongs">Loading...</div>
<br />
<input type='button' value='Add Song' style='position:relative;left:10px' onclick='window.location="?action=addsong"' />

<script type="text/javascript">
function loadsongs(x) {
    var g = splitresponse(x);
    var txt = "<table>";
    for (var i = 0; i < g.length - 1; i++) {
        txt += "<tr><td><a href='#' onclick='selectsong(" + g[i][0] + ");return false'>" + g[i][1];
        txt += "</a></td></tr>";
    }
    txt += "</table>";
    id("allsongs").innerHTML = txt;
}

doajax("allsongs", loadsongs);

function selectsong(id) {
    doajax("singlesong&songid=" + id, selectsong2);
}
function selectsong2(x) {
    x = x.split(smalldivider);
    var txt = "<h1>" + x[1] + "</h1><br />";
    txt += "<input type='hidden' id='songname' value='" + x[1] + "'>";
    txt += "<table style='width:420px'>";
    txt += "<tr><td><div style='width:100px'>Arranged by:</div></td><td><div style='width:320px'>" + x[2] + "</div></td></tr>";
    txt += "<tr><td><div style='width:100px'>Score:</div></td><td><div style='width:320px'><a target='_new' href='" + x[4] + "'>" + x[4] + "</a></div></td></tr>";
    txt += "<tr><td><div style='width:100px'>Original:</div></td><td><div style='width:370px'><a target='_new' href='" + x[3] + "'>" + x[3] + "</a></div></td></tr>";
    txt += "<tr><td><div style='width:100px'>MIDI:</div></td><td><div style='width:300px'><a target='_new' href='" + x[5] + "'>" + x[5] + "</a></div></td></tr>";
    
    txt += "<td colspan=2><center><input type='button' value='Edit Info' id='updatesongbutton' onclick='window.location=\"?action=editsong&songid="+x[0]+"\"'></center></td></tr>"
    txt += "</table>";
    
    txt += "<br />&nbsp;<br />";
    txt += "<h1>Parts</h1><br /><div id='songparts'></div>";
    
    id("singlesong").innerHTML = txt;
    
    doajax("partsforsong&songid=" + x[0], showsongparts);
}

function showsongparts(x) {
   x=x.split(bigdivider);
   var txt = "<table border=1>";
   var currentSong = "";
   for (var i = 0; i < x.length - 1; i++) {
      var thisSong = x[i].split(smalldivider)[0];
      if (thisSong != currentSong) {
         if (currentSong != "")
            txt += "</div></td></tr>";
         txt += "<tr><td>" + thisSong + "</td><td>";
	 txt += "<div style='width:250px'>";
         currentSong = thisSong;
      }
      else {
	 txt += ", ";
      }
      txt += "<span style='color:" + skilltocolor[x[i].split(smalldivider)[2]-1] + "'>";
      txt += x[i].split(smalldivider)[1];
      txt += "</span>";
   }
   if (currentSong != "")
      txt += "</div></td></tr>";
   txt += "</table>";
   id("songparts").innerHTML = txt;
}

</script>


</td><td class='half'>
<div id="singlesong"></div>

</td></tr>