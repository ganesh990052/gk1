@php
use Carbon\Carbon;

$firstAuctionDate = null;
$totalamount = 0;

if ($chit_detalis) {
    $firstAuctionDate = Carbon::parse($chit_detalis->first_auction_date);
    $totalamount = $chit_detalis->subscription_amount * $chit_detalis->duration;
}
@endphp

<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="chits"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Chits"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row mb-3">
            <div class="col-md-4">
                <div class="card p-3 text-center">
                    <h6>Total Collection</h6>
                    <h5 class="text-success">₹ {{ number_format($totalCollection) }}</h5>
                </div>
            </div>
        
            <div class="col-md-4">
                <div class="card p-3 text-center">
                    <h6>Total Chit Amount</h6>
                    <h5>₹ {{ number_format($totalAmount) }}</h5>
                </div>
            </div>
        
            <div class="col-md-4">
                <div class="card p-3 text-center">
                    <h6>Total Profit</h6>
                    <h5 class="text-success">
    ₹ {{ number_format(round($totalProfit, -3)) }}
</h5>

                </div>
            </div>
        </div>

            <div class="row">
                <div class="col-12">
                        <!--<div class=" me-3 my-3 text-end">-->
                        <!--    <a class="btn bg-gradient-dark mb-0" id="openchitsModal"><i-->
                        <!--            class="material-icons text-sm">add</i>&nbsp;&nbsp;Add Chits</a>-->
                        <!--</div>-->
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-3">
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
                                        </tr>
                                    </thead>
                                    <tbody>
                            
                                        @foreach ($data as $key=> $data)

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

                                               $firstAuctionDate->addMonth();
                                                ?>
                                                </h6>

                                                </div>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <p class="text-xs text-dark mb-0">{{ $chit_detalis->subscription_amount }}
                                                </p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-dark text-xs font-weight-bold">{{$data->presentage }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-dark text-xs font-weight-bold">{{round( $totalamount * $data->presentage/100 ,-3)  }}
                                                <?php
                                                    $profitamount= round( $totalamount * $data->presentage/100 ,-3)
                                                  ?>
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-dark text-xs font-weight-bold">
                                                    {{ number_format(round(($totalamount - $profitamount), -3)) }}
                                                </span>
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
            
            <x-footers.auth></x-footers.auth>
        </div>
    </main>
    <x-plugins></x-plugins>

</x-layout>