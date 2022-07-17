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
        <div class="category-section other-templates">
            <?php
                // Include the navbar here
                include "src/template/navbar.php";

                // Include the category section here
                include "src/template/category.php"; 

                // Include the notice box here
                include "src/template/quick-notice.php";
            ?>
        </div>

        <div class="article-content">
            
        </div>
    </div>
</body>
</html>