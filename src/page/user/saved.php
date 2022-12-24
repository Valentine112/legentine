<html lang="en">
<head> 
    <?php 
        use Src\Config\Head; 
        Head::tags(); 
    ?>
    <link rel="stylesheet" href="../config/config.css">
    <link rel="stylesheet" href="../src/page/css/user/setup.css">
    <link rel="stylesheet" href="../src/page/css/user/pageHead.css">
    <title>Setup</title>
</head>
<body>
    <div class="config setup">
        <header class="pageHead">
            <h2>Setup</h2>
        </header>

    </div>

    <!-- Include the notice box here -->
    <?php include "src/template/quick-notice.php"; ?>
    
</body>
<script src="../src/js/page/user/setup.js"></script>
</html>