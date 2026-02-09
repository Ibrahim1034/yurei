<x-app-layout>
    <x-slot name="header">
        
    </x-slot>

    <div class="py-4">
        <div class="d-flex justify-content-between align-items-center my-2" style="margin-left: 10%; margin-right: 15%">
            <h2 class="h4 fw-bold text-gray-800 mb-0">
                <i class="bi bi-people me-2"></i>Leadership Management
            </h2>
            <a href="{{ route('leadership.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Add Leader
            </a>
        </div>
        
        <div class="container">
            <div class="dashboard-card">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Leaders Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Order</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaders as $leader)
                                <tr>
                                    <td>
                                        <img src="{{ $leader->image_url }}" alt="{{ $leader->name }}" 
                                             class="rounded-circle" width="50" height="50" style="object-fit: cover;">
                                    </td>
                                    <td>{{ $leader->name }}</td>
                                    <td>{{ $leader->position }}</td>
                                    <td>{{ $leader->email ?? 'N/A' }}</td>
                                    <td>{{ $leader->phone ?? 'N/A' }}</td>
                                    <td>{{ $leader->order }}</td>
                                    <td>
                                        <span class="badge {{ $leader->is_active ? 'bg-success' : 'bg-warning' }}">
                                            {{ $leader->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('leadership.show', $leader) }}" class="btn btn-sm btn-outline-primary" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('leadership.edit', $leader) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('leadership.destroy', $leader) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirm('Are you sure you want to delete this leader?')" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="bi bi-people display-1 text-muted"></i>
                                        <p class="mt-3">No leaders found.</p>
                                        <a href="{{ route('leadership.create') }}" class="btn btn-primary">Add First Leader</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>