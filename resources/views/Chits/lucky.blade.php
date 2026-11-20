@php
use Carbon\Carbon;
$firstAuctionDate = Carbon::parse($data->date);
$nextmonth = Carbon::parse($data->date);
$winnerbool=false;
$totalamount=$data->chit->chitdetail[0]->subscription_amount * $data->chit->chitdetail[0]->duration ;
@endphp
<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="chits"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Chits"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                       
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0"   id="DataTable">
                                    <thead>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Sl.No 
                                            </th>
                                         
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Date</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Monthly Amount</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Total Profit Percentage</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Profit Amount 
                                            </th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Lucky Amount  
                                            </th>
                                            <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Select Lucky Member 
                                        </th>
                                        </tr>
                                    </thead>
                                    <tbody>

                            
                                        @foreach ($data->chit->profit as $key=> $item)
                                            
                                     
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
                                                    <h6 class="mb-0 text-sm">
                                                        
                                                        <?php 
                                          echo $firstAuctionDate->format('d-m-Y');

                                                ?>
                                                </h6>

                                                </div>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <p class="text-xs text-secondary mb-0">{{ $data->chit->chitdetail[0]->subscription_amount }}
                                                </p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{$item->presentage }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{round( $totalamount * $item->presentage/100 ,0)  }}
                                                <?php

$profitamount= round( $totalamount * $item->presentage/100 ,0)
?>
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{$totalamount-$profitamount}}</span>
                                            </td>

                                        <td class="align-middle">
                                            @php
                                            $winnerFound = false;
                                        @endphp
                                        
                                        @foreach ($data->luckyMember as $luckyMemberitem)
                                            @if (Carbon::parse($luckyMemberitem->date)->format('m-Y') == $firstAuctionDate->format('m-Y'))
                                                @php
                                                    $winnerFound = true;
                                                @endphp
                                                <i class="fa fa-trophy" style="color:green"></i> {{ $luckyMemberitem->user->name }}
                                                @break
                                            @endif
                                        @endforeach
                                        
                                        @if (!$winnerFound)
                                            <div class="card-body px-0 pb-2">
                                                <div class="table-responsive p-0">
                                                    <form id="winform{{$loop->index + 1}}" method="POST" action="{{ route('winner') }}">
                                                        @csrf
                                                        <input type="hidden" name="group_id" value="{{ $data->id }}">
                                                        <input type="hidden" name="amount" value="{{ $totalamount - $profitamount - $data->chit->chitdetail[0]->subscription_amount }}">
                                                        <input type="hidden" name="date" value="{{ $nextmonth->format('Y-m-d') }}">
                                                        <select name="user_id" id="group_id{{$loop->index + 1}}" class="group_id form-control border border-2 p-2">
                                                            <option value="null">Select Lucky Member</option>
                                                            @foreach ($data->bid as $biditem)
                                                                @if (Carbon::parse($biditem->date)->format('m-Y') == $firstAuctionDate->format('m-Y'))
                                                                    <option value="{{ $biditem->user->id }}" selected>{{ $biditem->user->name }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        @error('email')
                                                            <p class="text-danger inputerror">{{ $message }}</p>
                                                        @enderror
                                                        <button type="submit" id="submit{{$loop->index + 1}}" class="btn bg-gradient-dark">Submit</button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        </td>
                                        </tr>
                                        <?php
                                                                                       $firstAuctionDate->addMonth();

$nextmonth->addMonth();

                                        ?>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        // Initially hide the button if the selected value is "null"
        if ($('#group_id{{$key+1}}').val() == 'null') {
            $('#winform{{$key+1}}').hide();
        }
        
        // Listen for change events on the select element
        $('#group_id{{$key+1}}').change(function() {
            // Check if the selected value is "null"
            if ($(this).val() == 'null') {
                // Hide the button if the selected value is "null"
                $('#winform{{$key+1}}').hide();
            } else {
                // Show the button if a value other than "null" is selected
                $('#winform{{$key+1}}').show();
            }
        });
    });
</script>
<script>
    $(document).ready(function(){
        $('#submit{{$key+1}}').click(function() {
            var formData = $('#winform{{$key+1}}').serialize();
            $.ajax({
                url: '{{ route('winner') }}',
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Handle success response if needed
                    console.log(response.message);
                    // Optionally, you can display a success message to the user
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    // Handle error response if needed
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>

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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        // Initially hide the button if the selected value is "null"
        if ($('#group_id').val() === 'null') {
            $('#win').hide();
        }
        
        // Listen for change events on the select element
        $('#group_id').change(function() {
            // Check if the selected value is "null"
            if ($(this).val() === 'null') {
                // Hide the button if the selected value is "null"
                $('#win').hide();
            } else {
                // Show the button if a value other than "null" is selected
                $('#win').show();
            }
        });
    });
</script>

</x-layout>
