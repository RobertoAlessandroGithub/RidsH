<div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
    <!-- Badge Promo -->
    @if($menu->is_discounted)
    <div class="absolute bg-yellow-400 text-black px-2 py-1 text-xs font-bold rounded-tr-lg rounded-bl-lg">
        PROMO
    </div>
    @endif
    
    <!-- Menu Image -->
    <div class="h-48 bg-gray-200 overflow-hidden">
        <img src="{{ $menu->image_url ?? asset('images/default-food.png') }}" 
             alt="{{ $menu->name }}" 
             class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
    </div>
    
    <!-- Menu Info -->
    <div class="p-4">
        <h4 class="font-bold text-lg truncate">{{ $menu->name }}</h4>
        <p class="text-gray-600 text-sm mt-1 line-clamp-2">{{ $menu->description }}</p>
        
        <div class="flex justify-between items-center mt-3">
            <div>
                @if($menu->is_discounted)
                    <span class="text-red-600 font-bold">{{ format_currency($menu->discounted_price) }}</span>
                    <span class="text-gray-400 text-sm line-through ml-1">{{ format_currency($menu->price) }}</span>
                @else
                    <span class="text-red-600 font-bold">{{ format_currency($menu->price) }}</span>
                @endif
            </div>
            
            <button @click="addToCart({{ $menu->id }})" 
                    class="bg-red-600 text-white px-3 py-1 rounded-full text-sm hover:bg-red-700 transition-colors">
                + Add
            </button>
        </div>
    </div>
</div>