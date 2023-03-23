

<div class="container menu" id="menu-operator">
    <div class="container block-ltb">
        <div id="info-app" class="expanded">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title fw-bold mb-3">Info app, funcionament</h3>
                    <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vel porttitor
                        nibh, a scelerisque nisl. Suspendisse potenti. Praesent tempus at neque sit amet dapibus. Nulla
                        vehicula egestas bibendum.</p>
                </div>
            </div>
        </div>
        <div id="operator-data">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title fw-bold text-center">Trucades rebudes</h4>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title fw-bold text-center">Temps mitjana trucada</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="container block-rt" id="sandbox">
            <h3 class="title mt-2 mt-md-5 fw-bold text-center">Sandbox</h3>
            <div class="body">
                <p>El sandbox es un espai pensat per familiaritzar-se amb la la funcionalitat de la carta de trucada.</p>
                <p>Premsa aquesta icona <i class="bi bi-info-circle"></i> per obtenir mes informació</p>
                <button class="btn btn-secondary">Accedir a la carta</button>
            </div>
        
    </div>

    <div class="container block-rb" id="trucada">
        <h3 class="title mt-2 mt-md-5 fw-bold text-center">Trucada</h3>
        <div class="body">
            <p>Incia una carta de trucada amb un número de telèfon aleatori o intrudeix-ne un.</p>
            <div class="row w-100 justify-content-center">
                <label class="col-md-1 col-form-label d-flex justify-content-center"><i class="bi bi-telephone-inbound pe-1"></i></label>
                <div class="col-md-8 d-flex align-items-center">
                    <div class="input-group align-items-center">
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider round"></span>
                        </label>
                        <input type="text" class="form-control side" id="side" maxlength="9"
                            placeholder="000 000 000" aria-describedby="basic-addon1">
                            <label id="phone-mode" class="ms-3">Aleatori</label>
                    </div>

                </div>
            </div>
            <button class="btn btn-tertiary">Iniciar trucada</button>
        </div>
    </div>

    <div class="menu-bg"></div>
</div>