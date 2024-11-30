<div class="modal fade" id="choose_package">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            <div class="modal-header"><h5 class="modal-title">Select Package</h5></div>
            <div class="modal-body">
                <div class="row">
                    @foreach($packages as $package)
                        <div class="col-lg-6">
                            <div class="pkg_con">
                                <h5 class="pkg_title">{{$package->name}}</h5>
                                
                                <h4 class="text-center">{{format_with_cur($package->cost)}}</h4>
                                <div class="text-center">One time</div>
                                @if($package->discount > 0)
                                    <div class="text-center text-primary">Discount {{$package->discount}}%</div>
                                @endif 
                                
                                
                                <div style="margin-top:5px;">
                                    @if($package->max_gen > 0)
                                        <i class="fa fa-user-alt" style="font-size: 12px"></i> {{getPositionWithSuffix($package->max_gen)}} Gen. Level Earning
                                    @else 
                                        <div class="text-center">No commission</div>
                                    @endif 
                                </div>
                                
                                <div>
                                    @if($package->services) 
                                        @foreach($package->services as $service => $max)
                                            @if($service == E_SHOP)
                                                <div class="services_name"><i class="fa fa-store" style="font-size: 12px"></i> {{$max}} E-Shop Space</div>
                                            @endif 

                                            @if($service == HOTEL)
                                                <div class="services_name"><i class="fa fa-hotel" style="font-size: 12px"></i> {{$max}} Hotels Space</div>
                                            @endif 

                                            @if($service == RESTAURANT)
                                                <div class="services_name"><i class="fa fa-utensils" style="font-size: 12px"></i> {{$max}} Restaurant Space</div>
                                            @endif 

                                            @if($service == LOGISTICS)
                                                <div class="services_name"><i class="fa fa-motorcycle" style="font-size: 12px"></i> {{$max}} Logistics Space</div>
                                            @endif  
                                        @endforeach
                                    @endif 
                                </div>
                                <br>
                                @if($user->package && $user->package->cost >= $package->cost)
                                    <div class="select_pkg_btn_con">
                                        <div>
                                            <i style="font-size: 25px" class="fa-regular fa-circle-check text-success"></i>
                                        </div>
                                    </div>
                                @else 
                                    <form class="package_selector" method="POST">
                                        @csrf 
                                        <input type="hidden" name="id" value="{{$package->id}}">
                                        <div class="select_pkg_btn_con">
                                            <button class="btn btn-outline-primary btn-block">
                                                @if(!$user->package_id)
                                                    Select Package
                                                @else 
                                                    UPGRADE
                                                @endif 
                                            </button>
                                        </div>
                                    </form>
                                @endif 
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>