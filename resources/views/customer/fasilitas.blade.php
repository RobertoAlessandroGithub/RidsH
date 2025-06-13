@extends('layouts.app')

@section('content')
<section class="text-center py-16 bg-white">
    <h2 class="text-4xl font-bold mb-4">Facilities</h2>
    <p class="text-gray-600 mb-10">Enjoy the amenities we provide</p>

    <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto px-4">
        @foreach ($facilities as $facility)
        <div class="bg-white rounded-xl overflow-hidden shadow-lg">
            <img src="{{ asset('images/' . $facility->image) }}" alt="{{ $facility->name }}" class="h-48 w-full object-cover">
            <div class="p-4">
                <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $facility->name }}</h3>
                <p class="text-gray-600 text-sm mb-4">{{ $facility->description }}</p>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endsection
