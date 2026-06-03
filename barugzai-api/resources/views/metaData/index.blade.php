<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Meta Data') }}
        </h2>

          <a href="meta-data/create">
                        Create
                    </a>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="table table-striped w-100">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Meta Title</th>
                            <th>Meta Description</th>
                            <th>Slug</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($meta as $index => $meta)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $meta->meta_title }}</td>
                        <td>{{ $meta->meta_description }}</td>
                        <td>{{ $meta->slug }}</td>
                       <td>
                        <a href="{{ route('meta-data.edit', $meta->id) }}" class="btn btn-info btn-sm">Edit</a>
                            <a  class="btn btn-warning btn-sm">Delete</a>
                           
                        </td>
                    </tr>
                @endforeach
                    </tbody>
                </table>


            </div>
        </div>
    </div>
</x-app-layout>