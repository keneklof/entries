<?php
include "autoloader.php";
$competition = new Competitions();
//COMPETION VARIABLES
$competitionId = 1;
$title = "Imoittautuminen Jannen Kisat 2026";
$headerImage = "background-image: url('images/header-image.jpg')";
$compNameTextStyle = "color: #f2f7f9; text-shadow: 2px 2px #0c0b0b; position:absolute; left: 10%; top:10%;";
$compDateTextStyle = "color: #f2f7f9; text-shadow: 2px 2px #0c0b0b; position:absolute; left: 10%; top:20%;";
$compLocationTextStyle = "color: #f2f7f9; text-shadow: 2px 2px #0c0b0b; position:absolute; left: 10%; top:30%;";

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $title; ?>
  </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="style.css">
  <script src="https://kit.fontawesome.com/9e7a1653cf.js" crossorigin="anonymous"></script>
</head>

<body>
  <div class="container">
    <header>
      <div style="<?php echo $headerImage; ?>" id="header">
        <h3 style="<?php echo $compNameTextStyle; ?>">43. Jannen Kisat</h3>
        <h3 style="<?php echo $compDateTextStyle; ?>">18.7.-25.7.2026</h3>
        <h3 style="<?php echo $compLocationTextStyle; ?>">Räyskälä</h3>
      </div>
    </header>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>