<!DOCTYPE html>
<html lang="en">
<head> 
    <link rel="stylesheet" href="src/page/css/login.css">
    <link rel="stylesheet" href="src/page/css/signup.css">
</head>
<body>
    <div class="config signup">
        <div class="login-1">
            <div class="login-2">
                <div>
                    <h2>Register</h2>
                </div>

                <div class="form-holder">
                    <div class="server-error">
                        <span></span>
                    </div>
                    <form action="" onclick="event.preventDefault(); event.stopImmediatePropagation">

                        <div>
                            <input 
                                type="text" 
                                class="form" 
                                id="name" 
                                placeholder="fullname" aria-placeholder="fullname" autocomplete="off"
                                autofocus
                                value="Valentine Ngene"
                            />
                            <div class="error-message">
                                <span></span>
                            </div>
                        </div>

                        <div>
                            <input 
                                type="text" 
                                class="form" 
                                id="username" 
                                placeholder="username" aria-placeholder="username" autocomplete="off"
                                autofocus
                                value="Himself"
                            />
                            <div class="error-message">
                                <span></span>
                            </div>
                        </div>

                        <div>
                            <input 
                                type="text" 
                                class="form" 
                                id="email" 
                                placeholder="email" aria-placeholder="email" autocomplete="off"
                                autofocus
                                value="valenny112@gmail.com"
                            />
                            <div class="error-message">
                                <span></span>
                            </div>
                        </div>

                        <div>
                            <div class="password-div">
                                <input
                                    type="password"
                                    class="form" 
                                    id="password" 
                                    placeholder="password" 
                                    aria-placeholder="password" 
                                    autocomplete="off"
                                    value="Anthonyval1"
                                />
                                <span onclick="togglePassword(this)">show</span>
                            </div>
                            <div class="error-message">
                                <span></span>
                            </div>
                        </div>


                        <div>
                            <button class="form" id="signup-btn">
                                <span class="btn-text">Sign Up</span>
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
                        <a href="login">Login</a>
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
<script src="src/js/page/signup.js"></script>
</html>