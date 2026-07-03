<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3><i class="fas fa-edit"></i> Edit Quiz: {{ $quiz->title }}</h3>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <!-- Quiz Details Form -->
                        <h5>Quiz Details</h5>
                        <form action="{{ route('lecturer.quizzes.update', $quiz->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Title</label>
                                    <input type="text" name="title" class="form-control" value="{{ $quiz->title }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Category</label>
                                    <select name="category_id" class="form-control">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ $quiz->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>Description</label>
                                <textarea name="description" class="form-control" rows="2">{{ $quiz->description }}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label>Start Date</label>
                                    <input type="datetime-local" name="start_date" class="form-control" 
                                           value="{{ $quiz->start_date->format('Y-m-d\TH:i') }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>End Date</label>
                                    <input type="datetime-local" name="end_date" class="form-control" 
                                           value="{{ $quiz->end_date->format('Y-m-d\TH:i') }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Duration (minutes)</label>
                                    <input type="number" name="duration_minutes" class="form-control" 
                                           value="{{ $quiz->duration_minutes }}" min="1" max="180" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" class="form-check-input" value="1" 
                                           {{ $quiz->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label">Active</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Quiz Details</button>
                        </form>

                        <hr>

                        <!-- Questions Section -->
                        <h5 class="mt-4">Questions</h5>
                        @if($quiz->questions->count() > 0)
                            @foreach($quiz->questions as $question)
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <strong>{{ $question->question_text }}</strong>
                                                <br>
                                                <small class="text-muted">Type: {{ $question->question_type }} | Points: {{ $question->points }}</small>
                                                <br>
                                                @foreach($question->options as $option)
                                                    <span class="badge bg-{{ $option->is_correct ? 'success' : 'secondary' }}">
                                                        {{ $option->option_text }}
                                                        @if($option->is_correct) ✓ @endif
                                                    </span>
                                                @endforeach
                                            </div>
                                            <form action="{{ route('lecturer.quizzes.delete-question', [$quiz->id, $question->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this question?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="alert alert-info">No questions added yet.</div>
                        @endif

                        <!-- Add Question Form -->
                        <div class="card mt-4">
                            <div class="card-header bg-light">
                                <h6><i class="fas fa-plus"></i> Add New Question</h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('lecturer.quizzes.add-question', $quiz->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label>Question Text</label>
                                        <textarea name="question_text" class="form-control" rows="2" required></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label>Question Type</label>
                                            <select name="question_type" class="form-control" id="questionType" required>
                                                <option value="multiple_choice">Multiple Choice</option>
                                                <option value="true_false">True/False</option>
                                                <option value="short_answer">Short Answer</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label>Points</label>
                                            <input type="number" name="points" class="form-control" value="1" min="1" required>
                                        </div>
                                    </div>
                                    
                                    <!-- Multiple Choice Options -->
                                    <div id="multipleChoiceOptions">
                                        <label>Options (4 options)</label>
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <input type="text" name="options[]" class="form-control" placeholder="Option A">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <input type="text" name="options[]" class="form-control" placeholder="Option B">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <input type="text" name="options[]" class="form-control" placeholder="Option C">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <input type="text" name="options[]" class="form-control" placeholder="Option D">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label>Correct Option (0-3)</label>
                                            <input type="number" name="correct_option" class="form-control" min="0" max="3" value="0">
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Add Question</button>
                                </form>
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('lecturer.quizzes.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Quizzes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('questionType').addEventListener('change', function() {
            const optionsDiv = document.getElementById('multipleChoiceOptions');
            if (this.value === 'multiple_choice') {
                optionsDiv.style.display = 'block';
            } else {
                optionsDiv.style.display = 'none';
            }
        });
    </script>
</body>
</html>