<div x-data="{ activeTab: '{{ $categories->first()->id }}' }" class="mb-6">
    <div class="flex overflow-x-auto pb-2 space-x-2">
        @foreach($categories as $category)
            <button
                @click="activeTab = '{{ $category->id }}'"
                :class="{ 'bg-red-600 text-white': activeTab === '{{ $category->id }}', 'bg-white text-gray-800': activeTab !== '{{ $category->id }}' }"
                class="px-6 py-2 rounded-full font-medium whitespace-nowrap transition-colors duration-200 shadow-sm border border-gray-200"
            >
                {{ $category->name }}
            </button>
        @endforeach
    </div>
</div>