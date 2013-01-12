<?php

    // Do anything?
    if( $_REQUEST["newdispatcher"] )
    {
        // Update the cookie
        if( $_REQUEST["newdispatcher"]=="none" )
            $_REQUEST["newdispatcher"] = "";
        error_log( "Updating cookie from '$_COOKIE[dispatcher]' to '$_REQUEST[newdispatcher]'" );
        setcookie( "dispatcher", "", 0 );
        setcookie( "dispatcher", $_REQUEST["newdispatcher"], time() + 60*60*24*365, "/" );
        $_COOKIE["dispatcher"] = $_REQUEST["newdispatcher"];
    }

?>


<html>
You are currently: "<?= $_COOKIE["dispatcher"] ?>"
<ul>
<li><a href="?newdispatcher=none">Reset</a></li>
<li><a href="?newdispatcher=registration">Registration</a></li>
<li><a href="?newdispatcher=queue/PS">Photoshop Booth</a></li>
<li><a href="?newdispatcher=queue/BP">Beer Pong</a></li>
</ul>
</html>

