@extends('admin.layouts.app')

@section('title', 'Subscribers')

@section('content')
    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>Email</th>
                <th>Subscribed On</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($subscribers as $subscriber)
                <tr>
                    <td>{{ $subscriber->email }}</td>
                    <td>{{ $subscriber->created_at->format('d M Y, H:i') }}</td>
                    <td class="text-end">
                        <form action="{{ route('admin.subscribers.destroy', $subscriber) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this subscriber?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center text-muted">No newsletter subscribers yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $subscribers->links() }}
@endsection
