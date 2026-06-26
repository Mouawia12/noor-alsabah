@extends('layouts.app')
@section('module',"نظام الحوسبة")
@section('sub',"الاداري ")
@section('title',"$page_title")
@section('content')
<style>
.mol_home_ a {font-size: 16px !important;}
    </style>
    <div class="row g-5 g-xl-8">
        <div class="col-xl-3">
            <div class="card ">
                <!--begin::Body-->
                <div class="card-body d-flex align-items-center pt-3 pb-0 px-5">
                    <div class="d-flex flex-column flex-grow-1 py-2 py-lg-13 me-2">
                        <a href="" class="fw-bold text-dark fs-4 mb-2 text-hover-primary">ادارة المشاريع</a>
                    </div>
                    <img src="{{url('assets/media/svg/avatars/project.svg')}}" alt="" class=" h-80px">
                </div>
            </div>
        </div>
                        <div class="col-xl-3">
            <div class="card ">
                <div class="card-body d-flex align-items-center pt-3 pb-0 px-5">
                    <div class="d-flex flex-column flex-grow-1 py-2 py-lg-13 me-2">
                        <a href="" class="fw-bold text-dark fs-4 mb-2 text-hover-primary">عقود التشغيل<span class="fw-normal badge badge-danger">قريبا</span></a>
                    </div>
                    <img src="{{url('assets/media/svg/avatars/contract.svg')}}" alt="" class=" h-80px">
                </div>
            </div>
        </div>
            </div>
@endsection
@section('styles')
<style>
.card {  border: 1px solid #0abb87 !important;}
body {  background-color: #fff;}
.pt-3 {  padding-top: 0rem !important;}
    </style>
@endsection
@section('scripts')
    <script src="{{ asset('assets/js/custom/documentation/forms/select2.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js"></script>
@endsection
