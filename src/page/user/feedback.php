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
        <div class="sub-container">
            <header>
                Feedback
            </header>
            <p>
                Tell us how you feel about our website and what you think needs improvement
            </p>
            <div class="form">
                <textarea id="text" cols="70" rows="10" placeholder="At least 20 characters"></textarea><br>
                <button data-action="send-feedback">Send</button>
            </div>
        </div>
    </div>

    <!-- Include the notice box here -->
    <?php include "src/template/quick-notice.php"; ?>
</body>
</html>