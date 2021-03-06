<?php
require_once 'model/Registro.php';

class RegistroController{
    
    /**
     * Idioma seleccionado.
     * Por lo tanto lo habla o escribe.
     * 
     * @var int
     */
    const SELECTED     = 1;

    /**
     * Idioma no seleccionado.
     * 
     * @var int
     */
    const NOT_SELECTED = 2;
    private $model;
    
    public function __CONSTRUCT(){
        $this->model = new Registro();
    }
    
    /**
     * Retorna el formulario de inicio de sesión.
     *
     * @return view
     */
    public function Index(){
        // require_once 'view/header_index.php';
        // require_once 'view/Registro/index.php';
        // require_once 'view/footer.php';
        require_once 'view/auth/login2.php';
    }

    /**
     * Finaliza la sesión actual
     *
     * @return void
     */
    public function cerrar(){
        // require_once 'view/header.php';
        require_once 'view/Registro/cerrar.php';
        // require_once 'view/footer.php';
    }

    /**
     * Retorna la pantalla de bienvenida al sistema
     *
     * @return view
     */
    public function Principal(){
        // require_once 'view/header.php';
        // require_once 'view/Registro/Principal.php';
        // require_once 'view/footer.php';

        require_once 'view/medics/create.php';
    }

    /**
     * Retorna el listado de médicos registrados
     *
     * @return view
     */
    public function MT(){
        // require_once 'view/header.php';
        // require_once 'view/Registro/Registro_Medico.php';
        // require_once 'view/footer.php';
        require_once 'view/medics/index.php';
    }
    
    /**
     * Retorna el formulario de registro de usuarios y una lista
     * de todos los que han sido creados.
     * 
     *
     * @return view
     */
    public function usuarios() 
    {
        $rolController = new App\Controllers\RolController;
        $roles = $rolController->getAllRoles();

        $userController = new App\Controllers\UserController;
        $users = $userController->getAllUsers();

        require_once 'view/users/index.php';
    }

    /**
     * Metodo encargado de procesar la solicitud para guardar 
     * un nuevo usuario o actualizarlo.
     * 
     * Redirecciona a la vista de usuarios.
     * 
     * @param array $_REQUEST
     * @return void
     */
    public function guardarUsuario()
    {
        $user = new \App\Models\User;
        
        $user->Seq_Usuario = $_REQUEST['Seq_Usuario'];
        $user->Usuario     = $_REQUEST['username'];
        $user->Password    = $_REQUEST['password'];
        $user->sys_rol_id  = $_REQUEST['rolID'];


        $userController = new App\Controllers\UserController;

        if($user->Seq_Usuario > 0) {
            $result = $userController->updateUser($user);
        } else {
            $result = $userController->createUser($user);
        }
        
        /**
         * Variable de sesión usada para mostrar la notificación del
         * resultado de la solicitud.
         */
        $_SESSION['result'] = $result;
        
        header('Location: ?c=Registro&a=usuarios');
    }

    /**
     * Metodo encargado de procesar la solicitud para eliminar 
     * un usuario existente.
     * 
     * Retorna el formulario para editar usuarios.
     * 
     * @param string $_REQUEST['id']
     * @return view
     */
    public function eliminarUsuario()
    {
        if(isset($_REQUEST['id'])){

            $userController = new App\Controllers\UserController;
            $result = $userController->deleteUser($_REQUEST['id']);
            
            /**
             * Variable de sesión usada para mostrar la notificación del
             * resultado de la solicitud.
             */
            $_SESSION['result'] = $result;
        }

        header('Location: ?c=Registro&a=usuarios');

    }
    /**
     * Metodo encargado de procesar la solicitud para editar 
     * un usuario existente.
     * 
     * Retorna el formulario para editar usuarios.
     * 
     * @param string $_REQUEST['id']
     * @return view
     */
    public function editarUsuario()
    {
        if(isset($_REQUEST['id'])){

            $rolController = new App\Controllers\RolController;
            $roles = $rolController->getAllRoles();

            $userController = new App\Controllers\UserController;
            $user = $userController->getUser($_REQUEST['id']);
            
            require_once 'view/users/edit.php';
        } else {

            header('Location: ?c=Registro&a=usuarios');
        }

    }

   public function Buscar(){
        require_once 'view/header.php';
        require_once 'view/Registro/Buscar_Medico.php';
        require_once 'view/footer.php';
    }

    public function Provincia(){
        require_once 'view/header.php';
        require_once 'view/Registro/Listar_Provincias.php';
        require_once 'view/footer.php';
    }

     public function Municipio(){
        require_once 'view/header.php';
        require_once 'view/Registro/Listar_Municipio.php';
        require_once 'view/footer.php';
    }

    public function getAllDepartaments()
    {
        $td     = new \App\Controllers\TerritorialDistributionController();
        $result = $td->getAllDepartaments();
        
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($result);
    }

    public function getProvinceByDepartament()
    {
        $departamento_id = $_GET["code"];
        
        $td     = new \App\Controllers\TerritorialDistributionController();
        $result = $td->getProvinceByDepartament($departamento_id);

        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($result);
    }
 
