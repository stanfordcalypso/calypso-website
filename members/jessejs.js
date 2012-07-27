function id(ids) {return document.getElementById(ids);};

function doajax(myrequest, myaction) {
var xmlhttp;
if (window.XMLHttpRequest) {
xmlhttp=new XMLHttpRequest();
}
else {
xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
xmlhttp.onreadystatechange=function() {
if (xmlhttp.readyState==4 && xmlhttp.status==200) {
myaction(xmlhttp.responseText);
}
};
xmlhttp.open("GET","ajax.php?action="+myrequest,true);
xmlhttp.send();
}

function dopostajax(myrequest, myaction) {
var xmlhttp;
if (window.XMLHttpRequest) {
xmlhttp=new XMLHttpRequest();
}
else {
xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
xmlhttp.onreadystatechange=function() {
if (xmlhttp.readyState==4 && xmlhttp.status==200) {
myaction(xmlhttp.responseText);
}
};
var params = "action="+myrequest;
xmlhttp.open("POST","ajax.php",true);
xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xmlhttp.setRequestHeader("Content-length", params.length);
xmlhttp.setRequestHeader("Connection", "close");
xmlhttp.send(params);
}

var skilltocolor = new Array("#008800", "#888800", "#880000");
var smalldivider = "^";
var bigdivider = "%";

function splitresponse(resp) {
    var x = resp.split(bigdivider);
    for (var i = 0; i < x.length - 1; i++) {
        x[i] = x[i].split(smalldivider);
    }
    return x;
}

var monthnames = new Array("January","February","March","April","May","June","July","August","September","October","November","December");
var daynames = new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");

function dateselect(ids) {
    var d = new Date();
    var txt = "<select id='" + ids + "-month'>";
    for (var i = 1; i <= 12; i++) {
	txt += "<option value='"+ i +"'";
	if (d.getMonth() + 1 == i)
	    txt += " selected='selected'";
	txt += ">" + monthnames[i - 1];
	txt += "</option>";
    }
    txt += "</select>";
    
    txt += "<select id='" + ids + "-day'>";
    for (var i = 1; i <= 31; i++) {
	txt += "<option value='"+ i +"'";
	if (d.getDate() == i)
	    txt += " selected='selected'";
	txt += ">" + i;
	txt += "</option>";
    }
    txt += "</select>";

    txt += "<select id='" + ids + "-year'>";
    for (var i = d.getFullYear(); i <= d.getFullYear() + 1; i++) {
	txt += "<option value='"+ i +"'";
	if (d.getFullYear() == i)
	    txt += " selected='selected'";
	txt += ">" + i;
	txt += "</option>";
    }
    txt += "</select>";

    return txt;
}

function dateselectedit(ids, datestr) {
    if (datestr == "")
	return dateselect(ids);

    var x = datestr.split("-");
    var month = x[1];
    var day = x[2];
    var year = x[0];
    if (month.charAt(0) == "0")
	month = month.charAt(1);
    if (day.charAt(0) == "0")
	day = day.charAt(1);
    day = parseInt(day);
    month = parseInt(month);
    year = parseInt(year);
    var d = new Date(year, month-1, day, 0, 0, 0, 0);
    var d2 = new Date();

    var txt = "<select id='" + ids + "-month'>";
    for (var i = 1; i <= 12; i++) {
	txt += "<option value='"+ i +"'";
	if (d.getMonth() + 1 == i)
	    txt += " selected='selected'";
	txt += ">" + monthnames[i - 1];
	txt += "</option>";
    }
    txt += "</select>";
    
    txt += "<select id='" + ids + "-day'>";
    for (var i = 1; i <= 31; i++) {
	txt += "<option value='"+ i +"'";
	if (d.getDate() == i)
	    txt += " selected='selected'";
	txt += ">" + i;
	txt += "</option>";
    }
    txt += "</select>";

    txt += "<select id='" + ids + "-year'>";
    for (var i = d2.getFullYear(); i <= d2.getFullYear() + 1; i++) {
	txt += "<option value='"+ i +"'";
	if (d.getFullYear() == i)
	    txt += " selected='selected'";
	txt += ">" + i;
	txt += "</option>";
    }
    txt += "</select>";

    return txt;
}

function getdate(ids) {
    var month = id(ids + "-month").value;
    var day = id(ids + "-day").value;
    var year = id(ids + "-year").value;
    if (month.length == 1)
	month = "0" + month;
    if (day.length == 1)
	day = "0" + day;
    return year + "-" + month + "-" + day;
}

function printdate(x) {
    if (x == "") return "";
    x = x.split("-");
    var month = x[1];
    var day = x[2];
    var year = x[0];
    if (month.charAt(0) == "0")
	month = month.charAt(1);
    if (day.charAt(0) == "0")
	day = day.charAt(1);
    day = parseInt(day);
    month = parseInt(month);
    year = parseInt(year);

    var d = new Date(year,month-1,day,0,0,0,0);
    var dayname = daynames[d.getDay()];
    return dayname + ", " + monthnames[month-1] + " " + day;
}







function timeselect(ids) {
    var txt = "<select id='" + ids + "-hour'>";
    for (var i = 1; i <= 12; i++) {
	txt += "<option value='"+ i +"'";
	if (i == 1)
	    txt += " selected='selected'";
	txt += ">" + i;
	txt += "</option>";
    }
    txt += "</select>";
    
    txt += "<select id='" + ids + "-minute'>";
    for (var i = 0; i <= 59; i += 5) {
	var k = "";
	if (i < 10)
	    k += "0";
	k += i.toString();
	txt += "<option value='"+ k +"'";
	if (i == 0)
	    txt += " selected='selected'";
	txt += ">:" + k;
	txt += "</option>";
    }
    txt += "</select>";

    txt += "<select id='" + ids + "-period'>";
    txt += "<option value='AM'>AM</option>";
    txt += "<option value='PM' selected='selected'>PM</option>";
    txt += "</select>";

    return txt;
}

function timeselectedit(ids, timestr) {
    if (timestr == "")
	return timeselect(ids);

    x = timestr.split(":");
    var hour = x[0];
    var minute = x[1];
    if (hour.charAt(0) == "0")
	hour = hour.charAt(1);
    var period = "AM";
    var ihour = parseInt(hour);
    if (ihour == 12)
	period = "PM";
    else if (ihour == 0 || ihour == 24) {
	ihour = 12;
    }
    else if (ihour > 12) {
	ihour -= 12;
	period = "PM";
    }
    if (minute.charAt(0) == "0")
	minute = minute.charAt(1);
    var iminute = parseInt(minute);

    var txt = "<select id='" + ids + "-hour'>";
    for (var i = 1; i <= 12; i++) {
	txt += "<option value='"+ i +"'";
	if (i == ihour)
	    txt += " selected='selected'";
	txt += ">" + i;
	txt += "</option>";
    }
    txt += "</select>";
 
    txt += "<select id='" + ids + "-minute'>";
    for (var i = 0; i <= 59; i += 5) {
	var k = "";
	if (i < 10)
	    k += "0";
	k += i.toString();
	txt += "<option value='"+ k +"'";
	if (i == iminute)
	    txt += " selected='selected'";
	txt += ">:" + k;
	txt += "</option>";
    }
    txt += "</select>";

    txt += "<select id='" + ids + "-period'>";
    if (period == "AM") {
	txt += "<option value='AM' selected='selected'>AM</option>";
	txt += "<option value='PM'>PM</option>";
    }
    else {
	txt += "<option value='AM'>AM</option>";
	txt += "<option value='PM' selected='selected'>PM</option>";
    }
    txt += "</select>";

    return txt;
}

function gettime(ids) {
    var hour = id(ids + "-hour").value;
    var minute = id(ids + "-minute").value;
    var period = id(ids + "-period").value;
    var ihour = parseInt(hour);
    if (period == "PM" && ihour != 12)
	ihour += 12;
    else if (period == "AM" && ihour == 12)
	ihour = 0;
    hour = ihour.toString();
    if (hour.length == 1)
	hour = "0" + hour;
    return hour + ":" + minute;
}

function printtime(x) {
    if (x == "") return "";
    x = x.split(":");
    var hour = x[0];
    var minute = x[1];
    if (hour.charAt(0) == "0")
	hour = hour.charAt(1);
    var period = "AM";
    var ihour = parseInt(hour);
    if (ihour == 12)
	period = "PM";
    else if (ihour == 0 || ihour == 24) {
	ihour = 12;
    }
    else if (ihour > 12) {
	ihour -= 12;
	period = "PM";
    }
    hour = ihour.toString();

    return hour + ":" + minute + " " + period;
}

function sqltimestamp() {
    var d = new Date();
    var hour = d.getHours().toString();
    var minute = d.getMinutes().toString();
    var second = d.getSeconds().toString();
    var month = d.getMonth().toString();
    var date = d.getDate().toString();
    var year = d.getFullYear().toString();
    if (hour.length == 1)
	hour = "0" + hour;
    if (minute.length == 1)
	minute = "0" + minute;
    if (second.length == 1)
	second = "0" + second;
    if (month.length == 1)
	month = "0" + month;
    if (date.length == 1)
	date = "0" + date;
    return year+"-"+month+"-"+date+" "+hour+":"+minute+":"+second;
}