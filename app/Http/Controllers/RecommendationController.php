<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use App\Models\Topic;

class RecommendationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $recommendedTopicIds = [];

        // 1. Try collaborative filtering first, via the Python service
        try {
            $response = Http::timeout(3)->get("http://127.0.0.1:8001/recommendations/{$user->id}");

            if ($response->successful()) {
                $recommendedTopicIds = $response->json('recommended_topic_ids', []);
            }
        } catch (\Exception $e) {
            // Python service unreachable or timed out — silently fall through to content-based
        }

        // 2. If collaborative filtering had nothing to offer, fall back to interest-matching
        if (empty($recommendedTopicIds)) {
            $interestIds = $user->interests()->pluck('user_interests.InterestID');

            if ($interestIds->isNotEmpty()) {
                $topics = Topic::whereIn('interest_id', $interestIds)
                    ->with('user:id,name')
                    ->latest()
                    ->get();

                return response()->json($topics);
            }

            return response()->json([]);
        }

        // 3. Collaborative filtering gave us topic IDs — fetch their full details
        $topics = Topic::whereIn('id', $recommendedTopicIds)
            ->with('user:id,name')
            ->latest()
            ->get();

        return response()->json($topics);
    }

    public function interactionData(): JsonResponse
    {
        $views = \App\Models\TopicView::select('user_id', 'topic_id', 'view_count')->get();

        return response()->json($views);
    }
}

