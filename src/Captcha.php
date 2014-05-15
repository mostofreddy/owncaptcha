<?php
/**
 * Captcha
 *
 * PHP version 5.4
 *
 * Copyright (c) 2014 mostofreddy <mostofreddy@gmail.com>
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * @category  OwnCaptcha
 * @package   OwnCaptcha
 * @author    Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright 2014 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
namespace owncaptcha;
/**
 * Captcha
 *
 * @category  OwnCaptcha
 * @package   OwnCaptcha
 * @author    Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright 2014 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      http://www.mostofreddy.com.ar
 */
class Captcha
{
    const ERR_INVALID_ADAPTER = 'Invalid adapter';
    protected $apdater = null;
    protected $sessionSave = true;
    protected $sessionVar = 'owncaptcha';
    protected $ttl = 60;
    /**
     * Setea el tiempo de vida del captcha
     * default: 60 seg
     *
     * @param int $ttl tiempo de vida del captcha en segundos
     *
     * @access public
     * @return self
     */
    public function ttl($ttl)
    {
        $this->ttl = (int) $ttl;
        return $this;
    }
    /**
     * Habilita/deshabilita el guardado automatico del captcha en session
     * default: true
     *
     * @param bool $save valores posibles true o false
     *
     * @access public
     * @return self
     */
    public function sessionSave($save)
    {
        $this->sessionSave = (bool) $save;
        return $this;
    }

    /**
     * Setea el nombre de la variable de sesion donde se almacenara el valor del captcha
     * Default: owncaptcha
     *
     * @param string $var Nombre de la variable
     *
     * @access public
     * @return self
     */
    public function sessionVar($var)
    {
        $this->sessionVar = $this->cleanAndSanitize((string) $var);
        return $this;
    }
    /**
     * Setea el adapter a utilizar para crear el captcha
     * default: null
     *
     * @param \owncaptcha\adapters\ICaptchaAdapter $adapter Objeto adapter
     *
     * @access public
     * @return self
     */
    public function adapter(\owncaptcha\adapters\ICaptchaAdapter $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * Imprime en pantalla el captcha (el formato depende del Adaptar seteado)
     *
     * @throws \Exception Si el adapter no es un objeto o no implementa la interfaz ICaptchaAdapter
     * @access public
     * @return string captcha generado
     */
    public function draw()
    {
        $_SESSION[$this->sessionVar] = '';
        //valida que el adapter este bien seteado
        if (!is_object($this->adapter)
            || (is_object($this->adapter) && !($this->adapter instanceof \owncaptcha\adapters\ICaptchaAdapter))
        ) {
            throw new Exception(self::ERR_INVALID_ADAPTER);
        }
        //imprime en pantalla el captcha
        $txt = $this->adapter->draw();
        //guarda en sesion el texto del captcha si esta habilitado
        if ($this->sessionSave) {
            $_SESSION[$this->sessionVar] = $txt."-".microtime(true);
        }
        return $txt;
    }
    /**
     * Valida un codigo contra el dato guardado en session
     *
     * @param string $code codigo a validar
     *
     * @access public
     * @return bool
     */
    public function validate($code)
    {
        $aux = explode('-', $this->getSessionValue());
        //si no existe el momento en que se creo el captcha => no es valido
        if (!isset($aux[1])) {
            return false;
        }
        //si el captcha se creo hace mas tiempo que el permitido => no es valido
        $timeDiff = ceil(microtime(true) - $aux[1]);
        if ($timeDiff > $this->ttl) {
            return false;
        }
        //si los codigos no son iguales => no es valido
        return ($aux[0] == $this->cleanAndSanitize($code));
    }

    /**
     * Obtiene el captcha almacenado en sesion
     *
     * @access protected
     * @return string
     */
    protected function getSessionValue()
    {
        if (isset($_SESSION[$this->sessionVar])) {
            $aux = $this->cleanAndSanitize($_SESSION[$this->sessionVar]);
            $_SESSION[$this->sessionVar] = null;
            unset($_SESSION[$this->sessionVar]);
            return $aux;
        } else {
            return '';
        }
    }
    /**
     * Limpia y evita ataques xss en los datos a validar
     *
     * @param string $txt codigo
     *
     * @access protected
     * @return string
     */
    protected function cleanAndSanitize($txt)
    {
        return strtolower(trim(strip_tags($txt)));
    }
}
