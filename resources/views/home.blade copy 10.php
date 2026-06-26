@extends('layouts.app')
@section('module', 'نظام إدارة الشركة ')
@section('sub', 'الرئيسية')
@section('title', "$page_title")
@section('content')
    <style>
        .mol_home_ a {
            font-size: 16px !important;
        }
    </style>
    <div class="row g-5 g-xl-8">


        <div class="col-xl-3">
            <div class="card ">
                <div class="card-body d-flex align-items-center pt-3 pb-0 px-5">
                    <div class="d-flex flex-column flex-grow-1 py-2 py-lg-8 me-2">
                        <a href="" class="fw-bold text-info fs-4 mb-2 text-hover-primary">
                            <i class="fas fa-arrow-left fa-fw text-info"></i>
                            ادخال بيانات الموظفين</a>
                        <a href="" class="fw-bold text-info fs-4 mb-2 text-hover-primary"> <i
                                class="fas fa-arrow-left fa-fw text-info"></i>
                            إدارة الموظفين </a>

                    </div>
                    <img src="{{ url('assets/media/illustrations/unitedpalms-1/4.png') }}" alt="" class=" h-100px">
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card ">
                <div class="card-body d-flex align-items-center pt-3 pb-0 px-5">
                    <div class="d-flex flex-column flex-grow-1 py-2 py-lg-8 me-2">
                        <a href="" class="fw-bold text-info fs-4 mb-2 text-hover-primary">
                            <i class="fas fa-arrow-left fa-fw text-info"></i>
                            ادخال بيانات العامل</a>
                        <a href="" class="fw-bold text-info fs-4 mb-2 text-hover-primary">
                            <i class="fas fa-arrow-left fa-fw text-info"></i>
                            إدارة العمال </a>

                    </div>
                    <img src="{{ url('assets/media/svg/avatars/job-svgrepo-com.svg') }}" alt="" class=" h-80px">
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card ">
                <div class="card-body d-flex align-items-center pt-3 pb-0 px-5">
                    <div class="d-flex flex-column flex-grow-1 py-2 py-lg-8 me-2">
                        <a href="" class="fw-bold text-info fs-4 mb-2 text-hover-primary">
                            <i class="fas fa-arrow-left fa-fw text-info"></i>
                            حسابات العمال</a>
                        <a href="" class="fw-bold text-info fs-4 mb-2 text-hover-primary">
                            <i class="fas fa-arrow-left fa-fw text-info"></i>
                            استعراض الحسابات </a>

                    </div>
                    <img src="{{ url('assets/media/illustrations/unitedpalms-1/20.png') }}" alt="" class=" h-80px">
                </div>
            </div>
        </div>

        <div class="col-xl-3">
            <div class="card ">
                <div class="card-body d-flex align-items-center pt-3 pb-0 px-5">
                    <div class="d-flex flex-column flex-grow-1 py-2 py-lg-8 me-2">
                        <a href="" class="fw-bold text-info fs-4 mb-2 text-hover-primary">
                            <i class="fas fa-arrow-left fa-fw text-info"></i>
                            حسابات المحلات </a>
                        <a href="" class="fw-bold text-info fs-4 mb-2 text-hover-primary">
                            <i class="fas fa-arrow-left fa-fw text-info"></i>
                            إدارة حسابات المحلات </a>

                    </div>
                    <img src="{{ url('assets/media/svg/avatars/job-svgrepo-com.svg') }}" alt="" class=" h-80px">
                </div>
            </div>
        </div>


    </div>
@endsection
@section('styles')
    <style>
        .card {
            border: 1px solid #0abb87 !important;
        }

        body {
            background-color: #fff;
        }

        .pt-3 {
            padding-top: 0rem !important;
        }
    </style>
@endsection
