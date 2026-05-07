<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Management User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="d-flex justify-content-end align-items-center mb-3">
                <a href="{{ route('users.create') }}" class="btn btn-primary px-3">
                    <i class="fas fa-plus me-1"></i> Create User
                </a>
            </div>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4 p-md-5">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="datatable">
                        <thead class="table-light">
                            <tr>
                                <th class="fw-semibold">Name</th>
                                <th class="fw-semibold">Email</th>
                                <th class="fw-semibold">Created At</th>
                                <th class="fw-semibold text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $user->name }}</div>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $user->email }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $user->created_at->format('d/m/Y H:i:s') }}</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-2">
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('users.edit', $user->id) }}">
                                                <i class="fas fa-pen me-1"></i> Edit
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete" data-action="{{ route('users.destroy', $user->id) }}"
                                            data-confirm-title="Are you sure you want to delete this user?"
                                            data-confirm-text="This action cannot be undone."
                                            data-confirm-icon="warning"
                                            data-confirm-button-text="Delete"
                                            data-confirm-button-color="red">
                                                <i class="fas fa-trash me-1"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
