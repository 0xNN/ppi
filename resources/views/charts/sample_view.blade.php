@extends('layouts.app', ['title' => __('Antibiotik')])

@section('content')
  @include('header.partials.header', [
      'title' => __('Antibiotik'),
      'description' => __('Data Antibiotik'),
      'class' => 'col-lg-12'
      ])

  <div class="container-fluid mt--7">
    <div class="row">
      <div class="col-sm-12">
        {!! $chart->container() !!}
      </div>
    </div>
    @include('layouts.footers.auth')
  </div>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets') }}/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="{{ asset('assets') }}/dist/css/izitoast.min.css">
<link rel="stylesheet" href="{{ asset('assets') }}/css/style.css">
<link rel="stylesheet" href="{{ asset('assets') }}/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('assets') }}/css/sweetalert2.min.css">
<link rel="stylesheet" href="{{ asset('assets') }}/css/jquery.datetimepicker.min.css">
@endpush

@push('js')
{{-- <script src="{{ asset('assets') }}/vendor/chart.js/dist/Chart.min.js"></script>
<script src="{{ asset('assets') }}/vendor/chart.js/dist/Chart.extension.js"></script> --}}
<script src="{{ asset('assets') }}/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('assets') }}/dist/js/izitoast.min.js"></script>
<script src="{{ asset('assets') }}/js/index.var.js"></script>
<script src="{{ asset('assets') }}/js/select2.min.js"></script>
<script src="{{ asset('assets') }}/js/sweetalert2.all.min.js"></script>
<script src="{{ asset('assets') }}/js/jquery.datetimepicker.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fusioncharts@3.12.2/fusioncharts.js" charset="utf-8"></script>
{!! $chart->script() !!}
@endpush