<?php
  // menggunakan mysqli_connect untuk koneksi database
 $databaseHost = 'mysql:unix_socket=/cloudsql/trial-velostrata:asia-southeast1:test;dbname=cuaca';
 //$databaseHost = 'mysql:unix_socket=/cloudsql/projectID:google-cloud-instance;dbname=test';
 $databaseName = 'cuaca';
 $databaseUsername = 'root';
 $databasePassword = 'root';

 $mysqli = new PDO($databaseHost, $databaseUsername, $databasePassword, array(PDO::ATTR_PERSISTENT => true));

?>