<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php 
        use Src\Config\Head; 
        Head::tags(); 
    ?>
    <style>
        body{
            margin: 0;
        }
        .container{
            position: relative;
            width: 95%;
            height: 99vh;
            margin: auto;
            font-family: var(--theme-font);
        }
        .feedback-successful{
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(255, 255, 255, 1);
            z-index: 2;
            display: none;
        }
        .feedback-control{
            text-align: center;
        }
        .feedback-control img{
            height: 150px;
            width: 150px;
            margin: auto;
        }
        .feedback-control span{
            font-size: xx-large;
            color: #444;
        }
        .feedback-continue{
            position: absolute;
            bottom: 3%;
            left: 0;
            right: 0;
            width: fit-content;
            margin: auto;
            text-align: center;
            cursor: pointer;
        }
        .feedback-continue p{
            padding: 5px;
            border: 1px solid #444;
            border-radius: 5px;
        }
        .sub-container{
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            height: fit-content;
            width: fit-content;
            margin: auto;
            text-align: center;
        }
        header{
            font-size: xx-large;
        }
        p{
            font-size: medium;
        }
        .form{
            margin-top: 3%;
        }
        .response{
            font-size: smaller;
            color: red;
            display: none;
        }
        textarea{
            border: 1px solid #4444;
            padding: 2%;
            outline: none;
            font-size: 16px;
            resize: none;
            width: 330px;
            font-family: var(--theme-font);
        }
        button{
            margin-top: 10px;
            background-color: var(--theme-color);
            color: #fff;
            border: 1px solid var(--theme-color);
            outline: none;
            font-size: small;
            width: 100px;
            padding-top: 4px;
            padding-bottom: 4px;
            transition: background-color 0.3s ease-out, color 0.3s ease-out;
        }
    </style>
    <title>Feedback</title>
</head>
<body>
    <div class="container">
        <div class="feedback-successful">
            <div class="feedback-control sub-container">
                <img src="icons/checked.png" alt=""><br>
                <span>We really appreciate this</span>
            </div>
            <div class="feedback-continue" onclick="history.back()">
                <p>Continue</p>
            </div>
        </div>
        <div class="sub-container">
            <header>
                Feedback
            </header>
            <p>
                Tell us how you feel about our website and what you think needs improvement
            </p>
            <div class="form">
                <span class="response">Something went wrong . . . try again later</span><br>
                <textarea id="text" cols="70" rows="10" placeholder="At least 20 characters"></textarea><br>
                <button onclick="send(this)">Send</button>
            </div>
        </div>
    </div>
</body>
</html>