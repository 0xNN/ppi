@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    
    <div class="container-fluid mt--7">
        @foreach ($chartCapaian as $item)  
        <div class="row mt-2">
            <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="card shadow">
                    <div class="card-body">
                      @if ($item == "Not Found")
                        <div class="alert alert-danger">Data tidak ditemukan!</div>
                      @else
                        {!! $item->container() !!}
                      @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @include('layouts.footers.auth')
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/fusioncharts@3.12.2/fusioncharts.js" charset="utf-8"></script>

    @foreach ($chartCapaian as $item)  
      @if ($item != "Not Found")
      {!! $item->script() !!}
      @endif
    @endforeach
@endpush