    public function getMunicipioByProvince()
    {
        $provincia_id = $_GET["code"];
        
        $td     = new \App\Controllers\TerritorialDistributionController();
        $result = $td->getMunicipioByProvince($provincia_id);

        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($result);
    }

    public function Crud(){
        // $alm = new Registro();
         
        // if(isset($_REQUEST['Seq_Registro'])){
        //     $alm = $this->model->Obtener($_REQUEST['Seq_Registro']);
        // }
        
        // require_once 'view/header.php';
        // require_once 'view/Registro/Nuevo_registro.php';
        // require_once 'view/footer.php';
        require_once 'view/medics/create.php';
    }
    
     public function Editar(){
        $alm = new Registro();
        
        if(isset($_REQUEST['Seq_Registro'])){
            $alm = $this->model->Obtener($_REQUEST['Seq_Registro']);
            
            $td = new \App\Controllers\TerritorialDistributionController();
            $departamentos = $td->getAllDepartaments();
            $provincias    = $td->getProvinceByDepartament($alm->Departamento_Seq);
            $municipios    = $td->getMunicipioByProvince($alm->Provincia_Seq);
        }
        
        // require_once 'view/header.php';
        // require_once 'view/Registro/Modificar_registro.php';
        // require_once 'view/footer.php';
        require_once 'view/medics/edit.php';
    }

    public function getRegister()
    {
        $register_id = $_GET["code"];

        $td     = new \App\Controllers\RegisterController();
        $result = $td->getRegister($register_id);

        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($result);
    }

    public function Imagen(){
        $alm = new Registro();
        
        if(isset($_REQUEST['Seq_Registro'])){
            $alm = $this->model->Obtener($_REQUEST['Seq_Registro']);
        }
        
        require_once 'view/header.php';
        require_once 'view/Registro/registro_imagen.php';
        require_once 'view/footer.php';
    }
     public function Datos_Medico(){
        $alm = new Registro();
        
        if(isset($_REQUEST['Seq_Registro'])){
            $alm = $this->model->Obtener($_REQUEST['Seq_Registro']);
        }
        require_once 'view/Registro/pdf.php';
    }
    
    public function imprimir(){
        $alm = new Registro();
        
        if(isset($_REQUEST['Seq_Registro'])){
            $alm = $this->model->Obtener($_REQUEST['Seq_Registro']);
        }
        /*require_once 'view/Registro/app/reporte/print.php';
             require_once 'view/Registro/app/reporte/pdf.php';*/
        require_once 'view/Registro/app/reporte/productos.php';
        
    }

    public function pruebas()
    {        
        $td = new \App\Controllers\TerritorialDistributionController();
        $result = $td->getMunicipioByProvince(2);

        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($result); die();
    }

