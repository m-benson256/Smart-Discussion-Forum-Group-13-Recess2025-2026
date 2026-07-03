<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Quizzes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4"><i class="fas fa-puzzle-piece"></i> Available Quizzes</h1>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Available Quizzes -->
        <h3>Available Quizzes</h3>
        <div class="row">
            @forelse($availableQuizzes as $quiz)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">{{ $quiz->title }}</h5>
                            <p class="card-text">{{ Str::limit($quiz->description, 100) }}</p>
                            <div class="small text-muted">
                                <div>Duration: {{ $quiz->duration_minutes }} minutes</div>
                                <div>Questions: {{ $quiz->questions->count() }}</div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <a href="{{ route('student.quizzes.start', $quiz->id) }}" class="btn btn-primary w-100">
                                <i class="fas fa-play"></i> Start Quiz
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">No quizzes available right now.</div>
                </div>
            @endforelse
        </div>

        <!-- Past Quizzes -->
        @if($pastQuizzes->count() > 0)
            <h3 class="mt-4">Past Quizzes</h3>
            <div class="row">
                @foreach($pastQuizzes as $quiz)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm bg-light">
                            <div class="card-body">
                                <h5 class="card-title">{{ $quiz->title }}</h5>
                                <p class="card-text">{{ Str::limit($quiz->description, 100) }}</p>
                                <div class="small text-muted">
                                    <div><i class="far fa-calendar-alt"></i> Ended: {{ $quiz->end_date->format('M d, Y') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>