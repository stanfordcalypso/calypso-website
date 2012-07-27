<tr><td colspan='2'>
<h1>Help</h1><br />
   <b>If gig reminders are not on time:</b><br />
   Install the Firefox and Chrome extensions.<br />
   - For Firefox: <a href="calypso.xpi">calypso.xpi</a><br/>
   - For Chrome: <a href="calypso.crx">calypso.crx</a><br />

&nbsp;<br />

   <b>To add new members (Mac):</b><br />
   On a Mac,<br />
   - Open Applications -> Utilities -> Terminal<br />
   - Type "ssh -X yoursunetid@myth.stanford.edu" (I type "ssh -X jruder@myth.stanford.edu"). Press enter.<br />
   - Type your password. Press enter.<br />
   - Type "cd /afs/ir.stanford.edu/group/calypso". Press enter.<br />
   - Type "cd cgi-bin". Press enter.<br />
   - Type "cd members". Press enter.<br />
   - If you get an error at any of these steps, email <a href='mailto:jruder@stanford.edu'>me</a> and tell say exactly which command failed.<br />
   - Type "emacs .htaccess &". Press enter.<br />
   - Navigate the cursor to the space immediately after the last id currently listed. For each new member, add one space followed by the member's id. Do not press enter or make a new line.<br /> 
   - If there is a file drop down menu, click on File->Save and then File->Exit Emacs. Otherwise, hold the control key and press "x" followed by "s" while still holding control. Now hold control and type "x" then "c".<br />
   - Quit Terminal.<br />
   - The new members should be able to access the site! Let <a href='mailto:jruder@stanford.edu'>Jesse</a> know if something isn't working.<br />
   <i>- If you are a Mac user, you can do "ssh -X -Y" instead of just "ssh -X" to use gedit instead of emacs - just type "gedit .htaccess &" instead of "emacs .htaccess" (you DO want to use the ampersand in this case). Gedit can be a little more intuitive if you've never used keyboard-based text editors before.</i><br />
   <br /><b>To add new members (Windows):</b><br />
   - Download a program called "PuTTY".<br />
   - Install it.<br />
   - Open it and select the "ssh" option under "Connection type:" and type "myth.stanford.edu" into the text field that says "Host Name (or IP address)".<br />
   - Press "Open".<br />
   - Type in your username (i.e. jruder). Press enter.<br />
   - Follow the steps for Mac starting with "Type your password". <i>Note: do NOT include the ampersand after "emacs .htaccess" if you are using PuTTY.</i><br />
</td></tr>
