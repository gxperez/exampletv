<?php 

// echo "<h3>Probando recomendaciones</h3>";

header('Content-disposition: inline');
header('Content-type: application/msword'); // not sure if this is the correct MIME type
readfile('testDoc.docx');



?>