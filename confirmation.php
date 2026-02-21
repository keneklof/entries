<!DOCTYPE html>
<?php
include "language.php";
include "variables.php";

$entryTime = $actualTime->format("d.m.Y H:i:s");

?>

<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https: //kit.fontawesome.com/9e7a1653cf.js" crossorigin="anonymous"></script>
  </head>
  <body class="bg-secondary">
    <div class="container py-5 bg-light">
    
      <div class=" d-flex justify-content-center py-4">
        <img src="<?php echo $confirmationHeaderImage; ?>" alt="Logo" class="img-fluid">
      </div>
      <h4 class="text-center"><?php echo $language[$l]['confirmation-header']; ?></h4>
      <h4 class="lead text-center"><?php echo $language[$l]['confirmation-header-2']; ?></h4>
       <div class="row">
        <div class="col-12 col-md-6 offset-md-3">
        <?php echo $language[$l]['confirmation-text']; ?>
        <p class="pt-3"><small><?php echo $language[$l]['confirmation-entry-time'].$entryTime; ?></small></p> 
        
        </div>
       </div>
      </h3>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>