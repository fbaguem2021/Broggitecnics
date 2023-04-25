<div class="menu-container" id="menu-operator">
    <div class="block-ltb">
        <div id="info-app" class="expanded">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title fw-bold mb-3">Info app, funcionament</h3>
                    <p class="card-text intro">BROGGI 112 és una aplicació destinada a l'aprenentatge de gestió de trucades d'emergencia per el cicle formatiu de Emergències Sanitàries.</p>
                    <p class="list-title mb-1">Els teus objectius com a Operador són:</p>
                    <ol class="ordered-list"> 
                        <div>
                            <li>Recepció de la trucada</li>
                            <li>Localitzar l'incident</li>
                            <li>Identificació de l'emergencia</li>
                        </div>
                        <div>
                            <li>Despatxar agències</li>
                            <li>Creació / assignació d'expedient</li>
                        <div>
                    </ol>
                </div>
            </div>
        </div>
        <div id="operator-data">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title fw-bold text-center">Trucades rebudes</h3>
                    <div class="chart-container">
                        <div class="icon-stack">
                            <i class="bi bi-triangle"></i>
                            <i class="bi bi-hammer"></i>
                        </div>
                        <span class="ms-4">En construcció...</span> 
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h3 class="card-title fw-bold text-center">Temps mitjana trucada</h3>
                    <div class="chart-container">
                        <canvas id="avCallChart"></canvas>
                        <span style="
                            position: absolute;
                            bottom: 0;
                            left: 50%;
                            transform: translateX(-50%);
                        ">130s</span>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="block-rt" id="sandbox">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title fw-bold text-center">Sandbox</h3>
                <p>El sandbox es un espai pensat per familiaritzar-se amb la la funcionalitat de la carta de trucada.</p>
                <p>Premsa aquesta icona <i class="bi bi-info-circle"></i> per obtenir mes informació</p>
                <a class="btn btn-secondary" href="#">Accedir a la carta</a>
            </div>
        </div>
    </div>

    <div class="block-rb" id="trucada">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title fw-bold text-center">Trucada</h3>
                <p>Incia una carta de trucada amb un número de telèfon aleatori o intrudeix-ne un.</p>
                <form action="{{ route('carta') }}" method="GET">
                    <div class="row w-100 justify-content-center">
                        <label class="col-md-1 col-form-label d-flex justify-content-center"><i class="bi bi-telephone-inbound pe-1"></i></label>
                        <div class="col-md-8 d-flex align-items-center">
                            <div class="input-group align-items-center">
                                <label class="switch">
                                    <input type="checkbox" name="isManual">
                                    <span class="slider round"></span>
                                </label>
                                <input type="text" class="form-control side" id="side" maxlength="9" name="phone"
                                    placeholder="000 000 000" aria-describedby="basic-addon1">
                                <label id="phone-mode" class="ms-3">Aleatori</label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-tertiary" >Iniciar trucada</button>
                </form>
            </div>
        </div>
        
    </div>
    <div class="menu-bg"></div>
</div>