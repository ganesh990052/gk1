<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    
    <x-navbars.sidebar activePage="user-nominee_details"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Nominee Details"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                   
                        <div class=" me-3 my-3 text-end">
                            <a class="btn bg-gradient-dark mb-0" id="openAddNomineeDetailModal"><i
                                    class="material-icons text-sm">add</i>&nbsp;&nbsp;Add Nominee Detalis</a>
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
                                               User NAME</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Nominee Name</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Relation</th>
                                         
                                                <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Phone</th>
                                 <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                  Action
                                </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($nominees as $key=> $nominees)
                                            
                                     
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
                                                        @if($nominees->user->image==null)
                                                        <img src="{{ asset('assets') }}/img/default.png"
                                                            class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                                                            @else
                                                            <img src="{{$nominees->user->image}}"
                                                            class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                                                            @endif
                                                    </div>

                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $nominees->user->name }}</h6>

                                                </div>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <p class="text-xs text-secondary mb-0">{{ $nominees->nominee_name }}
                                                </p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $nominees->relation }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $nominees->contact_number }}</span>
                                            </td>
                                         
                                            <td class="align-middle text-center">

                                                <button class="btn btn-success nominee-details-btn-edit" data-id="{{ $nominees->id }}"> 
                                                    <i class="material-icons">edit</i>
                                                    <div class="ripple-container"></div>
                                                
                                                </button>
                                                <button class="btn btn-danger nominee-details-btn-link"
                      data-id="{{ $nominees->id }}" >
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
<div class="modal fade" id="addNomineeModal" tabindex="-1" aria-labelledby="addBankDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBankDetailModalLabel">Add Nominee Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Your form with all required fields -->
                <form id="addNomineeDetailForm">
                    <label for="user_id">User ID:</label>
                    <select id="user_id" name="user_id" class="form-select" required>
                        <!-- Options will be populated dynamically via JavaScript -->
                    </select>
                    <label for="insertnominee_name">Nominee Name:</label>
                    <input type="text" id="insertnominee_name" name="nominee_name" placeholder="Enter Nominee Name" required>

                    <label for="insertrelation">Nominee Relation:</label>
                    <input type="text" id="insertrelation" name="relation" placeholder="Enter Nominee Relation" required>

                    <label for="insertcontact_number">Contact Number:</label>
                    <input type="text" id="insertcontact_number" name="contact_number" placeholder="Enter Contact Number" required>

                   
                    <button type="submit" class="btn btn-success ">Add Nominee Detail</button>
                </form>
            </div>
        </div>
    </div>
</div>

            {{-- add model end --}}
        {{-- Edit Model --}}
        <div class="modal" id="editNomineeModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Nominee Detail</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="nominee_name">Nominee Name:</label>

                        <input type="text" id="nominee_name" class="form-control" placeholder="Enter new Nominee Name">
                    </div>
                    <div class="modal-body">
                        <label for="relation">Relation:</label>

                        <input type="text" id="relation" class="form-control" placeholder="Enter new Relation">
                    </div>
                    <div class="modal-body">
                        <label for="contact_number">Mobile No.:</label>

                        <input type="text" id="contact_number" class="form-control" placeholder="Enter new Contact Number">
                    </div>
                    
                   <input type="hidden" id="id" class="form-control" placeholder="Enter new Type Of Account	">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="btnSavenominee">Save</button>
                        {{-- <button type="button" class="btn btn-secondary  close" id="close" data-dismiss="modal">Close</button> --}}
                    </div>
                </div>
            </div>
        </div>
        
            <x-footers.auth></x-footers.auth>
        </div>
    </main>
    <x-plugins></x-plugins>

</x-layout>
