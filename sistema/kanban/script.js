const tarea = {
    id: '',
    nombre: '',
    descripcion: '',
    responsable: ''
}

let isEditando = false
let isValido = false

function drag(event) {
    event.dataTransfer.setData("text", event.target.id)
}

function allowDrop(event) {
    event.preventDefault()
}

function drop(event) {
    event.preventDefault()
    const data = event.dataTransfer.getData("text")
    event.currentTarget.appendChild(document.getElementById(data))
}

function crearTarea(event) {
    event.preventDefault()

    validarCampos(
        document.getElementById("tarea-nombre").value,
        document.getElementById("tarea-descripcion").value
    )

    if(isValido) {
        if (isEditando) {
            const divTarea = document.getElementById(tarea.id)
            divTarea.childNodes[0].textContent = document.getElementById("tarea-nombre").value
            divTarea.childNodes[1].textContent = document.getElementById("tarea-descripcion").value
            divTarea.childNodes[2].textContent = document.getElementById("tarea-responsable").value

            const btnEditar = document.getElementById("btn-crear-editar")
            btnEditar.value = "Crear Tarea"
            btnEditar.classList.remove('btn-editar')
            btnEditar.classList.add('btn-crear')
        } else {
            tarea.nombre = document.getElementById("tarea-nombre").value
            tarea.descripcion = document.getElementById("tarea-descripcion").value
            tarea.responsable = document.getElementById("tarea-responsable").value
            registrarTarea()
        }
    }

    limpiarCampos()
    limpiarObj()
}

function limpiarCampos() {
    document.getElementById("tarea-nombre").value = ''
    document.getElementById("tarea-descripcion").value = ''
    document.getElementById("tarea-responsable").value = ''
    
} 

function limpiarObj() {
    tarea.id = ''
    tarea.nombre = ''
    tarea.descripcion = ''
    tarea.responsable = ''

    isValido = false
    isEditando = false
}

function validarCampos(nombre, descripcion) {
    if (nombre === '' || descripcion === '') {
        alert('Debes asignar el nombre y la descripción de la tarea')
        isValido = false
    } else {
        isValido = true
    }
}

function registrarTarea() {
    tarea.id = new Date().getTime()

    const pendientes = document.getElementById("pendientes")

    const divTarea = document.createElement('div')
    divTarea.classList.add('tarea')
    divTarea.setAttribute('id', tarea.id)
    divTarea.setAttribute('draggable', true)
    divTarea.setAttribute('ondragstart', 'drag(event)')

    const pNombre = document.createElement('p')
    pNombre.setAttribute('id', 'nombre')
    pNombre.textContent = tarea.nombre

    const pDescripcion = document.createElement('p')
    pDescripcion.setAttribute('id', 'descripcion')
    pDescripcion.textContent = tarea.descripcion

    const pResponsable = document.createElement('p')    
    pResponsable.setAttribute('id', 'responsable')
    pResponsable.textContent = tarea.responsable

    const inputEditar = document.createElement('input')
    inputEditar.classList.add('btn-crear')
    inputEditar.setAttribute('type', 'submit')
    inputEditar.value = 'Editar'
    inputEditar.onclick = function() {
        isEditando = true
        tarea.id = divTarea.getAttribute('id')
        tarea.nombre = pNombre.textContent
        tarea.descripcion = pDescripcion.textContent
        tarea.responsable = pResponsable.textContent
        editarTarea()
    }

    const inputBorrar = document.createElement('input')
    inputBorrar.classList.add('btn-borrar')
    inputBorrar.setAttribute('type', 'submit')
    inputBorrar.value = 'Borrar'
    inputBorrar.onclick = function() {
        divTarea.remove()
    }

    divTarea.appendChild(pNombre)
    divTarea.appendChild(pDescripcion)
    divTarea.appendChild(pResponsable)
    divTarea.appendChild(inputEditar)
    divTarea.appendChild(inputBorrar)
    pendientes.appendChild(divTarea)
}

function editarTarea() {
    const btnEditar = document.getElementById("btn-crear-editar")
    btnEditar.value = "Editar Tarea"
    btnEditar.classList.remove('btn-crear')
    btnEditar.classList.add('btn-editar')

    document.getElementById("tarea-nombre").value = tarea.nombre
    document.getElementById("tarea-descripcion").value = tarea.descripcion
    document.getElementById("tarea-responsable").value = tarea.responsable
}
