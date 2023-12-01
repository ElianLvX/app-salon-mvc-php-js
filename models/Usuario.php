<?php

namespace Model;

class Usuario extends ActiveRecord {
    // Base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = [
        'id',
        'nombre',
        'apellido',
        'email',
        'password',
        'telefono',
        'admin',
        'confirmado',
        'token'
    ];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? '0'; // Esto es como decir que va ser null si no esta lleno admin
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }

    // Mensajes de Validacion para la creacion de una cuenta
    // Alertas es un arreglo asosiativo que dentro de el tiene un arreglo indexado
    // los textos de las errores se estan agregando en el arreglo indexado en posicion 'error' en el arreglo asosiativo
    public function validarNuevaCuenta() {
        if(!$this->nombre){
            self::$alertas['error'][] = 'Campo Nombre es Obligatorio';
        }

        if(!$this->apellido){
            self::$alertas['error'][] = 'Campo Apellido es Obligatorio';
        }

        if(!$this->telefono){
            self::$alertas['error'][] = 'Campo Telefono es Obligatorio';
        }

        if(!$this->email){
            self::$alertas['error'][] = 'Campo E-Mail es Obligatorio';
        }

        if(!$this->password){
            self::$alertas['error'][] = 'Campo Password es Obligatorio';
        }

        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'Campo Password debe contener al menos 6 caracteres';
        }

        return self::$alertas;
    }

    public function validarLogin() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'La estructura de ese Email no es Correcta';
        }

        if(!$this->password) {
            self::$alertas['error'][] = 'El Password es Obligatorio';
        }

        return self::$alertas;
    }

    public function validarEmail() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }
        return self::$alertas;
    }

    public function validarPassword() {
        if(!$this->password){
            self::$alertas['error'][] = 'Campo Password es Obligatorio';
        }

        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'Campo Password debe contener al menos 6 caracteres';
        }
        return self::$alertas;
    }

    // Revisa si el usuario ya tiene una cuenta
    public function existeUsuario() {
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";

        $resultado = self::$db->query($query); // return Obj

        if($resultado->num_rows) {
            self::$alertas['error'][] = 'El Usuario ya esta registrado';
        }

        return $resultado;
    }

    public function hashPassword() {
        // en este punto el constructor ya esta sincronizado con lo que
        // trae el $_POST
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken() {
        // aqui se llena el objeto actual de usuario que esta en LoginController
        // lo llenamos con el atributo token que esta sincronizado con el $_POST actual
        // es decir el $_POST su informacion ya esta en el constructor y Objeto(usuario) de Usuario por lo cual ya podemos editarlo
        $this->token = uniqid(); 
    }

    public function comprobarPassworAndVerificado($password) {
        // Compara lo que esta en $_POST con el password de la BD
        $resultado = password_verify($password, $this->password);

        if(!$resultado || !$this->confirmado) {
            self::$alertas['error'][] = 'Password Incorrecto o tu Cuenta no ha sido confirmada';
        } else {
            return true;
        }
    }
}