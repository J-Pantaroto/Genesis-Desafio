document.addEventListener("DOMContentLoaded", function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const currentPath = window.location.pathname;

    if (currentPath === "/viagens") {
        document.querySelectorAll(".edit-btn").forEach(button => {
            button.addEventListener("click", function (event) {
                event.preventDefault();
                let viagemId = this.getAttribute("data-id");
                fetch(`/viagens/edit/${viagemId}`, {
                    method: "GET",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept": "application/json"
                    }
                })
                    .then(response => {
                        let contentType = response.headers.get("content-type");

                        if (response.ok && contentType && contentType.includes("text/html")) {
                            window.location.href = `/viagens/edit/${viagemId}`;
                            return Promise.reject("redirected");
                        }
                        return response.json().then(data => ({ status: response.status, body: data }));
                    })
                    .then(({ status, body }) => {
                        if (status !== 200) {
                            Swal.fire("Erro!", body.error || "Ocorreu um problema ao tentar editar a viagem.", "error");
                        }
                    })
                    .catch(error => {
                        if (error !== "redirected") {
                            Swal.fire("Erro!", "Ocorreu um problema ao tentar editar a viagem.", "error");
                        }
                    });
            });
        });



        document.querySelectorAll(".iniciar-btn").forEach(button => {
            button.addEventListener("click", function () {
                let viagemId = this.getAttribute("data-id");
                Swal.fire({
                    title: "Iniciar viagem?",
                    text: "Tem certeza que deseja iniciar esta viagem?",
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#28a745",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Sim, iniciar!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/viagens/iniciar/${viagemId}`, {
                            method: "PATCH",
                            headers: {
                                "X-CSRF-TOKEN": csrfToken,
                                "Content-Type": "application/json"
                            }
                        })
                            .then(response => response.json().then(data => ({
                                status: response.status,
                                body: data
                            })))
                            .then(({ status, body }) => {
                                if (status === 200) {
                                    Swal.fire("Iniciada!", body.success, "success").then(() => location.reload());
                                } else if (status === 422) {
                                    let errorMessage = Object.values(body.errors).flat().join('<br>');
                                    Swal.fire("Erro de Validação", errorMessage, "error");
                                } else {
                                    Swal.fire("Erro!", body.error || "Ocorreu um problema ao iniciar a viagem.", "error");
                                }
                            })
                            .catch(() => Swal.fire("Erro!", "Ocorreu um problema ao iniciar a viagem.", "error"));
                    }
                });
            });
        });
        document.querySelectorAll(".finalizar-btn").forEach(button => {
            button.addEventListener("click", function () {
                let viagemId = this.getAttribute("data-id");
                Swal.fire({
                    title: "Finalizar viagem?",
                    html: `
                    <label for="km_fim">KM de Chegada:</label>
                    <input type="number" id="km_fim" class="swal2-input" placeholder="Informe o KM final" required>
                    <label for="data_hora_fim">Data e Hora de Chegada:</label>
                    <input type="datetime-local" id="data_hora_fim" class="swal2-input" required>
                    `,
                    showCancelButton: true,
                    confirmButtonText: "Finalizar",
                    cancelButtonText: "Cancelar",
                    preConfirm: () => {
                        let kmFim = document.getElementById("km_fim").value;
                        let dataHoraFim = document.getElementById("data_hora_fim").value;
                        if (!kmFim || !dataHoraFim) Swal.showValidationMessage("Por favor, preencha todos os campos!");
                        return { km_fim: kmFim, data_hora_fim: dataHoraFim };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/viagens/finalizar/${viagemId}`, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": csrfToken,
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                km_fim: result.value.km_fim,
                                data_hora_fim: result.value.data_hora_fim
                            })
                        })
                            .then(response => response.json().then(data => ({
                                status: response.status,
                                body: data
                            })))
                            .then(({ status, body }) => {
                                if (status === 200) {
                                    Swal.fire("Finalizada!", body.success, "success").then(() => location.reload());
                                } else if (status === 422) {
                                    let errorMessage = Object.values(body.errors).flat().join('<br>');
                                    Swal.fire("Erro de Validação", errorMessage, "error");
                                } else {
                                    Swal.fire("Erro!", body.error || "Ocorreu um problema ao finalizar a viagem.", "error");
                                }
                            })
                            .catch(() => Swal.fire("Erro!", "Ocorreu um problema ao finalizar a viagem.", "error"));
                    }
                });
            });
        });
        document.querySelectorAll(".delete-btn").forEach(button => {
            button.addEventListener("click", function () {
                let viagemId = this.getAttribute("data-id");
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
                        fetch(`/viagens/delete/${viagemId}`, {
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
                            .catch(() => Swal.fire("Erro!", "Ocorreu um problema ao excluir a viagem.", "error"));
                    }
                });
            });
        });
    } if (currentPath.includes("viagens/edit")) {
        document.querySelectorAll(".motorista-checkbox").forEach(checkbox => {
            checkbox.addEventListener("change", function () {
                let tipoSelect = this.nextElementSibling.nextElementSibling;
                tipoSelect.disabled = !this.checked;
                if (!this.checked) {
                    tipoSelect.value = "";
                }
            });
        });
        let veiculoSelect = document.getElementById("veiculo");
        let kmInicioInput = document.getElementById("km_inicio");

        if (veiculoSelect && kmInicioInput) {
            veiculoSelect.addEventListener("change", function () {
                let selectedOption = this.options[this.selectedIndex];
                let kmAtual = selectedOption.getAttribute("data-km");
                kmInicioInput.value = kmAtual ? kmAtual : "";
            });
        }
        const viagemForm = document.getElementById("viagemForm");
        if (viagemForm) {
            viagemForm.addEventListener("submit", function (event) {
                event.preventDefault();

                let formData = new FormData(this);
                formData.append("_method", "PUT");
                let viagemId = document.getElementById("viagemForm").getAttribute("data-id");
                console.log("ID da viagem a ser editada:", viagemId);
                let motoristasSelecionados = Array.from(document.querySelectorAll(".motorista-checkbox:checked"))
                    .map(checkbox => checkbox.value);
                formData.append("motoristas", JSON.stringify(motoristasSelecionados));
                fetch(`/viagens/${viagemId}`, {
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
                                window.location.href = "/viagens";
                            });
                        } else if (status === 422) {
                            let errorMessage = Object.values(body.errors).flat().join('<br>');
                            Swal.fire("Erro de Validação", errorMessage, "error");
                        } else {
                            Swal.fire("Erro!", body.error || "Ocorreu um problema ao atualizar a viagem.", "error");
                        }
                    })
                    .catch(() => {
                        Swal.fire("Erro!", "Ocorreu um problema ao atualizar a viagem.", "error");
                    });
            });
            let viagemId = viagemForm.getAttribute("data-id");
            fetch(`/viagens/${viagemId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.motoristas) {
                        data.motoristas.forEach(motorista => {
                            let checkbox = document.querySelector(`.motorista-checkbox[value="${motorista.id}"]`);
                            if (checkbox) {
                                checkbox.checked = true;
                            }
                        });
                    }
                });
        }
    } if (currentPath.includes("viagens/create")) {
        let veiculoSelect = document.getElementById("veiculo");
        let kmInicioInput = document.getElementById("km_inicio");

        if (veiculoSelect && kmInicioInput) {
            veiculoSelect.addEventListener("change", function () {
                let selectedOption = this.options[this.selectedIndex];
                let kmAtual = selectedOption.getAttribute("data-km");
                kmInicioInput.value = kmAtual ? kmAtual : "";
            });
        }

        const viagemForm = document.getElementById("viagemForm");
        if (viagemForm) {
            viagemForm.addEventListener("submit", function (event) {
                event.preventDefault();

                let motoristasSelecionados = [];

                document.querySelectorAll(".motorista-checkbox:checked").forEach(checkbox => {
                    let motoristaId = checkbox.value;
                    let tipoMotorista = checkbox.nextElementSibling.nextElementSibling.value;

                    if (tipoMotorista !== "") {
                        motoristasSelecionados.push({ id: motoristaId, tipo: tipoMotorista });
                    }
                });
                if (motoristasSelecionados.length === 0) {
                    Swal.fire("Erro!", "Selecione pelo menos um motorista e defina o tipo.", "error");
                    return;
                }
                let veiculo_id = document.getElementById("veiculo").value;
                let km_inicio = document.getElementById("km_inicio").value;
                let data_hora_inicio = document.getElementById("data_hora_inicio").value;
                let data_hora_fim = document.getElementById("data_hora_fim").value;

                console.log(motoristasSelecionados)
                fetch("/viagens/store", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        motoristas: motoristasSelecionados,
                        veiculo_id: veiculo_id,
                        km_inicio: km_inicio,
                        data_hora_inicio: data_hora_inicio,
                        data_hora_fim: data_hora_fim
                    })
                })
                    .then(response => response.json().then(data => ({
                        status: response.status,
                        body: data
                    })))
                    .then(({ status, body }) => {
                        if (status === 200 || 201) {
                            Swal.fire("Sucesso!", body.success, "success").then(() => {
                                window.location.href = "/viagens";
                            });
                        } else if (status === 422) {
                            let errorMessage = Object.values(body.errors).flat().join('<br>');
                            Swal.fire("Erro de Validação", errorMessage, "error");
                        } else {
                            Swal.fire("Erro!", body.error || "Ocorreu um problema ao cadastrar a viagem.", "error");
                        }
                    })
                    .catch(() => {
                        Swal.fire("Erro!", "Ocorreu um problema ao cadastrar a viagem.", "error");
                    });
            });
        }
    }
});
