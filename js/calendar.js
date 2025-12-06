// Aguarda o DOM (estrutura HTML) ser totalmente carregado
document.addEventListener('DOMContentLoaded', function () {

    // Referências aos elementos HTML
    var calendarEl = document.getElementById('calendar');
    var scheduleButton = document.getElementById('btnScheduling'); // Botão "Agendamento"
    var classesButton = document.getElementById('btnClasses'); // Botão "Horários"
    var calendar; // Variável para a instância do calendário

    // FLAG para prevenir o conflito de cliques
    var isViewChanging = false;

    // Se não houver calendário nesta página, para a execução
    if (!calendarEl) {
        return;
    }

    // Inicializa o FullCalendar
    calendar = new FullCalendar.Calendar(calendarEl, {

        themeSystem: 'bootstrap5', // Aplica o tema visual do Bootstrap

        height: 'auto', // Força o calendário a se ajustar ao conteúdo (remove espaço branco)

        // Configuração dos botões do cabeçalho
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },

        initialView: 'dayGridMonth', // Visão inicial de "Mês"
        locale: 'pt-br', // Tradução para português

        // Textos dos botões de visualização
        buttonText: {
            today: 'Hoje',
            month: 'Mês',
            week: 'Semana',
            day: 'Dia'
        },

        views: {
            // Configuração da visão Mês
            dayGridMonth: {
                dayMaxEvents: 0 // Esconde os blocos (mostra "+ X mais")
            },

            // Configuração da visão Semana
            timeGridWeek: {
                allDaySlot: false, // Esconde a linha "all-day" no topo
                slotMinTime: '06:00:00', // Inicia o calendário às 06:00
                slotMaxTime: '19:00:00'  // Termina o calendário às 18:00
            },

            // Configuração da visão Dia
            timeGridDay: {
                allDaySlot: false, // Esconde a linha "all-day" no topo
                slotMinTime: '06:00:00', // Inicia o calendário às 06:00
                slotMaxTime: '19:00:00'  // Termina o calendário às 18:00
            }
        },

        // TRADUÇÃO
        moreLinkText: function (num) {
            return '+ ' + num + ' mais'; // Traduz "+X more"
        },

        // Fonte dos eventos, busca os dados da URL (endpoint JSON)
        events: 'index.php?action=getSchedulesJson',

        // Função chamada ao clicar em um dia VAZIO no calendário
        dateClick: function (info) {

            // Se a visão acabou de mudar, ignore este clique
            if (isViewChanging) {
                return;
            }

            // Comportamento para Admin, criar horários
            if (typeof IS_ADMIN !== 'undefined' && IS_ADMIN) {
                // Preenche o campo de data do modal com o dia clicado
                var dateInput = document.getElementById('quickDate');
                var timeInput = document.getElementById('quickTime');

                if (dateInput) {
                    // CORREÇÃO: Separa a data da hora (YYYY-MM-DD)
                    // Se vier "2025-11-14T08:00:00", pega só o "2025-11-14"
                    dateInput.value = info.dateStr.split('T')[0];

                    // BÔNUS: Se tiver hora (visão Semana/Dia), preenche o campo de hora também
                    if (info.dateStr.includes('T') && timeInput) {
                        // Pega o "08:00" de "2025-11-14T08:00:00"
                        var timePart = info.dateStr.split('T')[1].substring(0, 5);
                        timeInput.value = timePart;
                    }

                    // Abre o modal 
                    var modalEl = document.getElementById('createScheduleModal');
                    var modal = new bootstrap.Modal(modalEl);
                    modal.show();
                }
                return;
            }

            // Se estiver na visão "Mês", muda para a visão "Semana" e para aqui 
            if (calendar.view.type === 'dayGridMonth') {
                isViewChanging = true;
                calendar.changeView('timeGridWeek', info.dateStr);
                setTimeout(() => { isViewChanging = false; }, 500); // Libera após 500ms
                return;
            }
        },

        // Função chamada ao clicar em um EVENTO
        eventClick: function (info) {
            
            // Se estiver na visão "Mês", apenas muda para a visão "Semana" (para todos)
            if (calendar.view.type === 'dayGridMonth') {
                isViewChanging = true;
                calendar.changeView('timeGridWeek', info.event.startStr);
                setTimeout(() => { isViewChanging = false; }, 500);
                return;
            }

            // Comportamendo para Admin, gerenciar
            if (typeof IS_ADMIN !== 'undefined' && IS_ADMIN) {
                // Formata dados visuais
                const dateObj = info.event.start;
                const timeStr = dateObj.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
                const dateStr = dateObj.toLocaleDateString('pt-BR', { weekday: 'long', day: '2-digit', month: 'long' });

                document.getElementById('manageTimeDisplay').textContent = timeStr;
                document.getElementById('manageDateDisplay').textContent = dateStr;
                const slotsContainer = document.getElementById('manageSlotsList');

                // Abre o modal primeiro
                var modalEl = document.getElementById('manageScheduleModal');
                var modal = new bootstrap.Modal(modalEl);
                modal.show();

                // Função reutilizável: carregar dados
                // É chamada de novo ao remover/adicionar
                const loadScheduleData = () => {
                    // Mostra loading enquanto carrega
                    slotsContainer.innerHTML = '<div class="text-center py-3"><div class="spinner-border text-success" role="status"></div></div>';

                    fetch(`index.php?action=getScheduleDetails&id=${info.event.id}`)
                    .then(resp => resp.json())
                    .then(data => {
                        if(data.status !== 'ok') return;

                        slotsContainer.innerHTML = ''; // Limpa loading
                        const capacity = parseInt(data.schedule.capacity);
                        const bookings = data.bookings; // Array de alunos inscritos

                        // Atualiza o calendário de fundo (para refletir cores/vagas)
                        calendar.refetchEvents();

                        // Loop para criar as linhas
                        for (let i = 0; i < capacity; i++) {
                            const student = bookings[i]; // Tenta pegar o aluno na posição i
                            const slotDiv = document.createElement('div');
                            
                            if (student) {
                                // --- VAGA OCUPADA ---
                                slotDiv.className = 'd-flex justify-content-between align-items-center p-2 border rounded bg-light';
                                slotDiv.innerHTML = `
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-check fs-5 text-success me-2"></i>
                                        <span class="fw-medium text-truncate" style="max-width: 180px;">${student.name}</span>
                                    </div>
                                    <button class="btn btn-sm btn-outline-danger border-0 btn-remove-student" data-booking-id="${student.booking_id}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                `;
                                
                                // Ação de remover
                                slotDiv.querySelector('.btn-remove-student').onclick = function() {
                                    Swal.fire({
                                        text: `Remover ${student.name}?`,
                                        icon: 'warning',
                                        iconColor: '#d33',
                                        width: '320px',
                                        padding: '1.2rem',
                                        showCancelButton: true,
                                        reverseButtons: true,
                                        confirmButtonText: 'Sim, remover',
                                        cancelButtonText: 'Cancelar',
                                        buttonsStyling: false,
                                        customClass: {
                                            popup: 'rounded-4',
                                            htmlContainer: 'small mb-3',
                                            confirmButton: 'btn btn-danger btn-sm px-3',
                                            cancelButton: 'btn btn-light btn-sm px-3 me-2'
                                        }
                                    }).then((r) => {
                                        if(r.isConfirmed) {
                                            const fd = new FormData();
                                            fd.append('booking_id', student.booking_id);
                                            fetch('index.php?action=deleteBooking', { method: 'POST', body: fd })
                                            .then(() => {
                                                loadScheduleData(); // Recarrega a lista em vez de fechar
                                                Swal.fire({
                                                    toast: true,
                                                    position: 'top-end',
                                                    icon: 'success',
                                                    title: 'Aluno removido',
                                                    showConfirmButton: false,
                                                    timer: 2000
                                                });
                                            });
                                        }
                                    });
                                };

                            // --- VAGA LIVRE ---
                            } else {
                                slotDiv.className = 'd-flex justify-content-between align-items-center p-2 border rounded';
                                slotDiv.style.borderStyle = 'dashed !important';
                                slotDiv.innerHTML = `
                                    <span class="text-muted small fst-italic">Vaga Disponível</span>
                                    <button class="btn btn-sm btn-outline-primary rounded-circle p-1 lh-1 btn-add-student">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                `;

                                // Ação de Adicionar (Abre seletor)
                                slotDiv.querySelector('.btn-add-student').onclick = function() {
                                    // Cria as opções do select com a lista ALL_USERS
                                    let optionsHtml = '<option value="" disabled selected>Selecione o aluno...</option>';
                                    ALL_USERS.forEach(u => {
                                        optionsHtml += `<option value="${u.id}">${u.name}</option>`;
                                    });

                                    Swal.fire({
                                        title: 'Adicionar Aluno',
                                        html: `<select id="swal-input-student" class="form-select">${optionsHtml}</select>`,
                                        showCancelButton: true,
                                        confirmButtonText: 'Adicionar',
                                        preConfirm: () => {
                                            return document.getElementById('swal-input-student').value;
                                        }
                                    }).then((res) => {
                                        if (res.isConfirmed && res.value) {
                                            const fd = new FormData();
                                            fd.append('user_id', res.value);
                                            fd.append('schedule_id', info.event.id);
                                            
                                            fetch('index.php?action=adminAddStudent', { method: 'POST', body: fd })
                                            .then(() => {
                                                loadScheduleData(); // Recarrega a lista
                                                Swal.fire({
                                                    toast: true,
                                                    position: 'top-end',
                                                    icon: 'success',
                                                    title: 'Aluno adicionado',
                                                    showConfirmButton: false,
                                                    timer: 2000
                                                });
                                            });
                                        }
                                    });
                                };
                            }
                            slotsContainer.appendChild(slotDiv);
                        }
                    });
                };

                // Chama a função pela primeira vez
                loadScheduleData();

                // Configura o botão de excluir a aula inteira
                const btnDelete = document.getElementById('btnDeleteEntireSchedule');
                btnDelete.onclick = function() {
                    Swal.fire({
                        text: 'Tem certeza que deseja excluir esta aula e todos os inscritos?',
                        icon: 'warning',
                        iconColor: '#d33',
                        width: '320px',
                        padding: '1.2rem',
                        showCancelButton: true,
                        reverseButtons: true,
                        confirmButtonText: 'Sim, excluir',
                        cancelButtonText: 'Cancelar',
                        buttonsStyling: false, // Desliga o padrão do SweetAlert para usar Bootstrap
                        customClass: { // Aplica classes do Bootstrap 5
                            popup: 'rounded-4',
                            htmlContainer: 'small mb-3',
                            confirmButton: 'btn btn-danger btn-sm px-3',
                            cancelButton: 'btn btn-light btn-sm px-3 me-2'
                        }
                    }).then((r) => {
                        if (r.isConfirmed) {
                             const formData = new FormData();
                             formData.append('id', info.event.id);
                             fetch('index.php?action=deleteScheduleAjax', { method: 'POST', body: formData })
                             .then(resp => resp.json())
                             .then(data => {
                                 modal.hide();
                                 calendar.refetchEvents();
                                 if (data.status === 'ok') {
                                     info.event.remove();
                                     // Toast discreto de sucesso
                                     Swal.fire({
                                         toast: true,
                                         position: 'top-end',
                                         icon: 'success',
                                         title: 'Aula excluída',
                                         showConfirmButton: false,
                                         timer: 2000
                                     });
                                 } else {
                                     Swal.fire('Erro', data.msg, 'error');
                                 }
                             });
                        }
                    });
                };

                return;
            }

            // Comportamento para usuário padrão
            // 1. Verifica se o usuário já reservou este horário
            if (info.event.extendedProps && info.event.extendedProps.isBooked) {
                Swal.fire({
                    icon: 'warning', // Ícone de aviso (amarelo/laranja)
                    title: 'Já Agendado!',
                    text: 'Você já possui uma reserva confirmada para este horário.',
                    confirmButtonColor: '#17a2b8', // Azul combinando com o evento
                    confirmButtonText: 'Ok'
                });
                return; // Para a execução aqui
            }

            // 2. Verifica se o horário está esgotado
            if (info.event.title.includes('Esgotado')) {
                Swal.fire({
                    icon: 'error',
                    title: 'Esgotado!',
                    text: 'Desculpe, este horário não tem mais vagas disponíveis.',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Entendi'
                });
                return;
            }

            // 3. Verifica login
            if (typeof SESSION_USER_ID === 'undefined' || SESSION_USER_ID === null) {
                Swal.fire({
                    icon: 'info',
                    title: 'Login Necessário',
                    text: 'Você precisa estar logado para realizar um agendamento.',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745', // Verde Pilates
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Fazer Login',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "index.php?action=login";
                    }
                });
                return;
            }

            // 4. Confirmação e Agendamento (Usuário Logado e Horário Disponível)
            // Formata a data/hora para mostrar bonito no alerta
            const dataHora = info.event.start.toLocaleString('pt-BR', { 
                weekday: 'long', day: '2-digit', month: 'long', hour: '2-digit', minute: '2-digit' 
            });

            Swal.fire({
                title: 'Confirmar Agendamento?',
                html: `Deseja reservar o horário de<br><strong>${dataHora}</strong>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745', // Verde
                cancelButtonColor: '#d33',     // Vermelho
                confirmButtonText: 'Sim, reservar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostra um "carregando..." enquanto o servidor processa
                    Swal.fire({
                        title: 'Processando...',
                        text: 'Aguarde enquanto confirmamos sua reserva.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const formData = new FormData();
                    formData.append("schedule_id", info.event.id);

                    fetch("index.php?action=saveBooking", {
                        method: "POST",
                        body: formData
                    })
                    .then(resp => resp.json())
                    .then(data => {
                        if (data.status === "ok") {
                            // Sucesso!
                            Swal.fire({
                                icon: 'success',
                                title: 'Agendado!',
                                text: 'Sua aula foi reservada com sucesso.',
                                confirmButtonColor: '#28a745'
                            });
                            calendar.refetchEvents(); // Atualiza o calendário visualmente
                        } else {
                            // Erro do servidor (ex: vaga acabou nesse milissegundo)
                            Swal.fire({
                                icon: 'error',
                                title: 'Ops!',
                                text: 'Erro ao salvar: ' + (data.msg || 'Tente novamente.'),
                            });
                        }
                    })
                    .catch(error => {
                        // Erro de rede
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro de Conexão',
                            text: 'Não foi possível conectar ao servidor.',
                        });
                    });
                }
            });
        },
    });

    // Lógica de salvamento do novo horário (ADMIN)
    var quickForm = document.getElementById('quickScheduleForm');

    if(quickForm) {
        quickForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Botão carregando
            var submitBtn = quickForm.querySelector('button[type="submit"]');
            var originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Salvando...';

            var formData = new FormData(quickForm);

            fetch('index.php?action=saveQuickSchedule', {
                method: 'POST',
                body: formData
            })
            .then(resp => resp.json())
            .then(data => {
                if (data.status === 'ok') {
                    // Fecha Modal
                    var modalEl = document.getElementById('createScheduleModal');
                    var modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();

                    // Sucesso
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Horário criado!',
                        showConfirmButton: false,
                        timer: 3000
                    });

                    // Atualiza calendário
                    calendar.refetchEvents();
                } else {
                    Swal.fire({ icon: 'error', title: 'Erro', text: data.msg });
                }
            })
            .catch(error => {
                Swal.fire({ icon: 'error', title: 'Erro', text: 'Falha na conexão.' });
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }

    // Lógica para renderizar o calendário
    // Função auxiliar para configurar o botão (só se ele existir)
    function setupCalendarRender(btn) {
        if (btn) { // <--- SÓ EXECUTA SE O BOTÃO EXISTIR
            btn.addEventListener('click', function () {
                setTimeout(function () {
                    calendar.render();
                    calendar.updateSize();
                }, 10); 
            });
        }
    }

    // Tenta configurar os dois botões
    setupCalendarRender(scheduleButton);
    setupCalendarRender(classesButton);

    // Se estiver no Admin (acesso direto), renderiza logo
    if (!scheduleButton && !classesButton) {
         calendar.render();
    }
});