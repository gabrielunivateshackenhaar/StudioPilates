<div id="contentScheduling" class="container my-5" style="display:none;">
    <?php
    // view/partials/calendar_view.php
    // Contém o HTML da legenda e do container do calendário
    ?>
    <div class="card shadow-sm mb-3">
        <div class="card-body d-flex flex-wrap align-items-center justify-content-center gap-3">

            <div class="d-flex align-items-center">
                <span class="badge" style="background-color: #28a745; width: 20px; height: 20px;">&nbsp;</span>
                <span class="ms-2 text-muted">Horários disponíveis</span>
            </div>

            <div class="d-flex align-items-center">
                <span class="badge" style="background-color: #dc3545; width: 20px; height: 20px;">&nbsp;</span>
                <span class="ms-2 text-muted">Horários Esgotados</span>
            </div>

        </div>
    </div>

    <div id="calendar-container" class="card shadow-sm">
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>

</div>