@inject('pixabay', 'App\Services\PixabayService')

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Skill Assessments') }}
            </h2>
            @can('create', App\Models\SkillAssessment::class)
                <a href="{{ route('admin.skill-assessments.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Create New Assessment') }}
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($image = $pixabay->getSkillAssessmentImage())
                <div class="mb-8">
                    <x-feature-image 
                        :imageUrl="$image['largeImageURL']" 
                        :alt="'Skill Assessment Feature'"
                        class="h-64 w-full"
                    />
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($assessments->isEmpty())
                        <p class="text-center text-gray-500 py-8">
                            {{ __('No skill assessments available.') }}
                        </p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($assessments as $assessment)
                                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                                    <h3 class="text-lg font-semibold mb-2">{{ $assessment->title }}</h3>
                                    <p class="text-gray-600 mb-4">{{ Str::limit($assessment->description, 100) }}</p>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-500">
                                            {{ $assessment->attempts_count ?? 0 }} attempts
                                        </span>
                                        <a href="{{ route('admin.skill-assessments.show', $assessment) }}" 
                                           class="text-blue-500 hover:text-blue-700">
                                            View Details →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-6">
                            {{ $assessments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 