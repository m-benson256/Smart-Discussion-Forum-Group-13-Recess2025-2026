<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3><i class="fas fa-chart-bar"></i> Quiz Results: {{ $quiz->title }}</h3>
            </div>
            <div class="card-body">
                <!-- Statistics -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h4>Total Attempts</h4>
                                <h2>{{ $stats['total_attempts'] }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h4>Average Score</h4>
                                <h2>{{ number_format($stats['average_score'], 1) }}%</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-dark">
                            <div class="card-body text-center">
                                <h4>Highest Score</h4>
                                <h2>{{ number_format($stats['highest_score'], 1) }}%</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body text-center">
                                <h4>Lowest Score</h4>
                                <h2>{{ number_format($stats['lowest_score'], 1) }}%</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attempts Table -->
                <h5>Student Attempts</h5>
                @if($attempts->count() > 0)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student</th>
                                <th>Score</th>
                                <th>Attempted</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attempts as $attempt)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $attempt->student->name ?? 'Unknown' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $attempt->score >= 70 ? 'success' : ($attempt->score >= 50 ? 'warning' : 'danger') }}">
                                            {{ number_format($attempt->score, 1) }}%
                                        </span>
                                    </td>
                                    <td>{{ $attempt->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <span class="badge bg-success">Submitted</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info">No attempts yet.</div>
                @endif

                <a href="{{ route('lecturer.quizzes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Quizzes
                </a>
            </div>
        </div>
    </div>
</body>
</html>