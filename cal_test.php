<?php
   require_once "../google-api-php-client/src/Google_Client.php";
   require_once "../google-api-php-client/src/contrib/Google_CalendarService.php";
   //
   $client = new Google_Client();
   $client->setUseObjects(true);
   $client->setApplicationName("calypsogigs");
   $client->setClientId("1072266037494-6oj77gc3stot6ejofvdmu51a5kdvs557.apps.googleusercontent.com");
   $client->setAssertionCredentials(
       new Google_AssertionCredentials(
           "1072266037494-6oj77gc3stot6ejofvdmu51a5kdvs557@developer.gserviceaccount.com",
           array(
               "https://www.googleapis.com/auth/calendar"
           ),
           file_get_contents("8a8cc4971b40.p12")
       )
   );
   //
   $service = new Google_CalendarService($client);
   //
   $event = new Google_Event();
   $event->setSummary('Event 1');
   $event->setLocation('Somewhere');
   $start = new Google_EventDateTime();
   $start->setDateTime('2013-10-22T19:00:00.000+01:00');
   $start->setTimeZone('Europe/London');
   $event->setStart($start);
   $end = new Google_EventDateTime();
   $end->setDateTime('2013-10-22T19:25:00.000+01:00');
   $end->setTimeZone('Europe/London');
   $event->setEnd($end);
   //
   $calendar_id = "sd7h90sdja97sdg9ahd0sa8bd@group.calendar.google.com";
   //
   $new_event = null;
   //
   try {
       $new_event = $service->events->insert($calendar_id, $event);
       //
       $new_event_id= $new_event->getId();
   } catch (Google_ServiceException $e) {
       syslog(LOG_ERR, $e->getMessage());
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