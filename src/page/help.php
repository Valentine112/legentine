<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
        use Src\Config\Head; 
        Head::tags(); 
    ?>
    <link rel="stylesheet" href="src/page/css/help.css">
    <title>Help</title>
</head>
<body>
    <div class="drawer" onclick="open_side(this)">
        <img src="src/icon/header/plain-icon/list.svg" alt="" id="side-drawer">
    </div>
    <div class="side-bar">
        <div class="sidebar-cover" onclick="close_side(this)"></div>
        <nav>
            <ul>
                <li>
                    <a href="#compose">Compose</a>
                </li>
                <li>
                    <span class="father">
                        <span style="border: 0;" class="parent">Picture &emsp14; <img src="src/icon/header/drop-down.svg" alt=""></span>
                        <ul class="first-child father">
                            <li><a style="font-size: 15px;" href="#profile-picture">Profile Picture</a></li>
                            <li style="font-size: 15px;" class="grand-child">
                                Post Picture &emsp14; <img src="src/icon/header/drop-down.svg" alt="">
                            </li>
                            <ul class="great-grand-child">
                                <li><a style="font-size: 15px;" href="#picture-single">Single</a></li>
                                <li><a style="font-size: 15px; border:0;" href="#picture-multiple">Multiple</a></li>
                            </ul>
                        </ul>
                    </span>
                </li>
                <li>
                    <a href="#feature">Feature</a>
                </li>
            </ul>
        </nav>
    </div>
    <div class="container">
        <header>
            Help
        </header>
        <p class="note">
            &emsp;&emsp;&ensp;Note: &ensp;Navigation starts from the Homepage
        </p>

        <div class="assist">
            <section id="compose">
                Compose
                <nav>
                    <ul>
                        <li>
                            On a <b>Small Device</b>, select the 2nd icon from left to right on the menu section. <br>
                            While on a <b>Large Device</b>, select session from the menu section. <br><br>
                            Then write the title and the content of your masterpiece. <br>
                            After that, select the category to which it belongs, then you can choose to make it a private or public post. <br>
                            Then click on done when you're through.
                        </li>
                    </ul>
                </nav>
            </section>
            <section id="picture">
                Picture
                <nav class="assist-sub">
                    <ul>
                        <li class="i">
                            <b id="profile-picture">Profile Picture</b>
                            <ul>
                                <li class="ii">
                                    On a <b>Small Device</b>, select the dropdown icon from the menu section and click on your name. Once on the profile page click on edit and select upload<br>
                                    While on a <b>Large Device</b>, select profile from the menu section. Once on the profile page click on upload. <br><br>

                                    After that select the picture you would like to use as your profile picture and click on upload when done
                                </li>
                            </ul>
                        </li><br>
                        <li class="i">
                            <b id="post-picture">Post Picture</b>
                            <ul>
                                <li class="ii">
                                    On a <b>Small Device</b>, select the dropdown icon from the menu section and click on your name.<br>
                                    While on a <b>Large Device</b>, select profile from the menu section.<br><br>

                                    Once on the profile page click on the photos from the section and select the camera icon. Then select the pictures you want to post. <b>Note</b>, You can select at most 5 pictures at once. <br><br>
                                    Once selected, choose between single or multiple from the dropdown. <br><br>

                                    <b id="picture-single">Single</b>
                                    <ul>
                                    <li>If more than one images is selected at once and uploaded, they are uploaded as if it was done one after the other <b>i.e</b> They are displayed individually<li>
                                    </ul>
                                    <br>
                                    <b id="picture-multiple">Multiple</b>
                                    <ul>
                                    <li>If more than one image is selected at once and uploaded, they are uploaded as if it was done all at once <b>i.e</b> They are displayed in a stack<li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </section>
            <section id="feature">
                Feature
                <nav>
                    <ul>
                        <li>
                            To be featured, simply go to any other users post and click the feature button, then wait till you get a response(You can this in your feature history). <br>
                            If accepted simply visit the post and add your own verse, try not to spoil it, LOL 😉.
                            <br><br>
                            <li class="i">
                                <b>Block feature notification</b>
                                <ul>
                                    <li class="ii" id="last">
                                        On a <b>Small Device</b>, simply click on the menu icon.<br>
                                        While on a <b>Large Device</b>, click on account or just hover over it with your mouse.<br><br>

                                        Then click on feature from the menu and a dropdown would be displayed, to prevent the feature notifications from disturbing you, click on <b>Quiet</b>, while to receive them, click on <b>Allow</b>.<br>
                                        <i>Default is Allow</i> 
                                    </li>
                                </ul>
                            </li>
                        </li>
                    </ul>
                </nav>
            </section>
        </div>
    </div>
</body>
<script>
    async function open_side(self) {
        var promise = new Promise(res => {
            res(
                document.querySelector(".side-bar").style.display = "block"
            )
        })
        await promise
        setTimeout(() => {
            document.querySelector(".side-bar nav").style.right = "0%"
            document.querySelector(".sidebar-cover").style.backgroundColor = "rgba(0, 0, 0, 0.3)"
        }, 0500)
    }

    async function close_side(self) {
        var promise = new Promise(res => {
            res(
                self.style.backgroundColor = "transparent",
                document.querySelector(".side-bar nav").style.right = "-71%"
            )
        })
        await promise
        setTimeout(() => {
            document.querySelector(".side-bar").style.display = "none"
        }, 0500)
    }
    var parent_i = document.querySelectorAll(".parent")
    var grand_child_i = document.querySelectorAll(".grand-child")
    function elements(parent) {
        parent.forEach(elem => {
            elem.addEventListener("click", function() {
                toggle(this, this.closest(".father"))
            })

        })
    }
    elements(parent_i)
    elements(grand_child_i)
    

    function toggle(parent, elem) {
        elem = elem.querySelector("ul")
        if(getComputedStyle(elem).display === "none"){
            elem.style.display = "block"
            parent.querySelectorAll("img")[0].style.transform = "rotateZ(180deg)"
        }else{
            elem.style.display = "none"
            parent.querySelectorAll("img")[0].style.transform = "rotateZ(0deg)"
        }
    }
</script>
</html>