<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="user-address"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="User Address Detalis"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                   
                        <div class=" me-3 my-3 text-end">
                            <a class="btn bg-gradient-dark mb-0" id="open-Add-Address-DetailModal"><i
                                    class="material-icons text-sm">add</i>&nbsp;&nbsp;Add User Address</a>
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
                                                House No</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Building Name</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Street Name
                                            </th>
                                            <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            City
                                        </th>
                                        <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        State
                                    </th> <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Postal Code
                                </th>
                                <th
                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Landmark
                            </th>
                               
                                 <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                  Action
                                </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($address as $key=> $address)
                                            
                                     
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
                                                        @if($address->user->image==null)
                                                        <img src="{{ asset('assets') }}/img/default.png"
                                                            class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                                                            @else
                                                            <img src="{{$address->user->image}}"
                                                            class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                                                            @endif
                                                    </div>

                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $address->user->name }}</h6>

                                                </div>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <p class="text-xs text-secondary mb-0">{{ $address->house_no }}
                                                </p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $address->building_name }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $address->street_name }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $address->city }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $address->state }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $address->postal_code }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $address->landmark }}</span>
                                            </td>
                                            <td class="align-middle">

                                                <button class="btn btn-success address-details-btn-edit" data-id="{{ $address->id }}"> 
                                                    <i class="material-icons">edit</i>
                                                    <div class="ripple-container"></div>
                                                
                                                </button>
                                                <button class="btn btn-danger address-details-btn-link"
                      data-id="{{ $address->id }}" >
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
<div class="modal fade" id="add-address-modal" tabindex="-1" aria-labelledby="add-address-ModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-address-ModalLabel">Add Address Detail</h5>
                <button type="button" class="btn-close btn btn-danger " data-bs-dismiss="modal" aria-label="Close"></button>

            </div>
            <div class="modal-body">
                <!-- Your form with all required fields -->
                <div class="modal-body">

                <form id="add-address-DetailForm">
                    <label for="user_id">User ID:</label>
                    <select id="user_id" name="user_id" class="form-select" required>
                        <!-- Options will be populated dynamically via JavaScript -->
                    </select>
                </div>
                    <div class="modal-body">

                    <label for="house_no">House No :</label>
                    <input type="text" id="inserthouse_no" name="house_no" placeholder="House Number" required>
                </div>
                    <div class="modal-body">
  <label for="insertbuilding_name">Building Name:</label>
                    <input type="text" id="insertbuilding_name" name="building_name" placeholder="Building  Name" required>
                </div>
                    <div class="modal-body">
   <label for="insertstreet_name">Bank Name:</label>
                    <input type="text" id="insertstreet_name" name="street_name" placeholder="Street Name" required>
                </div>
                    <div class="modal-body">
   <label for="insertcity">City Name:</label>
                    <input type="text" id="insertcity" name="city" placeholder="City Name" required>
                </div>
                    <div class="modal-body">
  <label for="inserstate">State Name:</label>
                    <input type="text" id="inserstate" name="state" placeholder="State Name" required>
                </div>
                    <div class="modal-body">
 <label for="insertpostal_code">Postal Code:</label>
                    <input type="text" id="insertpostal_code" name="postal_code" placeholder="Postal Code" required>
    </div>
              <div class="modal-body">
    <label for="insertlandmark">Landmark:</label>

<input type="text" id="insertlandmark" name="landmark" placeholder="Landmark" required>
                    <!-- Add other required fields -->
  </div>
                    <button type="submit" class="btn btn-success">Add Address Detail</button>
                </form>
            </div>
        </div>
    </div>
</div>

            {{-- add model end --}}
        {{-- Edit Model --}}
        <div class="modal" id="editAddressModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit  Address Detail</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="house_no" class="form-control" placeholder="Enter new House No">
                    </div>
                    <div class="modal-body">
                        <input type="text" id="building_name" class="form-control" placeholder="Enter new Building Name">
                    </div>
                    <div class="modal-body">
                        <input type="text" id="street_name" class="form-control" placeholder="Enter new Street Name">
                    </div>
                    <div class="modal-body">
                        <input type="text" id="city" class="form-control" placeholder="Enter new City">
                    </div>
                    <div class="modal-body">
                        <input type="text" id="state" class="form-control" placeholder="Enter new State	">
                    </div>
                    <div class="modal-body">
                        <input type="text" id="postal_code" class="form-control" placeholder="Enter new Postal Code	">
                    </div>
                    <div class="modal-body">
                        <input type="text" id="landmark" class="form-control" placeholder="Enter new Landmark	">
                    </div>
                   <input type="hidden" id="id" class="form-control" placeholder="Enter new Type Of Account	">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="address-btn-save-edit">Save</button>
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
