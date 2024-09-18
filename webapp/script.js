function comprar(idLancheira) {
    Swal.fire({
        title: 'Você precisa estar logado',
        text: "Faça login ou registre-se para continuar.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Login',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'login.php';
        }
    });
}
