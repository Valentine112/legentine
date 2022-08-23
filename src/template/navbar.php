<head>
    <style>
        .navbar{
            background-color: rgba(255, 255, 255, 0.5);
            -webkit-backdrop-filter: blur(50px);
            backdrop-filter: blur(50px);
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            border-top: 1px solid #f5f5f5;
            padding: 12px 3px 12px 3px;
            z-index: 3;
        }
        .navbar .small-navbar{
            display: flex;
            align-items: center;
            justify-content: space-around;
        }
        .navbar .large{
            display: none;
        }
        .navbar .small-navbar .alt-icon{
            display: none;
        }
        .navbar .large-navbar > div{
            display: flex;
            align-items: center;
            justify-content: space-around;
        }
        .navbar .large-navbar a, .navbar .large-navbar span{
            text-decoration: none;
            color: #1e1f22;
            font-family: var(--theme-font);
            transition: font-size 0.3s linear;
        }
        .navbar .large-navbar  div div a span:hover, .navbar .large-navbar  div div > span:hover{
            font-size: 19px;
        }

        /* Setting the active for large screen links */

        .navbar .large-navbar .active{
            background-color: #fc8290;
            padding: 2px 20px 2px 20px;
            border-radius: 5px;
            color: #fff!important;
        }

        /* Setting active links ends */

        @media screen and (max-width: 767px) {
            .nav-links .active{
                display: block!important;
            }

            .navbar .non-active{
                display: none;
            }
        }

        @media screen and (min-width: 768px) {
            .navbar{
                top: 0;
                bottom: unset;
                background-color: rgba(255, 255, 255, 0.5);
                box-shadow: 1px 1px 0px 1px #f1f1f1;
                padding: 18px 3px 18px 3px;
            }
            .navbar .small-navbar{
                display: none;
            }
            .navbar .large-navbar{
                display: flex;
                justify-content: space-between;
                align-items: center;
                width: 100%;
            }
            .navbar .large-navbar .section-1{
                width: 45%;
            }
            .navbar .large-navbar .section-2{
                width: 54%;
            }
            .navbar .large-navbar .section-1 > div{
                flex-grow: 1;
                text-align: center;
            }
            .navbar .large-navbar .section-2 > div:first-child{
                flex-grow: 4;
                text-align: right;
            }
            .navbar .large-navbar .section-2 > div:last-child{
                flex-grow: 1;
                text-align: center;
            }
            .navbar .large-navbar > a, .navbar .large-navbar span{
                font-size: 14px;
            }
            .navbar .large-navbar .section-2 input[type="search"]{
                width: 90%;
                padding: 10px 10px 10px 10px;
                background-color: #f5f5f5;
                border: 2px solid #f1f1f1;
                border-radius: 0px;
                font-family: var(--theme-font);
            }
            .navbar .large-navbar .section-2 input[type="search"]:focus{
                outline: 2px solid #f1f1f1;
            }

            /* More dropdownn here */
            .navbar .large-navbar #more{
                position: relative;
            }
            .navbar .large-navbar #more #more-drop{
                position: absolute;
                width: fit-content;
                margin-top: 0px;
                padding: 30px 50px 20px 20px;
                background: linear-gradient(145deg, #fff, #ecebeb), no-repeat;
                border-top: 3px solid #f1f1f1;
                border-bottom: 3px solid #f1f1f1;
                transition: width 0.3s linear;
            }
            .navbar .large-navbar #more #more-drop > div{
                display: flex;
            }
            .navbar .large-navbar #more #more-drop div > div{
                margin: 0 1px 0 1px;
            }

            .navbar .large-navbar #more #more-drop header{
                padding: 0 0 5px 0;
                color: grey;
                font-family: var(--theme-font);
            }
            .navbar .large-navbar #more #more-drop hr{
                width: 100%;
                border: 1px solid #f1f1f1;
                height: 2px;
            }
            .navbar .large-navbar #more #more-drop ul li a, .large-navbar #more #more-drop ul li span{
                color: #292a2c;
                font-weight: 400;
                font-size: 14px;
            }
            
            .navbar .large-navbar #more #more-drop ul li{
                list-style: none;
                text-align: left;
                padding: 7px 0 7px 0;
                font-family: var(--theme-font);
                font-size: 14px;
                font-weight: 400;
                cursor: pointer;
                color: #292a2c;
            }
        }

        @media screen and (min-width: 992px) {
            .navbar .large-navbar a, .navbar .large-navbar span{
                font-size: 15px;
            }
            .navbar .large-navbar .section-1{
                width: 40%;
            }
            .navbar .large-navbar #more #more-drop{
                padding: 30px 90px 20px 50px;
            }
            .navbar .large-navbar #more #more-drop div > div{
                margin: 0 5px 0 5px;
            }
            .navbar .large-navbar #more #more-drop ul li{
                padding: 10px 0 10px 0;
            }
        }

    </style>
