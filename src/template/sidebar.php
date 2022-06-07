<head>
    <?php include "../config/config.html"; ?>
    <link rel="stylesheet" href="../config/config.css">
    <style>
        .sidebar{
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
        }
        .sidebar-closure{
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1;
            background-color: rgba(0, 0, 0, 0.3);
            transition: background-color 0.3s ease-in-out;
        }
        .sidebar-list{
            position: absolute;
            right: 0;
            top:0;
            bottom: 0;
            width: 80%;
            z-index: 2;
            margin-left: auto;
            padding-left: 10px;
            background-color: #fff;
            overflow-y: auto;
            transition: right 0.3s ease-in-out;
        }
        .sidebar .sidebar-items{
            font-family: var(--theme-font);
        }
        .sidebar .sidebar-items header{
            color: grey;
            font-weight: 600;
        }
        .sidebar .sidebar-items hr{
            width: 100%;
            border: 1px solid #f1f1f1;
            height: 2px;
        }
        .sidebar .sidebar-items ul li{
            list-style: none;
            padding: 2px 0 2px 0;
        }
        .sidebar .sidebar-items ul li a{
            text-decoration: none;
            color: #000;
            display: block;
            width: 100%;
            padding: 12px 0 12px 0;
        }
        .sidebar .sidebar-items ul li div{
            width: 100%;
            padding: 12px 0 12px 0;
        }
        .sidebar .sidebar-items ul li img{
            height: 20px;
            width: 20px;
            margin: auto;
            vertical-align: top;
        }
        .sidebar .sidebar-items .dropdown-item{
            margin-top: 5px;
            padding-inline-start: 65px;
            margin-block-start: 15px;
            border-left: 1px solid #f1f1f1;
            border-right: 1px solid #f1f1f1;
        }
        .sidebar .sidebar-items .dropdown-item li{
            border-bottom: 1px solid #f1f1f1;
        }
    </style>
</head>
<div>
    <div class="config" id="small-sidebar">
        <div class="sidebar small">
            <div class="sidebar-closure" onclick="close_sidebar(this)"></div>
            <div class="sidebar-list">
                <div class="sidebar-list-1">
                    <header>

                    </header>
                    <div class="sidebar-items">

                        <div>
                            <header>General</header>
                            <ul>

                                <li>
                                    <a href="">
                                        <img src="../icon/sidebar-icon/chat.svg" alt="">
                                        &emsp;
                                        <span>Chat</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="">
                                        <img src="../icon/sidebar-icon/lock.svg" alt="">
                                        &emsp;
                                        <span>Private</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <hr>

                        <div>
                            <header>Activities</header>
                            <ul>
                                <li>
                                    <a href="">
                                        <img src="../icon/sidebar-icon/save.svg" alt="">
                                        &emsp;
                                        <span>Saved</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="">
                                        <img src="../icon/sidebar-icon/pin.svg" alt="">
                                        &emsp;
                                        <span>Pin</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <hr>

                        <div>
                            <header>Notification</header>
                            <ul>
                                <li>
                                    <a href="">
                                        <img src="../icon/sidebar-icon/notification.svg" alt="">
                                        &emsp;
                                        <span>Notification</span>
                                    </a>

                                </li>

                                <li class="dropdown-host" tabindex="0">
                                    <div>   
                                        <img src="../icon/sidebar-icon/feature.svg" alt="">
                                        &emsp;
                                        <span>
                                            Feature
                                            <img src="../icon/header/drop-down.svg" alt="more" class="dropdown-icon">
                                        </span>

                                        <ul class="dropdown-item">
                                            <li>
                                                <a href="">Request</a>
                                            </li>
                                            <li>
                                                <a href="">History</a>
                                            </li>
                                            <li>
                                                <div>
                                                    <span>Quiet</span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div> 
                                </li>
                            </ul>
                        </div>

                        <hr>

                        <div>
                            <header>Configure</header>
                            <ul>
                                <li>
                                    <a href="">
                                        <img src="../icon/sidebar-icon/lock.svg" alt="">
                                        &emsp;
                                        Password
                                    </a>
                                </li>

                                <li>
                                    <a href="">
                                        <img src="../icon/sidebar-icon/unlist.svg" alt="">
                                        &emsp;
                                        Unlist
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <hr>
                        
                        <div>
                            <header>Support</header>
                            <ul>
                                <li>
                                    <a href="">
                                        <img src="../icon/sidebar-icon/help.svg" alt="">
                                        &emsp;
                                        Help
                                    </a>
                                </li>

                                <li>
                                    <a href="">
                                        <img src="../icon/sidebar-icon/about.svg" alt="">
                                        &emsp;
                                        About us
                                    </a>
                                </li>
                                
                                <li>
                                    <a href="">
                                        <img src="../icon/sidebar-icon/feedback.svg" alt="">
                                        &emsp;
                                        Feedback
                                    </a>
                                </li>

                                <li>
                                    <a href="">
                                        <img src="../icon/sidebar-icon/more.svg" alt="">
                                        &emsp;
                                        More
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <hr>

                        <div>
                            <header>Account</header>
                            <ul>
                                <li>
                                    <a href="">
                                        <img src="../icon/sidebar-icon/logout.svg" alt="">

                                        <span>Log out</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function close_sidebar(self) {
        var promise = new Promise(res => {
            res(
                self.style.backgroundColor = "transparent",
                document.querySelector(".sidebar-list").style.right = "-81%"
            )
        })
        await promise
        setTimeout(() => {
            document.querySelector(".sidebar").style.display = "none"
        }, 0500)
    }
</script>