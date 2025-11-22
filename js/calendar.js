// Aguarda o DOM (estrutura HTML) ser totalmente carregado
document.addEventListener('DOMContentLoaded', function () {

    // Referências aos elementos HTML
    var calendarEl = document.getElementById('calendar');
    var scheduleButton = document.getElementById('btnScheduling'); // Botão "Agendamento"
    var calendar; // Variável para a instância do calendário

    // Se não houver um botão de agendamento ou um calendário nesta página, 
    // não continue executando o script.
    if (!calendarEl || !scheduleButton) {
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
            // Se estiver na visão "Mês", muda para a visão "Semana"
            if (calendar.view.type === 'dayGridMonth') {
                calendar.changeView('timeGridWeek', info.dateStr);
            }
        },

        // Função chamada ao clicar em um EVENTO
        eventClick: function (info) {
            
            // 1. Se estiver na visão "Mês", apenas muda para a visão "Semana" (comportamento padrão)
            if (calendar.view.type === 'dayGridMonth') {
                calendar.changeView('timeGridWeek', info.event.startStr);
                return;
            }

            // 2. Verifica se o usuário já reservou este horário
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

            // 3. Verifica se o horário está esgotado
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

            // 4. Verifica se o usuário NÃO está logado
            if (SESSION_USER_ID === null) {
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

            // 5. Confirmação e Agendamento (Usuário Logado e Horário Disponível)
            
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

                    fetch("controller/BookingController.php?action=saveBooking", {
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

    // Lógica para renderizar o calendário
    // O calendário só é desenhado na primeira vez que a aba "Agendamento" é clicada
    scheduleButton.addEventListener('click', function () {
        setTimeout(function () {
            calendar.render();
            calendar.updateSize(); // Ajusta o tamanho
        }, 10); // Pequeno delay para garantir que o <div> está visível
    }, {
        once: true
    }); // O 'once: true' garante que isso rode apenas uma vez
});