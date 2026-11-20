<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="ssa"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="SSA calculation "></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                      
                        @if(session('message'))
    <div class="alert alert-danger">
        {{ session('message') }}
    </div>
@endif
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-0">
                                 <form method='POST' action='{{ route('group') }}'>
                            @csrf
                            <div class="row">
                                
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Select  Group </label>
<select name="group_id" id="group_id" class="form-control border border-2 p-2">
    <option value="">Select Group </option>
    @foreach($group as $item)
    <option value="{{$item->id}}" selected>{{$item->group_name}}</option>
    @endforeach

</select>                                    @error('email')
                                <p class='text-danger inputerror'>{{ $message }} </p>
                                @enderror
                                </div>
                                
    
                               </div>
                                
                            <button type="submit" class="btn bg-gradient-dark">Submit</button>
                        </form>
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
