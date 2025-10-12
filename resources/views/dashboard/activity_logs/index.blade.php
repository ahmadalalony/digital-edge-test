@extends('layouts.app')

@section('title', __('dashboard.Activity Logs'))

@push('styles')
<style>
    .error-message {
        word-break: break-word;
        line-height: 1.5;
    }
    .error-card {
        transition: all 0.2s ease;
    }
    .error-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .stack-trace-container {
        max-height: 300px;
        overflow-y: auto;
    }
    .badge-level {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }
</style>
@endpush

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 m-0">{{ __('dashboard.Activity Logs') }}</h1>
        </div>

        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a class="nav-link {{ ($tab ?? 'activity') === 'activity' ? 'active' : '' }}" href="{{ route('admin_activity_logs_index', array_merge(request()->except('page'), ['type' => 'activity'])) }}">{{ __('dashboard.Activity') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($tab ?? 'activity') === 'notifications' ? 'active' : '' }}" href="{{ route('admin_activity_logs_index', array_merge(request()->except('page'), ['type' => 'notifications'])) }}">{{ __('dashboard.Notifications') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($tab ?? 'activity') === 'errors' ? 'active' : '' }}" href="{{ route('admin_activity_logs_index', array_merge(request()->except('page'), ['type' => 'errors'])) }}">{{ __('dashboard.Error Logs') }}</a>
            </li>
        </ul>

        @if(($tab ?? 'activity') === 'notifications')
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover m-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>{{ __('dashboard.Type') }}</th>
                                    <th>{{ __('dashboard.Data') }}</th>
                                    <th>{{ __('dashboard.Read At') }}</th>
                                    <th>{{ __('dashboard.Created At') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($notifications ?? []) as $notification)
                                    <tr>
                                        <td>{{ data_get($notification, 'id') }}</td>
                                        <td>{{ data_get($notification, 'type') }}</td>
                                        <td><pre class="m-0 small">{{ json_encode(data_get($notification, 'data', []), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre></td>
                                        <td>{{ data_get($notification, 'read_at') }}</td>
                                        <td>{{ data_get($notification, 'created_at') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center py-4 text-muted">{{ __('dashboard.No data') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if(($notifications ?? null) && method_exists(($notifications ?? null), 'links'))
                    <div class="card-footer">{{ $notifications->links() }}</div>
                @endif
            </div>
        @elseif(($tab ?? 'activity') === 'errors')
            @if(!empty($parsedErrors))
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="row g-3">
                            @php
                                $errorCounts = array_count_values(array_column($parsedErrors, 'level'));
                                $totalErrors = count($parsedErrors);
                            @endphp
                            <div class="col-md-3">
                                <div class="card bg-danger text-white">
                                    <div class="card-body text-center py-3">
                                        <h4 class="mb-1">{{ $errorCounts['ERROR'] ?? 0 }}</h4>
                                        <small>Errors</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center py-3">
                                        <h4 class="mb-1">{{ $errorCounts['WARNING'] ?? 0 }}</h4>
                                        <small>Warnings</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center py-3">
                                        <h4 class="mb-1">{{ $errorCounts['INFO'] ?? 0 }}</h4>
                                        <small>Info</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center py-3">
                                        <h4 class="mb-1">{{ $totalErrors }}</h4>
                                        <small>Total Entries</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="row">
                <div class="col-12">
                    @if(!empty($parsedErrors))
                        @foreach($parsedErrors as $index => $error)
                            <div class="card mb-3 error-card border-start border-{{ $error['level'] === 'ERROR' ? 'danger' : ($error['level'] === 'WARNING' ? 'warning' : 'info') }} border-4 shadow-sm">
                                <div class="card-header bg-light py-2">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                <span class="badge badge-level bg-{{ $error['level'] === 'ERROR' ? 'danger' : ($error['level'] === 'WARNING' ? 'warning' : 'info') }}">
                                                    <i class="bi bi-{{ $error['level'] === 'ERROR' ? 'exclamation-triangle' : ($error['level'] === 'WARNING' ? 'exclamation-circle' : 'info-circle') }}"></i>
                                                    {{ $error['level'] }}
                                                </span>
                                                <small class="text-muted">
                                                    <i class="bi bi-clock"></i>
                                                    {{ $error['timestamp'] }}
                                                </small>
                                                @if($error['file_path'])
                                                    <small class="text-primary">
                                                        <i class="bi bi-file-earmark-code"></i>
                                                        <code>{{ $error['file_path'] }}@if($error['line_number']):{{ $error['line_number'] }}@endif</code>
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            @if(!empty($error['stack_trace']))
                                                <button class="btn btn-sm btn-outline-secondary" type="button" 
                                                        data-bs-toggle="collapse" data-bs-target="#stackTrace{{ $index }}" 
                                                        aria-expanded="false">
                                                    <i class="bi bi-code-slash"></i> Stack Trace
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body py-3">
                                    <div class="error-message">
                                        <strong>{{ $error['message'] }}</strong>
                                    </div>
                                    
                                    @if(!empty($error['stack_trace']))
                                        <div class="collapse mt-3" id="stackTrace{{ $index }}">
                                            <div class="bg-dark text-light p-3 rounded stack-trace-container">
                                                <h6 class="text-warning mb-2">
                                                    <i class="bi bi-bug"></i> Stack Trace:
                                                </h6>
                                                <pre class="mb-0 small text-light" style="line-height: 1.4;">@foreach($error['stack_trace'] as $trace){{ $trace }}
@endforeach</pre>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="card shadow-sm">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 text-success">{{ __('dashboard.No errors found') }}</h5>
                                <p class="text-muted mb-0">{{ __('dashboard.Your application is running smoothly') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover m-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>{{ __('dashboard.Log Name') }}</th>
                                    <th>{{ __('dashboard.Description') }}</th>
                                    <th>{{ __('dashboard.Causer') }}</th>
                                    <th>{{ __('dashboard.Subject') }}</th>
                                    <th>{{ __('dashboard.Properties') }}</th>
                                    <th>{{ __('dashboard.Created At') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($activities ?? []) as $activity)
                                    <tr>
                                        <td>{{ data_get($activity, 'id') }}</td>
                                        <td><span class="badge text-bg-secondary">{{ data_get($activity, 'log_name') }}</span></td>
                                        <td>{{ data_get($activity, 'description') }}</td>
                                        <td>
                                            @if(data_get($activity, 'causer'))
                                                {{ data_get($activity, 'causer.name') }} (ID: {{ data_get($activity, 'causer.id') }})
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(data_get($activity, 'subject_type'))
                                                {{ class_basename(data_get($activity, 'subject_type')) }} #{{ data_get($activity, 'subject_id') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td><pre class="m-0 small">{{ json_encode(optional(data_get($activity, 'properties'))->toArray() ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre></td>
                                        <td>{{ data_get($activity, 'created_at') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center py-4 text-muted">{{ __('dashboard.No data') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if(($activities ?? null) && method_exists(($activities ?? null), 'links'))
                    <div class="card-footer">{{ $activities->links() }}</div>
                @endif
            </div>
        @endif
    </div>
@endsection


