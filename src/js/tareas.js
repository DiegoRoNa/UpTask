//IIFE

//TODO DENTRO DE ESTE CALLBACK, SE PROTEGE, ES DECIR:
//TOAS VARIABLES Y FUNCIONES DEFINIDAS EN ESTE ARCHIVO, NO PUEDEN SER EJECUTADAS DESDE OTRO
(function(){

    //OBTENER TAREAS en la vista proyecto.php
    obtenerTareas();
    let tareas = [];//DOM VIRTUAL: PASO 1
    let filtradas = [];//arreglo para los filtros

    //boton para mostrar el modal de agregar tarea
    const nuevaTareabtn = document.querySelector('#agregar-tarea');
    nuevaTareabtn.addEventListener('click', function() {
        mostrarFormulario();
    });

    //FILTROS DE BUSQUEDA
    const filtros = document.querySelectorAll('#filtros input[type="radio"]');

    //Hiterar sobre los input para añadirles un evento
    filtros.forEach( radio => {
        radio.addEventListener('input', filtrarTareas);
    });

    function filtrarTareas(e){
        //VALUE DE LOS INPUT
        const filtro = e.target.value;
        
        //Si el filtro es direfente a TODAS
        if (filtro !== '') {
            //FILTER: Hiteramos y creamos un nuevo arreglo con cada tarea por el estado 
            filtradas = tareas.filter( tarea => tarea.estado === filtro);
        }else{
            filtradas = [];
        }

        mostrarTareas();
        
    }
    

    async function obtenerTareas(){
        //CONECTAR A LA API
        try {
            //OBTENER LA url DEL PROYECTO
            const id = obtenerProyecto();

            //URL DE LA API
            const url = `http://localhost:3000/api/tareas?id=${id}`;
            
            //CONECTAR A LA API
            const resultado = await fetch(url);

            //OBTENER RESPUESTA DE LA API
            const respuesta = await resultado.json();//OBJECT
            
            //DOM VIRTUAL: PASO 2
            //ASIGNAR LAS TAREAS EN EL ARREGLO DECLARADO AL INICIO
            tareas = respuesta.tareas;
            mostrarTareas();
            
        } catch (error) {
            console.log(error);
            
        }
    }


    function mostrarTareas(){

        //DOM VIRTUAL: PASO 5
        //Limipiar el html donde se muentran las tareas
        limpiarTareas();

        //CALCULAR TAREAS PENDIENTES Y COMPLETAS
        totalPendientes();
        totalCompletas();

        //ASGINAMOS EL ARREGLO QUE CORRESPONDA AL FILTRO
        //SI filtradas CONTIENE ALGO, arrayTareeas CONTIENE EL VALOR DE filtradas
        //SI NO, CONTIENE EL VALOR DE tareass
        const arrayTareas = filtradas.length ? filtradas : tareas;

        if (arrayTareas.length === 0) {
            const contenedorTareas = document.querySelector('#listado-tareas');//ul
            const textoNoTareas = document.createElement('LI');
            textoNoTareas.textContent = 'No hay ninguna tarea en este proyecto';
            textoNoTareas.classList.add('no-tareas');

            contenedorTareas.appendChild(textoNoTareas);
            return;
        }

        //ESTADO PARA LAS TAREAS
        const estados = {
            0: 'Pendiente',
            1: 'Completa'
        }

        //RECORRER EL OBJETO Y MOSTRAR LA INFORMACION DE CADA TAREA
        arrayTareas.forEach(tarea => {
            //li de la lista
            const contenedorTarea = document.createElement('LI');
            contenedorTarea.dataset.tareaId = tarea.id;//data-cy
            contenedorTarea.classList.add('tarea');

            //nombre de la tarea
            const nombreTarea = document.createElement('P');
            nombreTarea.textContent = tarea.nombre;
            nombreTarea.ondblclick = function(){
                mostrarFormulario(true, {...tarea});
            }

            //CONTENEDOR DE LOS BOTONES
            const opcionesDiv = document.createElement('DIV');
            opcionesDiv.classList.add('opciones');
        
            //BOTONES PARA MOSTRAR EL ESTADO DE LA TAREA
            const btnEstadoTarea = document.createElement('BUTTON');
            btnEstadoTarea.classList.add('estado-tarea');
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`);
            btnEstadoTarea.textContent = estados[tarea.estado];
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;
            btnEstadoTarea.ondblclick = function() {

                //CREAMOS UNA COPIA DE LA TAREA PARA NO SOBRE ESCRIBIR EL OBJETO ACTUAL
                cambiarEstadoTarea({...tarea});
            }
          
            //BOTONES PARA ELIMINAR UNA TAREA
            const btnEliminarTarea = document.createElement('BUTTON');
            btnEliminarTarea.classList.add('eliminar-tarea');
            btnEliminarTarea.dataset.idTarea = tarea.id;
            btnEliminarTarea.textContent = 'Eliminar';
            btnEliminarTarea.ondblclick = function(){
                confirmarEliminarTarea({...tarea});
            }

            //AGREGAR LOS BOTOTNES AL CONTENEDOR
            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);

            //AGREGAR CONTENEDOR AL li
            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);

            //AGREGAR li AL ul
            const listadoTareas = document.querySelector('#listado-tareas');//ul
            listadoTareas.appendChild(contenedorTarea);

        })
        
    }

    //FUNCIONES PARA CALCULAR TAREAS PENDIENTES Y COMPLETAS
    function totalPendientes(){
        const totalPendientes = tareas.filter( tarea => tarea.estado === '0');
        const pendientesRadio = document.querySelector('#pendientes');

        //validar si hay elementos en el arreglo de totalPendientes
        if (totalPendientes.length === 0) {
            pendientesRadio.disabled = true;
        }else{
            pendientesRadio.disabled = false;
        }
    }

    function totalCompletas(){
        const totalCompletas = tareas.filter( tarea => tarea.estado === '1');
        const completadasRadio = document.querySelector('#completadas');

        //validar si hay elementos en el arreglo de totalPendientes
        if (totalCompletas.length === 0) {
            completadasRadio.disabled = true;
        }else{
            completadasRadio.disabled = false;
        }
    }


    //FUNCION PARA MOSTRAR EL FORMULARIO EN EL MODAL
    function mostrarFormulario(editar = false, tarea){
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `
            <form action="" method="POST" class="formulario nueva-tarea">
                <legend>${editar ? 'Cambia el nombre de la tarea' : 'Añade una nueva tarea'}</legend>

                <div class="campo">
                    <label for="tarea">Tarea</label>
                    <input 
                        type="text" 
                        name="tarea" 
                        id="tarea" 
                        value="${editar ? tarea.nombre : ''}" 
                        placeholder="${editar ? 'Nuevo nombre' : 'Añadir tarea al proyecto actual'}"
                    >
                </div>

                <div class="opciones">
                    <input 
                        type="submit" 
                        class="submit-nueva-tarea" 
                        value="${editar ? 'Actualizar' : 'Añadir tarea'}"
                    >
                    <button type="button" class="cerrar-modal">Cancelar</button>
                </div>
            </form>
        `;

        setTimeout(() => {
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
        }, 0);

        //CERRAR MODAL CON DELEGATION
        modal.addEventListener('click', function(e){
            e.preventDefault();

            //DENTRO DE TODO EL MODAL, SELECCIONAR EL BOTON CANCELAR
            if (e.target.classList.contains('cerrar-modal')) {

                const formulario = document.querySelector('.formulario');
                    formulario.classList.add('cerrar');
                setTimeout(() => {
                    modal.remove();    
                }, 500);
 
            }

            //DENTRO DE TODO EL MODAL, SELECCIONAR EL BOTON AÑADIR TAREA
            if (e.target.classList.contains('submit-nueva-tarea')) {

                const nombreTarea = document.querySelector('#tarea').value.trim();//valor del input
        
                //VALIDAR EL FORMULARIO
                if (nombreTarea === '') {
                    //MOSTRAR UNA ALERTA DE ERROR
                    mostrarAlerta('El nombre de la tarea es obligatorio', 'error', document.querySelector('.formulario legend'));
                    return; //no haga el script de abajo   
                }

                //EVALUAR SI SE ESTA EDITANDO O CREANDO TAREA
                if (editar) {
                    tarea.nombre = nombreTarea;
                    actualizarTarea(tarea);
                }else{
                    agregarTarea(nombreTarea);
                }
 
            }
        });

        //AÑADIR AL BODY
        document.querySelector('.dashboard').appendChild(modal);
        
    }


    //ALERTA DE INPUT VACIO
    function mostrarAlerta(mensaje, tipo, referencia){
        //PREVENIR CREAR MUCHAS ALERTAS
        const alertaPrevia = document.querySelector('.alerta');
        if (alertaPrevia) {
            alertaPrevia.remove();
        }

        const alerta = document.createElement('DIV');
        alerta.classList.add('alerta', tipo);
        alerta.textContent = mensaje;

        //INSERTAR LA ALERTA DESPUÉS DEL LEGEND
        referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);

        //ELIMINAR LA ALERTA DESPUES DE 5 SEGUNDOS
        setTimeout(() => {
            alerta.remove();
        }, 5000);

    }

    //CONSULTAR API, PARA AÑADIR UNA NUEVA TAREA AL PROYECTO
    async function agregarTarea(tarea){
        //formdata siempre se usa para hacer peticiones
        const datos = new FormData();

        //Agregar la informacion a formData para enviar al BACKEND
        //LEER LA URL, PARA PASARLE id= AL BACKEND
        datos.append('idProyecto', obtenerProyecto());
        datos.append('nombre', tarea);
    
        //SIEMPRE USAR TRY-CATCH PARA HACER CONEXIONES WEBSERVICE
        try {
            //PRIMER await: CONEXION A LA API
            const url = 'http://localhost:3000/api/tarea';
            const resultado = await fetch(url, {
                method: 'POST',
                body: datos
            });

            //OBTENER LA RESPUESTA DEL BACKEND (API)
            const respuesta = await resultado.json();

            //MOSTRAR UNA ALERTA DE ERROR SI EL PROYECTO NO ES EL CORRECTO
            mostrarAlerta(respuesta.mensaje, respuesta.tipo, document.querySelector('.formulario legend'));
             
            if (respuesta.tipo === 'exito') {
                const modal = document.querySelector('.modal');
                setTimeout(() => {
                    modal.remove();
                }, 2000);
            }

            //DOM VIRTUAL: PASO 3
            //AGREGAR EL OBJETO DE TAREA AL GLOBAL DE TAREAS, IDENTIDO AL DE LA BD
            const tareaObj = {
                id: String(respuesta.id),
                idProyecto: respuesta.idProyecto,
                nombre: tarea,
                estado: '0'
            }
            //DOM VIRTUAL: PASO 4
            //CREAR UNA COPIA DE tareas AGREGANDO tareaObj
            tareas = [...tareas, tareaObj];
            mostrarTareas();
            
            
        } catch (error) {
            console.log(error);
            
        }
    }

    //CAMBIAR EL ESTADO DE LA TAREA
    function cambiarEstadoTarea(tarea){

        //SI estado ES IGUAL A 1, PONLO EN 0, SI NO ES IGUAL A 1, PONLO EN 1
        const nuevoEstado = tarea.estado === "1" ? "0" : "1";
        tarea.estado = nuevoEstado;
        actualizarTarea(tarea);
        
    }


    async function actualizarTarea(tarea){
        
        //CREAR EL FORMDATA() PARA MANDARLE LA INFORMACION NUEVA A LA API
        const { id, idProyecto, nombre, estado } = tarea;

        const datos = new FormData();
        datos.append('id', id);
        datos.append('idProyecto', obtenerProyecto());//url del proyecto
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        
        /**PARA PODER VISUALIZAR LOS DATOS QUE SE ENVIARÁN AL BACKEND
         * for( let valor of datos.values()){
            console.log(valor);
        }
         */

        //CONEXION A LA API
        try {
            const url = 'http://localhost:3000/api/tarea/actualizar';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            
            //OBTENER LA RESPUESTA DEL BACKEND
            const resultado = await respuesta.json();
            
            if (resultado.respuesta.tipo === 'exito') {
                Swal.fire(
                    'Actualizado',
                    resultado.respuesta.mensaje,
                    'success'
                )

                //QUITAR EL MODAL DEL FORMULARIO
                const modal = document.querySelector('.modal');
                if (modal) {
                    modal.remove();
                }

                //ACTUALIZAR LA TAREA EN TIEMPO REAL DOM VIRTUAL
                tareas = tareas.map(tareaMemoria => {//hiteramos el arreglo para crear uno nuevo
                    //Evaluar si el id de la tarea es igual al que se le da doble click
                    if(tareaMemoria.id === id) {
                        //cambiar el estado y nombre al valor nuevo del doble click
                        tareaMemoria.estado = estado;
                        tareaMemoria.nombre = nombre;
                    } 

                    //Retornar la tarea actualizada
                    return tareaMemoria;
                });
                    
                //Mostrar las tareas para generar de nuevo el html
                mostrarTareas();
            }
  
        } catch (error) {
            console.log(error);
            
        }
    }


    function confirmarEliminarTarea(tarea){
        Swal.fire({
            title: '¿Eliminar tarea?', 
            showCancelButton: true,
            confirmButtonText: 'Sí',
            cancelButtonText: 'No'
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                eliminarTarea(tarea);
            }
        });
    }


    async function eliminarTarea(tarea){

        //CREAR EL FORMDATA() PARA MANDARLE LA INFORMACION NUEVA A LA API
        const { id, nombre, estado } = tarea;

        const datos = new FormData();
        datos.append('id', id);
        datos.append('idProyecto', obtenerProyecto());//url del proyecto
        datos.append('nombre', nombre);
        datos.append('estado', estado);

        //CONECTAR CON LA API PARA ELIMINAR
        try {
            const url = 'http://localhost:3000/api/tarea/eliminar';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });

            //OBTENER LA RESPUESTA DEL BACKEND
            const resultado = await respuesta.json();

            if (resultado.resultado) {

                //MOSTRAR MENSAJE DE EXITO
                Swal.fire('Eliminado', resultado.mensaje, 'success');

                //DOM VIRTUAL PARA ELIMINACION DINAMICA
                //filter(): Creará un arreglo nuevo con las tareas que tienen ID diferente al que vamos a eliminar
                tareas = tareas.filter( tareaMemoria => tareaMemoria.id !== tarea.id );
                mostrarTareas();
            }
            
            
        } catch (error) {
            console.log(error);
            
        }
    }


    function obtenerProyecto(){
        //LEER LA URL, PARA PASARLE id= AL BACKEND
        const proyectoParams = new URLSearchParams(window.location.search);//objeti
        const proyecto = Object.fromEntries(proyectoParams.entries());
        return proyecto.id;
    }

    //LIMPIAR EL DOM DONDE SE MUESTRAN LAS TAREAS
    function limpiarTareas(){
        const listadoTareas = document.querySelector('#listado-tareas');

        while (listadoTareas.firstChild) {//mientras haya elementos
            listadoTareas.removeChild(listadoTareas.firstChild);//elimina el primer elemento
        }
    }
})();//este (), ejecuta esta funcion inmediatamente