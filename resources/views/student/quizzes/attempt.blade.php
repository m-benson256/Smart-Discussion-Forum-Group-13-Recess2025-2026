<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $quiz->title }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-8">
                <h1>{{ $quiz->title }}</h1>
                <p class="text-muted">{{ $quiz->description }}</p>
            </div>
            <div class="col-md-4 text-end">
                <div class="card">
                    <div class="card-body">
                        <h5 id="timer" class="text-danger">Time Remaining: <span id="timeDisplay">00:00</span></h5>
                        <form action="{{ route('student.quizzes.submit', $attempt->id) }}" method="POST" id="submitForm">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Submit quiz?')">
                                <i class="fas fa-paper-plane"></i> Submit Quiz
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Questions -->
        <form id="quizForm">
            @csrf
            @foreach($questions as $index => $question)
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-light">
                        <strong>Question {{ $index + 1 }}</strong>
                        <span class="badge bg-primary float-end">{{ $question->points }} point(s)</span>
                    </div>
                    <div class="card-body">
                        <p>{{ $question->question_text }}</p>
                        <input type="hidden" name="questions[{{ $question->id }}]" value="{{ $question->id }}">
                        
                        @if($question->question_type == 'multiple_choice' || $question->question_type == 'true_false')
                            @foreach($question->options as $option)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" 
                                           name="answers[{{ $question->id }}]" 
                                           value="{{ $option->id }}"
                                           id="option_{{ $option->id }}"
                                           data-question="{{ $question->id }}"
                                           {{ (isset($question->studentAnswers) && $question->studentAnswers->first() && $question->studentAnswers->first()->selected_option_id == $option->id) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="option_{{ $option->id }}">
                                        {{ $option->option_text }}
                                    </label>
                                </div>
                            @endforeach
                        @elseif($question->question_type == 'short_answer')
                            <div class="mb-3">
                                <textarea class="form-control" 
                                          name="answers[{{ $question->id }}]" 
                                          rows="3"
                                          placeholder="Enter your answer here..."
                                          data-question="{{ $question->id }}">{{ (isset($question->studentAnswers) && $question->studentAnswers->first()) ? $question->studentAnswers->first()->answer_text : '' }}</textarea>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Timer
            let totalSeconds = {{ $remainingSeconds ?? 0 }};
            const timerDisplay = document.getElementById('timeDisplay');

            function updateTimer() {
                const minutes = Math.floor(totalSeconds / 60);
                const seconds = totalSeconds % 60;
                timerDisplay.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

                if (totalSeconds <= 60) {
                    timerDisplay.style.color = 'red';
                }

                if (totalSeconds <= 0) {
                    document.getElementById('submitForm').submit();
                }

                totalSeconds--;
            }

            updateTimer();
            const timerInterval = setInterval(updateTimer, 1000);

            // Auto-save answers
            let saveTimeout;

            $('input[type="radio"], textarea').on('change input', function() {
                clearTimeout(saveTimeout);
                saveTimeout = setTimeout(saveAnswer, 500);
            });

            function saveAnswer() {
                const formData = $('#quizForm').serialize();
                const attemptId = {{ $attempt->id }};

                $.ajax({
                    url: "{{ route('student.quizzes.save-answer', $attempt->id) }}",
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log('Answer saved');
                    },
                    error: function(xhr) {
                        if (xhr.status === 403) {
                            alert('Time is up! Quiz will be submitted automatically.');
                            document.getElementById('submitForm').submit();
                        }
                    }
                });
            }

            // Auto-submit when time expires
            setTimeout(function() {
                document.getElementById('submitForm').submit();
            }, {{ $remainingSeconds ?? 0 }} * 1000);
        });
    </script>
</body>
</html>