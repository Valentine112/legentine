<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
        use Src\Config\Head; 
        Head::tags(); 
    ?>
    <title>About Us</title>
    <style>
        body{
            margin: 0;
            background-color: #f5f5f5;
        }
        .intro{
            background-color: #ff465b;
            position: relative;
            height: 100vh;
        }
        .intro > :first-child{
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            height: fit-content;
            width: fit-content;
            margin: auto;
            color: #fff;
            font-family: 'Fredericka', sans-serif;
            font-size: 50px;
            font-weight: 600;
        }
        .scroll{
            position: absolute;
            bottom: 2%;
            left: 0;
            right: 0;
            margin: auto;
            text-align: center;
            color: #5e5e5e;
            font-family: 'Roboto', sans-serif;
        }
        .details{
            width: 80%;
            margin: auto;
            margin-top: 5%;
            font-family: var(--theme-font);
            text-align: center;
        }
        @media screen and (min-width: 992px) {
            .intro{
                height: 50vh;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <section class="intro">
            <div>
                YOU
            </div>
        </section>
        <section class="details">
            <p>
                Hi there, we are antaonar and we came to existence because of you. You are a star, and you definitely deserve to shine. But sometimes its not that easy, we all need a medium at some point, and that's where we come in.<br><br>
                We let the world know what you are capable of by sharing all your works with the world. There are no limitations to who can see your works. A person from another universe could see your work as long as he/she is on antaonar. That's to show you just how far and fast your light could shine<br><br> <h1></h1><b>SO WELCOME</b></h1>            </p>
        </section>
    </div>
</body>
</html>