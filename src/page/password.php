<html lang="en">
<head>
    <?php 
        use Src\Config\Head; 
        Head::tags(); 
    ?>
    <link rel="stylesheet" href="src/page/css/forgot.css">
    <link rel="stylesheet" href="src/page/css/password.css">
</head>
<body>
    <div class="config password">
        <div class="forgot-1">
            <div class="forgot-2 password-2">
                <div>
                    <h2>New Password</h2>
                </div>
                <div>
                    <div>
                    <div class="server-error">
                        <span></span>
                    </div>
                        <div class="form-div">
                            <div>
                                <input 
                                    type="password" 
                                    class="form" 
                                    id="password"
                                    placeholder="New password" aria-placeholder="New password"
                                />
                                <span onclick="togglePassword(this)">show</span>
                            </div>
                        </div>
                        <div class="error-message">
                            <span></span>
                        </div>

                        <div class="button-div recover-box">
                            <button id="change-btn">
                                <span>Change</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="src/js/page/password.js"></script>
</html>