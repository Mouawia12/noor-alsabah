@extends('layouts.app')
@section('module', 'نظام الحوسبة')
@section('sub', 'الذكاء الاصطناعي')
@section('title', $page_title)

@section('styles')
<style>
    .ai-hero{background:linear-gradient(135deg,#009ef7 0%,#0b56a4 100%);border-radius:1rem;color:#fff;padding:2rem;position:relative;overflow:hidden}
    .ai-hero:before{content:"";position:absolute;top:-40px;left:-40px;width:180px;height:180px;background:rgba(255,255,255,.08);border-radius:50%}
    .ai-hero:after{content:"";position:absolute;bottom:-60px;right:-30px;width:220px;height:220px;background:rgba(255,255,255,.06);border-radius:50%}
    .kpi-card{border-radius:1rem;padding:1.4rem;height:100%;transition:transform .15s ease,box-shadow .15s ease;border:1px solid #eef0f3}
    .kpi-card:hover{transform:translateY(-4px);box-shadow:0 .8rem 2rem rgba(0,0,0,.08)}
    .kpi-ico{width:52px;height:52px;border-radius:.9rem;display:flex;align-items:center;justify-content:center;font-size:1.4rem}
    .kpi-val{font-size:1.9rem;font-weight:800;line-height:1}
    .donut{--val:0;width:150px;height:150px;border-radius:50%;
        background:conic-gradient(#50cd89 calc(var(--val)*1%),#f1416c 0);display:flex;align-items:center;justify-content:center;margin:auto}
    .donut .hole{width:104px;height:104px;background:#fff;border-radius:50%;display:flex;flex-direction:column;align-items:center;justify-content:center}
    .tl{position:relative;padding-inline-start:1.4rem}
    .tl:before{content:"";position:absolute;inset-inline-start:6px;top:4px;bottom:4px;width:2px;background:#eef0f3}
    .tl-item{position:relative;padding:.35rem 0 .9rem}
    .tl-dot{position:absolute;inset-inline-start:-1.4rem;top:.55rem;width:14px;height:14px;border-radius:50%;border:3px solid #fff;box-shadow:0 0 0 2px #eef0f3}
</style>
@endsection

@section('content')
@php
    $p = $stats['purchase']; $r = $stats['rent'];
    $card = function($color,$icon,$val,$label) {
        return ['color'=>$color,'icon'=>$icon,'val'=>$val,'label'=>$label];
    };
@endphp

    {{-- الترويسة الترحيبية --}}
    <div class="ai-hero mb-6">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-4 position-relative">
            <div>
                <h1 class="text-white fw-bolder mb-2"><i class="fas fa-robot me-2"></i> لوحة معلومات الذكاء الاصطناعي</h1>
                <div class="fs-5 opacity-75">نظرة شاملة وحيّة على معالجة الفواتير وعقود الإيجار</div>
            </div>
            <div class="d-flex gap-3 flex-wrap">
                <a href="{{ route('dashboard.purchase.ai.index') }}" class="btn btn-light fw-bold"><i class="fas fa-file-invoice me-1"></i> رفع فواتير</a>
                <a href="{{ route('dashboard.rent.ai.index') }}" class="btn btn-light-primary fw-bold"><i class="fas fa-file-contract me-1"></i> رفع عقود</a>
            </div>
        </div>
    </div>

    {{-- بطاقات المؤشّرات --}}
    <div class="row g-5 mb-2">
        @php $cards = [
            $card('primary','fa-layer-group',$p['batches']+$r['batches'],'إجمالي الدفعات المرفوعة'),
            $card('info','fa-file-invoice',number_format($p['processed']),'فواتير معالَجة'),
            $card('success','fa-check-double',number_format($p['transferred']),'فواتير مُرحّلة للفروع'),
            $card('warning','fa-clock',number_format($p['pending']),'بانتظار الترحيل'),
            $card('danger','fa-times-circle',number_format($p['rejected']),'فواتير مرفوضة'),
            $card('dark','fa-coins',number_format($p['total_amount'],2),'إجمالي المشتريات المُرحّلة'),
        ]; @endphp
        @foreach ($cards as $c)
            <div class="col-6 col-md-4 col-xl-2">
                <div class="kpi-card bg-white">
                    <div class="kpi-ico bg-light-{{ $c['color'] }} text-{{ $c['color'] }} mb-3"><i class="fas {{ $c['icon'] }}"></i></div>
                    <div class="kpi-val text-gray-900">{{ $c['val'] }}</div>
                    <div class="text-gray-500 fs-7 mt-1">{{ $c['label'] }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-5 mt-1">
        {{-- دقة الفواتير (Donut) --}}
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-header"><h3 class="card-title">دقة قبول الفواتير</h3></div>
                <div class="card-body text-center">
                    <div class="donut" style="--val:{{ $p['success_rate'] }}">
                        <div class="hole">
                            <div class="fs-2 fw-bolder text-gray-900">{{ $p['success_rate'] }}%</div>
                            <div class="fs-8 text-gray-500">مقبولة</div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center gap-6 mt-5">
                        <div><span class="bullet bg-success me-1"></span> مقبولة: <b>{{ number_format($p['accepted']) }}</b></div>
                        <div><span class="bullet bg-danger me-1"></span> مرفوضة: <b>{{ number_format($p['rejected']) }}</b></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ملخّص الإيجارات --}}
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-header"><h3 class="card-title">عقود الإيجار</h3></div>
                <div class="card-body">
                    @php $rentRows = [
                        ['عقود معالَجة',$r['processed'],'info'],
                        ['عقود معتمدة',$r['approved'],'success'],
                        ['بانتظار المراجعة',$r['pending'],'warning'],
                        ['دفعات مولّدة آلياً',$r['payments'],'primary'],
                    ]; @endphp
                    @foreach ($rentRows as [$lbl,$v,$col])
                        <div class="d-flex align-items-center justify-content-between py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <span class="text-gray-700"><span class="bullet bg-{{ $col }} me-2"></span>{{ $lbl }}</span>
                            <span class="fw-bolder fs-4 text-gray-900">{{ number_format($v) }}</span>
                        </div>
                    @endforeach
                    <div class="mt-4">
                        <div class="d-flex justify-content-between mb-1"><span class="fs-7 text-gray-600">نسبة نجاح الاستخراج</span><span class="fw-bold">{{ $r['success_rate'] }}%</span></div>
                        <div class="progress h-8px"><div class="progress-bar bg-success" style="width:{{ $r['success_rate'] }}%"></div></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- النشاط الحيّ --}}
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-header"><h3 class="card-title">النشاط الأخير</h3></div>
                <div class="card-body">
                    @if (empty($stats['activity']))
                        <div class="text-center text-muted py-8"><i class="fas fa-clock-rotate-left fs-2x d-block mb-2 opacity-50"></i>لا يوجد نشاط بعد</div>
                    @else
                        <div class="tl">
                            @foreach ($stats['activity'] as $a)
                                <div class="tl-item">
                                    <span class="tl-dot bg-{{ $a['color'] }}"></span>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="text-gray-800 fs-7"><i class="fas {{ $a['icon'] }} text-{{ $a['color'] }} me-1"></i>{{ $a['label'] }}</span>
                                        <span class="text-muted fs-9">{{ $a['time'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
