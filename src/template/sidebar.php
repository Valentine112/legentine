<head>
    <style>
        .sidebar{
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 3;
            display: none;
        }
        .sidebar-closure{
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 3;
            background-color: rgba(255, 255, 255, 0.5);
            transition: background-color 0.3s ease-in-out;
        }
        .sidebar-list{
            position: absolute;
            right: -81%;
            top:0;
            bottom: 0;
            width: 75%;
            z-index: 3;
            height: 100vh;
            margin-left: auto;
            padding-left: 10px;
            background-color: #fff;
            overflow-y: auto;
            transition: right 0.3s ease-in-out;
        }
        .sidebar-list-1{
            margin-bottom: 10%;
        }
        .sidebar .sidebar-items{
            font-family: var(--theme-font-3)!important;
            padding: 10px;
        }
        .sidebar-items .profile .profile-sub{
            display: flex;
            justify-content: left;
            align-items: center;
        }
        .sidebar-items .profile a{
            color: #000;
        }
        .sidebar-items .profile-sub > div:first-child{
            flex: 2;
        }
        .sidebar-items .profile-sub > div:last-child{
            flex: 5;
        }
        .profile-sub div:last-child > div:first-child{
            font-size: 17px;
        }
        .profile-sub div:last-child > div:last-child{
            font-size: 15px;
        }
        .sidebar .sidebar-items .profile img{
            height: 50px;
            width: 50px;
            margin: auto;
        }
        .sidebar .sidebar-items header{
            font-size: 15px;
            color: grey;
            font-weight: 400;
        }
        .sidebar .sidebar-items hr{
            width: 100%;
            border: 1px solid #f1f1f1;
            height: 1px;
        }
        .sidebar .sidebar-items .first-list{
            padding-inline-start: 30px;
            margin-block-start: 16px;
        }
        .sidebar .sidebar-items ul li{
            list-style: none;
            padding: 2px 0 2px 0;
        }
        .sidebar .sidebar-items ul li a{
            text-decoration: none;
            font-size: 15px;
            color: #5e5e5e;
            display: block;
            width: 100%;
            padding: 12px 0 12px 0;
        }
        .sidebar .sidebar-items ul li span{
            color: #5e5e5e;
            font-size: 15px;
            font-family: var(--theme-font-3);
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
        <div class="small">
            <div class="sidebar">
                <div class="sidebar-closure" onclick="close_sidebar(this)"></div>
                <div class="sidebar-list">
                    <div class="sidebar-list-1">
                        <div class="sidebar-items">
                            <div class="profile">
                                <a href="">
                                    <div class="profile-sub">
                                        <div>
                                            <img src="../src/photo/image.jpg" alt="">
                                        </div>
                                        <div>
                                            <div>
                                                <span>Ngene Valentine</span>
                                            </div>

                                            <div>
                                                <span>Himself</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <hr>

                            <div>
                                <header>General</header>
                                <ul class="first-list">

                                    <li>
                                        <a href="">
                                            <img src="../src/icon/sidebar-icon/chat.svg" alt="">
                                            &emsp;
                                            <span>Chat</span>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="">
                                            <img src="../src/icon/sidebar-icon/lock.svg" alt="">
                                            &emsp;
                                            <span>Private</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <hr>

                            <div>
                                <header>Activities</header>
                                <ul class="first-list">
                                    <li>
                                        <a href="">
                                            <img src="../src/icon/sidebar-icon/save.svg" alt="">
                                            &emsp;
                                            <span>Saved</span>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="">
                                            <img src="../src/icon/sidebar-icon/pin.svg" alt="">
                                            &emsp;
                                            <span>Pin</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <hr>

                            <div>
                                <header>Notification</header>
                                <ul class="first-list">
                                    <li>
                                        <a href="">
                                            <img src="../src/icon/sidebar-icon/notification.svg" alt="">
                                            &emsp;
                                            <span>Notification</span>
                                        </a>

                                    </li>

                                    <li class="faeture-dropdown action" tabindex="0" data-action="sub-dropdown">
                                        <div>   
                                            <img src="../src/icon/sidebar-icon/feature.svg" alt="">
                                            &emsp;
                                            <span>
                                                Feature
                                                <img src="../src/icon/header/drop-down.svg" alt="more" class="dropdown-icon">
                                            </span>

                                            <ul id="drop" class="dropdown-item feature-dropdown-item">
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
                                <ul class="first-list">
                                    <li>
                                        <a href="">
                                            <img src="../src/icon/sidebar-icon/lock.svg" alt="">
                                            &emsp;
                                            Password
                                        </a>
                                    </li>

                                    <li>
                                        <a href="">
                                            <img src="../src/icon/sidebar-icon/unlist.svg" alt="">
                                            &emsp;
                                            Unlist
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <hr>
                            
                            <div>
                                <header>Support</header>
                                <ul class="first-list">
                                    <li>
                                        <a href="../help">
                                            <img src="../src/icon/sidebar-icon/help.svg" alt="">
                                            &emsp;
                                            Help
                                        </a>
                                    </li>

                                    <li>
                                        <a href="../about">
                                            <img src="../src/icon/sidebar-icon/about.svg" alt="">
                                            &emsp;
                                            About us
                                        </a>
                                    </li>
                                    
                                    <li>
                                        <a href="feedback">
                                            <img src="../src/icon/sidebar-icon/feedback.svg" alt="">
                                            &emsp;
                                            Feedback
                                        </a>
                                    </li>

                                    <li class="faeture-dropdown action" tabindex="0" data-action="sub-dropdown">
                                        <div>   
                                            <img src="../src/icon/sidebar-icon/more.svg" alt="">
                                            &emsp;
                                            <span>
                                                More
                                                <img src="../src/icon/header/drop-down.svg" alt="more" class="dropdown-icon">
                                            </span>

                                            <ul id="drop" class="dropdown-item feature-dropdown-item">
                                                <li>
                                                    <a href="../disclaimer">Disclaimer</a>
                                                </li>
                                                <li>
                                                    <a href="../privacy">Privacy</a>
                                                </li>
                                                <li>
                                                    <a href="../terms">Our terms</a>
                                                </li>
                                            </ul>
                                        </div> 
                                    </li>
                                </ul>
                            </div>

                            <hr>

                            <div>
                                <header>Account</header>
                                <ul class="first-list">
                                    <li>
                                        <a href="">
                                            <img src="../src/icon/sidebar-icon/logout.svg" alt="">
                                            &emsp;
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