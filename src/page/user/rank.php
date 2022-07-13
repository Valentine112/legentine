<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../src/page/css/user/rank.css">
    <title>Rank</title>
</head>
<body>
    <div class="content">
        <div class="ranking">
            <div class="ranking-section category-section">
                <?php
                    // Include the category section here
                    include "src/template/category.php"; 
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
</html>