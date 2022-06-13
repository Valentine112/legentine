<html lang="en">
<head> 
    <?php include "../config/config.html"; ?>
    <link rel="stylesheet" href="../config/config.css">
    <link rel="stylesheet" href="css/forgot.css">
    <link rel="stylesheet" href="css/confirm.css">
</head>
<body>
<div class="config forgot confirm">
        <div class="forgot-1">
            <div class="forgot-2">
                <div>
                    <h2>Confirm Email</h2>
                </div>
                <div>
                    <div>
                        <p>Put in the code that was sent to your email address.</p>
                    </div>
                    <div>
                        <form action="">
                            <div class="form-div">
                                <div>
                                    <input 
                                        type="number" 
                                        class="form" 
                                        aria-placeholder="code"
                                        pattern="/^-?\d+\.?\d*$/" 
                                        onKeyPress="if(this.value.length == 1) return false;"
                                        autofocus
                                        required
                                    />
                                </div>
                                <div>
                                    <input 
                                        type="number" 
                                        class="form" 
                                        aria-placeholder="code"
                                        maxlength="1"
                                        pattern="/^-?\d+\.?\d*$/" 
                                        onKeyPress="if(this.value.length == 1) return false;"
                                        required
                                    />
                                </div>
                                <div>
                                    <input 
                                        type="number" 
                                        class="form" 
                                        aria-placeholder="code"
                                        maxlength="1"
                                        pattern="/^-?\d+\.?\d*$/" 
                                        onKeyPress="if(this.value.length == 1) return false;"
                                        required
                                    />
                                </div>
                                <div>
                                    <input 
                                        type="number" 
                                        class="form" 
                                        aria-placeholder="code"
                                        maxlength="1"
                                        pattern="/^-?\d+\.?\d*$/" 
                                        onKeyPress="if(this.value.length == 1) return false;"
                                        required
                                    />
                                </div>
                                <div>
                                    <input 
                                        type="number" 
                                        class="form" 
                                        aria-placeholder="code"
                                        maxlength="1"
                                        pattern="/^-?\d+\.?\d*$/" 
                                        onKeyPress="if(this.value.length == 1) return false;"
                                        required
                                    />
                                </div>
                            </div>

                            <div class="btn confirm-btn">
                                <button>
                                    <span>Confirm</span>
                                </button>
                            </div>

                            <div class="info-div">
                                <span>Code would expire in 5 minutes time</span>
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

                            <div class="btn resend-btn">
                                <button>
                                    <span>Resend</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>