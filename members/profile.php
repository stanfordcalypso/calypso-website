
 <tr>
  <td class='half'>
  <h1>My Gigs</h1>
<p>

<table><tr><td>
<div id="responseneeded"></div>
<div id="upcominggigs"></div>
</td></tr></table>

<script type="text/javascript">
function showresponseneeded(x) {
   if (x != "") {
      x = x.split(bigdivider);
      var txt = "Response";
      if (x.length > 2) txt += "s";
      txt += " needed!<br />&nbsp;<br />";
      for (var i = 0; i < x.length - 1; i++) {
         txt += "<center><div style='background: #E0FAFA; border: 2px #772222 ridge;padding:3px;width:200px;font-size:90%'>";
         txt += "" + x[i].split(smalldivider)[0] + "<br />";
         if (x[i].split(smalldivider)[1] != "")
	   txt += printdate(x[i].split(smalldivider)[1]) + "<br />";
         if (x[i].split(smalldivider)[2] != "")
	   txt += "at " + printtime(x[i].split(smalldivider)[2]) + "<br />&nbsp;";
         txt += "<br />";
         txt += "<input type='button' onclick='respondtogig(" + x[i].split(smalldivider)[3] + ")' value='Respond'>";
         txt += "</div></center><br />";
      }

      id("responseneeded").innerHTML = txt;
   }
}

function respondtogig(x) {
   window.location = "?action=respond&gigid=" + x;
}

function showupcominggigs(x) {
    if (x == "")
        return;

    x = splitresponse(x);
    var txt = "<div style='padding-bottom:5px'>Upcoming gigs:</div>";
    for (var i = 0; i < x.length - 1; i++) {
        txt += "<div style='position:relative;left:20px;width:350px;font-size:90%;border:1px solid #ccc;padding: 3px'>";
        txt += x[i][0];
        if (x[i][1] != "")
	  txt += " on " + printdate(x[i][1]);
	if (x[i][2] != "")
	  txt += " at " + printtime(x[i][2]);
	if (x[i][4] == 1) {/*loading*/
	  if (x[i][8] != "")
	    txt += "<br />Loading is at " + printtime(x[i][8]);
	}
        
        txt += "<br />"
        
        if (x[i][4] == 0 && x[i][5] == 0 && x[i][6] == 0 && x[i][7] == 0)
            txt += "You are not attending.";
        else {
            var count = 0;
            for (var j = 4; j <= 7; j++) {
                if (x[i][j] == 1)
                    count++;
            }
            var origcount = count;
            
            txt += "You are";
            if (x[i][7] == 1) {
                txt += " driving";
                count--;
                if (count > 1)
                    txt += ",";
                else if (count == 1) {
                    if (origcount > 2)
                        txt += ", and";
                    else
                        txt += " and";
                }
            }
            
            if (x[i][4] == 1) {
                txt += " loading";
                count--;
                if (count > 1)
                    txt += ",";
                else if (count == 1) {
                    if (origcount > 2)
                        txt += ", and";
                    else
                        txt += " and";
                }
            }
                
            if (x[i][5] == 1) {
                txt += " playing";
                count--;
                if (count > 1)
                    txt += ",";
                else if (count == 1) {
                    if (origcount > 2)
                        txt += ", and";
                    else
                        txt += " and";
                }
            }
                
            if (x[i][6] == 1)
                txt += " cleaning up";
                
            txt += ".<br />";
            
            if (x[i][9] != "")
            	txt += "Comment: " + x[i][9];
        }
        
        txt += " (<a href='?action=respond&gigid=" + x[i][3] + "'>edit</a>)";
        
        //txt += "<br />"
        //txt += "<input type='button' value='Edit Response' onclick='respondtogig(" + x[i][3] + ")'>";
        txt += "</div>&nbsp;<br />";
    }
    id("upcominggigs").innerHTML = txt;
}

doajax("responseneeded&id=<?php echo $SUNETID ?>", showresponseneeded);
doajax("upcominggigs&id=<?php echo $SUNETID ?>", showupcominggigs);
</script>

</p>
  </td>
  <td class='half'>
    <h1>My Songs</h1>
<p>
<table><tr><td>
I learned a new part!
<br />
&nbsp;<br />
<table>
<form id="newpartform"><tr>
<td>
<table><tr><td>Song:</td></tr><tr><td><div id="songdropdowndiv"></div></td></tr></table>
</td>
<td>

