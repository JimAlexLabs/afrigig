<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SkillAssessment;
use App\Models\Skill;
use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Facades\Activity;

class SkillAssessmentController extends Controller
{
    /**
     * Display a listing of skill assessments.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = SkillAssessment::with(['skill', 'questions']);
        
        // Apply filters
        if ($request->has('skill')) {
            $query->where('skill_id', $request->skill);
        }
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Get paginated results
        $assessments = $query->latest()->paginate(20);
        
        // Get skills for filter
        $skills = Skill::all();
        
        return view('admin.assessments.index', compact('assessments', 'skills'));
    }
    
    /**
     * Show the form for creating a new assessment.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $skills = Skill::all();
        
        return view('admin.assessments.create', compact('skills'));
    }
    
    /**
     * Store a newly created assessment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'skill_id' => 'required|exists:skills,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'time_limit' => 'required|integer|min:5',
            'passing_score' => 'required|integer|min:1|max:100',
            'questions' => 'required|array|min:1',
            'questions.*.content' => 'required|string',
            'questions.*.type' => 'required|in:multiple_choice,true_false,coding',
            'questions.*.options' => 'required_if:questions.*.type,multiple_choice|array|min:2',
            'questions.*.correct_answer' => 'required|string',
            'questions.*.points' => 'required|integer|min:1'
        ]);
        
        try {
            DB::beginTransaction();
            
            // Create assessment
            $assessment = SkillAssessment::create([
                'skill_id' => $request->skill_id,
                'title' => $request->title,
                'description' => $request->description,
                'time_limit' => $request->time_limit,
                'passing_score' => $request->passing_score,
                'status' => 'active'
            ]);
            
            // Create questions
            foreach ($request->questions as $questionData) {
                $question = $assessment->questions()->create([
                    'content' => $questionData['content'],
                    'type' => $questionData['type'],
                    'options' => $questionData['options'] ?? null,
                    'correct_answer' => $questionData['correct_answer'],
                    'points' => $questionData['points']
                ]);
                
                // Handle code files for coding questions
                if ($questionData['type'] === 'coding' && isset($questionData['test_cases'])) {
                    foreach ($questionData['test_cases'] as $testCase) {
                        $question->testCases()->create([
                            'input' => $testCase['input'],
                            'expected_output' => $testCase['expected_output']
                        ]);
                    }
                }
            }
            
            DB::commit();
            
            // Log the action
            activity()
                ->performedOn($assessment)
                ->log('created skill assessment');
            
            return redirect()->route('admin.assessments.show', $assessment)
                ->with('success', 'Skill assessment created successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create assessment: ' . $e->getMessage());
        }
    }
    
    /**
     * Display the specified assessment.
     *
     * @param  \App\Models\SkillAssessment  $assessment
     * @return \Illuminate\View\View
     */
    public function show(SkillAssessment $assessment)
    {
        $assessment->load([
            'skill',
            'questions.testCases',
            'attempts.user'
        ]);
        
        // Get activity log
        $activities = DB::table('activity_log')
            ->where('subject_type', 'App\Models\SkillAssessment')
            ->where('subject_id', $assessment->id)
            ->latest()
            ->take(50)
            ->get();
            
        return view('admin.assessments.show', compact('assessment', 'activities'));
    }
    
    /**
     * Show the form for editing the assessment.
     *
     * @param  \App\Models\SkillAssessment  $assessment
     * @return \Illuminate\View\View
     */
    public function edit(SkillAssessment $assessment)
    {
        $assessment->load(['skill', 'questions.testCases']);
        
        $skills = Skill::all();
        
        return view('admin.assessments.edit', compact('assessment', 'skills'));
    }
    
    /**
     * Update the specified assessment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SkillAssessment  $assessment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SkillAssessment $assessment)
    {
        $request->validate([
            'skill_id' => 'required|exists:skills,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'time_limit' => 'required|integer|min:5',
            'passing_score' => 'required|integer|min:1|max:100',
            'status' => 'required|in:active,inactive',
            'questions' => 'required|array|min:1',
            'questions.*.id' => 'sometimes|exists:questions,id',
            'questions.*.content' => 'required|string',
            'questions.*.type' => 'required|in:multiple_choice,true_false,coding',
            'questions.*.options' => 'required_if:questions.*.type,multiple_choice|array|min:2',
            'questions.*.correct_answer' => 'required|string',
            'questions.*.points' => 'required|integer|min:1'
        ]);
        
        try {
            DB::beginTransaction();
            
            // Update assessment
            $assessment->update([
                'skill_id' => $request->skill_id,
                'title' => $request->title,
                'description' => $request->description,
                'time_limit' => $request->time_limit,
                'passing_score' => $request->passing_score,
                'status' => $request->status
            ]);
            
            // Get existing question IDs
            $existingQuestionIds = $assessment->questions->pluck('id')->toArray();
            $updatedQuestionIds = [];
            
            // Update or create questions
            foreach ($request->questions as $questionData) {
                if (isset($questionData['id'])) {
                    // Update existing question
                    $question = Question::find($questionData['id']);
                    $question->update([
                        'content' => $questionData['content'],
                        'type' => $questionData['type'],
                        'options' => $questionData['options'] ?? null,
                        'correct_answer' => $questionData['correct_answer'],
                        'points' => $questionData['points']
                    ]);
                    
                    $updatedQuestionIds[] = $question->id;
                } else {
                    // Create new question
                    $question = $assessment->questions()->create([
                        'content' => $questionData['content'],
                        'type' => $questionData['type'],
                        'options' => $questionData['options'] ?? null,
                        'correct_answer' => $questionData['correct_answer'],
                        'points' => $questionData['points']
                    ]);
                    
                    $updatedQuestionIds[] = $question->id;
                }
                
                // Handle test cases for coding questions
                if ($questionData['type'] === 'coding' && isset($questionData['test_cases'])) {
                    // Remove existing test cases
                    $question->testCases()->delete();
                    
                    // Create new test cases
                    foreach ($questionData['test_cases'] as $testCase) {
                        $question->testCases()->create([
                            'input' => $testCase['input'],
                            'expected_output' => $testCase['expected_output']
                        ]);
                    }
                }
            }
            
            // Delete questions that were not updated
            $questionsToDelete = array_diff($existingQuestionIds, $updatedQuestionIds);
            Question::whereIn('id', $questionsToDelete)->delete();
            
            DB::commit();
            
            // Log the action
            activity()
                ->performedOn($assessment)
                ->withProperties(['status' => $request->status])
                ->log('updated skill assessment');
            
            return redirect()->route('admin.assessments.show', $assessment)
                ->with('success', 'Skill assessment updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update assessment: ' . $e->getMessage());
        }
    }
    
    /**
     * Remove the specified assessment.
     *
     * @param  \App\Models\SkillAssessment  $assessment
     * @return \Illuminate\Http\Response
     */
    public function destroy(SkillAssessment $assessment)
    {
        try {
            DB::beginTransaction();
            
            // Delete all related questions and test cases
            foreach ($assessment->questions as $question) {
                $question->testCases()->delete();
            }
            $assessment->questions()->delete();
            
            // Log the action before deletion
            activity()
                ->performedOn($assessment)
                ->log('deleted skill assessment');
                
            // Delete the assessment
            $assessment->delete();
            
            DB::commit();
            
            return redirect()->route('admin.assessments.index')
                ->with('success', 'Skill assessment deleted successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete assessment: ' . $e->getMessage());
        }
    }
}
