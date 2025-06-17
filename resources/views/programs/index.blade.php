@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">برنامه‌های کانون کوه</h2>

    @if($programs->count())
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($programs as $program)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $program->title }}</h5>

                            <p class="card-text mb-1 text-muted">
                                تاریخ برگزاری: {{ jdate($program->date)->format('Y/m/d') }}
                            </p>

                            @if($program->location)
                                <p class="card-text text-muted">مکان: {{ $program->location }}</p>
                            @endif

                            <p class="card-text mt-2 flex-grow-1">
                                {{ \Illuminate\Support\Str::limit(strip_tags($program->description), 120, '...') }}
                            </p>

                            <a href="{{ route('programs.show', $program->id) }}" class="btn btn-outline-primary mt-3">
                                مشاهده جزئیات
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-secondary">
            هیچ برنامه‌ای در حال حاضر موجود نیست.
        </div>
    @endif
</div>
@endsection