<table>
<tr><td colspan='2'>Instrument:</td></tr>
<tr><td colspan='2'><div id="instrumentdropdowndiv"></div></td></tr>
<tr><td colspan='2'>How well:</td></tr>
<tr><td colspan='2'><div id="skilldropdowndiv"></div></td></tr>
<tr><td colspan='2'>&nbsp;</td></tr>
<tr><td><input type="button" value="Add!" onclick="addnewpart()"></td>
<td><span id="addpartprocess" style="font-size:80%"></span></td>
</tr>
</table>

</td>
</tr>
</form></table>

</td></tr><tr><td>
<br />
&nbsp;<br />
Parts I know:<br >
Sort by: <select id="partsort" onchange="refreshpartsiknow()">
<option value="0" selected>Song</option>
<option value="1">Instrument</option>
</select>
<br />
<div id="partsiknow"></div>

</td></tr></table>

<script type="text/javascript"><!--
id("partsort").value = 0;

function addnewpart() {
   var songid = id("newsongselect").value;
   var instrumentid = id("newinstrumentselect").value;
   var skillid = id("newskillselect").value;
   var comm = "addnewpart&id=<?php echo $SUNETID ?>&song=" + songid + "&instrument=" + instrumentid + "&skill=" + skillid;
   doajax(comm, refreshpartsiknow);
   id("addpartprocess").innerHTML = "Processing...";
}

function refreshpartsiknow(x) {
   if (id("partsort").value == 0)
      doajax("partsbysong&id=<?php echo $SUNETID ?>", getpartsiknow);
   else
      doajax("partsbyinst&id=<?php echo $SUNETID ?>", getpartsiknow);
}

function getpartsiknow(x) {
  id("addpartprocess").innerHTML = "";
   x=x.split(bigdivider);
   var txt = "<table border=1>";
   var currentSong = "";
   for (var i = 0; i < x.length - 1; i++) {
      var thisSong = x[i].split(smalldivider)[0];
      if (thisSong != currentSong) {
         if (currentSong != "")
            txt += "</div></td></tr>";
         txt += "<tr><td><span style='font-size:90%'>" + thisSong + "</span></td><td>";
	 txt += "<div style='width:250px'>";
         currentSong = thisSong;
      }
      else {
	 txt += ", ";
      }
      txt += "<span style='color:" + skilltocolor[x[i].split(smalldivider)[2]-1] + ";font-size:90%'>";
      txt += x[i].split(smalldivider)[1];
      txt += "</span> ";
      txt += "<span style='font-size:80%'>(<a href='#' onclick='deletepart(" + x[i].split(smalldivider)[3] + "," + x[i].split(smalldivider)[4] + ");return false'>x</a>)</span>";
   }
   if (currentSong != "")
      txt += "</div></td></tr>";
   txt += "</table>";
   id("partsiknow").innerHTML = txt;
}

function deletepart(songid, instid) {
  doajax("deletepart&id=<?php echo $SUNETID; ?>&songid="+songid+"&instrumentid="+instid, refreshpartsiknow);
}

function songsdropdown(x) {
x=x.split(bigdivider);
var txt="<select size='10' id='newsongselect' style='width:180px'>"
for (var i = 0; i < x.length - 1; i++) {
   txt+="<option value='" + x[i].split(smalldivider)[0] + "'";
   if (i == 0)
      txt+=" selected";
   txt+=">"+x[i].split(smalldivider)[1]+"</option>";
}
txt+="</select>"
id("songdropdowndiv").innerHTML = txt;
}

function instrumentsdropdown(x) {
x=x.split(bigdivider);
var txt="<select size='1' id='newinstrumentselect' style='width:140px'>"
for (var i = 0; i < x.length - 1; i++)
    txt+="<option value='" + x[i].split(smalldivider)[0] + "'>"+x[i].split(smalldivider)[1]+"</option>";
txt+="</select>"
id("instrumentdropdowndiv").innerHTML = txt;
}

function skillsdropdown(x) {
x=x.split(bigdivider);
var txt="<select size='3' id='newskillselect' style='width:200px'>"
for (var i = 0; i < x.length - 1; i++) {
    txt+="<option value='" + x[i].split(smalldivider)[0] + "' style='color:";
    txt+=skilltocolor[x[i].split(smalldivider)[0] - 1]; 
    txt+="'>"+x[i].split(smalldivider)[1]+"</option>";
}
txt+="</select>"
id("skilldropdowndiv").innerHTML = txt;
}

doajax("songs", songsdropdown);
doajax("instruments", instrumentsdropdown);
doajax("skills", skillsdropdown);
refreshpartsiknow();

function resetajax() {
   doajax("songs", songsdropdown);
   doajax("instruments", instrumentsdropdown);
   doajax("skills", skillsdropdown);
   refreshpartsiknow();
}
//--></script>

</p>
  </td></tr>