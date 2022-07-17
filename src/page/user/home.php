<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home</title>
    <?php 
        use Src\Config\Head; 
        Head::tags(); 
    ?>
</head>
<body>
    <div class="content">
        <div class="category-section">
            <?php
                // Include the category section
                include "src/template/category.php";
            ?>
        </div>

        <div class="article-content">
            
        </div>
    </div>
</body>
</html>