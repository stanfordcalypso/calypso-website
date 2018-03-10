<html>
<body>
<style>
.date select {background:#fff;border:solid #999 1px;margin-left:-5px}
td {line-height:1.4em; color:#000; font-size:12pt};
</style>
<table><tr><td>

<p>This is only a request. We will contact you if we are able to play at your event.</p>

<div style="width:480px">
   <form action="gigformsubmit.php" method="post" onsubmit="parent.scroll(0,0)">
    <table>
      <tr>
        <td>
          <strong>Name *</strong><br/>
          <input name="name" size=24>
        </td>
        <td>
          <strong>Email *</strong><br />
          <input name="email" size=25>
        </td>
        <td>
          <strong>Phone *</strong><br />
          <input name="phone" size=26>
        </td>
      </tr>
      <tr>
        <td>
          <strong>Event Date *</strong><br />
          <div class="date">
            <table width = 155px>
              <tr>
                <td>
                  <select name="month">
                    <option value="Jan">Jan
                    <option value="Feb">Feb
                    <option value="Mar">Mar
                    <option value="Apr">Apr
                    <option value="May">May
                    <option value="Jun">Jun
                    <option value="Jul">Jul
                    <option value="Aug">Aug
                    <option value="Sep">Sep
                    <option value="Oct">Oct
                    <option value="Nov">Nov
                    <option value="Dec">Dec
                  </select>
                </td>
                <td>
                  <select name="day">
                    <option value="1">1
                    <option value="2">2
                    <option value="3">3
                    <option value="4">4
                    <option value="5">5
                    <option value="6">6
                    <option value="7">7
                    <option value="8">8
                    <option value="9">9
                    <option value="10">10
                    <option value="11">11
                    <option value="12">12
                    <option value="13">13
                    <option value="14">14
                    <option value="15">15
                    <option value="16">16
                    <option value="17">17
                    <option value="18">18
                    <option value="19">19
                    <option value="20">20
                    <option value="21">21
                    <option value="22">22
                    <option value="23">23
                    <option value="24">24
                    <option value="25">25
                    <option value="26">26
                    <option value="27">27
                    <option value="28">28
                    <option value="29">29
                    <option value="30">30
                    <option value="31">31
                  </select>
                </td>
                <td>
                  <div id="yearselect">
                  </div>
                  <script>

                  var d = new Date();
                  var year = d.getFullYear();
                  var txt = "<select name='year'>";
                  for (var i = 0; i < 3; i++) {
                    txt += "<option value=\"" + year + "\">"+year;
                    year++;
                  }
                  txt += "</select>";
                  document.getElementById("yearselect").innerHTML = txt;

                  </script>
                </td>
              </tr>
            </table>
          </div>
        </td>
        <td>
          <strong>Time of performance *</strong><br />
          <input name="time" size=25>
        </td>
        <td>
          <strong>Performance duration *</strong><br />
          <input name="duration" size=26>
        </td>
      </tr>
      <tr>
        <td colspan=3>
          <strong>Event description *</strong> <br />
          <input name="description" size="80">
        </td>
      </tr>
      <tr>
        <td colspan=3>
          <strong>Location</strong> (if entering an address, please specify the city)<br />
          <input name="location" size="80">
        </td>
      </tr>
    </table>

    <table>
      <tr>
        <td>
          <strong>Comments</strong><br />
          <textarea name="comments" style="width:490px;height:60px"></textarea>
        </td>
      </tr>
      <tr>
        <td>
          <div style="border:solid #999 1px;border-radius:15px;position:relative;top:20px;height:20px;background:#eee;padding-left:20px;padding-top:3px"><strong>Human verification *</strong></div>
          <div style="border:solid #999 1px;border-radius:15px;padding:10px;padding-top:30px">

            <?php

            include "human.php";

            echo get_question();

            ?>
          </div>
        </td>
      </tr>
    </table>

    <input type="submit" value="Submit" style="background:#eee;border:solid #999 1px;margin-top:20px;border-radius:5px;font-size:1.1em">
    </form>
</div>
<div style="padding-top:10px">
  <i>* - Required</i>
</div>

</td></tr></table>

</body>
</html>
