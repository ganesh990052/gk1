<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="user-management"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="User Management"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                       
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                ID
                                            </th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                PHOTO</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                NAME</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                EMAIL</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Balance</th>
                                           
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">

                                                Mony Transfer
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                             @foreach ($user as $key=> $item)
                                 
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <p class="mb-0 text-sm">{{ $key+1 }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div>
                                                        @if($item->image==null)
                                                        <img src="{{ asset('assets') }}/img/default.png"
                                                            class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                                                            @else
                                                            <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->name }}"   class="avatar avatar-sm me-3 border-radius-lg">

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
                                                <p class="text-xs text-secondary mb-0">{{ $item->email }}
                                                </p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $item->wallet[0]->balance }}</span>
                                            </td>
                                         
                                            <td class="align-middle  text-center text-center">
                                                <a rel="tooltip" class="transfer"
                                                href="javascript:void(0)" 
                                                data-original-title=""
                                                title=""
                                                data-id="{{ $item->id }}">   
                                                <img src="{{ asset('assets/img/icons/transfer.png') }}" alt="Money Transfer Logo" width="32" height="32">
                                            </a>
                                                {{-- <button type="button" class="btn btn-danger btn-link"
                                                data-original-title="" title="">
                                                <i class="material-icons">close</i>
                                                <div class="ripple-container"></div>
                                            </button> --}}
                                            </td>
                                        </tr>
                                        <div class="modal" id="edittranserModal">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <label for="">Bank Name</label>
                                                        <input type="text" id="editedBankName" class="form-control" placeholder="This user has no  bank name" readonly>
                                                    </div>
                                                    <div class="modal-body">
                                                        <label for="">Account Number</label>

                                                        <input type="text" id="account_number" class="form-control" placeholder="This user has no  Account Number" readonly>
                                                    </div>
                                                    <div class="modal-body">
                                                        <label for="">Account Holder Name</label>

                                                        <input type="text" id="account_holder_name" class="form-control" placeholder="This user has no   Account Holder Name" readonly>
                                                    </div>
                                                    <div class="modal-body">
                                                        <label for="">Branch Name</label>

                                                        <input type="text" id="branch_name" class="form-control" placeholder="This user has no   Branch Name" readonly>
                                                    </div>
                                                    <div class="modal-body">
                                                        <label for="">Ifsc Code</label>

                                                        <input type="text" id="ifsc_code" class="form-control" placeholder="This user has no  Ifsc Code	" readonly>
                                                    </div>
                                                    <div class="modal-body">
                                                        <label for="">Account Type</label>

                                                        <input type="text" id="type_of_account" class="form-control" placeholder="This user has no Account	" readonly> 
                                                    </div>

                                                   <input type="hidden" id="id"  class="form-control" >
                                                   <label for="">Transfer Amount</label>

                                                  <input type="text" id="amount" class="form-control" placeholder="Enter Transfer Amount	">

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-success   " id="transfer_mony">Transfer Mony</button>
                                                        {{-- <button type="button" class="btn btn-secondary" id="close" data-dismiss="modal">Close</button> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
