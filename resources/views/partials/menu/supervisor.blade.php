<div class="menu-container" id="menu-operator">
    <div class="block-ltb">
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
                    <h3 class="card-title fw-bold text-center mb-2">Gràfics<i class="bi bi-bar-chart-line"></i></h3>
                    <ul aria-label="Tipus:"> 
                        <li>Municipis amb més incidents</li>
                        <li>Municipis amb més trucades</li>
                    </ul>
                </div>
                <div class="card-footer">
                    <a class="btn btn-primary" style="width: 200px" href="{{route('graphs')}}">Accedir</a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h3 class="card-title fw-bold text-center mb-2">Gestió d'usuaris<i class="bi bi-person-gear"></i></h3>
                    <ul aria-label="Accions:"> 
                        <li>Cercar usuaris</li>
                        <li>Crear, modifcar i eliminar usuaris</li>
                    </ul>
                </div>
                <div class="card-footer">
                    <a type="submit" class="btn btn-primary" style="width: 200px" href="{{route('operadors')}}">Accedir</a>
                </div>
            </div>
        </div>
    </div>

    <div class="block-rt" id="sandbox">
        <div class="card">
            <div class="card-body px-4">
                <h3 class="card-title fw-bold text-center">Gestió d'expedients<i class="bi bi-folder-check"></i></h3>
                <ul aria-label="Accions:"> 
                    <li>Cercar expedients</li>
                    <li>Modifcar el seus estats</li>
                    <li>Inspeccionar les cartes asignades</li>
                    <li>Modifcar els estats de les agències</li>
                </ul>
                <a class="btn btn-secondary" style="width: 200px" href="{{route('expedients')}}">Accedir</a>
            </div>
        </div>
    </div>

    <div class="block-rb" id="trucada">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title fw-bold text-center">Trucada<i class="bi bi-telephone-inbound pe-1"></i></h3>
                <p>Incia una carta de trucada amb un número de telèfon aleatori o intrudeix-ne un.</p>
                <form action="{{ route('carta') }}" method="GET">
                    <div id="phone-selector" class="row w-100 justify-content-center">
                        <label class="col-md-1 col-form-label d-flex justify-content-center pe-4">Telèfon</label>
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