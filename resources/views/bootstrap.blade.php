@extends('layout.base')

@section('content')
    <div class="container h-100">
        <div class="container mt-5">
            <h2>COLORS</h2>
            <div class="row">
                <div class="col-2">
                    <div class="card">
                        <div class="card-img-top bg-primary" style="height: 100px"></div>
                        <div class="card-body">
                            <h5 class="card-title text-center">PRIMARY</h5>
                        </div>
                    </div>
                </div>
                <div class="col-2">
                    <div class="card">
                        <div class="card-img-top bg-secondary" style="height: 100px"></div>
                        <div class="card-body">
                            <h5 class="card-title text-center">SECONDARY</h5>
                        </div>
                    </div>
                </div>
                <div class="col-2">
                    <div class="card">
                        <div class="card-img-top bg-tertiary" style="height: 100px"></div>
                        <div class="card-body">
                            <h5 class="card-title text-center">TERTIARY</h5>
                        </div>
                    </div>
                </div>
                <div class="col-2">
                    <div class="card">
                        <div class="card-img-top bg-success" style="height: 100px"></div>
                        <div class="card-body">
                            <h5 class="card-title text-center">SUCCESS</h5>
                        </div>
                    </div>
                </div>
                <div class="col-2">
                    <div class="card">
                        <div class="card-img-top bg-danger" style="height: 100px"></div>
                        <div class="card-body">
                            <h5 class="card-title text-center">DANGER</h5>
                        </div>
                    </div>
                </div>
                <div class="col-2"></div>
            </div>
            <div class="row mt-4">
                <div class="col-2">
                    <div class="card">
                        <div class="card-img-top bg-light" style="height: 100px"></div>
                        <div class="card-body border-top">
                            <h5 class="card-title text-center">LIGHT</h5>
                        </div>
                    </div>
                </div>
                <div class="col-2">
                    <div class="card">
                        <div class="card-img-top bg-dark" style="height: 100px"></div>
                        <div class="card-body">
                            <h5 class="card-title text-center">DARK</h5>
                        </div>
                    </div>
                </div>
                <div class="col-2 position-relative">
                    <div class="container position-absolute d-flex justify-content-around"
                    style="top:50%;left:50%;transform:translate(-50%,-50%)">
                        <button type="button" class="btn btn-danger">Danger</button>
                        <button type="button" class="btn btn-success">Success</button>
                    </div>
                </div>
                <div class="col-5">
                    <h5>Form</h5>
                    <div class="row g-3 align-items-center">
                        <div class="col-2">
                          <label for="tel" class="col-form-label">Telèfon</label>
                        </div>
                        <div class="col-auto">
                          <input type="phone" id="tel" class="form-control">
                        </div>
                    </div>
                    <div class="row mt-2 g-3 align-items-center">
                        <div class="col-2">
                          <label for="nom" class="col-form-label">Nom</label>
                        </div>
                        <div class="col-auto">
                          <input type="text" id="nom" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection