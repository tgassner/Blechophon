<div id=footercontent>
    <?php
    if (isset($_SESSION['login']) && ($_SESSION['login'] == "true") && isset($_SESSION['username'])) {
        $loggedinmessage = "<form name='termine' method='post' action='" . $_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING'] . "' class='formloginindex' >" .
                "  <span class='topmenulinkelement'>Hallo " . $_SESSION['username'] . "</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" .
                "	 <input type='hidden' name='logout' value='' >" .
                "	 <input type='submit' name='submit' value='Logout' >" .
                "</form>";
    } else {
        $loggedinmessage = "<form name='termine' method='post' action='" . $_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING'] . "' class='formloginindex' >" .
                "	 <input type='Text' name='loginusername' value='' SIZE='40' MAXLENGTH='60' style='width:50px'>" .
                "	 <input type='password' name='loginpasswort' value='' SIZE='40' MAXLENGTH='60' style='width:50px'>" .
                "	 <input type='submit' name='submit' value='Login' >" .
                "</form>";
    }
    echo($loggedinmessage);
    ?>
</div>
