<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark" aria-label="Main
navigation">
<div class="container-fluid">
<a class="navbar-brand" href="{{ url('/') }}">PWL</a>
<button class="navbar-toggler p-0 border-0" type="button" id="navbarSideCollapse" aria-
label="Toggle navigation">
<span class="navbar-toggler-icon"></span>
</button>
<div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
<ul class="navbar-nav me-auto mb-2 mb-lg-0">
<li class="nav-item">
<a class="nav-link active" aria-current="page" href="{{ url('/home') }}">Home</a>
</li>
<li class="nav-item dropdown">
<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"
aria-expanded="false">Profile</a>
<ul class="dropdown-menu">
<li><a class="dropdown-item" href="{{ url('/profile/identity')
}}">Identity</a></li>
<li><a class="dropdown-item" href="{{ url('/profile/family') }}">Family</a></li>
</ul>
</li>
</ul>
<form class="d-flex" role="search">
<input class="form-control me-2" type="search" placeholder="Search" aria-
label="Search">
<button class="btn btn-outline-success" type="submit">Search</button>
</form>
</div>
</div>
</nav>