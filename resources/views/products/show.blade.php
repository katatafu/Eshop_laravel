@extends('layouts.app')

@section('content')
<div class="h-20"> </div>
    <div class="container mt-20 px-4 sm:px-6 lg:px-8 mx-auto relative top-11">
        <!-- Back Button (Přesunuto doleva nahoře) -->
        <div class="fixed top-10 left-6 z-50">
            <a href="{{ route('products.index') }}" class="btn btn-secondary bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded-md">
                Back to Products
            </a>
        </div>

        <!-- Product Details (Posunuto trochu dolů) -->
        <div class="product-details bg-white p-6 shadow-lg rounded-lg mx-auto w-full lg:w-4/5 flex justify-between">
            <!-- Product Image Section -->
            <div class="w-1/3 pr-4">
                <div class="main-image mb-6">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image mx-auto w-full h-auto object-cover rounded-lg">
                </div>
                <!-- Additional Product Images -->
                <div class="flex justify-between">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }} - Image 1" class="w-1/4 h-24 object-cover rounded-lg">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }} - Image 2" class="w-1/4 h-24 object-cover rounded-lg">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }} - Image 3" class="w-1/4 h-24 object-cover rounded-lg">
                </div>
            </div>

            <!-- Product Information Section -->
            <div class="w-1/2 pl-4">
                <h1 class="text-3xl font-semibold text-gray-800 mb-4">{{ $product->name }}</h1>
                <p class="text-lg text-gray-600 mb-4">{{ $product->description }}</p>
                <div class="flex justify-between text-lg font-medium text-gray-800">
                    <p><strong>Price: </strong>{{ $product->price }} Kč</p>
                    <p><strong>SKU: </strong>{{ $product->sku }}</p>
                </div>
                <div class="flex justify-between text-lg font-medium text-gray-800 mt-2">
                    <p><strong>In Stock: </strong>{{ $product->in_stock }}</p>
                    <p>
                        Hodnocení:
                        <strong>
                        {{ number_format($product->averageRating(), 1) }} ⭐
                        </strong>
                        <span class="text-gray-400">({{ $product->reviews->count() }}x)</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="p-8 m-8">
            <div class="mt-8 p-6 bg-white shadow-lg rounded-lg">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Customer Reviews</h2>
            
                <!-- Výpis recenzí -->
                @if($product->reviews->count() > 0)
                    @foreach($product->reviews as $review)
                        <div class="border-b border-gray-200 pb-4 mb-4">
                            <p class="text-lg font-semibold">{{ $review->user->name ?? 'Neznámý uživatel' }}</p>
                            <p class="text-yellow-500">⭐ {{ $review->rating }} / 5</p>
                            <p class="text-gray-700">{{ $review->comment }}</p>
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-500">No reviews yet. Be the first to review this product!</p>
                @endif
            
                <!-- Formulář pro přidání recenze -->
                <!-- Formulář pro přidání recenze -->
                @auth
                    <!-- Check if the user has already reviewed this product -->
                    @php
                        $existingReview = $product->reviews->where('user_id', Auth::id())->first();
                    @endphp

                    @if($existingReview)
                        <p class="text-lg font-semibold text-gray-600">You have already reviewed this product. Thank you!</p>
                    @else
                        <div class="mt-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">Add a Review</h3>
                            <form action="{{ route('reviews.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="mb-4">
                                    <label for="rating" class="block text-lg font-medium text-gray-700">Rating</label>
                                    <select name="rating" id="rating" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="5">⭐️⭐️⭐️⭐️⭐️ - Excellent</option>
                                        <option value="4">⭐️⭐️⭐️⭐️ - Good</option>
                                        <option value="3">⭐️⭐️⭐️ - Average</option>
                                        <option value="2">⭐️⭐️ - Poor</option>
                                        <option value="1">⭐️ - Terrible</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="comment" class="block text-lg font-medium text-gray-700">Comment</label>
                                    <textarea name="comment" id="comment" rows="3" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                                </div>
                                <button type="submit" class="bg-blue-500 text-white py-2 px-6 rounded-md hover:bg-blue-600">
                                    Submit Review
                                </button>
                            </form>
                        </div>
                    @endif
                @else
                    <p class="mt-4 text-gray-500">
                        <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Log in</a> to leave a review.
                    </p>
                @endauth
            </div>
        <x-infinite_slider :products="$relatedProducts" />

        </div>
        </div>
    </div>
@endsection