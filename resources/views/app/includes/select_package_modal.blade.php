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
                                <br>
                                <h4 class="text-center">{{format_with_cur($package->cost)}}</h4>
                                <div class="text-center" style="margin-top:-15px">One time</div>
                                <br>
                                <div>
                                    @if($package->max_gen > 0)
                                        <i class="fa fa-user-alt" style="font-size: 12px"></i> {{getPositionWithSuffix($package->max_gen)}} Gen. Level Earning
                                    @else 
                                        No commission
                                    @endif 
                                </div>
                                
                                <div>
                                    @foreach($package->services as $service)
                                        @if($service == E_SHOP)
                                            <div class="services_name"><i class="fa fa-store" style="font-size: 12px"></i> E-Shop Space</div>
                                        @endif 

                                        @if($service == HOTEL)
                                            <div class="services_name"><i class="fa fa-hotel" style="font-size: 12px"></i> Hotels Space</div>
                                        @endif 

                                        @if($service == RESTAURANT)
                                            <div class="services_name"><i class="fa fa-utensils" style="font-size: 12px"></i> Restaurant Space</div>
                                        @endif 

                                        @if($service == LOGISTICS)
                                            <div class="services_name"><i class="fa fa-motorcycle" style="font-size: 12px"></i> Logistics Space</div>
                                        @endif 

                                        @if($service == HEALTH)
                                            <div class="services_name"><i class="fa fa-heartbeat" style="font-size: 12px"></i> Health Insurance</div>
                                        @endif 
                                    @endforeach
                                </div>
                                <br>
                                <form action="" method="POST">
                                    @csrf 
                                    <div class="text-center">
                                        <button class="btn btn-outline-primary btn-block">Select Package</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>