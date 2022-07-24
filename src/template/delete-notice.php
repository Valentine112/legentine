<head>
    <style>
        .delete-notice{
            position: fixed;
            bottom: 8%;
            left: 0;
            right: 0;
            width: 92%;
            margin: auto;
            background-color: snow;
            z-index: 4;
            font-family: var(--theme-font);
            display: none;
        }

        .delete-notice .delete-animation{
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            background-color: #444;
            border-radius: 5px;
            z-index: -1;
            transition: width 5s linear;
            -o-transition: width 5s linear;
        }
        .delete-notice .delete-cover{
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            border: 1px solid #444;
        }

        .delete-notice > div div{
            display: inline-block;
            vertical-align: top;
        }

        .delete-notice > div div:first-child{
            width: 20%;
        }
        .delete-notice > div div:last-child{
            width: 75%;
        }
        .delete-notice .delete-cover span{
            color: #f1f1f1;
            mix-blend-mode: difference;
        }
        .delete-notice img{
            height: 20px;
            width: 20px;
            margin: auto;
        }
        @media screen and (min-width: 768px) {
            .delete-notice{
                bottom: 2%;
                width: 35%;
            }
        }
        @media screen and (min-width: 992px) {
            .delete-notice{
                bottom: 2%;
                width: 25%;
            }
        }
    </style>
</head>
<div class="delete-notice">
    <div class="delete-animation"></div>
    <div class="delete-cover">
        <div>
            <img src="../src/icon/delete-notice-icon/cancel-delete.svg" alt="">
        </div>
        <div>
            <span>
                Click to cancel this process
            </span>
        </div>
    </div>
</div>

<script>
    window.addEventListener("click", function() {
        var delete_animation = document.querySelector(".delete-animation")
        delete_animation.style.width = "0%"
    })
</script>