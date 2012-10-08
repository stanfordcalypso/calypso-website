<html>
<head>
  <title>Cardinal Calypso</title>
  <link rel='stylesheet' type='text/css' href='css.css' />
</head>
<body><div align='center'><table id='wrapper' cellspacing='0' cellpadding='0'>
  <tr>
    <td id='left'>
      <div id='logo'></div>
      <table id='nav' cellspacing='0' cellpadding='0'><tr><?php
   $actions = array('home' => 'Home', 'group' => 'The Group', 'pans' => 'The Pans', 'booking' => 'Booking', 'media' => 'Media', 'premieres' => 'Premieres', 'requestgig' => 'Request Gig', 'requestgig2' => 'Request Gig');
	$p = (isset($_GET['p']) && in_array($_GET['p'], array_keys($actions))) ? $_GET['p'] : 'home';
	foreach ($actions as $key => $label) {
	  if ($key == "requestgig" || $key == "requestgig2") {
	  continue;
	  }

	  echo "<td";
          if ($p == $key) echo " id='here'";
	  echo "><a href='?p={$key}'>{$label}</a></td>";
	}
      ?></tr></table>
    </td>
    <td id='right' rowspan='2'><div class='green'>
      <h1>Calendar</h1>
      <div id='calendar'>
        <iframe id='calembed' src="http://www.google.com/calendar/embed?showTitle=0&amp;showNav=0&amp;showTabs=0&amp;showCalendars=0&amp;mode=AGENDA&amp;wkst=1&amp;bgcolor=%23eeeeff&amp;src=tuleai9qf617ins2h47jfeiqac%40group.calendar.google.com" frameborder="0" scrolling="no"></iframe>
      </div>
    </div></td>
  </tr><tr>
    <td id='content'><?
      include_once "content/{$p}.php";
    ?></td>
  </tr>
  <tr id='foot'><td colspan='2'>Copyright &copy;2006-<?=date('Y')?> Cardinal Calypso &middot; <a href='http://assu.stanford.edu' target='_blank'>ASSU</a> &middot; <a href='http://www.facebook.com/pages/Cardinal-Calypso/106620806083662' target='_blank'>FB</a> &middot; <a href="http://cardinalcalypso.stanford.edu/members">Members</a></td></tr>
</table></div></body>
</html>
