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
                            <a class="btn bg-gradient-dark mb-0" id="openchitsModal"><i
                                    class="material-icons text-sm">add</i>&nbsp;&nbsp;Add Chits</a>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-3">
                                <table class="table align-items-center mb-0" id="DataTable">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                ID
                                            </th>
                                         
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Group Name</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                VALUE</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                DURATION</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                              SUBSCRIPTION
                                            </th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            First Auction Date
                                            </th>
                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Auction
                                            </th>
                                          <!--  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">-->
                                          <!--Select Lucky Member-->
                                          <!--  </th>-->
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                          Select Monthly Winner
                                        </th>
                                         <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                          Action
                                        </th>
                            
                            
                               </tr>
                                    </thead>
                                    <tbody>

                            
                                        @foreach ($chits as $key=> $chit)
                                            
                                     
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <p class="mb-0 text-sm">{{ $key+1 }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                           
                                            <td>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">A{{ $chit->id }}</h6>

                                                </div>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <p class="text-xs text-secondary mb-0">{{ $chit->chitdetail[0]->chit_value }}
                                                </p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $chit->chitdetail[0]->duration }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{  $chit->chitdetail[0]->subscription_amount }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $chit->chitdetail[0]->first_auction_date }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $chit->chitdetail[0]->chit_type }}</span>
                                            </td> 
                                           <!-- <td class="align-middle text-center">-->
                                           <!--     <a  class="btn btn-success" href="chit/lucky/{{ $chit->id  }}">-->
                                           <!--         <i class="material-icons">edit</i>-->
                                           <!--         <div class="ripple-container"></div>-->
                                           <!--         </a>                                            -->
                                           <!--</td>-->
                                            <td class="align-middle text-center">
                                                <a  class="btn btn-success" href="chit/winner/{{ $chit->id  }}">
                                                    <i class="material-icons">add</i>
                                                    <div class="ripple-container"></div>
                                                    </a>                                            
                                           </td>
                                            
                                            <td class="align-middle">
                                                <a  class="btn btn-success" href="chit/table/{{ $chit->id  }}">
                                                    Table
                                                    </a>
                                                   
                                                <button class="btn btn-success chit-details-btn-edit" data-id="{{ $chit->id }}"> 
                                                    <i class="material-icons">edit</i>
                                                    <div class="ripple-container"></div>
                                                
                                                </button>
                                                <button class="btn btn-danger chit-details-btn-link"
                      data-id="{{ $chit->id }}" >
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
        <div class="modal fade" id="chitModal" tabindex="-1" aria-labelledby="chitModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="chitModalLabel">Add Chit</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Your form with all required fields -->
                        <form id="chitForm">
                           
                            <label for="chit_name">Chit Name</label>
                            <input type="text"  name="name" placeholder="Enter Chit Name" required>
        
                            <label for="chit_value">Chit Value:</label>
                            <input type="text"  name="chit_value" placeholder="Enter Chit Value" required>
        
                            <label for="bank_name">Duration:</label>
                            <input type="text" name="duration" id="add_duration" placeholder="Enter Duration" required>
        
                            <label for="subscription_amount">Subscription Amount:</label>
                            <input type="text" name="subscription_amount" placeholder="Enter Subscription Amount" required>
        
                            <label for="first_auction_date">First Auction Date</label>
                            <input type="date"  name="first_auction_date" placeholder="Enter First Auction Date" required>
                            <label for="chit_type">Auction </label>
                            <input type="text"  name="chit_type" placeholder="Enter  Auction" required>
                            <label for="presentage[]">Percentage Value:</label>
                            <input type="text" id="valueofprofit"  name="valueofprofit"  readonly>
                            <div id="presentageFields">
                                <!-- Initial input field for presentage -->
                                <label for="presentage[]">Percentage:</label>
                                <input type="text" name="presentage[]"  id="presentageValue"  step="0.01">
                            </div> 
                            <p class="mt-2">
                                Total Value:
                                <strong id="percentageCount">0</strong>
                            </p>

                         <button type="submit" class="btn btn-success ">Add Chit Detail</button>
                        </form>
        
                        <button id="addMore" class="btn btn-success" style=" margin-left: 20rem;  margin-top: -116px;  "> Add
         <i class="fas fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>
        </div>

            {{-- add model end --}}
        {{-- Edit Model --}}
          <div class="modal" id="editchitModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="text" id="chit_name" class="form-control"  placeholder="Enter Chit Name">
                        </div>
                        <div class="modal-body">
                            <input type="text" id="chit_value" class="form-control" placeholder="Enter Chit Value">
                        </div>
                        <div class="modal-body">
                            <input type="text" id="duration"  class="form-control" placeholder="Enter Duration">
                        </div>
                        <div class="modal-body">
                            <input type="text" id="subscription_amount" class="form-control" placeholder="Enter Subscription Amount">
                        </div>
                        <div class="modal-body">
                            <input type="date" id="first_auction_date" class="form-control" >
                        </div>
                        <div class="modal-body">
                            <input type="text" id="chit_type" class="form-control" placeholder="Enter Type of chit	">
                        </div>
                       <input type="hidden" id="id" class="form-control" >
    
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success   " id="btnchitEdit">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        
            <x-footers.auth></x-footers.auth>
        </div>
    </main>
    <x-plugins></x-plugins>

</x-layout>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const addBtn = document.getElementById('addMore');
    const input = document.getElementById('presentageValue');
    const valueBox = document.getElementById('valueofprofit');
    const countBox = document.getElementById('percentageCount');

    function updateCount() {
        if (valueBox.value.trim() === '') {
            countBox.innerText = 0;
            return;
        }

        countBox.innerText = valueBox.value
            .split(',')
            .map(v => v.trim())
            .filter(v => v !== '').length;
    }

    addBtn.addEventListener('click', function () {

        let val = input.value.trim();
        if (val === '') {
            alert('Enter percentage');
            return;
        }

        if (valueBox.value === '') {
            valueBox.value = val;
        } else {
            valueBox.value += ', ' + val;
        }

        input.value = '';
        updateCount();
    });

});
</script>
