<?php 
echo "Welcome user " . $_COOKIE['User'];

?>


<p>Click this button to logout</p>
<form action="/logout" method="POST">
    <input type="submit" value="Logout"/>
</form>