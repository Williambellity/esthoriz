<?php
$dir_list = array_slice(scandir("/home/esthoriz/www/Appli/CV/"),2);

foreach($dir_list as $val){
    copy("/home/esthoriz/www/Appli/CV/".$val, "/home/esthoriz/www/Appli/tmp/".ucfirst($val));
}

?>
