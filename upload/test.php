<?php
session_start();
print("nothing to see here");
print_r($_SESSION);
print(isset($_SESSION['userid']));
print($_SESSION['userid']);
?>