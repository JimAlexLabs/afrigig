<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\SkillAssessment;
use App\Models\SkillAssessmentAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkillAssessmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $query = SkillAssessment::query()->with('skill');

        if ($request->has('skill')) {
            $query->where('skill_id', $request->skill);
        }

        if ($request->has('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        $assessments = $query->active()->paginate(10);

        return view('skill-assessments.index', compact('assessments'));
    }

    public function show(SkillAssessment $assessment)
    {
        $this->authorize('view', $assessment);

        $lastAttempt = $assessment->attempts()
            ->where('user_id', Auth::id())
            ->latest()
            ->first();

        return view('skill-assessments.show', compact('assessment', 'lastAttempt'));
    }

    public function start(SkillAssessment $assessment)
    {
        $this->authorize('attempt', $assessment);

        if (!$assessment->isAvailableForUser(Auth::user())) {
            return back()->with('error', 'You cannot attempt this assessment at this time.');
        }

        $attempt = SkillAssessmentAttempt::create([
            'user_id' => Auth::id(),
            'skill_assessment_id' => $assessment->id,
            'started_at' => now()
        ]);

        return redirect()->route('skill-assessments.attempt', $attempt);
    }

    public function attempt(SkillAssessmentAttempt $attempt)
    {
        $this->authorize('view', $attempt);

        if ($attempt->isCompleted()) {
            return redirect()->route('skill-assessments.results', $attempt);
        }

        if ($attempt->getTimeRemainingAttribute() <= 0) {
            $attempt->complete($attempt->answers ?? []);
            return redirect()->route('skill-assessments.results', $attempt)
                ->with('warning', 'Time limit exceeded. Your attempt has been submitted.');
        }

        return view('skill-assessments.attempt', compact('attempt'));
    }

    public function submit(Request $request, SkillAssessmentAttempt $attempt)
    {
        $this->authorize('update', $attempt);

        if ($attempt->isCompleted()) {
            return redirect()->route('skill-assessments.results', $attempt);
        }

        $feedback = $attempt->complete($request->input('answers', []));

        return redirect()->route('skill-assessments.results', $attempt);
    }

    public function results(SkillAssessmentAttempt $attempt)
    {
        $this->authorize('view', $attempt);

        if (!$attempt->isCompleted()) {
            return redirect()->route('skill-assessments.attempt', $attempt);
        }

        $feedback = $attempt->assessment->generateFeedback($attempt);

        return view('skill-assessments.results', compact('attempt', 'feedback'));
    }

    public function history()
    {
        $attempts = Auth::user()->skillAssessmentAttempts()
            ->with(['assessment.skill'])
            ->latest()
            ->paginate(10);

        return view('skill-assessments.history', compact('attempts'));
    }
} 