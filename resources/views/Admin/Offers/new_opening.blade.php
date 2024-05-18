@extends('layouts.master')
@section('css')

@endsection
@section('title')
    Restaurants Management - Offer Details
@stop
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Layout</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Blank</a></li>
        </ol>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-3 col-lg-6  col-md-6 col-xxl-5 ">
                            <!-- Tab panes -->
                            <div class="tab-content">
                                @foreach ($offer->images as $index => $image)
                                @if ($index < 4)
                                    <div role="tabpanel" class="tab-pane fade show {{ $index == 0 ? 'active' : '' }}" id="imageTab{{ $index }}">
                                        <img class="img-fluid" src="{{ asset($image->filename) }}" alt="">
                                    </div>
                                    @endif
                                @endforeach
                            </div>

                            <div class="tab-slide-content new-arrival-product mb-4 mb-xl-0">
                                <!-- Nav tabs -->
                                <ul class="nav slide-item-list mt-3" role="tablist">
                                    @foreach ($offer->images as $index => $image)
                                    @if ($index < 4)
                                        <li role="presentation" class="{{ $index == 0 ? 'show' : '' }}">
                                            <a href="#imageTab{{ $index }}" role="tab" data-bs-toggle="tab">
                                                <img class="img-fluid" src="{{ asset($image->filename) }}" alt="" width="50">
                                            </a>
                                        </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <!--Tab slider End-->
                        <div class="col-xl-9 col-lg-6  col-md-6 col-xxl-7 col-sm-12">
                            <div class="product-detail-content">
                                <!--Product details-->
                                <div class="new-arrival-content pr">
                                    <h4>{{$offer->name}}</h4>

                                    <div class="d-table mb-2">
                                        <p class="price float-start d-block">{{$offer->start_date}}</p>
                                    </div>
                                    <p>Availability: <span class="item">{{$offer->status}} <i
                                                class="fa fa-shopping-basket"></i></span>
                                    </p>

                                    <p class="text-content">{{$offer->description}}</p>
                                    <div class="d-flex align-items-end flex-wrap mt-4">
                                        <div class="filtaring-area me-3">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
@section('js')
@endsection
