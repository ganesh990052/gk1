<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="user-occupation"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Occupation Detalis"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                   
                        <div class=" me-3 my-3 text-end">
                            <a class="btn bg-gradient-dark mb-0" id="openAddOccupationDetailModal"><i
                                    class="material-icons text-sm">add</i>&nbsp;&nbsp;Add Occupation Detalis</a>
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
                                                Occupation</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Current Employer</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Years At Current Employer
                                            </th>
                                            <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Monthly Income
                                        </th>
                                        <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Living Status                                    </th> <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Years At Current Residence                                </th>
                                    
                                 <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                  Action
                                </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($occupations as $key=> $occupation)
                                            
                                     
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
                                                        @if($occupation->user->image==null)
                                                        <img src="{{ asset('assets') }}/img/default.png"
                                                            class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                                                            @else
                                                            <img src="{{$occupation->user->image}}"
                                                            class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                                                            @endif
                                                    </div>

                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $occupation->user->name }}</h6>

                                                </div>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <p class="text-xs text-secondary mb-0">{{ $occupation->occupation }}
                                                </p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $occupation->current_employer }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $occupation->years_at_current_employer }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $occupation->monthly_income }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $occupation->living_status }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $occupation->years_at_current_residence }}</span>
                                            </td>
                                            <td class="align-middle">

                                                <button class="btn btn-success occupation-details-btn-edit" data-id="{{ $occupation->id }}"> 
                                                    <i class="material-icons">edit</i>
                                                    <div class="ripple-container"></div>
                                                
                                                </button>
                                                <button class="btn btn-danger occupation-details-btn-link"
                      data-id="{{ $occupation->id }}" >
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
<div class="modal fade" id="addOccupationModal" tabindex="-1" aria-labelledby="addBankDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBankDetailModalLabel">Add Occupation Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Your form with all required fields -->
                <form id="addOccupationDetailForm">
                    <label for="user_id">User ID:</label>
                    <select id="user_id" name="user_id" class="form-select" required>
                        <!-- Options will be populated dynamically via JavaScript -->
                    </select>
                    <label for="insertoccupation">Occupation:</label>
                    <input type="text" id="insertoccupation" name="occupation" placeholder="Enter Occupation" required>

                    <label for="insertcurrent_employer">Current Employer:</label>
                    <input type="text" id="insertcurrent_employer" name="current_employer" placeholder="Enter Current Employer" required>

                    <label for="insertyears_at_current_employer">Years At Current Employer:</label>
                    <input type="text" id="insertyears_at_current_employer" name="years_at_current_employer" placeholder="Enter Years At Current Employer" required>

                    <label for="insertmonthly_income">Monthly Income:</label>
                    <input type="text" id="insertmonthly_income" name="monthly_income" placeholder="Enter Monthly Income" required>

                    <label for="insertliving_status">Living Status:</label>
                    <input type="text" id="insertliving_status" name="living_status" placeholder="Living Status" required>

                    <label for="insertyears_at_current_residence">Years At Current Residence:</label>
                    <input type="text" id="insertyears_at_current_residence" name="years_at_current_residence" placeholder="Years At Current Residence" required>

                    <!-- Add other required fields -->

                    <button type="submit" class="btn btn-success ">Add Occupation Detail</button>
                </form>
            </div>
        </div>
    </div>
</div>

            {{-- add model end --}}
        {{-- Edit Model --}}
        <div class="modal" id="editOccupationModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Occupation Detail</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="occupation" class="form-control" placeholder="Enter new occupation">
                    </div>
                    <div class="modal-body">
                        <input type="text" id="current_employer" class="form-control" placeholder="Enter new current employer">
                    </div>
                    <div class="modal-body">
                        <input type="text" id="years_at_current_employer" class="form-control" placeholder="Enter new years at current employer">
                    </div>
                    <div class="modal-body">
                        <input type="text" id="monthly_income" class="form-control" placeholder="Enter new monthly income	">
                    </div>
                    <div class="modal-body">
                        <input type="text" id="living_status" class="form-control" placeholder="Enter new  living status	">
                    </div>
                    <div class="modal-body">
                        <input type="text" id="years_at_current_residence" class="form-control" placeholder="Enter new years at current residence	">
                    </div>
                   <input type="hidden" id="id" class="form-control" placeholder="Enter new Type Of Account	">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="btnSaveoccupation">Save</button>
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
