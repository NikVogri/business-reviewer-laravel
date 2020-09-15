@extends('layouts.app')

@section('content')

<div class="flex justify-center">

    <form action="/businesses" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="py-3 flex flex-col">
            <label for="country">Country</label>
            @include('helpers._country-dropdown')
            @error('country')
            <p class="text-red-400 text-xs">{{ $message }}</p>
            @enderror
        </div>

        <div class="py-3 flex flex-col">
            <label for="name">Business Name</label>
            <input type="text" name="name" class="py-2 px-3 rounded" value="{{ old('name') }}" required>
            @error('name')
            <p class="text-red-400 text-xs">{{ $message }}</p>
            @enderror
        </div>

        <div class="py-3 flex flex-col">
            <label for="address">Address</label>
            <input type="text" name="address" class="py-2 px-3 rounded" value="{{ old('address') }}" required>
            @error('address')
            <p class="text-red-400 text-xs">{{ $message }}</p>
            @enderror
        </div>

        <div class="py-3 flex flex-col">
            <label for="city">City</label>
            <input type="text" name="city" class="py-2 px-3 rounded" value="{{ old('city') }}" required>
            @error('city')
            <p class="text-red-400 text-xs">{{ $message }}</p>
            @enderror
        </div>

        <div class="py-3 flex flex-col">
            <label for="phone_number">Phone Number</label>
            <input type="text" name="phone_number" class="py-2 px-3 rounded" value="{{ old('phone') }}" required>
            @error('phone_number')
            <p class="text-red-400 text-xs">{{ $message }}</p>
            @enderror
        </div>


        <div class="py-3 flex flex-col">
            <label for="email">Business Email</label>
            <input type="text" name="email" class="py-2 px-3 rounded" value="{{ old('phone') }}" required>
            @error('email')
            <p class="text-red-400 text-xs">{{ $message }}</p>
            @enderror
        </div>


        <div class="py-3 flex flex-col">
            <label for="website_url">Website Address</label>
            <input type="text" name="website_url" class="py-2 px-3 rounded" value="{{ old('website_url') }}" required>
            @error('website_url')
            <p class="text-red-400 text-xs">{{ $message }}</p>
            @enderror
        </div>

        <div class="py-3 flex flex-col">
            <label for="categories">Business Categories</label>
            <select name="categories[]" class="py-2 px-3 rounded" multiple required>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('name')
            <p class="text-red-400 text-xs">{{ $message }}</p>
            @enderror
        </div>

        <div class="py-3 flex flex-col">
            <label for="description">Description</label>
            <textarea type="text" name="description" class="py-2 px-3 rounded" cols="30"
                rows="10">{{ old('description') }}</textarea>
            @error('description')
            <p class="text-red-400 text-xs">{{ $message }}</p>
            @enderror
        </div>

        <div class="py-3 flex flex-col">
            <label for="front_image">Business Image</label>
            <input type="file" name="front_image" class="py-2  rounded">
            @error('image')
            <p class="text-red-400 text-xs">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="py-2 px-5 bg-blue-400 text-white rounded">Add New</button>
    </form>

</div>

@endsection