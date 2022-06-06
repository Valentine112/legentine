<head>
    <?php include "../config/config.html"; ?>
    <link rel="stylesheet" href="../config/config.css">
    <style>
        .navbar{
            background-color: rgba(255, 255, 255, 0.5);
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            border-top: 1px solid #f5f5f5;
            padding: 12px 3px 12px 3px;
            z-index: 2;
        }
        .navbar .small{
            display: flex;
            align-items: center;
            justify-content: space-around;
        }
        .navbar .large{
            display: none;
        }
        .navbar .small .alt-icon{
            display: none;
        }
        .navbar .large > div{
            display: flex;
            align-items: center;
            justify-content: space-around;
        }
        .navbar .large a, .navbar .large span{
            text-decoration: none;
            color: #1e1f22;
            font-family: var(--font-family);
            transition: font-size 0.3s linear;
        }
        .navbar .large  div div a span:hover, .navbar .large  div div > span:hover{
            font-size: 19px;
        }
        .navbar .large .active{
            background-color: #fc8290;
            padding: 2px 20px 2px 20px;
            border-radius: 5px;
            color: #fff!important;
        }

        @media screen and (min-width: 600px) and (max-width: 767px) {

        }

        @media screen and (min-width: 768px) {
            .navbar{
                top: 0;
                bottom: unset;
                background-color: rgba(255, 255, 255, 0.5);
                box-shadow: 1px 1px 0px 1px #f1f1f1;
                padding: 18px 3px 18px 3px;
            }
            .navbar .small{
                display: none;
            }
            .navbar .large{
                display: flex;
                justify-content: space-between;
            }
            .navbar .large .section-1{
                width: 45%;
            }
            .navbar .large .section-2{
                width: 54%;
            }
            .navbar .large .section-1 > div{
                flex-grow: 1;
                text-align: center;
            }
            .navbar .large .section-2 > div:first-child{
                flex-grow: 4;
                text-align: right;
            }
            .navbar .large .section-2 > div:last-child{
                flex-grow: 1;
                text-align: center;
            }
            .navbar .large > a, .navbar .large span{
                font-size: 14px;
            }
            .navbar .large .section-2 input[type="search"]{
                width: 90%;
                padding: 10px 10px 10px 10px;
                background-color: #f5f5f5;
                border: 2px solid #f1f1f1;
                border-radius: 0px;
                font-family: var(--font-family);
            }
            .navbar .large .section-2 input[type="search"]:focus{
                outline: 2px solid #f1f1f1;
            }

            /* More dropdownn here */
            .large #more{
                position: relative;
            }
            .large #more #more-drop{
                position: absolute;
                width: fit-content;
                margin-top: 0px;
                padding: 30px 50px 20px 20px;
                background: linear-gradient(145deg, #fff, #ecebeb), no-repeat;
                border-top: 3px solid #f1f1f1;
                border-bottom: 3px solid #f1f1f1;
                transition: width 0.3s linear;
            }
            .large #more #more-drop > div{
                display: flex;
            }
            .large #more #more-drop div > div{
                margin: 0 1px 0 1px;
            }

            .large #more #more-drop header{
                padding: 0 0 5px 0;
                color: grey;
                font-family: var(--font-family);
            }
            .large #more #more-drop hr{
                width: 100%;
                border: 1px solid #f1f1f1;
                height: 2px;
            }
            .large #more #more-drop ul li a, .large #more #more-drop ul li span{
                color: #292a2c;
                font-weight: 400;
                font-size: 14px;
            }
            
            .large #more #more-drop ul li{
                list-style: none;
                text-align: left;
                padding: 7px 0 7px 0;
                font-family: var(--font-family);
                font-size: 14px;
                font-weight: 400;
                cursor: pointer;
                color: #292a2c;
            }
        }

        @media screen and (min-width: 992px) {
            .navbar .large a, .navbar .large span{
                font-size: 15px;
            }
            .navbar .large .section-1{
                width: 40%;
            }
            .large #more #more-drop{
                padding: 30px 90px 20px 50px;
            }
            .large #more #more-drop div > div{
                margin: 0 5px 0 5px;
            }
            .large #more #more-drop ul li{
                padding: 10px 0 10px 0;
            }
        }

    </style>
