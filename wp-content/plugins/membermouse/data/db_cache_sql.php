<?php

$sql[] = "DROP TABLE IF EXISTS mm_container;";
$sql[] = "CREATE TABLE mm_container (
id int(11) NOT NULL AUTO_INCREMENT,
name varchar(255) NOT NULL,
obj mediumtext NOT NULL,
is_system tinyint(4) NOT NULL DEFAULT '0',
date_added datetime NOT NULL,
PRIMARY KEY  (id),
KEY name (name)
);";

?>