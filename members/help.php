<tr><td colspan='2'>
<h1>Help</h1><br />
   <p><b>If gig reminders are not on time:</b><br />
   Install the Firefox and Chrome extensions.
   <ul>
   <li>For Firefox: <a href="calypso.xpi">calypso.xpi</a></li>
   <li>For Chrome: <a href="calypso.crx">calypso.crx</a></li>
   </ul></p>

   <p><b>To add new members (Mac):</b>
   <ul>
   <li>Open Applications -> Utilities -> Terminal</li>
   <li>Type "ssh -X yoursunetid@myth.stanford.edu" (I type "ssh -X jruder@myth.stanford.edu"). Press enter.</li>
   <li>Type your password. Press enter.</li>
   <li>Type "cd /afs/ir.stanford.edu/group/calypso". Press enter.</li>
   <li>Type "cd cgi-bin". Press enter.</li>
   <li>Type "cd members". Press enter.</li>
   <li>If you get an error at any of these steps, email <a href='mailto:jruder@stanford.edu'>Jesse</a> and say exactly which command failed.</li>
   <li>Type "emacs .htaccess &". Press enter.</li>
   <li>Navigate the cursor to the space immediately after the last id currently listed. For each new member, add one space followed by the member's id. Do not press enter or make a new line.</li>
   <li>If there is a file drop down menu, click on File->Save and then File->Exit Emacs. Otherwise, hold the control key and press "x" followed by "s" while still holding control. Now hold control and type "x" then "c".</li>
   <li>Quit Terminal.</li>
   <li>The new members should be able to access the site! Let <a href='mailto:jruder@stanford.edu'>Jesse</a> know if something isn't working.</li>
   <li><i>If you are a Mac user, you can do "ssh -X -Y" instead of just "ssh -X" to use gedit instead of emacs - just type "gedit .htaccess &" instead of "emacs .htaccess" (you DO want to use the ampersand in this case). Gedit can be a little more intuitive if you've never used keyboard-based text editors before.</i></li>
   </ul></p>
   <p><b>To add new members (Windows):</b>
   <ul>
   <li>Download a program called "PuTTY".</li>
   <li>Install it.</li>
   <li>Open it and select the "ssh" option under "Connection type:" and type "myth.stanford.edu" into the text field that says "Host Name (or IP address)".</li>
   <li>Press "Open".</li>
   <li>Type in your username (i.e. jruder). Press enter.</li>
   <li>Follow the steps for Mac starting with "Type your password". <i>Note: do NOT include the ampersand after "emacs .htaccess" if you are using PuTTY.</i></li>
   </ul></p>
</td></tr>
