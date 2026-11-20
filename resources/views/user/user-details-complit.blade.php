<x-layout bodyClass="g-sidenav-show bg-gray-200">

    <x-navbars.sidebar activePage="user-details"></x-navbars.sidebar>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

        <x-navbars.navs.auth titlePage="EMI Details"></x-navbars.navs.auth>

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

            @forelse($allChitEmis as $chit)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">

                        <!-- CHIT HEADER -->
                        <div class="card-header pb-0">
                            <h6>
                                🧾 Group Name: A{{ $chit['chit_id'] }} |
                                💰 Value: ₹ {{ number_format($chit['chit_value']) }} |
                                ⏳ Duration: {{ $chit['duration'] }} Months
                            </h6>
                        </div>

                        <!-- SUMMARY -->
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="alert alert-success text-white">
                                        ✅ Paid EMI <br>
                                        <strong>{{ $chit['paid_count'] }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="alert alert-danger text-white">
                                        ❌ Pending EMI <br>
                                        <strong>{{ $chit['pending_count'] }}</strong>
                                    </div>
                                </div>
                            </div>

                            <!-- EMI TABLE -->
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Month</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Date</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Amount</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center">Status</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center">Admin Pay</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($chit['emis'] as $emi)
                                        
                                        <tr>
                                            <td>
                                                <span class="text-sm">{{ $emi->month }}</span>
                                            </td>
                                            <td>
                                                <span class="text-sm">
                                                    {{ \Carbon\Carbon::parse($emi->date)->format('d-m-Y') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-sm">₹ {{ number_format($emi->amount) }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if($emi->status == 'Done')
                                                    <span class="badge bg-success">Paid</span>
                                                @else
                                                    <span class="badge bg-danger">Pending</span>
                                                @endif
                                            </td>
                                           <td>
                                                @if($emi->status == 'Pending')
                                                <form method="POST" action="{{ route('admin.pay.emi') }}"
                                                      onsubmit="return confirm('Do you want to pay the EMI??');">
                                                    @csrf
                                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                    <input type="hidden" name="applied_chit_id" value="{{ $emi->apply_id }}">
                                                    <input type="hidden" name="confirmed" value="yes">
                                                    <input type="hidden" name="chit_id" value="{{ $chit['chit_id'] }}">
                                                    <input type="hidden" name="month" value="{{ $emi->date }}">
                                                    <input type="hidden" name="amount" value="{{ $emi->amount }}">
                                                
                                                    @if(\Carbon\Carbon::parse($emi->date)->lte(now()->startOfMonth()))
                                                        <button class="btn btn-sm btn-warning">
                                                            Pay by Admin
                                                        </button>
                                                    @else
                                                        <button class="btn btn-sm btn-secondary" disabled>
                                                            Not Allowed
                                                        </button>
                                                    @endif

                                                </form>
                                                @endif
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
            @empty
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-warning">
                            No EMI Data Found
                        </div>
                    </div>
                </div>
            @endforelse

            <x-footers.auth></x-footers.auth>
        </div>
    </main>

    <x-plugins></x-plugins>

</x-layout>
