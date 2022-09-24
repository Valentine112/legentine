<!DOCTYPE html>
<html lang="en">
<head>
    <script src="../src/js/view/PostHTML.js"></script>
    <script src="../src/js/view/Properties.js"></script>
    <?php 
        use Src\Config\Head;
        Head::tags(); 
    ?>
    <link rel="stylesheet" href="../src/element/css/post.css">
    <link rel="stylesheet" href="../src/element/css/options.css">
    <link rel="stylesheet" href="../src/element/css/properties.css">
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

                    // Include sidebar here
                    include "src/template/sidebar.php";
                    
                    // Include the category section here
                    include "src/template/category.php"; 

                    // Include the notice box here
                    include "src/template/quick-notice.php";

                    // Include the delete notice here
                    include "src/template/delete-notice.php";
                ?>
            </div>

            <div class="ranking-time">
                <div class="active action" value="all-time" data-action="time-section">
                    <span>Tops</span>
                </div>

                <div class="action" value="weekly" data-action="time-section">
                    <span>Weekly</span>
                </div>
            </div>

            <div class="article-content post-controller box">
                <div id="postCover">

                </div>

                <div class="content-loader">
                    <div></div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../src/js/view/Options.js"></script>
<script src="../src/js/general/general.js"></script>
</html>