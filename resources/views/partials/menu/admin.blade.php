<div class="menu-container" id="menu-admin">
    <div class="block-lt">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title fw-bold text-center">Gràfics</h3>
                <i class="bi bi-bar-chart-line"></i>
            </div>
            <a class="btn btn-primary" style="width: 200px" href="{{route('graphs')}}">Accedir</a>
        </div>
    </div>
    <div class="block-lb">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title fw-bold text-center mb-4">Adminsitració de l'aplicació</h3>
                <p class="card-text">Modificació de variables de l'aplicació.</p>
                <p class="card-text">Aqui podràs modificar paràmetres del funcionament i de l'estructura de l'aplicació.</p>
                <p class="card-text">També podràs afegir, modificar o liminar camps de la base de dades.</p>
                <div class="info-badge">
                    <div class="icon-stack">
                        <i class="bi bi-triangle"></i>
                        <i class="bi bi-hammer"></i>
                    </div>
                <span class="ms-4">En construcció...</span> 
                </div>
            </div>
        </div>
    </div>
    <div class="block-rt">
        <div class="card">
            <div class="card-body px-4">
                <h3 class="card-title fw-bold text-center">Gestió d'expedients</h3>
                <ul aria-label="Accions"> 
                    <li>Cercar expedients</li>
                    <li>Modifcar el seus estats</li>
                    <li>Inspeccionar les cartes asignades</li>
                    <li>Modifcar els estats de les agències</li>
                </ul>
                <i class="bi bi-folder-check"></i>
                <a class="btn btn-secondary" style="width: 200px" href="{{route('expedients')}}">Accedir</a>
            </div>
        </div>
       
    </div>
    <div class="block-rb">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title fw-bold text-center">Gestió d'usuaris</h3>
                <ul aria-label="Accions:"> 
                    <li>Cercar usuaris</li>
                    <li>Crear, modifcar i eliminar usuaris</li>
                </ul>
                <i class="bi bi-person-gear"></i>
                <a type="submit" class="btn btn-tertiary" style="width: 200px" href="{{route('operadors')}}">Accedir</a>
            </div>
        </div>
    </div>
    <div class="menu-bg"></div>
</div>