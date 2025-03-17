document.addEventListener("DOMContentLoaded", function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const currentPath = window.location.pathname;
    if (currentPath.includes("veiculos")) {
        document.querySelectorAll(".delete-btn").forEach(button => {
            button.addEventListener("click", function () {
                let veiculoId = this.getAttribute("data-id");
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
                        fetch(`/veiculos/delete/${veiculoId}`, {
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
                        .catch(() => Swal.fire("Erro!", "Ocorreu um problema ao excluir o veículo.", "error"));
                    }
                });
            });
        });
    } if (currentPath.includes("veiculos/edit")) {
        const veiculoForm = document.getElementById("veiculoForm");
        if (veiculoForm) {
            veiculoForm.addEventListener("submit", function (event) {
                event.preventDefault();
                const veiculoId = document.getElementById("veiculo_id") ? document.getElementById("veiculo_id").value : null;
                let formData = new FormData(this);
                formData.append("_method", "PUT");
                fetch(`/veiculos/${veiculoId}`, {
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
                            window.location.href = "/veiculos";
                        });
                    } else if (status === 422) {
                        let errorMessage = Object.values(body.errors).flat().join('<br>');
                        Swal.fire("Erro de Validação", errorMessage, "error");
                    } else {
                        Swal.fire("Erro!", body.error || "Ocorreu um problema ao atualizar o veículo.", "error");
                    }
                })
                .catch(() => {
                    Swal.fire("Erro!", "Ocorreu um problema inesperado.", "error");
                });
            });
        }
    } if (currentPath.includes("veiculos/create")) {
        const veiculoForm = document.getElementById("veiculoForm");
        if (veiculoForm) {
            veiculoForm.addEventListener("submit", function (event) {
                event.preventDefault();
                let modelo = document.getElementById("modelo").value;
                let ano = document.getElementById("ano").value;
                let data_aquisicao = document.getElementById("data_aquisicao").value;
                let km_aquisicao = document.getElementById("km_aquisicao").value;
                let km_atual = document.getElementById("km_atual").value;
                let renavam = document.getElementById("renavam").value;
                let placa = document.getElementById("placa").value;
                fetch("/veiculos/store", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        modelo: modelo,
                        ano: ano,
                        data_aquisicao: data_aquisicao,
                        km_aquisicao: km_aquisicao,
                        km_atual: km_atual,
                        renavam: renavam,
                        placa: placa
                    })
                })
                .then(response => response.json().then(data => ({
                    status: response.status,
                    body: data
                })))
                .then(({ status, body }) => {
                    if (status === 200) {
                        Swal.fire("Sucesso!", body.success, "success").then(() => {
                            window.location.href = "/veiculos";
                        });
                    } else if (status === 422) {
                        let errorMessage = Object.values(body.errors).flat().join('<br>');
                        Swal.fire("Erro de Validação", errorMessage, "error");
                    } else {
                        Swal.fire("Erro!", body.error || "Ocorreu um problema ao cadastrar o veículo.", "error");
                    }
                })
                .catch(() => {
                    Swal.fire("Erro!", "Ocorreu um problema inesperado ao cadastrar o veículo.", "error");
                });
            });
        }
    }
});
