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
                // Implementar modal de Edição/Exclusão
                // Por enquanto alerta visual
                Swal.fire({
                    icon: 'info',
                    title: 'Gerenciar Horário',
                    text: 'Em breve você poderá editar ou excluir este horário aqui.',
                    confirmButtonText: 'Ok'
                });
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
                        icon: 'success', title: 'Criado!',
                        text: 'Horário adicionado.', timer: 1500, showConfirmButton: false
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