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

            <!--<section class="login">
                <div class="header">
                    <h2>Access your private post</h2>
                </div>
                <div class="form">
                    <div>
                        <input type="password" placeholder="..." autofocus id="pin">
                    </div>

                    <div>
                        <button>Submit</button>
                    </div>
                </div>
            </section>-->

            <!--<section class="create">
                <div class="header">
                    <h2>Create pin for your private post</h2>
                </div>
                <div class="form">
                    <div>
                        <input type="number" placeholder="..." autofocus id="pin">
                    </div>

                    <div>
                        <button>Submit</button>
                    </div>
                </div>
            </section>-->

            <!--<section class="forgot">
                <div class="header">
                    <h2>Recover pin for private post</h2>
                </div>
                <div class="form">
                    <div>
                        <input type="password" placeholder="Account password" autofocus id="password">
                    </div>

                    <div>
                        <input type="number" placeholder="New Pin" id="pin">
                    </div>

                    <div>
                        <button>Submit</button>
                    </div>
                </div>
            </section>-->
        </div>
    </div>
</body>
<script src="../src/js/page/user/privateAccess.js"></script>
<script src="../src/js/general/general.js"></script>
<script src="../src/js/general/background.js"></script>
</html>