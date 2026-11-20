<x-layout bodyClass="g-sidenav-show bg-gray-200">

    <x-navbars.sidebar activePage="user-details"></x-navbars.sidebar>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        
        <x-navbars.navs.auth titlePage="User Details"></x-navbars.navs.auth>

        <div class="container-fluid py-4">
            <div class="row ">
                <div class="col-12">
                    <div class="card my-4 ">

                        <div class="card-body px-0 pb-2 ">
                            <div class="table-responsive p-3">

                                <table class="table align-items-center mb-0" id="DataTable">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">PHOTO</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">NAME</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">EMAIL</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Mobile No.</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Emi Detils</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Chit Detils</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($user as $key => $item)

                                            @php
                                                $wallet = $item->wallet->first(); // SAFE ACCESS
                                            @endphp

                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <p class="mb-0 text-sm">{{ $key + 1 }}</p>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div>
                                                            @if ($item->image == null)
                                                                <img src="{{ asset('assets/img/default.png') }}"
                                                                    class="avatar avatar-sm me-3 border-radius-lg" alt="user">
                                                            @else
                                                                <img src="{{ asset('storage/'.$item->image) }}"
                                                                    class="avatar avatar-sm me-3 border-radius-lg"
                                                                    alt="{{ $item->name }}">
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $item->name }}</h6>
                                                    </div>
                                                </td>

                                                <td class="align-middle text-center text-sm">
                                                    <p class="text-xs text-secondary mb-0">{{ $item->email }}</p>
                                                </td>

                                                <td class="align-middle text-center">
                                                    <span class="text-secondary text-xs font-weight-bold">
                                                       {{ $item->phone }}
                                                    </span>
                                                </td>
                                                <td class="align-middle text-center">
                                                  <a href="{{ route('emi.details', $item->id) }}" class="btn btn-success btn-sm"> Emi Details </a>
                                                </td>
                                                <td class="align-middle text-center">
                                                  <a href="{{ route('chit.details', $item->id) }}" class="btn btn-success btn-sm"> Chit Details </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <x-footers.auth></x-footers.auth>
        </div>
        
    </main>

    <x-plugins></x-plugins>

</x-layout>
