<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="user-details"></x-navbars.sidebar>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth titlePage="EMI / Bid Details"></x-navbars.navs.auth>

        <div class="container-fluid py-4">

            <!-- USER INFO -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-0">👤 User: <strong>{{ $user->name }}</strong></h5>
                            <p class="text-sm mb-0">
                                📧 {{ $user->email }} | 📱 {{ $user->phone }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BID TABLE -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Chit</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Email</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Phone</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Date</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center">
    Action </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse($bids as $bid)
                                            <tr>
                                                <td>
                                                    <span class="text-sm">
                                                        💰 Group Name: A{{ $bid->chit_id ?? '-' }} <br>
                                                        💰 Value: {{ $bid->chit_value ?? '-' }} <br>
                                                        📆 Duration: {{ $bid->duration  ?? '-' }} Months <br>
                                                        💸 EMI: {{ $bid->subscription_amount  ?? '-' }}
                                                    </span>

                                                </td>
                                                <td>
                                                    <span class="text-sm">{{ $bid->email ?? '-' }}</span>
                                                </td>
                                                <td>
                                                    <span class="text-sm">{{ $bid->phone ?? '-' }}</span>
                                                </td>
                                                <td>
                                                    <span class="text-sm">
                                                        {{ \Carbon\Carbon::parse($bid->applied_date)->format('d-m-Y') }}
                                                    </span>
                                                </td>
                                               <td class="text-center">

    @if($bid->status == 1)
        {{-- Deactivate --}}
        <form action="{{ route('applied.chit.deactivate', $bid->id) }}"
              method="POST"
              onsubmit="return confirm('Are you sure you want to deactivate this chit?');">
            @csrf
            @method('PATCH')
            <button class="btn btn-sm btn-warning">
                Deactivate
            </button>
        </form>
    @else
        {{-- Activate --}}
        <form action="{{ route('applied.chit.activate', $bid->id) }}"
              method="POST"
              onsubmit="return confirm('Are you sure you want to activate this chit?');">
            @csrf
            @method('PATCH')
            <button class="btn btn-sm btn-success">
                Activate
            </button>
        </form>
    @endif

</td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-danger">
                                                    No Bid Data Found
                                                </td>
                                            </tr>
                                        @endforelse
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
