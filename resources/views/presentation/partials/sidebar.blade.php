<div class="tournament-logo">
    <div class="logo-container is-left">
        @if($tournament->tournamentLogo != null)
            <img src="{{ $tournamentData->tournamentLogo }}" alt="Tournament Logo">
        @elseif(Config::get('config-variables.current_layout') == 'tmp')
            <img  src="{{ asset('assets/img/tmplogo.svg')}}" alt="Tournament Logo">
        @elseif(Config::get('config-variables.current_layout') == 'commercialisation')
            <img  src="{{ asset('assets/img/easy-match-manager/emm.svg')}}" alt="Tournament Logo">
        @endif
    </div>
</div>
<div class="sidebar-container">
    <div class="sidebar-header">
        <div class="sidebar-title">
            Age categories
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="category-list">
            @foreach($ageCategories as $ageCategory)
                <li class="list-item {{ $loop->first ? 'active' : '' }}">
                    <a href="#" class="list-link">{{ $ageCategory['group_name'] . '(' . $ageCategory['category_age'] . ')' }}</a>
                </li>    
            @endforeach
        </ul>
    </div>
    <div class="sidebar-footer"></div>
</div>
