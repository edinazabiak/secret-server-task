<?php 

class Page 
{

    public static function ShowBegin() 
    {
        echo '<html>
        <head>
            <title>Secret Server</title>
            <link rel="stylesheet" href="main.css">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" 
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        </head>
        <body>
            <header>
                <h1>Secret Server</h1>
            </header>';
    }

    public static function ShowEnd()
    {
        echo '</body>
        </html>';
    }
}
