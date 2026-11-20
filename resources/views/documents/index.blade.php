<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="users-document"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Users Document"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                   
                        <div class=" me-3 my-3 text-end">
                            <a class="btn bg-gradient-dark mb-0" id="openDocumentDetailModal"><i
                                    class="material-icons text-sm">add</i>&nbsp;&nbsp;Add User Document</a>
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
                                                Aadhar Card Front</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Aadhar Card Back</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Pan Card
                                            </th>
                                            <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Bank Statement
                                        </th>
                                        <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Salary Slip
                                    </th>
                                 <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                  Action
                                </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($documents as $key=> $document)
                                            
                                     
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
                                                        @if($document->user->image==null)
                                                        <img src="{{ asset('assets') }}/img/default.png"
                                                            class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                                                            @else
                                                            <img src="{{$document->user->image}}"
                                                            class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                                                            @endif
                                                    </div>

                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $document->user->name }}</h6>

                                                </div>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <a href="{{ asset('storage/app/public/documents/' . basename($document->aadhar_card_front)) }}" download="{{$document->user->name}}.aadhar_card_front_image">
                                                    <img src="{{ asset('storage/app/public/documents/' . basename($document->aadhar_card_front)) }}" alt="Aadhar Card Front Image" style="height: 100px; width: 100px;">
                                                </a>
                                            </td>
                                            <td class="align-middle text-center">
                                                <a href="{{ asset('storage/app/public/documents/' . basename($document->aadhar_card_back)) }}" download="{{$document->user->name}}.aadhar_card_back">
                                                    <img src="{{ asset('storage/app/public/documents/' . basename($document->aadhar_card_back)) }}" alt="Aadhar Card Front Image" style="height: 100px; width: 100px;">
                                                </a>
                                            </td>
                                            <td class="align-middle text-center">
                                                <a href="{{ asset('storage/app/public/documents/' . basename($document->pan_card)) }}" download="{{$document->user->name}}.pan_card">
                                                    <img src="{{ asset('storage/app/public/documents/' . basename($document->pan_card)) }}" alt="Aadhar Card Front Image" style="height: 100px; width: 100px;">
                                                </a>                                            </td>
                                            <td class="align-middle text-center">
                                                <a href="{{ asset('storage/app/public/documents/' . basename($document->bank_statement)) }}" download="{{$document->user->name}}.bank_statement">
                                                    <img src="{{ asset('storage/app/public/documents/' . basename($document->bank_statement)) }}" alt="Aadhar Card Front Image" style="height: 100px; width: 100px;">
                                                </a>                                             </td>
                                            <td class="align-middle text-center">
                                                <a href="{{ asset('storage/app/public/documents/' . basename($document->salary_slip)) }}" download="{{$document->user->name}}.salary_slip">
                                                    <img src="{{ asset('storage/app/public/documents/' . basename($document->salary_slip)) }}" alt="Aadhar Card Front Image" style="height: 100px; width: 100px;">
                                                </a>                                             </td>
                                           
                                            <td class="align-middle">

                                                <button class="btn btn-success document-details-btn-edit" data-id="{{ $document->id }}"> 
                                                    <i class="material-icons">edit</i>
                                                    <div class="ripple-container"></div>
                                                
                                                </button>
                                                <button class="btn btn-danger document-details-btn-link"
                      data-id="{{ $document->id }}" >
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
<div class="modal fade" id="adddocumentModal" tabindex="-1" aria-labelledby="adddocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adddocumentModalLabel">Add Document Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Your form with all required fields -->
                <form enctype="multipart/form-data" id="adddocumentDetailForm" method="POST">
                    <label for="user_id">User ID:</label>
                    <select id="user_id" name="user_id" class="form-select" required>
                        <!-- Options will be populated dynamically via JavaScript -->
                    </select>
                    <label for="account_number">Aadhar Card Front:</label>
                    <input type="file" id="insertaadhar_card_front" name="aadhar_card_front" accept="image/*, application/pdf" required>

                    <label for="account_holder_name">Aadhar Card Back:</label>
                    <input type="file"  id="insertaadhar_card_back" name="aadhar_card_back" accept="image/*, application/pdf" required>

                    <label for="bank_name">Pan Card:</label>
                    <input type="file" name="pan_card"   id="insertpan_card" accept="image/*, application/pdf" required>

                    <label for="branch_name">Bank Statement:</label>
                    <input type="file" name="bank_statement" id="insertbank_statement" accept="image/*, application/pdf" required>
 

                    <label for="ifsc_code">Salary Slip:</label>
                    <input type="file" name="salary_slip" id="insertsalary_slip" accept="image/*, application/pdf" required> 



                  
                    <!-- Add other required fields -->

                    <button type="submit" class="btn btn-success ">Add Document Detail</button>
                </form>
            </div>
        </div>
    </div>
</div>

            {{-- add model end --}}
        {{-- Edit Model --}}
        <div class="modal" id="editDocumentDetailModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Document Detail</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form  enctype="multipart/form-data" id="editFileForm" >
                    <div class="modal-body">
                        <label for="aadhar_card_front">Aadhar Card Front:</label>
                        <input type="file" id="aadhar_card_front" class="form-control" required>
                        <img id="imgaadhar_card_front" src="#" alt="Preview" style="max-width: 100%; max-height: 100px;">
                    </div>
                    
                    <div class="modal-body">
                        <label for="aadhar_card_back">Aadhar Card Back:</label>
                        <input type="file" id="aadhar_card_back" class="form-control"  required>
                        <img id="imgaadhar_card_back" src="#" alt="Preview" style="max-width: 100%; max-height: 100px;">
                    </div>
                    
                    <div class="modal-body">
                        <label for="pan_card">Pan Card:</label>
                        <input type="file" id="pan_card" class="form-control"  required>
                        <img id="imgpan_card" src="#" alt="Preview" style="max-width: 100%; max-height: 100px;">
                    </div>
                    
                    <div class="modal-body">
                        <label for="bank_statement">Bank Statement:</label>
                        <input type="file" id="bank_statement" class="form-control" required>
                        <img id="imgbank_statement" src="#" alt="Preview" style="max-width: 100%; max-height: 100px;">
                    </div>
                    
                    <div class="modal-body">
                        <label for="salary_slip">Salary Slip:</label>
                        <input type="file" id="salary_slip" class="form-control"  required>
                        <img id="imgsalary_slip" src="#" alt="Preview" style="max-width: 100%; max-height: 100px;">
                    </div>
                    
                    <input type="hidden" id="id" class="form-control">
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="document-btn-save-edit">Save</button>
                        {{-- <button type="button" class="btn btn-secondary" id="close" data-dismiss="modal">Close</button> --}}
                    </div>
                </form>

                </div>
            </div>
        </div>
        
            <x-footers.auth></x-footers.auth>
        </div>
    </main>
    <x-plugins></x-plugins>

</x-layout>
