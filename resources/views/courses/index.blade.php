@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">دوره‌های آموزشی</h2>

    @if($courses->count())
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($courses as $course)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $course->title }}</h5>

                            <p class="card-text mb-1 text-muted">
                                تاریخ برگزاری: {{ jdate($course->date)->format('Y/m/d') }}
                            </p>

                            @if($course->location)
                                <p class="card-text text-muted">مکان: {{ $course->location }}</p>
                            @endif

                            <p class="card-text mt-2 flex-grow-1">
                                {{ \Illuminate\Support\Str::limit(strip_tags($course->description), 120, '...') }}
                            </p>

                            <a href="{{ route('courses.show', $course->id) }}" class="btn btn-outline-primary mt-3">
                                مشاهده جزئیات
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-secondary">
            هیچ دوره‌ای در حال حاضر موجود نیست.
        </div>
    @endif
</div>
@endsection
