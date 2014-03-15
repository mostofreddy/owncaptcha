<?php
/**
 * ICaptchaAdapter
 *
 * PHP version 5.4
 *
 * Copyright (c) 2014 mostofreddy <mostofreddy@gmail.com>
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * @category   Adapters
 * @package    OwnCaptcha
 * @subpackage Adapters
 * @author     Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright  2014 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link       http://www.mostofreddy.com.ar
 */
namespace owncaptcha\adapters;
/**
 * ICaptchaAdapter
 * Interfaz que debe implementar todo adapter
 *
 * @category   Adapters
 * @package    OwnCaptcha
 * @subpackage Adapters
 * @author     Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright  2014 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link       http://www.mostofreddy.com.ar
 */
Interface ICaptchaAdapter
{
    /**
     * Imprime el capctha en pantalla y devuelve el texto que contiene
     *
     * @access public
     * @return string
     */
    public function draw();
}
