<?php
/**
 * Ejemplo simple
 *
 * PHP version 5.4
 *
 * Copyright (c) 2014 mostofreddy <mostofreddy@gmail.com>
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * @category   OwnCaptcha
 * @package    OwnCaptcha\Examples
 * @author     Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright  2014 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link       http://www.mostofreddy.com.ar
 */

require_once realpath(__DIR__."/../vendor")."/autoload.php";
session_start();

$adapter = new \owncaptcha\adapters\TextImage();
$captcha = new \owncaptcha\Captcha();
$captcha->ttl(60*5)
    ->adapter($adapter)
    ->build();