</head>
    <nav class="navbar config">

        <div class="small">
            <div>
                <a href="">
                    <img src="../icon/header/plain-icon/home.svg" alt="home" class="config-icon-1 ori-icon">

                    <img src="../icon/header/color-icon/home.svg" alt="home" class="config-icon-1 alt-icon">
                </a>
            </div>

            <div>
                <a href="">
                    <img src="../icon/header/plain-icon/note.svg" alt="home" class="config-icon-1 ori-icon">

                    <img src="../icon/header/color-icon/note.svg" alt="home" class="config-icon-1 alt-icon">
                </a>
            </div>
                
            <div>
                <a href="">
                    <img src="../icon/header/plain-icon/star.svg" alt="home" class="config-icon-1 ori-icon">

                    <img src="../icon/header/color-icon/star.svg" alt="home" class="config-icon-1 alt-icon">
                </a>
            </div>
                
            <div>
                <img src="../icon/header/plain-icon/search.svg" alt="home" class="config-icon-1 ori-icon">

                <img src="../icon/header/color-icon/search.svg" alt="home" class="config-icon-1 alt-icon">
            </div>

            <div>
                <img src="../icon/header/plain-icon/list.svg" alt="home" class="config-icon-1 ori-icon">

                <img src="../icon/header/color-icon/list.svg" alt="home" class="config-icon-1 alt-icon">
            </div>
        </div>

        <div class="large">
            <div class="section-1">
                <div>
                    <a href="">
                        <span class="active">Home</span>
                    </a>
                </div>

                <div>
                    <a href="">
                        <span>Session</span>
                    </a>
                </div>

                <div>
                    <a href="">
                        <span>Top</span>
                    </a>
                </div>

                <div id="more" class="dropdown-host" tabindex="0">
                    <div>
                        <span>More <img src="../icon/header/drop-down.svg" alt="more" class="dropdown-icon"></span>
    
                        <div id="more-drop" class="dropdown-item">
                            <div>
                                <div>
                                    <header>General</header>
                                    <hr>
                                    <ul>
                                        <li><a href="">Chat</a></li>
                                        <li><a href="">Private</a></li>
                                    </ul>
                                </div>
    
                                <div>
                                    <header>Activites</header>
                                    <hr>
                                    <ul>
                                        <li><a href="">Saved</a></li>
                                        <li><a href="">Pinned</a></li>
                                    </ul>
                                </div>
    
                                <div>
                                    <header>Notification</header>
                                    <hr>
                                    <ul>
                                        <li><a href="">Notification</a></li>
                                        <li class="dropdown-host" tabindex="0">
                                            <div>
                                                <span>Feature <img src="../icon/header/drop-down.svg" alt="more" class="dropdown-icon"></span>
    
                                                <ul id="drop" class="dropdown-item">
                                                    <li><a href="">Request</a></li>
                                                    <li><a href="">History</a></li>
                                                    <li>Quiet</li>
                                                </ul>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
    
                                <div>
                                    <header>Configure</header>
                                    <hr>
                                    <ul>
                                        <li><a href="">Password</a></li>
                                        <li><a href="">Unlisted</a></li>
                                    </ul>
                                </div>
    
                                <div>
                                    <header>Support</header>
                                    <hr>
                                    <ul>
                                        <li><a href="">Help</a></li>
                                        <li><a href="">About us</a></li>
                                        <li><a href="">Feedback</a></li>
                                        <li class="dropdown-host" tabindex="0">
                                            <div>
                                                <span>More <img src="../icon/header/drop-down.svg" alt="more" class="dropdown-icon"></span>
                                                
                                                <ul id="drop" class="dropdown-item">
                                                    <li><a href="">Disclaimer</a></li>
                                                    <li><a href="">Privacy</a></li>
                                                    <li><a href="">Our terms</a></li>
                                                </ul>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>    
                    </div>
                </div>

                <div>
                    <a href="">
                        <span>Logout</span>
                    </a>
                </div>
            </div>

            <div class="section-2">
                <div>
                    <input type="search"  id="search" autocomplete="off" placeholder="Search for people. . .">
                </div>

                <div>
                    <a href="">
                        <span>Profile</span>
                    </a>
                </div>
            </div>

        </div>

    </nav>
