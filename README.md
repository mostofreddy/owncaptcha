OwnCaptcha
==========

Detects human/robots easily!

Version
-------

__0.2.0__

Features
--------

* Lifetime
* Saves the captcha in session
* Generates the captcha in png format
* XSS clean
* Extensible using adapters
* Apdaters: random text, math equation

License
-------

[MIT License](http://www.opensource.org/licenses/mit-license.php)

Installation
-----------

### Requirements

- PHP 5.3.*

### Github

    cd /var/www
    git clone git@github.com:mostofreddy/owncaptcha.git
    cd owncaptcha
    composer install

### Composer

    "require": {
        "php": ">=5.3.0",
        "mostofreddy/owncaptcha": "*",
    }

Roadmap & issues
----------------

[Roadmap & issues](https://github.com/mostofreddy/owncaptcha/issues)

Changelog
---------

__0.2.0__

* Math image adapter
* New examples
* Unit testing
* Change function name: setSessionVar to sessionVar
* Change function name in \owncaptcha\Captcha class: draw to build

__0.1.0__

* Text image adapter
* Session storage 
* Captcha validation

Examples
========

[View examples](https://github.com/mostofreddy/owncaptcha/tree/master/examples)

Docs
====

Captcha
-------

### Tiempo de vida del captcha

El tiempo de vida del captcha se configura mediante el método ttl.
*Default: 60 seg*

    $captcha = new \owncaptcha\Catpcha();
    $captcha->ttl(3600); //1 hora

### Sessiones

__Habilitar/deshabilitar sessiones__

Para habilitar/deshabilitar que el script guarde el valor del captcha en sessiones:
*Default: true*

    $captcha->sessionSave(true); //o false

__Variable donde se almacena el valor del capcha en session__

Con el método setSessionVar se puede cambiar la variable donde se almacena el captcha en session
*Default: owncaptcha*

    $captcha->sessionVar('myvar');

### Validación

La validación se realiza con el método validate

    $code = (isset($_POST['code']))?$_POST['code']:'';
    $result = $captcha->validate($code); // true or false

Apdaters
--------

Los adapters son distintas clases que implementan la lógica de como renderizar el captcha.

### TextImage

Crea imagen con un texto aleatorio.

    $adapter = new \owncaptcha\adapters\TextImage();
    $adapter->config(
        array(....)
    );

* __width__: (int) ancho de la imagen
* __height__: (int) alto de la imagen
* __bg__: (array) color de fondo (rgb)
* __forecolor__: (array) color del testo (rgb)
* __linecolor__: (array) color de las lineas del fondo (rgb)
* __lines__: (int) cantidad de lineas en el fondo
* __enablettf__: (bool) indica si se usa una tipografia ttf o del sistema (true: usa ttf, false: usa del sistema)
* __ttf__: (string) ubicación de la fuente ttf
* __fontsize__: (int) tamaño de la fuente ttf
* __padding__: (int) padding de la imagen
* __lineweight__: (int) grosor de las lineas de fondo

### MathImage

Crea imagen con una ecuación matemática.

    $adapter = new \owncaptcha\adapters\MathImage();
