<!DOCTYPE html>
<html lang="en">
<head> 
    <?php include "../config/config.html"; ?>
    <link rel="stylesheet" href="../config/config.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/signup.css">
</head>
<body>
    <div class="config signup">
    <div class="login-1">
            <div class="login-2">
                <div>
                    <h2>Register</h2>
                </div>

                <div class="form-holder">
                    <form action="" onclick="event.preventDefault(); this.stopImmediatePropagation()">

                        <div>
                            <input 
                                type="text" 
                                class="form" 
                                id="user" 
                                placeholder="fullname" aria-placeholder="fullname" autocomplete="off"
                                autofocus
                            />
                        </div>

                        <div>
                            <input 
                                type="text" 
                                class="form" 
                                id="username" 
                                placeholder="username" aria-placeholder="username" autocomplete="off"
                                autofocus
                            />
                        </div>

                        <div>
                            <input 
                                type="text" 
                                class="form" 
                                id="email" 
                                placeholder="email" aria-placeholder="email" autocomplete="off"
                                autofocus
                            />
                        </div>

                        <div class="password-div">
                            <input
                                type="password"
                                class="form" 
                                id="password" 
                                placeholder="password" 
                                aria-placeholder="password" 
                                autocomplete="off"
                            />
                            <span>show</span>
                        </div>

                        <div>
                            <button class="form">
                                <span class="btn-text">Sign Up</span>
                                <div class="btn-loader">
                            </button>
                        </div>

                    </form>
                </div>

                <div class="divider">
                    <div></div>
                    &ensp;
                    <div>
                        <span>or</span>
                    </div>
                    &ensp;
                    <div></div>
                </div>

                <div class="form-link">
                    <div>
                        <a href="">Login</a>
                    </div>
                </div>

                <div class="terms">
                    <span>
                        By signing up, it means that you have accepted our <br>
                        <a href="">Terms & Conditions</a>
                    </span>
                </div>

            </div>
        </div>
    </div>
</body>
</html>