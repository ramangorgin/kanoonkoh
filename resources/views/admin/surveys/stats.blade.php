
@extends('layouts.admin')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">آمار</a></li>
        </ol>
    </nav>
@endsection

@section('title', 'آمار کلی نظرسنجی')

@section('content')
<div class="container mt-4">
<div class="container mt-4">
    <h4 class="mb-4">آمار کلی نظرسنجی‌ها</h4>

    {{-- آمار دوره‌ها --}}
    <div class="mb-5">
        <h5>نظرسنجی‌های دوره‌ها</h5>
        <div class="row">
            @foreach($courseSurveys as $survey)
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header">{{ $survey->course->title ?? 'عنوان ناشناس' }}</div>
                        <div class="card-body">
                            <p><strong>امتیاز:</strong> {{ $survey->rating ?? '-' }}</p>
                            <p><strong>ناشناس؟</strong> {{ $survey->anonymity ? 'بله' : 'خیر' }}</p>
                            @if($survey->comment)
                                <p><strong>نظر:</strong> {{ $survey->comment }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- آمار برنامه‌ها --}}
    <div class="mb-5">
        <h5>نظرسنجی‌های برنامه‌ها</h5>
        <div class="row">
            @foreach($programSurveys as $survey)
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header">{{ $survey->program->title ?? 'عنوان ناشناس' }}</div>
                        <div class="card-body">
                            <p><strong>امتیاز:</strong> {{ $survey->rating ?? '-' }}</p>
                            <p><strong>ناشناس؟</strong> {{ $survey->anonymity ? 'بله' : 'خیر' }}</p>
                            @if($survey->comment)
                                <p><strong>نظر:</strong> {{ $survey->comment }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection