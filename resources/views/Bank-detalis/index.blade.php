<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="bank-detalis"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Bank Detalis"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                   
                        <div class=" me-3 my-3 text-end">
                            <a class="btn bg-gradient-dark mb-0" id="openAddBankDetailModal"><i
                                    class="material-icons text-sm">add</i>&nbsp;&nbsp;Add Bank Detalis</a>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-3">
                                <table class="table align-items-center mb-0" id="DataTable">
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
                                                Account Number</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Account Holder Name</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Bank
                                            </th>
                                            <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Branch
                                        </th>
                                        <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Ifsc Code
                                    </th> <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                  Type Of Account
                                </th>
                                 <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                  Action
                                </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bankdetail as $key=> $bankdetail)
                                            
                                     
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <p class="mb-0 text-sm">{{ $key }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div>
                                                        @if($bankdetail->user->image==null)
                                                        <img src="{{ asset('assets') }}/img/default.png"
                                                            class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                                                            @else
                                                            <img src="{{ asset('stor') }}{{$bankdetail->user->image}}"
                                                            class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                                                            @endif
                                                    </div>

                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $bankdetail->user->name }}</h6>

                                                </div>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <p class="text-xs text-secondary mb-0">{{ $bankdetail->account_number }}
                                                </p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $bankdetail->account_holder_name }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $bankdetail->bank_name }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $bankdetail->branch_name }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $bankdetail->ifsc_code }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $bankdetail->type_of_account }}</span>
                                            </td>
                                            <td class="align-middle">

                                                <button class="btn btn-success bank-details-btn-edit" data-id="{{ $bankdetail->id }}"> 
                                                    <i class="material-icons">edit</i>
                                                    <div class="ripple-container"></div>
                                                
                                                </button>
                                                <button class="btn btn-danger bank-details-btn-link"
                      data-id="{{ $bankdetail->id }}" >
                                                <i class="material-icons">close</i>
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
<div class="modal fade" id="addBankDetailModal" tabindex="-1" aria-labelledby="addBankDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBankDetailModalLabel">Add Bank Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Your form with all required fields -->
                <form id="addBankDetailForm">
                    <label for="user_id">User ID:</label>
                    <select id="user_id" name="user_id" class="form-select" required>
                        <!-- Options will be populated dynamically via JavaScript -->
                    </select>
                    <label for="account_number">Account Number:</label>
                    <input type="text" id="insertaccount_number" name="account_number" placeholder="Account Number" required>

                    <label for="account_holder_name">Account Holder Name:</label>
                    <input type="text" id="insertaccount_holder_name" name="account_holder_name" placeholder="Account Holder Name" required>

                    <label for="bank_name">Bank Name:</label>
                    <input type="text" id="insertbank_name" name="bank_name" placeholder="Bank Name" required>

                    <label for="branch_name">Branch Name:</label>
                    <input type="text" id="insertbranch_name" name="branch_name" placeholder="Branch Name" required>

                    <label for="ifsc_code">IFSC Code:</label>
                    <input type="text" id="insertifsc_code" name="ifsc_code" placeholder="IFSC Code" required>

                    <label for="type_of_account">Type of Account:</label>
                    <input type="text" id="inserttype_of_account" name="type_of_account" placeholder="Type of Account" required>

                    <!-- Add other required fields -->

                    <button type="submit">Add Bank Detail</button>
                </form>
            </div>
        </div>
    </div>
</div>

            {{-- add model end --}}
        {{-- Edit Model --}}
        <div class="modal" id="editBankDetailModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="editedBankName" class="form-control" placeholder="Enter new bank name">
                    </div>
                    <div class="modal-body">
                        <input type="text" id="account_number" class="form-control" placeholder="Enter new Account Number">
                    </div>
                    <div class="modal-body">
                        <input type="text" id="account_holder_name" class="form-control" placeholder="Enter new Account Holder Name	">
                    </div>
                    <div class="modal-body">
                        <input type="text" id="branch_name" class="form-control" placeholder="Enter new Branch Name	">
                    </div>
                    <div class="modal-body">
                        <input type="text" id="ifsc_code" class="form-control" placeholder="Enter new Ifsc Code	">
                    </div>
                    <div class="modal-body">
                        <input type="text" id="type_of_account" class="form-control" placeholder="Enter new Type Of Account	">
                    </div>
                   <input type="hidden" id="id" class="form-control" placeholder="Enter new Type Of Account	">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-success   " id="btnSaveEdit">Save</button>
                        {{-- <button type="button" class="btn btn-secondary" id="close" data-dismiss="modal">Close</button> --}}
                    </div>
                </div>
            </div>
        </div>
        
            <x-footers.auth></x-footers.auth>
        </div>
    </main>
    <x-plugins></x-plugins>

</x-layout>
