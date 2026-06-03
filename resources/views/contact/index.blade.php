<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Contact Person') }}
        </h2>

          <a href="contact/create">
                        Add New Contact Person
                    </a>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="table table-striped w-100">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone No</th>
                            <th>Whatsapp No</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($meta as $index => $meta)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $meta->name }}</td>
                        <td>{{ $meta->email }}</td>
                        <td>{{ $meta->phone_no }}</td>
                        <td>{{ $meta->whatsapp_no }}</td>
                       <td>
    @if($meta->status == 1)
        <span class="badge bg-success">Active</span>
    @else
        <span class="badge bg-danger">Inactive</span>
    @endif
</td>

                       <td>
                        <a href="{{ route('contact.edit', $meta->id) }}" class="btn btn-warning btn-sm">Edit</a>
                         <form action="{{ route('contact.delete', $meta->id) }}" method="POST" style="display:inline;">
                          @csrf
                        @method('POST')
                         <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
</form>

                        </td>
                    </tr>
                @endforeach
                    </tbody>
                </table>


            </div>
        </div>
    </div>
</x-app-layout>