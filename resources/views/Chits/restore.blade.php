<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="chit-restore"></x-navbars.sidebar>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Chits"></x-navbars.navs.auth>
        <!-- End Navbar -->

        <div class="container-fluid py-4">

            <div class="row">
                <div class="col-12">

                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0">

                            <table class="table align-items-center mb-0" id="DataTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Group Name</th>
                                        <th class="text-center">Value</th>
                                        <th class="text-center">Duration</th>
                                        <th class="text-center">Subscription</th>
                                        <th class="text-center">First Auction Date</th>
                                        <th class="text-center">Auction</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($chits as $key => $chit)

                                        @php
                                            $detail = $chit->chitdetail->first(); 
                                        @endphp

                                        <tr>

                                            <td>{{ $key + 1 }}</td>

                                            <td>
                                                <h6 class="mb-0 text-sm">A{{ $chit->id }}</h6>
                                            </td>

                                            <td class="text-center">
                                                {{ $detail->chit_value ?? 'N/A' }}
                                            </td>

                                            <td class="text-center">
                                                {{ $detail->duration ?? 'N/A' }}
                                            </td>

                                            <td class="text-center">
                                                {{ $detail->subscription_amount ?? 'N/A' }}
                                            </td>

                                            <td class="text-center">
                                                {{ $detail->first_auction_date ?? 'N/A' }}
                                            </td>

                                            <td class="text-center">
                                                {{ $detail->chit_type ?? 'N/A' }}
                                            </td>

                                            <td class="text-center">
                                                <form action="{{ route('chit.restore', $chit->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')

                                                    <button class="btn btn-success btn-sm">
                                                        Restore
                                                    </button>
                                                </form>
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
    </main>

    <x-plugins></x-plugins>

</x-layout>
