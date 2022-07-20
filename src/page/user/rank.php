<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
        use Src\Config\Head;
        Head::tags(); 
    ?>
    <link rel="stylesheet" href="../src/page/css/user/rank.css">
    <title>Rank</title>
</head>
<body>
    <div class="content">
        <div class="ranking">
            <div class="ranking-section category-section">
                <?php
                    // Include the navbar here
                    include "src/template/navbar.php";
                    
                    // Include the category section here
                    include "src/template/category.php"; 

                    // Include the notice box here
                    include "src/template/quick-notice.php";
                ?>
            </div>

            <div class="ranking-time">
                <div class="active">
                    <span>Tops</span>
                </div>

                <div>
                    <span>Weekly</span>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../src/js/general/general.js"></script>
</html>