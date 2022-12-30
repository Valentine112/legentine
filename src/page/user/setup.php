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

        <div class="main">
            <div class="quote mainSub" data-type="quote">
                <h4>What quote inspires you</h4>

                <div class="form">
                    <span class="error"></span>
                    <span class="success"></span><br><br>

                    <textarea name="" id="quote" cols="30" rows="3" maxlength="80" placeholder="your favorite quote" onkeyup="writingQuote(this)"></textarea>
                </div>

                <div class="textGuage">
                    <div>
                        <div id="innerTextGuage"></div>
                    </div>
                </div>

                <div>
                    <button onclick="saveSetup(this)">Save</button>
                </div>
            </div>

            <div class="username mainSub" data-type="username">
                <h4>Change Username</h4>

                <div class="form">
                    <span class="error"></span>
                    <span class="success"></span><br><br>

                    <div>
                        <input type="text" placeholder="Username" id="username">
                    </div>
                    <div>
                        <input type="password" placeholder="Password" class="confirmPassword">
                    </div>
                </div>

                <div>
                    <button onclick="saveSetup(this)">Save</button>
                </div>
                <br>
                <span class="username-info" id="usernameInfo"></span>
            </div>

            <div class="password mainSub" data-type="password" id="changePassword">
                <h4>Change Password</h4>

                <div class="form">
                    <span class="error"></span>
                    <span class="success"></span><br><br>

                    <div>
                        <input type="password" placeholder="Old password" class="formPassword confirmPassword">
                    </div>
                    <div>
                        <input type="password" id="newPassword" placeholder="New Password" class="formPassword">
                    </div>
                    <div class="see">
                        <span onclick="TogglePasswordVisibility(this)">See</span>
                    </div>
                </div>

                <div>
                    <button onclick="saveSetup(this)">Save</button>
                </div>
            </div>

        </div>
    </div>
    <!-- Include the notice box here -->
    <?php include "src/template/quick-notice.php"; ?>

    
</body>
<script src="../src/js/page/user/setup.js"></script>
</html>