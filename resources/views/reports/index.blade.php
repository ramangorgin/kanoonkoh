@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">گزارش برنامه‌ها</h2>

    @if($reports->count())
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($reports as $report)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $report->program->title }}</h5>

                            <p class="card-text mb-1 text-muted">
                                تاریخ برنامه: {{ jdate($report->program->date)->format('Y/m/d') }}
                            </p>

                            <p class="card-text mt-2 flex-grow-1">
                                {{ \Illuminate\Support\Str::limit(strip_tags($report->content), 120, '...') }}
                            </p>

                            <a href="{{ route('reports.show', $report->id) }}" class="btn btn-outline-primary mt-3">
                                مشاهده گزارش کامل
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-secondary">
            هنوز گزارشی منتشر نشده است.
        </div>
    @endif
</div>
@endsection
