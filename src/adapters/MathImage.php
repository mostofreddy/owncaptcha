<?php
/**
 * MathImage
 *
 * PHP version 5.4
 *
 * Copyright (c) 2014 mostofreddy <mostofreddy@gmail.com>
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * @category   OwnCaptcha
 * @package    OwnCaptcha\Adapters
 * @author     Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright  2014 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link       http://www.mostofreddy.com.ar
 */
namespace owncaptcha\adapters;
/**
 * MathImage
 *
 * @category   OwnCaptcha
 * @package    OwnCaptcha\Adapters
 * @author     Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright  2014 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link       http://www.mostofreddy.com.ar
 */
class MathImage extends \owncaptcha\adapters\TextImage
{
    protected $num1 = 0;
    protected $num2 = 0;
    protected $exp = '';
    /**
     * Metodo que devuelve el texto que se mostrará en el captcha
     *
     * @access protected
     * @return string
     */
    protected function txt()
    {
        $exp = array('+', '-');
        $this->exp = $exp[rand(0, count($exp) - 1)];
        $this->num1 = rand(0, 99);
        $this->num2 = rand(0, 99);
        $str = $this->num1." ".$this->exp." ".$this->num2." = ?";
        return $str;
    }
    /**
     * Imprime el captcha en pantalla y devuelve el texto que contiene
     *
     * @access public
     * @return string
     */
    public function draw()
    {
        $str = parent::draw();
        switch ($this->exp) {
            case '+':
                error_log(($this->num1 + $this->num2).PHP_EOL, 3, '/tmp/log');
                return $this->num1 + $this->num2;
            case '-':
                error_log(($this->num1 - $this->num2).PHP_EOL, 3, '/tmp/log');
                return $this->num1 - $this->num2;
            default:
                return 0;
        }
    }
}
