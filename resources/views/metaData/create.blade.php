<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Meta Data') }}
        </h2>

       
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-3 overflow-hidden shadow-sm sm:rounded-lg">
              <form action='store' method="POST">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">Meta Title:</label>
            <input type="text" name="meta_title" value="{{ old('meta_title') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="meta_description" class="form-label">Meta Description:</label>
            <input type="text" name="meta_description" value="{{ old('meta_description') }}" class="form-control" required>
        </div>

      <div class="mb-3">
            <label for="slug" class="form-label">Slug:</label>
            <input type="text" name="slug" value="{{ old('slug') }}" class="form-control" required>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">Add Meta Data</button>
        </div>
    </form>
</div>


            </div>
        </div>
    </div>
</x-app-layout>