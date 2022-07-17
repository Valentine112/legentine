<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
        use Src\Config\Head; 
        Head::tags(); 
    ?>
    <link rel="stylesheet" href="src/page/css/forgot.css">
    <link rel="stylesheet" href="src/page/css/confirm.css">
</head>
<body>
    <div class="config forgot">
        <div class="forgot-1">
            <div class="forgot-2">
                <div>
                    <h2>Forgot Password</h2>
                </div>
                <div class="second-section">
                    <div>
                        <p>Put in your email address or username associated with your account.</p>
                    </div>
                    <div class="server-error">
                        <span></span>
                    </div>
                    <div>
                        <div>
                            <input 
                                type="text" 
                                class="form" 
                                placeholder="email or username" aria-placeholder="email or username"
                            />
                        </div>

                        <div class="recover-box">
                            <button id="recover-btn">
                                <span>Recover</span>
                            </button>
                        </div>

                        <div class="others">
                            <div>
                                <a href="login">
                                    <span>Login</span>
                                </a>
                            </div>
                            <div>
                                <a href="signup">
                                    <span>Signup</span>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="src/js/page/forgot.js"></script>
</html>