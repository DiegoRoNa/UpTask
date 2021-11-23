//IIFE

//TODO DENTRO DE ESTE CALLBACK, SE PROTEGE, ES DECIR:
//TOAS VARIABLES Y FUNCIONES DEFINIDAS EN ESTE ARCHIVO, NO PUEDEN SER EJECUTADAS DESDE OTRO
(function(){
    //boton para mostrar el modal de agregar tarea
    const nuevaTareabtn = document.querySelector('#agregar-tarea');
    nuevaTareabtn.addEventListener('click', mostrarFormulario);

    function mostrarFormulario(){
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `
            <form action="" method="POST" class="formulario nueva-tarea">
                <legend>Añade una nueva tarea</legend>

                <div class="campo">
                    <label for="tarea">Tarea</label>
                    <input type="text" name="tarea" id="tarea" placeholder="Añadir tarea al proyecto actual">
                </div>

                <div class="opciones">
                    <input type="submit" class="submit-nueva-tarea" value="Añadir tarea">
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

                submirFormularioNuevaTarea();
 
            }
        });

        //AÑADIR AL BODY
        document.querySelector('.dashboard').appendChild(modal);
        
    }

    function submirFormularioNuevaTarea(){
        const tarea = document.querySelector('#tarea').value.trim();//valor del input
        
        //VALIDAR EL FORMULARIO
        if (tarea === '') {
            //MOSTRAR UNA ALERTA DE ERROR
            mostrarAlerta('El nombre de la tarea es obligatorio', 'error', document.querySelector('.formulario legend'));
            return; //no haga el script de abajo   
        }

        //AGREGAR TAREA
        agregarTarea(tarea);
        
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
})();//este (), ejecuta esta funcion inmediatamente