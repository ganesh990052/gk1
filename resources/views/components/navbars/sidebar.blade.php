@props(['activePage'])

<aside
    class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0 d-flex text-wrap align-items-center" href=" {{ route('dashboard') }} ">
            <img src="{{ asset('assets') }}/img/logo-ct.png" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-2 font-weight-bold text-white">Chit Fund</span>
        </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto  max-height-vh-100" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <!-- <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Chit Fund</h6>
            </li> -->
            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'chits' ? 'active bg-gradient-primary' : '' }} "
                href="{{ route('chit-index') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i style="font-size: 1.2rem;" class="fas fa fa-bank ps-2 pe-2 text-center"></i>
                    </div>
                    <span class="nav-link-text ms-1">Chits</span>
              </a>
           </li>
           <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'user-details' ? 'active bg-gradient-primary' : '' }} "
                href="{{ route('user-details') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i style="font-size: 1.2rem;" class="fas fa fa-bank ps-2 pe-2 text-center"></i>
                    </div>
                    <span class="nav-link-text ms-1">User Details</span>
              </a>
           </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'bank-detalis' ? 'active bg-gradient-primary' : '' }} "
                href="{{ route('bank-index') }}">
                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i style="font-size: 1.2rem;" class="fas fa fa-bank ps-2 pe-2 text-center"></i>
                </div>
                <span class="nav-link-text ms-1">Bank Detalis</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white {{ $activePage == 'user-address' ? 'active bg-gradient-primary' : '' }} "
            href="{{ route('address.index') }}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                <i style="font-size: 1.2rem;" class="fas  fa fa-map-marker ps-2 pe-2 text-center"></i>
            </div>
            <span class="nav-link-text ms-1">Users Address</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link text-white {{ $activePage == 'users-document' ? 'active bg-gradient-primary' : '' }} "
        href="{{ route('document.index') }}">
        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i style="font-size: 1.2rem;" class="fas  fa  fa-file ps-2 pe-2 text-center"></i>
        </div>
        <span class="nav-link-text ms-1">Users Document</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link text-white {{ $activePage == 'user-occupation' ? 'active bg-gradient-primary' : '' }} "
    href="{{ route('occupation.index') }}">
    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
        <i style="font-size: 1.2rem;" class="fas  fa  fa-tasks ps-2 pe-2 text-center"></i>
    </div>
    <span class="nav-link-text ms-1">Users Occupation</span>
</a>
</li>
<li class="nav-item">
    <a class="nav-link text-white {{ $activePage == 'user-nominee_details' ? 'active bg-gradient-primary' : '' }} "
    href="{{ route('nominee.index') }}">
    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
        <i style="font-size: 1.2rem;" class="fas  fa   fa-group ps-2 pe-2 text-center"></i>
    </div>
    <span class="nav-link-text ms-1">Users Nominee Details</span>
</a>
</li>
            <!--<li class="nav-item">-->
            <!--    <a class="nav-link text-white {{ $activePage == 'user-management' ? ' active bg-gradient-primary' : '' }} "-->
            <!--        href="{{ route('user-management') }}">-->
            <!--        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">-->
            <!--            <i style="font-size: 1rem;" class="fas fa-lg fa-list-ul ps-2 pe-2 text-center"></i>-->
            <!--        </div>-->
            <!--        <span class="nav-link-text ms-1">User Management</span>-->
            <!--    </a>-->
            <!--</li>-->
            
            <li class="nav-item">
                <a class="nav-link text-white {{ $activePage == 'chit-restore' ? 'active bg-gradient-primary' : '' }} "
                href="{{ route('chit-restore') }}">
                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i style="font-size: 1.2rem;" class="fas fa fa-bank ps-2 pe-2 text-center"></i>
                </div>
                <span class="nav-link-text ms-1">Chit Restore</span>
            </a>
        </li>
            
        </ul>
    </div>
  
</aside>
