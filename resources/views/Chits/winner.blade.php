<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="chits"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Chits"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                        <div class=" me-3 my-3 text-end">
                            <a class="btn bg-gradient-dark mb-0" id="openAddMontlyWinerModal1"><i class="material-icons text-sm">add</i>&nbsp;&nbsp;Add Monthly Winner</a>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0" id="DataTable">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> ID
                                            </th>
                                           <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> User Name </th>
                                           <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Chits Name </th>
                                           <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Date </th>
                                           <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Monthly Winner </th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Action
                                    </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($monthlyWinner as $key=> $winner)
                                        @php  $userName = \App\Models\User::where('id',$winner->user_id )->first(); 
                                        $ChitName = \App\Models\Chit::where('id',$winner->chit_id )->first(); 
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <p class="mb-0 text-sm text-center">{{ $key+1 }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm text-center">{{ $userName->name }}</h6>

                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm text-center">{{ $ChitName->name}}</h6>

                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column justify-content-center text-center">
                                                    <h6 class="mb-0 text-sm text-center">{{ \Carbon\Carbon::parse($winner->date)->format('F Y') }}</h6>
                                                   
                                                </div>
                                              
                                            </td>
                                            <td class="text-center">
                                                <i class="fa fa-trophy" style="color:green"></i>
                                            </td>
                                           
                                            <td class="align-middle">
                                                <button class="btn btn-danger monthly-winner-btn-link text-center" data-id="{{ $winner->id }}" >
                                                <i class="material-icons text-center">close</i>
                                                <div class="ripple-container"></div>
                                            </button>
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
            {{-- add model --}}
<!-- Bootstrap Modal for adding bank detail -->
                <div class="modal fade" id="addMonthlyWinnerModal" tabindex="-1" aria-labelledby="addBankDetailModalLabel1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addBankDetailModalLabel1">Add Monthly Winner</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Your form with all required fields -->
                                <form id="addMonthlyWinnerForm">
                                    <label for="user_id">User Name:</label>
                                    <select id="user_id" name="user_id" class="form-select" required> </select>
                                    <label for="date"> Date</label>
                                    <input type="date" id="date"  name="date" placeholder="Enter First Auction Date" required>
                                   
                                    <input type="hidden" id="chit_id" name="chit_id" value="{{ $chits_id->id }}" >
                                    <!-- Add other required fields -->
                                    <button type="submit">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            {{-- add model end --}}
       
        
            <x-footers.auth></x-footers.auth>
        </div>
    </main>
    <x-plugins></x-plugins>

</x-layout>
