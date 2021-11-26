<?php 

namespace Model;

class Usuario extends ActiveRecord{
    //TABLA DE LA BD
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellidos', 'email', 'password', 'token', 'confirmado'];

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellidos = $args['apellidos'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->password_actual = $args['password_actual'] ?? '';
        $this->password_nuevo = $args['password_nuevo'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }

    //VALIDAR FORMULARIO DE LOGIN
    public function validarLogin() : array{
        if (!$this->email) {
            self::$alertas['error'][] = 'El correo es obligatorio';
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Correo no válido';
        }

        if (!$this->password) {
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }

        return self::$alertas;
    }

    //VALIDAR FORMULARIO DE CREAR CUENTA
    public function validarNuevaCuenta() : array{
        if (!$this->nombre) {
            self::$alertas['error'][] = 'El nombre es obligatorio';
        }

        if (!$this->apellidos) {
            self::$alertas['error'][] = 'Los apellidos son obligatorios';
        }

        if (!$this->email) {
            self::$alertas['error'][] = 'El correo es obligatorio';
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Correo no válido';
        }

        if (!$this->password) {
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }

        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'La contraseña debe contener mínimo 6 caracteres';
        }

        if ($this->password !== $this->password2) {
            self::$alertas['error'][] = 'Las contraseñas son diferentes';
        }


        return self::$alertas;
    }

    //VALIDAR FORMULARIO DE OLVIDE PASSWORD
    public function validarEmail() : array{
        if (!$this->email) {
            self::$alertas['error'][] = 'Coloca tu correo';
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Correo no válido';
        }

        return self::$alertas;
    }

    //VALIDAR FORMULARIO DE REESTABLECER PASSWORD
    public function validarPassword() : array{
        if (!$this->password) {
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }

        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'La contraseña debe contener mínimo 6 caracteres';
        }

        if ($this->password !== $this->password2) {
            self::$alertas['error'][] = 'Las contraseñas son diferentes';
        }

        return self::$alertas;
    }


    //VALIDAR EL FORMULARIO DE EDITAR PERFIL
    public function validar_perfil() : array{
        if (!$this->nombre) {
            self::$alertas['error'][] = 'El nombre es obligatorio';
        }

        if (!$this->apellidos) {
            self::$alertas['error'][] = 'Los apellidos son obligatorios';
        }

        if (!$this->email) {
            self::$alertas['error'][] = 'El correo es obligatorio';
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Correo no válido';
        }

        return self::$alertas;
    }


    //COMPROBAR QUE EL PASSWORD ACTUAL ES IGUAL AL HASHEADO
    public function comprobarPassword() : bool{
        return password_verify($this->password_actual, $this->password);
    }


    //HASHEAR EL PASSWORD
    public function hashPassword() : void{
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    //GENERAR UN TOKEN
    public function crearToken() : void{
        $this->token = uniqid();
    }


    //VALIDAR FORMULARIO DE CAMBIAR PASSWORD
    public function nuevoPassword(){
        if (!$this->password_actual) {
            self::$alertas['error'][] = 'La contraseña actual no puede estar vacía';
        }

        if (!$this->password_nuevo) {
            self::$alertas['error'][] = 'La contraseña nueva no puede estar vacía';
        }

        if (strlen($this->password_nuevo) < 6) {
            self::$alertas['error'][] = 'La contraseña debe contener mínimo 6 caracteres';
        }

        return self::$alertas;
    }
}
