<?php
/**
 * CaptchaTests
 *
 * PHP version 5.4
 *
 * Copyright (c) 2014 Federico Lozada Mosto <mosto.federico@gmail.com>
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * @category  OwnCaptcha
 * @package   Tests
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2014 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
/**
 * CaptchaTests
 *
 * @category  OwnCaptcha
 * @package   Tests
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2014 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
class CaptchaTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Testea el metodo ttl
     * 
     * @return void
     */
    public function testTtl()
    {
        $o = new \owncaptcha\Captcha();
        $o->ttl(5);
        $this->assertAttributeEquals(5, 'ttl', $o);
    }
    /**
     * Testea el metodo sessionsave
     * 
     * @return void
     */
    public function testSessionsave()
    {
        $o = new \owncaptcha\Captcha();
        $o->sessionSave('true');
        $this->assertAttributeEquals(true, 'sessionSave', $o);
    }
    /**
     * Testea el metodo sessionvar
     * 
     * @return void
     */
    public function testSessionvar()
    {
        $o = new \owncaptcha\Captcha();
        $o->sessionVar('texto');

        $this->assertAttributeEquals('texto', 'sessionVar', $o);
    }

    /**
     * Testea el metodo sessionvar
     * 
     * @return void
     */
    public function testSessionvarwhithxss()
    {
        $o = new \owncaptcha\Captcha();
        $o->sessionVar('texto<script>alert()</script>');

        $this->assertAttributeEquals('textoalert()', 'sessionVar', $o);
    }
}
