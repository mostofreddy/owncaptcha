<?php
/**
 * formvalidate
 *
 * PHP version 5.4
 *
 * Copyright (c) 2014 Federico Lozada Mosto <mosto.federico@gmail.com>
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * @category  OwnCaptcha
 * @package   OwnCaptcha\Examples
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2014 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */

require_once realpath(__DIR__."/../vendor")."/autoload.php";
session_start();
// var_dump($_SESSION, $_POST);
try {
    $code = isset($_POST['code'])?$_POST['code']:'';
    $captcha = new \owncaptcha\Captcha();
    $result = $captcha->ttl(60*5)->validate($code);
    $err = 'Invalid code';
} catch (\Exception $e) {
    $result = false;
    $err = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        OwnCaptcha - examples
    </title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Oleo+Script' rel='stylesheet' type='text/css'>

    <style>
    *, body {
        font-family: 'Open Sans Condensed', sans-serif;
    }
    html, body {
        background-color: #333;
        height:100%;
    }
    body {
        color: #fff;
        text-shadow: 0 1px 3px rgba(0,0,0,.5);
        box-shadow: inset 0 0 100px rgba(0,0,0,.5);
    }
    .container {
        /*height:100%;*/
    }
    .container-title {
        text-align: center;
        background-color:#444444;
        width:100%;
    }
    .container h1 {
        font-family: 'Oleo Script', cursive;
        margin-bottom:0;
    }
    .container p {
        font-size:20px;
    }
    .box {
        height:170px;
    }
    .box .captcha {
        width:100%;
        /*text-align:center;*/
        /*padding:10px; */
    }
    .example-title {
        border-bottom:2px solid #666;
    }
    hr {
        border-top:2px solid #444;
    }
    </style>
</head>
<body>

    <a href="https://github.com/rocket-code/owncaptcha">
        <img style="position: absolute; top: 0; right: 0; border: 0;" 
            src="https://github-camo.global.ssl.fastly.net/365986a132ccd6a44c23a9169022c0b5c890c387/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f7265645f6161303030302e706e67" 
            alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_right_red_aa0000.png">
    </a>

    <div class="container container-title">
        <h1>OwnCaptcha</h1>
        <p>Detects human/robots easily!</p>
        <!-- <p>
            <a class="btn btn-primary btn-lg" role="button" href="https://github.com/mostofreddy/owncaptcha">
                <i class="fa fa-github "></i> Ver código &raquo;
            </a>
        </p> -->
        <br/>
    </div>
    <br/>
    <div class="container">
<?php
if ($result) {
    ?>
        <div class="alert alert-success"><strong>OK!</strong></div>
    <?php
} else {
    ?>
        <div class="alert alert-danger">
            <strong>NOK!</strong><br/>
            <?php echo $err;?>
        </div>
        <?php
}
?>


        <footer>
            <p>
            <hr/>
            © Copyright 2014 - <a href="https://github.com/mostofreddy">Mostofreddy</a>
            </p>
        </footer>
    </div>

</body>
</html>
