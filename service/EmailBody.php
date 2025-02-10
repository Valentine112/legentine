<?php
    namespace Service;

    use Service\Response;

    class EmailBody extends Response {

        public static function AuthEmail(int $code, $info) : string {
            return "
                <style>
                    main{
                        background-color: #f5f5f5;
                        padding: 5%;
                        font-family: Arial, Helvetica, sans-serif;
                        font-size: 15px;
                    }
                    h1{
                        color: #ff465b;
                    }
                    a{
                        background-color: #ff465b;
                        color: #fff;
                        padding: 5px 15px;
                        text-decoration: none;
                    }
            
                    @media screen and (min-width: 767px) {
                        main{
                            width: 70%;
                        }
                    }
                </style>
                <main>
                    <h1 style='color: #ff465b;'>egoPhren</h1>
            
                    <p>$info <b>$code</b>.</p>
            
                    <a href='egophren.com' style='background-color: #ff465b; color: #fff; padding: 5px 15px; text-decoration: none;'>Go now</a>
                    <h5>The above code expires in 5 minutes.</h5>
                    <br>
                    <small>Remember to comletely express yourself!</small>
                </main>
            ";
        }

    }
?>