    public function Guardar(){
        $alm = new Registro();
        
        $alm->Seq_Registro = $_REQUEST['Seq_Registro'];
        $alm->Departamento_Seq= $_REQUEST['Departamento_Seq'];
        $alm->Provincia_Seq= $_REQUEST['Provincia_Seq'];
        $alm->Municipio_Seq= $_REQUEST['Municipio_Seq'];
        $alm->Comunidad= $_REQUEST['Comunidad'];
        $alm->Organizacion_Pertenece= $_REQUEST['Organizacion_Pertenece'];
        $alm->Especialedad_Seq= $_REQUEST['Especialedad_Seq'];
        $alm->SubEspecialidad_Seq= $_REQUEST['SubEspecialidad_Seq'];
        $alm->Primer_Nombre= $_REQUEST['Primer_Nombre'];
        $alm->Segundo_Nombre= $_REQUEST['Segundo_Nombre'];
        $alm->Primer_Apellido= $_REQUEST['Primer_Apellido'];
        $alm->Segundo_Apellido= $_REQUEST['Segundo_Apellido'];  
        $alm->Tipo_Documento= $_REQUEST['Tipo_Documento'];
        $alm->Numero_Documento= $_REQUEST['Numero_Documento'];
        $alm->complemento= $_REQUEST['complemento'];
        $alm->Expedido= $_REQUEST['Expedido'];
        $alm->Estado_Civil= $_REQUEST['Estado_Civil'];     
        $alm->Sexo= $_REQUEST['Sexo'];
        $alm->Fecha_Nacimiento= date('Y-m-d', strtotime($_REQUEST['Fecha_Nacimiento']));

        // Idioma
        $alm->Castellano  = ($_REQUEST['Lee'] == 1 || $_REQUEST['Escribe'] == 1) ? self::SELECTED : self::NOT_SELECTED ;
        $alm->Lee         = $_REQUEST['Lee'];
        $alm->Escribe     = $_REQUEST['Escribe'];

        $alm->Quechua     = ($_REQUEST['Lee_Quechua'] == 1 || $_REQUEST['EscribeQ'] == 1) ? self::SELECTED : self::NOT_SELECTED ;
        $alm->Lee_Quechua = $_REQUEST['Lee_Quechua'];
        $alm->EscribeQ    = $_REQUEST['EscribeQ'];

        $alm->Aymara      = ($_REQUEST['LeeA'] == 1 || $_REQUEST['EscribeA'] == 1) ? self::SELECTED : self::NOT_SELECTED ;
        $alm->LeeA        = $_REQUEST['LeeA'];
        $alm->EscribeA    = $_REQUEST['EscribeA'];

        $alm->Guarani     = ($_REQUEST['LeeG'] == 1 || $_REQUEST['EscribeG'] == 1) ? self::SELECTED : self::NOT_SELECTED ;
        $alm->LeeG        = $_REQUEST['LeeG'];
        $alm->EscribeG    = $_REQUEST['EscribeG'];

        $alm->Otro        = ($_REQUEST['LeeO'] == 1 || $_REQUEST['EscribeO'] == 1) ? self::SELECTED : self::NOT_SELECTED ;
        $alm->LeeO        = $_REQUEST['LeeO'];
        $alm->EscribeO    = $_REQUEST['EscribeO'];        
        $alm->Descripcion_idiomaO= $_REQUEST['Descripcion_idiomaO'];
        
        $alm->Direccion_vive= $_REQUEST['Direccion_vive'];
        $alm->Telefono= $_REQUEST['Telefono'];
        $alm->Celular= $_REQUEST['Celular'];
        $alm->Tipo_Trabajo= $_REQUEST['Tipo_Trabajo'];
        $alm->Direccion_Trabajo= $_REQUEST['Direccion_Trabajo'];
        $alm->Anos_Experiencia_Especialidad= $_REQUEST['Anos_Experiencia_Especialidad'];
        $alm->Anos_Experiencia_Sub_Especialidad= $_REQUEST['Anos_Experiencia_Sub_Especialidad'];
        $alm->Numero_Pacientes= $_REQUEST['Numero_Pacientes'];
        $alm->Codigo_Formulario= $_REQUEST['Codigo_Formulario'];
        $alm->Fecha_Formulario= date('Y-m-d', strtotime($_REQUEST['Fecha_Formulario']));
        $alm->HoraRegistro_Formulario  = isset($_REQUEST['HoraRegistro_Formulario']) ? $_REQUEST['HoraRegistro_Formulario'] : date(" H:i:s ",time()-3909600) ;
        $alm->FechaRegistro_Formulario = isset($_REQUEST['FechaRegistro_Formulario']) ? $_REQUEST['FechaRegistro_Formulario'] : date(" d-m-Y ", time()) ;
        $alm->Certificado_Naci= 1; //$_REQUEST['Certificado_Naci'];
        
        
        // $alm->Seq_Registro > 0 
        //     ? $this->model->Actualizar($alm)
        //     : $this->model->Registrar($alm);

        if($alm->Seq_Registro > 0) {
            $this->model->Actualizar($alm);

            $_SESSION['result'] = [
                'status'  => 'success',
                'message' => 'Médico actualizado satisfactoriamente!',
                'notification' => 'success'
            ];
        } else {
            $this->model->Registrar($alm);

            $_SESSION['result'] = [
                'status'  => 'success',
                'message' => 'Médico registrado satisfactoriamente!',
                'notification' => 'success'
            ];
        }
        
        header('Location: ?c=Registro&a=MT');
    }
    
    public function Eliminar(){
        $this->model->Eliminar($_REQUEST['Seq_Registro']);
        header('Location: ?c=Registro&a=MT');
    }
 
    public function validar(){

        if (isset($_POST['NUsuario']) and isset($_POST['Pass']))
        {
            include ('model/conexion.php');
            $Nombredeusuario = mysqli_real_escape_string($mysqli,$_POST['NUsuario']);
            $possword        = mysqli_real_escape_string($mysqli,$_POST['Pass']);
            $comprobar       = 'select * from sys_usuario WHERE Usuario ="'.$Nombredeusuario.'"';
            $comprobacion    = $mysqli->query($comprobar);

            if($comprobacion->num_rows > 0)
            {
                $consulta_BD = mysqli_query($mysqli, 'Select Password from sys_usuario where Usuario ="'.$Nombredeusuario.'"');
                $recoger     = mysqli_fetch_assoc($consulta_BD);
                $comprobar_Password = password_verify($possword, $recoger['Password']);
                
                if($comprobar_Password)
                {
                    // session_start(); 
                    $_SESSION['Usuario']=$Nombredeusuario;
                    $_SESSION['authenticated'] = true;

                    // Registrar el inicio de sesión.
                    $userController = new App\Controllers\UserController;
                    $users = $userController->registerSessionStart();

                    header('Location: ?c=Registro&a=Principal');
                }
                else
                {
                   // print 'los datos osn incorrectos <a href="index.php">Volver</a>';
                    $_SESSION['error']="erroro";
                    header('Location: ?c=Registro&a=index');
                }
            }
            else
            {
                print 'verificar los datos introduciodos <a href="index.php">Volver</a>';
                 $_SESSION['error']="erroro";
                    header('Location: ?c=Registro&a=index');
            }
        }
        else
        {
            header('location: ./');
            print 'noooooooooooo <a href="index.php">Volver</a>';
        }
    }
}


