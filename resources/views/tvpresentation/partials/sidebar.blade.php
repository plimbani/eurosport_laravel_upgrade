<div class="tournament-logo">
    <div class="logo-container is-left">
        <img src="//placehold.it/90x90" alt="">
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
            <li class="list-item active">
                <a href="#" class="list-link">U11/7</a>
            </li>
            @for ($i = 0; $i < 15; $i++)
            <li class="list-item">
                <a href="" class="list-link">U11/{{ $i }}</a>
            </li>
            @endfor
        </ul>
    </div>
    <div class="sidebar-footer"></div>
</div>
