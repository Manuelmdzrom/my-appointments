@extends('layouts.panel')

@section('content')
<div class="card shadow">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Mis citas</h3>
                </div>
               
              </div>
            </div>
            <div class="card-body">
              @if (session('notification'))
              <div class="alert alert-success" role="alert">
                {{ session('notification') }}
              </div>
              @endif
            </div>

            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active"data-toggle="tab" href="#confirmed-appointments" role="tab" aria-selected="true">
                Mis próximas citas
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link"data-toggle="tab" href="#pending-appointments" role="tab" aria-selected="false">
                Citas por confirmar
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link"data-toggle="tab" href="#old-appointments" role="tab" aria-selected="false">
                Historial de Citas
                </a>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="confirmed-appointments" role="tabpanel">
              @include('appointments.tables.confirmed')
              </div>
              <div class="tab-pane fade" id="pending-appointments" role="tabpanel">
              @include('appointments.tables.pendig')
              </div>
              <div class="tab-pane fade" id="old-appointments" role="tabpanel">
              @include('appointments.tables.old')
              </div>
            </div>  
          </div>
@endsection