<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController
{

    public static function login(Router $router)
    {
        // Importante siempre Inicializarlo vacio conforme a los resultados que tengamos se ira llenando este arreglo
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            /* 
                * OJO $auth: contiene lo que usuario escribiro
                * OJO $usuario: contiene el registro(resultado) de busqueda con base a lo que el usuario puso en $auth
                -> Observar bien esas dos variables para entender el flujo del codigo bien
            */
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin(); // Metodo que solo valida el Login

            if (empty($alertas)) {
                // Comprobar que exista el usuario
                // Aquie se busca el usuario que nos retornara un objeto con el registro completo
                $usuario = Usuario::where('email', $auth->email);

                if ($usuario) {
                    // Verificar el password
                    /*
                        * Aqui se estan pasando dos cosas
                            1.- el objeto que contiene $usuario, es el registro que buscamos con $auth
                            2.- el atributo(password) que el usuario puso en el formulario viene en el $_POST
                    */
                    // $usuario <- (bool) si password y confirmado son true return true
                    if ($usuario->comprobarPassworAndVerificado($auth->password)) {
                        // Autenticar el usuario

                        /* 
                            Cree este metodo por que en Routes.php se crea una sesion que se esta pasando 
                            al instanciar la clase Router en cada metodo con la funcion de 'isSession()'
                            verificamos si no existe una sesion si no existe alli se inicia
                        */
                        isSession();

                        // En teoria(realidad) estamos accediendo a la sesion creada en Route.php
                        $_SESSION['id'] = $usuario->id; // Creamos en array $_SESION la posision id
                        $_SESSION['nombre'] = $usuario->nombre . ' ' . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // Redireccionamiento
                        if ($usuario->admin === '1') {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }
                    }
                } else {
                    Usuario::setAlerta('error', 'Usuario no Encontrado'); // Agregamos una alerta
                }
            } else {
            }
        }

        $alertas = Usuario::getAlertas(); // reescribimos la variable $alertas para mandarlas a la vista

        $router->render('auth/login', [
            'alertas' => $alertas
        ]);
    }

    public static function logout()
    {
        isSession();

        // Limpiamos el arreglo, el usuario que tenia ya no estara mas tendra que iniciar sesion de nuevo
        $_SESSION = [];

        header('Location: /');
    }

    public static function olvide(Router $router)
    {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if (empty($alertas)) { // si alertas esta lleno entonces procedemos
                $usuario = Usuario::where('email', $auth->email);

                if ($usuario && $usuario->confirmado === '1') {

                    // Generar un Token
                    $usuario->crearToken();
                    $usuario->guardar(); // Actualizamos el registro UPDATE por que ya tiene ID

                    // Enviar el Email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    // Alerta de exito
                    Usuario::setAlerta('exito', 'Revisa tu Email');
                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router)
    {
        $alertas = [];
        $error = false;

        $token = s($_GET['token']);

        // Buscar usuario por su token
        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) { // Si usuario Null (usuario no encontrado)
            Usuario::setAlerta('error', 'Token no Valido');
            $error = true;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Leer el nuevo Password y Guardarlo

            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            if (empty($alertas)) {
                /*
                Nota:
                    Para evitar que por algún error del servidor se concatene el nuevo password con el anterior.
                    Por seguridad es mejor siempre borrar el antiguo y luego insertar el nuevo, especialmente en campos tan delicados como un password hasheado
                */
                $usuario->password = null;

                $usuario->password = $password->password;
                $usuario->hashPassword(); // Metodo que esta en Usuario.php para hashear
                $usuario->token = null; // Limpiamos el Token por que es de un solo uso

                $resultado = $usuario->guardar(); // En realidad hace un "UPDATE" por que el registro tiene ID
                if($resultado) {
                    header('Location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router)
    {
        $usuario = new Usuario;

        // Tiene que empezar vacio debido a que cuando el usuario vea esa vista no tendra ningun error
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario->sincronizar($_POST); // Basicamente iguala lo que trae $_POST con el constructor de Usuario

            $alertas = $usuario->validarNuevaCuenta();

            // Risar que alertas este vacio
            if (empty($alertas)) {
                $resultado = $usuario->existeUsuario(); // OBJ

                if ($resultado->num_rows) { // si num_rows es 1 entonces el usuario ya existe y no se puede guardar
                    $alertas = Usuario::getAlertas();
                } else {

                    // Hashear Password
                    $usuario->hashPassword();

                    // Generar un Token Unico
                    $usuario->crearToken();

                    // Enviar El Email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);

                    $email->enviarConfirmacion();

                    // Crear el usuario
                    $resultado = $usuario->guardar();

                    if ($resultado) {
                        header('Location: /mensaje');
                    }

                    // debuguear($usuario);
                }
            }
        }

        $router->render('auth/crear-cuenta', [
            'alertas' => $alertas,
            'usuario' => $usuario
        ]);
    }

    public static function mensaje(Router $router)
    {
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router)
    {
        $alertas = []; // Inicializamos alerta en blanco

        $token = s($_GET['token']); // sanitizamos la url para evitar inyecciones SQL

        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            // Mostrar mensaje error
            Usuario::setAlerta('error', "Token No Valido"); // Creamos la alerta
        } else {
            // Modificar a usuario Confirmado

            $usuario->confirmado = "1"; // Confirmamos
            $usuario->token = null; // Borramos el token

            // Guardamos los procesos realizados
            $usuario->guardar(); // Guardar es metodo de ActiveRecord
            Usuario::setAlerta('exito', 'Cuenta Confirmada Correctamente');
        }

        // Aqui Obtenemos las "alertas" que creamos anteriormente
        $alertas = Usuario::getAlertas();

        // Renderizamos la vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}
