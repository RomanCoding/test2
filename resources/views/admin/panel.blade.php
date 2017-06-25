@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="container">
            <div class="panel panel-info">
                <div class="panel-heading"><h3 class="panel-title">Добро пожаловать!</h3></div>
                <div class="panel-body">Админ панель.
                    <br>Пользователь: {{ Auth::user()->fullName() }}
                </div>
                <a href="{{ route('logout') }}" class="pull-right"
                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    Выход
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                      style="display: none;">
                    {{ csrf_field() }}
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <admin-panel></admin-panel>
            </div>
        </div>
    </div>
@endsection
