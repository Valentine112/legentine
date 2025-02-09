<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
        use Src\Config\Head; 
        Head::tags(); 
    ?>
    <link rel="stylesheet" href="src/page/css/login.css">
</head>
<body>
    <div class="config login">
        <div class="login-1">
            <div class="login-2">
                <div>
                    <h2>egophren</h2>
                </div>

                <div class="form-holder">
                    <div class="server-error">
                        <span></span>
                    </div>
                    <form action="" onclick="event.preventDefault(); event.stopImmediatePropagation()">
                        <div>
                            <input 
                                type="text" 
                                class="form" 
                                id="user" 
                                placeholder="email or username" aria-placeholder="email or username" autocomplete="off"
                                autofocus
                            />
                        </div>

                        <div>
                            <input
                                type="password"
                                class="form" 
                                id="password" 
                                placeholder="password" 
                                aria-placeholder="password" 
                                autocomplete="off"
                            />
                        </div>

                        <div>
                            <button class="form" id="login-btn">
                                <span class="btn-text">Login</span>
                            </button>
                        </div>

                    </form>
                </div>

                <div class="form-save">
                     <label for="save-details-id" class="label-check">
                            <input type="checkbox" name="save-details" id="save-details-id">
                            <div class="switch">
                                <div class="slider"></div>
                            </div>
                            <span class="save-notice">Save details</span>
                     </label>
                </div>

                <div class="form-link">
                    <div>
                        <a href="forgot">forgot password</a>
                    </div>

                    <div>
                        <a href="signup">sign up</a>
                    </div>
                </div>

                <div class="explore">
                    <button class="explore-btn" onclick="explore(this)">Explore</button>
                </div>

            </div>
        </div>
    </div>
</body>
<script src="src/js/page/login.js"></script>
</html>