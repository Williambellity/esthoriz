<?php

system("mysqldump --host=mysql51-34.perso --user=esthorizbdd --password=mines11nancy esthorizbdd > save-".date('d-m-Y-H-i', time()).".sql");

?>
