<?php
/**
 * TextImage
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
 * TextImage
 *
 * @category   Adapters
 * @package    OwnCaptcha
 * @subpackage Adapters
 * @author     Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright  2014 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link       http://www.mostofreddy.com.ar
 */
class TextImage implements ICaptchaAdapter
{
    const ERR_DEPS_GD = 'GD extension is not enabled';
    const ERR_DEPS_FUNCS = 'The following functions do not exist:';
    protected $config = array(
        'width' => 65,
        'height' => 40,
        'bg' => array("r"=>255, "g"=>255, "b"=>255),
        'forecolor' => array("r"=>51, "g"=>51, "b"=>51),
        'linecolor' => array("r"=>51, "g"=>51, "b"=>51),
        'lines' => 12,
        'enablettf' => true,
        'ttf' => '',
        'fontsize' => 15,
        'padding' => 5,
        'lineweight' => 1,
        'charlen' => 6
    );
    protected $img = null;
    protected $imgForeColor = null;
    protected $imgBkColor = null;
    /**
     * Constructor
     *
     * @access public
     * @return self
     */
    public function __construct()
    {
        $this->config['ttf'] = realpath(dirname(__FILE__)."/../fonts").'/LEVIBRUSH.TTF';
    }
    /**
     * Metodo que devuelve el texto que se mostrará en el captcha
     *
     * @access protected
     * @return string
     */
    protected function txt()
    {
        $cant = $this->config['charlen'];
        $inicial = 65;
        $final = 90;
        $str = '';
        for ($i=0; $i<$cant; $i++) {
            $str .= chr(rand($inicial, $final));
        }
        return $str;
    }
    /**
     * Configuracion del adapter. Los posibles parametros a configurar son:
     *
     * width: (int) ancho de la imagen
     * height: (int) alto de la imagen
     * bg: (array) color de fondo (rgb)
     * forecolor: (array) color del testo (rgb)
     * linecolor: (array) color de las lineas del fondo (rgb)
     * lines: (int) cantidad de lineas en el fondo
     * enablettf: (bool) indica si se usa una tipografia ttf o del sistema (true: usa ttf, false: usa del sistema)
     * ttf: (string) ubicación de la fuente ttf
     * fontsize: (int) tamaño de la fuente ttf
     * padding: (int) padding de la imagen
     * lineweight: (int) grosor de las lineas de fondo
     *
     * Ej:
     * $adapter = array(
     *    'forecolor' => array("r"=>255, "g"=>20, "b"=>20),
     *    'linecolor' => array("r"=>255, "g"=>20, "b"=>20),
     *    'fontsize' => 20,
     *    'enablettf' => true
     * )
     * $adapter = new \owncaptcha\adapters\TextImage();
     * $adapter->config($config);
     *
     * @param array $config array de configuracion
     *
     * @access public
     * @return self
     */
    public function config(array $config)
    {
        $this->config = $config + $this->config;
        return $this;
    }
    /**
     * Valida las dependencias. Si alguna no valida lanza una Excepcion
     *
     * @throws Exception Si no esta habilitado GD o alguna funcion de GD no existe
     * @access protected
     * @return mixed Value.
     */
    protected function validateDeps()
    {
        $errores = array();
        if (!extension_loaded('gd')) {
            throw new Exception(self::ERR_DEPS_GD);
        }
        $functions = array(
            'imagepng', 'imagedestroy', 'imagecolorallocatealpha', 'imageline', 'imagestring',
            'imagecreate', 'imagecolorallocate'
            , 'imagettftext'
        );
        foreach ($functions as $func) {
            if (!function_exists($func)) {
                $errores[] = $func;
            }
        }
        if (count($errores) > 0) {
            throw new Exception(self::ERR_DEPS_FUNCS." ".implode(", ", $errores));
        }
        return true;
    }
    /**
     * Imprime el capctha en pantalla y devuelve el texto que contiene
     *
     * @access public
     * @return string
     */
    public function draw()
    {
        //valida las dependencias de GD
        $this->validateDeps();
        //recupera el texto a mostrar en el captcha
        $txt = $this->txt();
        //setea el ancho según el largo del texto
        $this->setDinamicSize($txt);
        //crea la imagen
        $this->createImg();
        //crea lineas en el fondo
        $this->setLines();
        //setea el texto
        $this->setText($txt);
        //imprime en pantall
        $this->printImg();
        return $txt;
    }

