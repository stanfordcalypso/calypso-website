<tr><td class='third'>
<h1>Gigs</h1>
<br />
<div id="allgigs">Loading...</div>
<br />
<input type='button' value='Add Gig' style='position:relative;left:10px' onclick='window.location="?action=addgig"' />

<script type="text/javascript">
function loadgigs(x) {
    var g = splitresponse(x);
    var txt = "<table>";
    for (var i = 0; i < g.length - 1; i++) {
        txt += "<tr><td><a href='#' onclick='selectgig(" + g[i][0] + ");return false'>" + g[i][1];
	/*if (g[i][3] != "")
	  txt += " on " + printdate(g[i][3]);*/
         txt += "</a></td></tr>";
    }
    txt += "</table>";
    id("allgigs").innerHTML = txt;
}

doajax("allgigs", loadgigs);

var currentgig = 0;
function selectgig(id) {
    doajax("singlegig&gigid=" + id, selectgig2);
}
function selectgig2(x) {
    x = x.split(smalldivider);
    currentgig = x[0];
    var txt = "<h1>Info for " + x[1] + "</h1><br />";
    txt += "<table>";
    txt += "<tr><td style='width:500px'><div style='width:110px'>Date:</div></td><td>" + printdate(x[3]) + "</td></tr>";
    txt += "<tr><td style='width:500px'><div style='width:110px'>Load Time:</div></td><td>" + printtime(x[4]) + "</td></tr>";
    txt += "<tr><td style='width:500px'><div style='width:110px'>Start Time:</div></td><td>" + printtime(x[5]) + "</td></tr>";
    txt += "<tr><td style='width:500px'><div style='width:110px'>End Time:</div></td><td>" + printtime(x[6]) + "</td></tr>";
    txt += "<tr><td style='width:500px'><div style='width:110px'>Location:</div></td><td>" + x[7] + "</td></tr>";
    txt += "<tr><td style='width:500px'><div style='width:110px'>Confirmed:</div></td><td>";
    if (x[8] == 1)
      txt += "Yes";
    else
      txt += "No";
    txt += "</td></tr>";
    txt += "<tr><td style='width:500px'><div style='width:110px'>Attire:</div></td><td>";
    txt += x[10] + "</td></tr>";
    txt += "<tr><td style='width:500px'><divstyle='width:110px'>Comments:</div></td><td>";
    txt += x[2] + "</td></tr>";
    txt += "<tr><td colspan='2'>&nbsp;<br /><center><input type='button' value='Edit Gig' onclick='window.location=\"?action=editgig&gigid="+x[0]+"\"'></center></td></tr>"
    txt += "</table>";
    
    txt += "<br />&nbsp;<br />";
    txt += "<h1>People</h1><br /><div id='gigresponses'></div>";
    
    id("singlegig").innerHTML = txt;
    
    doajax("gigresponses&gigid=" + x[0], showgigresponses);
}

function showgigresponses(x) {
    x = splitresponse(x);
    var loading = new Array();
    var playing = new Array();
    var cleanup = new Array();
    var cars = new Array();
    var notcoming = new Array();
    var comments = new Array();
    
    for (var i = 0; i < x.length - 1; i++) {
        var p = new Object();
        p.id = x[i][0];
        p.name = x[i][1];
        p.comment = x[i][6];
        if (x[i][2] == 1)
            loading.push(p);
        if (x[i][3] == 1)
	  playing.push(p);
        if (x[i][4] == 1)
            cleanup.push(p);
        if (x[i][5] == 1)
            cars.push(p);
		if (x[i][2] == 0 && x[i][3] == 0 && x[i][4] == 0 && x[i][5] == 0)
	    notcoming.push(p);
	    if (x[i][6] != "") {
	    	comments.push(p);
	    }
	    
    }
    
    var txt = "<table>";
    
    txt += "<tr><td style='width:500px'><div style='width:110px'>Loading:</div></td><td>";
    for (var i = 0; i < loading.length; i++) {
        txt += loading[i].name;
        if (i < loading.length - 1)
            txt += ", ";
    }
    txt += "</td></tr>";
    
    txt += "<tr><td style='width:500px'><div style='width:110px'>Playing:</div></td><td>";
    for (var i = 0; i < playing.length; i++) {
        txt += playing[i].name;
        if (i < playing.length - 1)
            txt += ", ";
    }
    txt += "</td></tr>";
    
    txt += "<tr><td style='width:500px'><div style='width:110px'>Cleanup:</div></td><td>";
    for (var i = 0; i < cleanup.length; i++) {
        txt += cleanup[i].name;
        if (i < cleanup.length - 1)
            txt += ", ";
    }
    txt += "</td></tr>";
    
    txt += "<tr><td style='width:500px'><div style='width:110px'>Cars:</div></td><td>";
    for (var i = 0; i < cars.length; i++) {
        txt += cars[i].name;
        if (i < cars.length - 1)
            txt += ", ";
    }
    txt += "</td></tr>";

    txt += "<tr><td style='width:500px'><div style='width:110px'>Not Coming:</div></td><td>";
    for (var i = 0; i < notcoming.length; i++) {
        txt += notcoming[i].name;
        if (i < notcoming.length - 1)
            txt += ", ";
    }
    txt += "</td></tr>";
    txt += "</table><br />";
    txt += "<h1>Comments</h1><br />";
    txt += "<table>";
    for (var i = 0; i < comments.length; i++) {
    	txt += "<tr><td style='width:500px'><div style='width:110px'>" + comments[i].name + ":</div></td><td>";
    	txt+= comments[i].comment;
    	txt += "</td></tr>";
    }
    
    txt += "</table>";
    txt += "<br /><a href='?action=songstoplay&gigid=" + window.currentgig + "'>Show recommended songs</a>";

    id("gigresponses").innerHTML = txt;
}
</script>


</td><td class='two-thirds'>
<div id="singlegig"></div>

</td></tr>
