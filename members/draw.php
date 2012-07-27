<tr><td colspan='2'>
<center>
<div id="txt"></div>
<canvas id="jesseCanvas" width="800" height="500" style="border:0px solid #c3c3c3;"></canvas>
</center>

<script type="text/javascript">
var c=document.getElementById("jesseCanvas");
var ctx=c.getContext("2d");
ctx.fillStyle="#FF0000";
//ctx.fillRect(0,0,150,75);

var cwidth = id("jesseCanvas").width;
var cheight = id("jesseCanvas").height;
var cleft = id("jesseCanvas").offsetLeft;
var ctop = id("jesseCanvas").offsetTop;
var offpar = id("jesseCanvas").offsetParent;
while (offpar) {
  cleft += offpar.offsetLeft;
  ctop += offpar.offsetTop;
  offpar = offpar.offsetParent;
}

var mine = new Array();
var ismousedown = false;
window.onmousemove = moveevt;
window.onmousedown = mousedown;
window.onmouseup = mouseup;

function mousedown(evt) {if(evt.button==0){ismousedown=true;moveevt(evt);}};
function mouseup(evt) {if(evt.button==0)ismousedown=false;};

function drawcircle(color, x, y) {
  ctx.fillStyle = "#" + color;
  ctx.beginPath();
  ctx.arc(cwidth*x, cheight*y, 10, 0, Math.PI*2, true);
  ctx.closePath();
  ctx.fill();
}

function moveevt(evt) {
  if (!ismousedown)
    return;

  var mycleft = cleft - document.body.scrollLeft;
  var myctop = ctop - document.body.scrollTop;
  var ptrX = evt.clientX - 2.5;
  var ptrY = evt.clientY - 8;
  var x = (ptrX - mycleft)/cwidth;
  var y = (ptrY - myctop)/cheight;

  /* Add x, y to database */
  sendrequest("add&color=FF0000&x="+x+"&y="+y);
  //drawcircle("FF0000", x, y);

  /*
  var my = new Object();
  my.x = x;
  my.y = y;
  mine.push(my);*/
}

/*
function display() {
  ctx.fillStyle = '#FFFFFF';
  ctx.fillRect(0, 0, ctx.canvas.width, ctx.canvas.height);
  ctx.fillStyle="#FF0000";
  for (var i = 0; i < mine.length; i++) {
    ctx.beginPath();
    ctx.arc(cwidth*mine[i].x, cheight*mine[i].y, 10, 0, Math.PI*2, true);
    ctx.closePath();
    ctx.fill();
  }
  }*/

function sendrequest(myrequest) {
var xmlhttp;
if (window.XMLHttpRequest) {
xmlhttp=new XMLHttpRequest();
}
else {
xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
xmlhttp.onreadystatechange=function() {
if (xmlhttp.readyState==4 && xmlhttp.status==200) {
update(xmlhttp.responseText);
}
};
xmlhttp.open("GET","drawajax.php?action="+myrequest,true);
xmlhttp.send();
}

function update(x) {
  //  id("txt").innerHTML = x;
  if (x == "")
    return;

  x = x.split("%");
  var i = 0;

  var firstresult = x[i++];
  if (x[i] == "deleted") {
    i++;
    ctx.fillStyle = '#FFFFFF';
    ctx.fillRect(0, 0, ctx.canvas.width, ctx.canvas.height);
  }

  for (; i < x.length - 1; i++) {
    var y = x[i].split("^");
    
    drawcircle(y[0], y[1], y[2]);
  }

  var lastupdate = sqltimestamp();
  sendrequest("get&first=" + firstresult + "&lastupdate=" + lastupdate);
}

sendrequest("get&first=&lastupdate=");
//setInterval("display()",1000);

</script>

</td></tr> 