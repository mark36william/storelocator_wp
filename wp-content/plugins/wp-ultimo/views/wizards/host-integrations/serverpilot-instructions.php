<?php
/**
 * ServerPilot instructions view.
 *
 * @since 2.1.2
 */
?>
<article id="fullArticle">
    <h1 id="step-1-getting-a-serverpilot-api-key" class="intercom-align-left" data-post-processed="true">Step 1: Getting the API Key and the Client ID</h1>
    <p class="intercom-align-left">On your ServerPilot admin panel, first go to the Account Settings page. (<a href="https://serverpilot.io/docs/how-to-use-the-serverpilot-api/" rel="nofollow noopener noreferrer" target="_blank">Read the tutorial</a>). <b>Copy the API Key as we will need it in the following steps.</b></p>
    <div class="intercom-container intercom-align-left">
        <a href="https://downloads.intercomcdn.com/i/o/96426745/c962227b542a7ec870d0e9a8/Capto_Capture-2018-08-20_09-18-17_AM.png" rel="nofollow noopener noreferrer" target="_blank"><img class="wu-w-full" src="https://downloads.intercomcdn.com/i/o/96426745/c962227b542a7ec870d0e9a8/Capto_Capture-2018-08-20_09-18-17_AM.png"></a>
    </div>
    <p class="intercom-align-left"><em>Visit your Account Settings page</em></p>
    <p class="intercom-align-left">Next, on the API menu item, copy the Client ID and API Key values (if the API Key field is empty, click the New API Key button). Paste those values somewhere as we’ll need them in a later step.</p>
    <div class="intercom-container intercom-align-left">
        <a href="https://downloads.intercomcdn.com/i/o/96426749/80ca9b3cef4ce7c192986feb/Capto_Capture-2018-08-20_09-22-23_AM.png" rel="nofollow noopener noreferrer" target="_blank"><img class="wu-w-full" src="https://downloads.intercomcdn.com/i/o/96426749/80ca9b3cef4ce7c192986feb/Capto_Capture-2018-08-20_09-22-23_AM.png"></a>
    </div>
    <p class="intercom-align-left"><em>Copy the Client ID and API key values for later</em></p>
    
    <h1 id="step-2-get-the-server-id" class="intercom-align-left" data-post-processed="true">Step 2: Getting the App ID</h1>
    <p class="intercom-align-left">Next, we’ll need to get the App ID for your WordPress site. To find that ID, navigate to your app’s manage page and take a look at the URL at the top of your browser. The APP ID is the portion between the app/ and the /settings segments of the URL.</p>
    <div class="intercom-container intercom-align-left">
        <a href="https://downloads.intercomcdn.com/i/o/96426759/bc0a2766419289e62c81b92d/Capto_Capture-2018-08-20_09-24-20_AM.png" rel="nofollow noopener noreferrer" target="_blank"><img class="wu-w-full" src="https://downloads.intercomcdn.com/i/o/96426759/bc0a2766419289e62c81b92d/Capto_Capture-2018-08-20_09-24-20_AM.png"></a>
    </div>
    <p class="intercom-align-left"> <em>The APP ID can be found on the URL</em></p>
    
    <h1 id="step-3-adding-the-config" class="intercom-align-left" data-post-processed="true">Step 3: Adding the config to your wp-config.php file</h1>
    <p class="intercom-align-left"> You’ll need to edit your <strong><em>wp-config.php</em></strong> file to include the custom configuration constants WP Ultimo needs to correctly connect to the ServerPilot.io API.</p>
    <p class="intercom-align-left"> Add the following lines to your <strong><em>wp-config.php</em></strong> file, right above the <strong><em>/* That’s all, stop editing! Happy blogging. */</em></strong> line. Replace the contents with the information obtained in the prior steps.</p>
    <p class="intercom-align-left"> After replacing the values, you should have something like this:</p>
    <div class="intercom-container intercom-align-left">
        <a href="https://downloads.intercomcdn.com/i/o/96426767/0c58144272a6ae1e34f60257/455F20F7-8D94-4530-A467-ABD69730FD32.png" rel="nofollow noopener noreferrer" target="_blank"><img class="wu-w-full" src="https://downloads.intercomcdn.com/i/o/96426767/0c58144272a6ae1e34f60257/455F20F7-8D94-4530-A467-ABD69730FD32.png"></a>
    </div>
    <p class="intercom-align-left"> <em>Your settings should end up similar to this</em></p>
    <p class="intercom-align-left">You're all set!</p>
    
    
</article>
