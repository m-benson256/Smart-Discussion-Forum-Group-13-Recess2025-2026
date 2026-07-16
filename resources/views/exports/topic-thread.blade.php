<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1e293b; }
        .header { border-bottom: 2px solid #1a2e4c; margin-bottom: 20px; padding-bottom: 12px; }
        .header h1 { font-size: 18px; margin: 0 0 6px 0; color: #1a2e4c; }
        .header .meta { font-size: 10px; color: #64748b; }
        .topic-content { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px; margin-bottom: 20px; }
        .message { margin-bottom: 14px; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; }
        .message .meta { font-size: 10px; color: #64748b; margin-bottom: 4px; }
        .message .author { font-weight: bold; color: #334155; }
        .message .body { white-space: pre-wrap; }
        .footer { margin-top: 20px; font-size: 9px; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $topic->title }}</h1>
        <div class="meta">
            Started by {{ $topic->user->name ?? 'Unknown' }} &middot;
            Exported {{ now()->format('M d, Y H:i') }} by {{ $exportedBy }}
        </div>
    </div>

    <div class="topic-content">
        {{ $topic->content }}
    </div>

    @forelse ($messages as $message)
        <div class="message">
            <div class="meta">
                <span class="author">{{ $message->user->name ?? 'Deleted user' }}</span>
                &middot; {{ $message->created_at->format('M d, Y H:i') }}
            </div>
            <div class="body">{{ $message->body }}</div>
        </div>
    @empty
        <p>No replies in this thread yet.</p>
    @endforelse

    <div class="footer">
        Generated from Smart Discussion Forum
    </div>
</body>
</html>