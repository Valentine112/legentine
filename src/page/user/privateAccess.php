<html lang="en">
<head> 
    <script src="../src/js/view/PostHTML.js"></script>
    <script src="../src/js/view/Properties.js"></script>
    <script src="../src/js/view/Profile.js"></script>
    <?php 
        use Src\Config\Head; 
        Head::tags(); 
    ?>
    <link rel="stylesheet" href="../src/page/css/user/privateAccess.css">
    <title>Private</title>
</head>
<body>
    <div class="config">
        <div class="privateAccess" id="privateAccess">
            <!-- SECTION GOES HERE -->
        </div>
    </div>

    <!-- Include the notice box here -->
    <?php include "src/template/quick-notice.php"; ?>
    
</body>
<script src="../src/js/page/user/privateAccess.js"></script>
<script src="../src/js/general/general.js"></script>
<script src="../src/js/general/background.js"></script>
</html>