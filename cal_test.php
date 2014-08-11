<?php
   set_include_path(get_include_path() . PATH_SEPARATOR . './google-api-php-client/src');
   require_once 'Google/Client.php';
   require_once 'Google/Service/Calendar.php';
   //
   $client = new Google_Client();
   $client->setApplicationName("API Project");
   $client->setClientId("1072266037494-6oj77gc3stot6ejofvdmu51a5kdvs557.apps.googleusercontent.com");
   $client->setAssertionCredentials(
       new Google_Auth_AssertionCredentials(
           '1072266037494-6oj77gc3stot6ejofvdmu51a5kdvs557@developer.gserviceaccount.com',
           array(
               'https://www.googleapis.com/auth/calendar'
           ),
           file_get_contents("certificates/8a8cc4971b40.p12")
       )
   );
  
   //
   $service = new Google_Service_Calendar($client);
   //
   $event = new Google_Service_Calendar_Event();
   $event->setSummary('TestGig');
   $event->setLocation('The White House');
   $start = new Google_Service_Calendar_EventDateTime();
   $start->setDateTime('2014-08-11T11:00:00');
   $start->setTimeZone('America/Los_Angeles');
   $event->setStart($start);
   $end = new Google_Service_Calendar_EventDateTime();
   $end->setDateTime('2014-08-11T12:00:00');
   $end->setTimeZone('America/Los_Angeles');
   $event->setEnd($end);
   /*
   $attendee = new Google_service_Calendar_EventAttendee();
   $attendee->setEmail('stanfordcalypso@gmail.com');
   $attendees = array($attendee);
   $event->setAttendees($attendees);
   */
   //
   $calendar_id = 'tuleai9qf617ins2h47jfeiqac@group.calendar.google.com';
   //
   $new_event = null;

   //
   try {
       $new_event = $service->events->insert($calendar_id, $event);
       //
       $new_event_id= $new_event->getId();
   } catch (Google_Service_Exception $e) {
       echo "ERROR \n";
       syslog(LOG_ERR, $e->getMessage());
       echo $e->getMessage();
   }
   //
   $event = $service->events->get($calendar_id, $new_event->getId());
   //
   if ($event != null) {
       echo "Inserted:";
       echo "EventID=".$event->getId();
       echo "Summary=".$event->getSummary();
       echo "Status=".$event->getStatus();
   }

   ?>