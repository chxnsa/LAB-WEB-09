@props(['title', 'description', 'image'])

<div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden group">
    <div class="aspect-video overflow-hidden bg-gray-200">
        <img src="{{ $image }}" alt="{{ $title }}" 
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
    </div>
    <div class="p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $title }}</h3>
        <p class="text-gray-600 leading-relaxed">{{ $description }}</p>
    </div>
</div>