</head>
    <nav class="navbar config">

        <div class="small small-navbar">
            <div>
                <a href="home" class="nav-links home">
                    <img src="../src/icon/header/plain-icon/home.svg" alt="home" class="config-icon-1 ori-icon">

                    <img src="../src/icon/header/color-icon/home.svg" alt="home" class="config-icon-1 alt-icon">
                </a>
            </div>

            <div>
                <a href="session" class="nav-links session">
                    <img src="../src/icon/header/plain-icon/note.svg" alt="session" class="config-icon-1 ori-icon">
                </a>
            </div>
                
            <div>
                <a href="rank" class="nav-links rank">
                    <img src="../src/icon/header/plain-icon/star.svg" alt="tops" class="config-icon-1 ori-icon">

                    <img src="../src/icon/header/color-icon/star.svg" alt="tops" class="config-icon-1 alt-icon">
                </a>
            </div>
                
            <div>
                <img src="../src/icon/header/plain-icon/search.svg" alt="search" class="config-icon-1 ori-icon">

                <img src="../src/icon/header/color-icon/search.svg" alt="search" class="config-icon-1 alt-icon">
            </div>

            <div>
                <img src="../src/icon/header/plain-icon/list.svg" alt="menu" class="config-icon-1 ori-icon" data-action="show_sidebar">
            </div>
        </div>

        <div class="large large-navbar">
            <div class="section-1">
                <div>
                    <a href="home" class="nav-links home">
                        <span>Home</span>
                    </a>
                </div>

                <div>
                    <a href="session" class="nav-links session">
                        <span>Session</span>
                    </a>
                </div>

                <div>
                    <a href="rank" data-href="rank" class="nav-links rank">
                        <span>Top</span>
                    </a>
                </div>

                <div id="more" class="dropdown-host" tabindex="0">
                    <div>
                        <span>More <img src="../src/icon/header/drop-down.svg" alt="more" class="dropdown-icon"></span>
    
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
                                                <span>Feature <img src="../src/icon/header/drop-down.svg" alt="more" class="dropdown-icon"></span>
    
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
                                        <li><a href="../help">Help</a></li>
                                        <li><a href="../about">About us</a></li>
                                        <li><a href="feedback">Feedback</a></li>
                                        <li class="dropdown-host" tabindex="0">
                                            <div>
                                                <span>More <img src="../src/icon/header/drop-down.svg" alt="more" class="dropdown-icon"></span>
                                                
                                                <ul id="drop" class="dropdown-item">
                                                    <li><a href="../disclaimer">Disclaimer</a></li>
                                                    <li><a href="../privacy">Privacy</a></li>
                                                    <li><a href="../terms">Our terms</a></li>
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
                    <a href="" class="nav-links profile">
                        <span>Profile</span>
                    </a>
                </div>
            </div>

        </div>

    </nav>

    <script>
        // Placed inside a windows loaded function to get all the necessary links
        // Mostly the js files

        window.addEventListener("load", () => {
            // Get the current path
            var last_path = new Func().getPath()['main_path']

            // Check if the path matches any link
            if(document.querySelectorAll(`.${last_path}`) != null){
                var linked_path = document.querySelectorAll(`.${last_path}`)

                linked_path.forEach(elem => {
                    // Check if its a small device
                    if(elem.querySelectorAll("img")[0] != null){

                        // Hide the default icon
                        elem.querySelector(".ori-icon").classList.add("non-active")

                        // Show the hidden icon
                        elem.querySelector(".alt-icon").classList.add("active")
                    }

                    //Check if its a large device
                    if(elem.querySelector("span") != null){
                        var large_navbar = document.querySelector(".large-navbar")
                        if(large_navbar.querySelector(".active") != null){

                            large_navbar.querySelector(".active").classList.remove("active")
                        }

                        elem.querySelector("span").classList.add("active")
                    }
                }) 
            }
        }, true)
    </script>