    /**
     * Recalcula el ancho y alto automaticamente según el texto a mostrar
     *
     * @param string $txt texto del captcha
     *
     * @access protected
     * @return sefl
     */
    protected function setDinamicSize($txt)
    {
        if ($this->config['enablettf']) {
            $cajaTexto = imagettfbbox($this->config['fontsize'], 0, $this->config['ttf'], $txt);
            $this->config['width'] = $cajaTexto[2] + (2 * $this->config['padding']);
            $this->config['height'] = $cajaTexto[3] + $this->config['fontsize'] + (2 * $this->config['padding']);
        } else {
            $fonty = imagefontheight(5);
            $fontx = imagefontwidth(5);
            $this->config['width'] = ($fontx * strlen($txt)) + (2 * $this->config['padding']);
            $this->config['height'] = $fonty + (2 * $this->config['padding']);
        }
        return $this;
    }
    /**
     * Crea lineas en el fondo
     *
     * @access protected
     * @return self
     */
    protected function setLines()
    {
        $linescolor = imagecolorallocatealpha(
            $this->img,
            $this->config['linecolor']['r'],
            $this->config['linecolor']['g'],
            $this->config['linecolor']['b'],
            //127 * ( 100 - 70 ) / 100
            10
        );
        $offset = 2 * $this->config['padding'];
        for ($i=0; $i<$this->config['lines']; $i++) {
            imageline(
                $this->img,
                mt_rand(0, $this->config['width'] + $offset),
                mt_rand(0, $this->config['height'] + $offset),
                mt_rand(0, $this->config['width'] + $offset),
                mt_rand(0, $this->config['height'] + $offset),
                $linescolor
            );
            imagesetthickness($this->img, $this->config['lineweight']);
        }
        return $this;
    }
    /**
     * Inserta el texto en la imagen
     *
     * @param string $txt texto a insertar
     *
     * @access protected
     * @return self
     */
    protected function setText($txt)
    {
        if ($this->config['enablettf']) {
            $x = $this->config['padding'];
            $y = $this->config['fontsize'] + $this->config['padding'];
            imagettftext(
                $this->img,
                $this->config['fontsize'],
                0,
                $x, $y,
                $this->imgForeColor,
                $this->config['ttf'],
                $txt
            );
        } else {
            imagestring(
                $this->img, 5, $this->config['padding'], $this->config['padding'], $txt, $this->imgForeColor
            );
        }
        return $this;
    }
    /**
     * Crea una imagen en memoria
     *
     * @access protected
     * @return self
     */
    protected function createImg()
    {
        $this->img = imagecreate($this->config['width'], $this->config['height']);
        $this->imgBkColor = imagecolorallocate(
            $this->img,
            $this->config['bg']['r'],
            $this->config['bg']['g'],
            $this->config['bg']['b']
        );
        $this->imgForeColor = imagecolorallocate(
            $this->img,
            $this->config['forecolor']['r'],
            $this->config['forecolor']['g'],
            $this->config['forecolor']['b']
        );
        return $this;
    }
    /**
     * Imprime en pantalla
     *
     * @access protected
     * @return self
     */
    protected function printImg()
    {
        $this->noCache();
        // Output
        header('Content-type: image/png');
        imagepng($this->img);
        imagedestroy($this->img);
        $this->img = null;
        return $this;
    }
    /**
     * Setea los header de no cache.
     * Esto se realiza para que no quede cacheada la imagen del captcha y genere inconsistencias.
     *
     * @access protected
     * @return self
     */
    protected function noCache()
    {
        header('Pragma: public');
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: pre-check=0, post-check=0, max-age=0', false);
        header("Pragma: no-cache");
        header("Expires: 0", false);
        return $this;
    }
}
