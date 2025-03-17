document.addEventListener("DOMContentLoaded", function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const currentPath = window.location.pathname;

    if (currentPath.includes("motoristas")) {
        document.querySelectorAll(".delete-btn").forEach(button => {
            button.addEventListener("click", function () {
                let motoristaId = this.getAttribute("data-id");
                Swal.fire({
                    title: "Tem certeza?",
                    text: "Essa ação não pode ser desfeita!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Sim, excluir!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/motoristas/delete/${motoristaId}`, {
                            method: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": csrfToken,
                                "Content-Type": "application/json"
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire("Excluído!", data.success, "success").then(() => location.reload());
                            } else {
                                Swal.fire("Erro!", data.error, "error");
                            }
                        })
                        .catch(() => Swal.fire("Erro!", "Ocorreu um problema ao excluir o motorista.", "error"));
                    }
                });
            });
        });
    } if (currentPath.includes("motoristas/edit")) {
        const motoristaForm = document.getElementById("motoristaForm");
        if (motoristaForm) {
            motoristaForm.addEventListener("submit", function (event) {
                event.preventDefault();

                const motoristaId = document.getElementById("motorista_id") ? document.getElementById("motorista_id").value : null;
                let formData = new FormData(this);
                formData.append("_method", "PUT");

                fetch(`/motoristas/${motoristaId}`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: formData
                })
                .then(response => response.json().then(data => ({
                    status: response.status,
                    body: data
                })))
                .then(({ status, body }) => {
                    if (status === 200) {
                        Swal.fire("Sucesso!", body.success, "success").then(() => {
                            window.location.href = "/motoristas";
                        });
                    } else if (status === 422) {
                        let errorMessage = Object.values(body.errors).flat().join('<br>');
                        Swal.fire("Erro de Validação", errorMessage, "error");
                    } else {
                        Swal.fire("Erro!", body.error || "Ocorreu um problema ao atualizar o motorista.", "error");
                    }
                })
                .catch(() => {
                    Swal.fire("Erro!", "Ocorreu um problema inesperado.", "error");
                });
            });
        }
    } if (currentPath.includes("motoristas/create")) {
        const motoristaForm = document.getElementById("motoristaForm");
        if (motoristaForm) {
            motoristaForm.addEventListener("submit", function (event) {
                event.preventDefault();

                let nome = document.getElementById("nome").value;
                let data_nascimento = document.getElementById("data_nascimento").value;
                let cnh = document.getElementById("cnh").value;

                fetch("/motoristas/store", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        nome: nome,
                        data_nascimento: data_nascimento,
                        cnh: cnh
                    })
                })
                .then(response => response.json().then(data => ({
                    status: response.status,
                    body: data
                })))
                .then(({ status, body }) => {
                    if (status === 200) {
                        Swal.fire("Sucesso!", body.success, "success").then(() => {
                            window.location.href = "/motoristas";
                        });
                    } else if (status === 422) {
                        let errorMessage = Object.values(body.errors).flat().join('<br>');
                        Swal.fire("Erro de Validação", errorMessage, "error");
                    } else {
                        Swal.fire("Erro!", body.error || "Ocorreu um problema ao cadastrar o motorista.", "error");
                    }
                })
                .catch(() => {
                    Swal.fire("Erro!", "Ocorreu um problema inesperado ao cadastrar o motorista.", "error");
                });
            });
        }
    }
});