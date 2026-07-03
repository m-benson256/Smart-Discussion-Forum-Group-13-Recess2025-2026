<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Quizzes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-puzzle-piece"></i> My Quizzes</h1>
            <a href="{{ route('lecturer.quizzes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create New Quiz
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            @forelse($quizzes as $quiz)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">{{ $quiz->title }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($quiz->description, 100) }}</p>
                            
                            <div class="mb-2">
                                <span class="badge bg-info">Questions: {{ $quiz->questions_count ?? 0 }}</span>
                                <span class="badge bg-warning text-dark">Attempts: {{ $quiz->attempts_count ?? 0 }}</span>
                            </div>
                            
                            <div class="small text-muted">
                                <div><i class="far fa-calendar-alt"></i> Start: {{ $quiz->start_date->format('M d, Y H:i') }}</div>
                                <div><i class="far fa-calendar-alt"></i> End: {{ $quiz->end_date->format('M d, Y H:i') }}</div>
                                <div><i class="far fa-clock"></i> Duration: {{ $quiz->duration_minutes }} minutes</div>
                            </div>
                            
                            <div class="mt-2">
                                @if($quiz->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="btn-group w-100">
                                <a href="{{ route('lecturer.quizzes.edit', $quiz->id) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="{{ route('lecturer.quizzes.results', $quiz->id) }}" 
                                   class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-chart-bar"></i> Results
                                </a>
                                <form action="{{ route('lecturer.quizzes.destroy', $quiz->id) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('Delete this quiz?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle"></i> No quizzes created yet. 
                        <a href="{{ route('lecturer.quizzes.create') }}">Create your first quiz!</a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>