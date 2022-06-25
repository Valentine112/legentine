<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "src/config/config.html"; ?>
    <?php include "src/template/navbar.php"; ?>
    <link rel="stylesheet" href="src/config/config.css">
    <style>
        .container{
            margin: 8% 0 8% 0;
        }
        .contents{
            margin-top: 2%;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php require "src/page/ranks.php"; ?>
    </div>
</body>