<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update Contact Person') }}
        </h2>


    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-3 overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('contact.update', $data->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" name="name" value="{{ old('name', $data->name) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="text" name="email" value="{{ old('email', $data->email) }}" class="form-control" required>
        </div>

      <div class="mb-3">
            <label for="phone_no" class="form-label">Phone No:</label>
            <input type="text" name="phone_no" value="{{ old('phone_no', $data->phone_no) }}" class="form-control" required>
        </div>
        
     <div class="mb-3">
            <label for="whatsapp_no" class="form-label">Whatsapp No:</label>
            <input type="text" name="whatsapp_no" value="{{ old('whatsapp_no', $data->whatsapp_no) }}" class="form-control" required>
        </div>
        
      <div class="mb-3">
    <label class="form-label d-block">Status:</label>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="status" id="status_active" value="1"
            {{ old('status', $data->status) == '1' ? 'checked' : '' }} required>
        <label class="form-check-label" for="status_active">Active</label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="status" id="status_inactive" value="0"
            {{ old('status', $data->status) == '0' ? 'checked' : '' }}>
        <label class="form-check-label" for="status_inactive">Inactive</label>
    </div>
</div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update Contact Person</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
    </div>
</x-app-layout>