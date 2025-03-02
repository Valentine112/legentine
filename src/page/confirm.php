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
<div class="config forgot confirm">
        <div class="forgot-1">
            <div class="forgot-2">
                <div>
                    <h2>Confirm Email</h2>
                </div>
                <div class="second-section">
                    <div>
                        <p>Put in the code that was sent to your email address.</p>
                    </div>
                    <div class="server-error">
                        <span></span>
                    </div>
                    <div>
                        <form action="" onclick="event.preventDefault(); event.stopImmediatePropagation" class="form-holder">
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

                            <div class="btn confirm-btn recover-box">
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

                            <div class="btn resend-btn recover-box">
                                <button>
                                    <span>Resend</span>
                                </button>
                            </div>
                            <div class="countdown">
                                <span></span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="src/js/page/confirm.js"></script>
</html>