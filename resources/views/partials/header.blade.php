<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Главная</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('news.index') }}">Новости</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('cars.index') }}">Модельный ряд</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('services.index') }}">Сервис</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('contacts.index') }}">Контакты</a>
                </li>
            </ul>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light">Админпанель</a>
                    <a href="{{ route('logout') }}" class="btn btn-outline-light"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Выйти
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-light">Войти</a>
            @endauth
        </div>
    </div>
</nav>
