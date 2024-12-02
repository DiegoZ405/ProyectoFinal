// Variables
const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
const IVA = 0.16;

// Función para agregar un producto al carrito
function agregarAlCarrito(producto) {
    const index = carrito.findIndex(item => item.nombre === producto.nombre);
    if (index !== -1) {
        carrito[index].cantidad += producto.cantidad;
    } else {
        carrito.push(producto);
    }
    guardarCarrito();
}

// Guardar carrito en localStorage
function guardarCarrito() {
    localStorage.setItem('carrito', JSON.stringify(carrito));
    renderizarCarrito();
}

// Renderizar carrito en la página
function renderizarCarrito() {
    const carritoContainer = document.querySelector('.table-responsive');
    const totalContainer = document.querySelector('.aside-checkout p.m-0.fs-5.fw-bold');
    if (!carritoContainer) return;

    carritoContainer.innerHTML = '';

    let subtotal = 0;

    carrito.forEach((producto, index) => {
        subtotal += producto.precio * producto.cantidad;

        const productRow = document.createElement('div');
        productRow.classList.add('row', 'mx-0', 'py-4', 'g-0', 'border-bottom');

        productRow.innerHTML = `
            <div class="col-2 position-relative">
                <picture class="d-block border">
                    <img class="img-fluid" src="${producto.imagen}" alt="${producto.nombre}">
                </picture>
            </div>
            <div class="col-9 offset-1">
                <div>
                    <h6 class="justify-content-between d-flex align-items-start mb-2">
                        ${producto.nombre}
                        <button onclick="eliminarDelCarrito(${index})" class="btn btn-sm btn-danger ms-3">
                            <i class="ri-close-line"></i>
                        </button>
                    </h6>
                    <span class="d-block text-muted fw-bolder text-uppercase fs-9">
                        Cantidad: ${producto.cantidad}
                    </span>
                </div>
                <p class="fw-bolder text-end text-muted m-0">
                    $${(producto.precio * producto.cantidad).toFixed(2)}
                </p>
            </div>
        `;

        carritoContainer.appendChild(productRow);
    });

    const totalIVA = subtotal * IVA;
    const total = subtotal + totalIVA;

    if (totalContainer) {
        totalContainer.textContent = `$${total.toFixed(2)} (Incluye IVA)`;
    }

    if (carrito.length === 0) {
        carritoContainer.innerHTML = `
            <p class="text-center text-muted">Tu carrito está vacío. ¡Agrega productos!</p>
        `;
    }
}

// Eliminar producto del carrito
function eliminarDelCarrito(index) {
    carrito.splice(index, 1);
    guardarCarrito();
}

// Inicializar carrito al cargar la página
document.addEventListener('DOMContentLoaded', renderizarCarrito);

function añadirProductoAlCarrito(event) {
    event.preventDefault();

    const boton = event.currentTarget;
    const producto = {
        nombre: boton.getAttribute('data-nombre'),
        precio: parseFloat(boton.getAttribute('data-precio')),
        imagen: boton.getAttribute('data-imagen'),
        cantidad: 1
    };

    agregarAlCarrito(producto);

    Swal.fire({
        title: '¡Producto añadido!',
        text: `"${producto.nombre}" se ha añadido al carrito.`,
        icon: 'success',
        timer: 2000,
        showConfirmButton: false
    });
}
