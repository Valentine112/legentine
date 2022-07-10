<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        .welcome{
            font-size: 30px;
            font-family: 'montserrat', sans-serif;
            color: var(--theme-color);
        }
        .welcome > div{
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            height: fit-content;
            width: fit-content;
            margin: auto;
            text-align: center;
        }


        @media screen and (min-width: 600px) {
            .welcome{
                font-size: 45px;
            }
        }
        @media screen and (min-width: 768px) {
            .welcome{
                font-size: 55px;
            }
        }
        @media screen and (min-width: 992px) {
            .welcome{
                font-size: 70px;
            }
        }
    </style>
</head>
<body>
    <div class="welcome">
        <div>
            <div id="welcome-box">

            </div>
        </div>
    </div>
</body>
<script>
    window.addEventListener("load", () => {
        // Get username from localstorage
        var name = localStorage.getItem("LT-username") 
        name = name != null ? name : ""

        var welcome = `Welcome ${name}`

        var box = document.getElementById("welcome-box")

        // Split through the text to access each character
        welcome = welcome.split("")

        welcome.forEach((elem, ind) => {
            // Multiplied their index by 0500 millsec and used the result for the timeout
            // i.e 1 * 0500 = 0500, 2 * 0500 = 1
            // So the first displays after 0500 secs and the second 1 second and so on
            setTimeout(() => {
                var element = document.createElement("span")
                element.innerText = elem
                box.insertAdjacentElement("beforeend", element)

                if(ind + 1 >= welcome.length){
                    // Remove the name from localstorage and redirect to login page
                    localStorage.removeItem("LT-username")
                    //window.location = "login"
                }
            }, ind * 0500)
        })
    })
</script>
</html>