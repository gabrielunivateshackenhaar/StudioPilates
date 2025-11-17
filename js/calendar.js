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
            // Se estiver na visão "Mês", muda para a visão "Dia"
            if (calendar.view.type === 'dayGridMonth') {
                calendar.changeView('timeGridDay', info.dateStr);
            }
        },

        // Função chamada ao clicar em um EVENTO (o fundo colorido)
        eventClick: function (info) {
            // Se estiver na visão "Mês", muda para a visão "Dia"
            if (calendar.view.type === 'dayGridMonth') {
                // info.event.startStr contém a data (ex: '2025-11-14')
                calendar.changeView('timeGridDay', info.event.startStr);
            // Se o evento clicado estiver esgotado, avisa e para.
            } else if (info.event.title.includes('Esgotado')) {
                alert('Desculpe, este horários já está esgotado!');
                return;
            // verifica se o usuário está logado e manda para tela de login
            } else if (SESSION_USER_ID === null) {
                alert("Você precisa estar logado para realizar esta ação!");
                window.location.href = "index.php?action=login";
                return;
            // manda as informações do agendamento via post pro controller
            } else {

                // Confirmação
                if (!confirm("Confirmar agendamento para este horário?")) {
                    return; // Usuário clicou em 'Cancelar'
                }

                const formData = new FormData();
                formData.append("schedule_id", info.event.id);

                fetch("controller/BookingController.php?action=saveBooking", {
                    method: "POST",
                    body: formData
                })
                .then(resp => resp.json())
                .then(data => {
                    if (data.status === "ok") {
                        alert("Agendamento salvo!");
                        calendar.refetchEvents();
                    } else {
                        alert("Erro: " + data.msg);
                    }
                });
            }
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