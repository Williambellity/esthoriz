<?php 
  // Le chemin du fichier (path) 
  $file = "/Appli/CV/l.nicaud@eleves-alsacienne.org.pdf";
    
  // Type de contenu d'en-tête
  header("Content-type: application/pdf"); 
    
  header("Content-Length: " . filesize($file)); 
    
  // Envoyez le fichier au navigateur.
  readfile($file); 
?>