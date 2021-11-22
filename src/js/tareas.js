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

        //AÑADIR AL BODY
        document.querySelector('body').appendChild(modal);
        
    }
})();//este (), ejecuta esta funcion inmediatamente