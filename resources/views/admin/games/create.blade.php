@extends('layouts.admin')
@section('title', __('Create New Competition'))
@section('content')
<div class="py-12 relative overflow-hidden" x-data="gameCreator()" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 relative z-10">

        {{-- Page Header --}}
        <div class="mb-10" data-aos="fade-down">
            <a href="{{ route('admin.games.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium hover:underline mb-3" style="color: var(--color-text-muted);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                {{ __('Back to Competitions') }}
            </a>
            <h1 class="text-4xl font-extrabold"><span class="text-gradient">{{ __('Create New Competition') }}</span></h1>
            <p class="mt-2 text-sm" style="color: var(--color-text-muted);">{{ __('Set up a live quiz competition for your students.') }}</p>
        </div>

        {{-- Step Indicator --}}
        <div class="flex items-center justify-center gap-2 mb-10" data-aos="fade-up">
            <template x-for="(label, idx) in stepLabels" :key="idx">
                <div class="flex items-center gap-2">
                    <button type="button" @click="currentStep = idx + 1"
                            class="flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold transition-all duration-300"
                            :class="currentStep === idx + 1
                                ? 'bg-primary-500 text-white shadow-lg shadow-primary-500/30 scale-105'
                                : (currentStep > idx + 1
                                    ? 'bg-emerald-500/15 text-emerald-500'
                                    : '')"
                            :style="currentStep !== idx + 1 && currentStep <= idx + 1 ? 'background: var(--color-surface-hover); color: var(--color-text-muted);' : ''">
                        <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-extrabold"
                              :class="currentStep > idx + 1 ? 'bg-emerald-500 text-white' : (currentStep === idx + 1 ? 'bg-white/20 text-white' : '')"
                              :style="currentStep <= idx + 1 && currentStep !== idx + 1 ? 'background: var(--color-border); color: var(--color-text-muted);' : ''">
                            <template x-if="currentStep > idx + 1"><span>✓</span></template>
                            <template x-if="currentStep <= idx + 1"><span x-text="idx + 1"></span></template>
                        </span>
                        <span x-text="label" class="hidden sm:inline"></span>
                    </button>
                    <template x-if="idx < stepLabels.length - 1">
                        <div class="w-8 h-0.5 rounded-full transition-colors duration-300"
                             :class="currentStep > idx + 1 ? 'bg-emerald-500' : ''"
                             :style="currentStep <= idx + 1 ? 'background: var(--color-border);' : ''"></div>
                    </template>
                </div>
            </template>
        </div>

        @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 font-medium" data-aos="shake">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.games.store') }}" @submit="submitForm($event)">
            @csrf

            {{-- ========== STEP 1: Basic Info ========== --}}
            <div x-show="currentStep === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="glass-card overflow-hidden" data-aos="fade-up">
                    <div class="glass-card-header">
                        <h2 class="text-lg font-bold flex items-center gap-2" style="color: var(--color-text);">
                            <span class="w-8 h-8 rounded-lg bg-primary-500/15 flex items-center justify-center text-primary-500">📝</span>
                            {{ __('Competition Info') }}
                        </h2>
                    </div>
                    <div class="glass-card-body space-y-5">
                        <div>
                            <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Competition Name') }} *</label>
                            <input type="text" name="title" required
                                   class="input-glass w-full"
                                   placeholder="{{ __('Competition Name Placeholder') }}"
                                   value="{{ old('title') }}">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Start Time') }} *</label>
                                <input type="datetime-local" name="start_time" required
                                       class="input-glass w-full"
                                       value="{{ old('start_time') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Description') }}</label>
                                <textarea name="description" class="input-glass w-full" rows="1"
                                          placeholder="{{ __('Description Placeholder') }}">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="glass-card-footer flex justify-end">
                        <button type="button" @click="currentStep = 2" class="btn-primary ripple-btn">
                            {{ __('Next') }} →
                        </button>
                    </div>
                </div>
            </div>

            {{-- ========== STEP 2: Course & Level ========== --}}
            <div x-show="currentStep === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="glass-card overflow-hidden" data-aos="fade-up">
                    <div class="glass-card-header">
                        <h2 class="text-lg font-bold flex items-center gap-2" style="color: var(--color-text);">
                            <span class="w-8 h-8 rounded-lg bg-blue-500/15 flex items-center justify-center text-blue-500">📚</span>
                            {{ __('Choose Course & Level') }}
                        </h2>
                    </div>
                    <div class="glass-card-body space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Select Course') }} *</label>
                                <select name="course_id" x-model="courseId" @change="onCourseChange()" class="input-glass w-full" required>
                                    <option value="">{{ __('Choose Course') }}</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('Minimum Lesson') }}</label>
                                <select name="min_lesson_id" x-model="lessonId" @change="fetchStudents()" class="input-glass w-full">
                                    <option value="">{{ __('All Enrolled Students') }}</option>
                                    <template x-for="lesson in filteredLessons" :key="lesson.id">
                                        <option :value="lesson.id" x-text="lesson.title"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        {{-- Eligible Students Counter --}}
                        <div class="flex items-center gap-4 p-5 rounded-2xl relative overflow-hidden" style="background: var(--color-bg-elevated);">
                            <div class="absolute inset-0 opacity-5 bg-gradient-to-r from-primary-500 to-accent-500"></div>
                            <div class="relative z-10 w-14 h-14 rounded-2xl bg-primary-500/15 flex items-center justify-center text-3xl">👥</div>
                            <div class="relative z-10">
                                <div class="text-xs font-medium mb-0.5" style="color: var(--color-text-muted);">{{ __('Eligible Students') }}</div>
                                <div class="text-3xl font-extrabold text-primary-500" x-text="eligibleCount"></div>
                            </div>
                            <template x-if="loading">
                                <div class="relative z-10 ml-auto">
                                    <div class="animate-spin w-6 h-6 border-2 border-primary-500 border-t-transparent rounded-full"></div>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="glass-card-footer flex justify-between">
                        <button type="button" @click="currentStep = 1" class="btn-secondary">← {{ __('Back') }}</button>
                        <button type="button" @click="currentStep = 3" class="btn-primary ripple-btn">{{ __('Next') }} →</button>
                    </div>
                </div>
            </div>

            {{-- ========== STEP 3: Teams ========== --}}
            <div x-show="currentStep === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="glass-card overflow-hidden" data-aos="fade-up">
                    <div class="glass-card-header">
                        <h2 class="text-lg font-bold flex items-center gap-2" style="color: var(--color-text);">
                            <span class="w-8 h-8 rounded-lg bg-emerald-500/15 flex items-center justify-center text-emerald-500">⚔️</span>
                            {{ __('Teams') }}
                        </h2>
                    </div>
                    <div class="glass-card-body space-y-5">
                        <div class="flex items-center gap-4">
                            <label class="text-sm font-bold" style="color: var(--color-text);">{{ __('Team Count') }}</label>
                            <div class="flex items-center gap-2">
                                <button type="button" @click="teamCount = Math.max(2, teamCount - 1); generateTeams()"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center text-lg font-bold transition-colors hover:bg-primary-500/10 hover:text-primary-500"
                                        style="background: var(--color-surface-hover); color: var(--color-text);">−</button>
                                <input type="number" name="team_count" x-model.number="teamCount" @change="generateTeams()"
                                       class="input-glass w-16 text-center font-bold text-lg" min="2" max="20">
                                <button type="button" @click="teamCount = Math.min(20, teamCount + 1); generateTeams()"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center text-lg font-bold transition-colors hover:bg-primary-500/10 hover:text-primary-500"
                                        style="background: var(--color-surface-hover); color: var(--color-text);">+</button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <template x-for="(team, index) in teams" :key="index">
                                <div class="flex items-center gap-3 p-4 rounded-xl transition-all duration-200 hover:shadow-md group"
                                     style="background: var(--color-bg-elevated); border: 1px solid var(--color-border);">
                                    <input type="color" :name="'team_colors[' + index + ']'" x-model="team.color"
                                           class="w-10 h-10 rounded-xl border-0 cursor-pointer shrink-0 shadow-sm"
                                           style="padding: 2px;">
                                    <div class="flex-1">
                                        <input type="text" :name="'team_names[' + index + ']'" x-model="team.name"
                                               class="input-glass w-full text-sm font-semibold" :placeholder="'Team ' + (index + 1)">
                                    </div>
                                    <div class="text-xs font-bold px-2 py-1 rounded-lg bg-primary-500/10 text-primary-500 shrink-0"
                                         x-text="Math.floor(eligibleCount / teamCount) + (index < eligibleCount % teamCount ? 1 : 0) + ' {{ __('student') }}'">
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="glass-card-footer flex justify-between">
                        <button type="button" @click="currentStep = 2" class="btn-secondary">← {{ __('Back') }}</button>
                        <button type="button" @click="currentStep = 4" class="btn-primary ripple-btn">{{ __('Next') }} →</button>
                    </div>
                </div>
            </div>

            {{-- ========== STEP 4: Questions ========== --}}
            <div x-show="currentStep === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="glass-card overflow-hidden" data-aos="fade-up">
                    <div class="glass-card-header flex items-center justify-between">
                        <h2 class="text-lg font-bold flex items-center gap-2" style="color: var(--color-text);">
                            <span class="w-8 h-8 rounded-lg bg-amber-500/15 flex items-center justify-center text-amber-500">❓</span>
                            {{ __('Questions') }}
                        </h2>
                        <span class="text-xs font-bold px-3 py-1 rounded-full bg-primary-500/10 text-primary-500"
                              x-text="questions.length + ' {{ __('Question') }}'"></span>
                    </div>
                    <div class="glass-card-body space-y-5">

                        <template x-for="(question, qIndex) in questions" :key="qIndex">
                            <div class="rounded-2xl relative overflow-hidden transition-all duration-200 hover:shadow-md"
                                 style="background: var(--color-bg-elevated); border: 1px solid var(--color-border);">

                                {{-- Question Header --}}
                                <div class="flex items-center justify-between p-4 pb-0">
                                    <div class="flex items-center gap-2">
                                        <span class="w-7 h-7 rounded-lg bg-primary-500 text-white flex items-center justify-center text-xs font-extrabold"
                                              x-text="qIndex + 1"></span>
                                        <span class="text-sm font-bold" style="color: var(--color-text);">{{ __('Question') }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <input type="number" :name="'questions[' + qIndex + '][time_limit]'"
                                                   x-model.number="question.time_limit"
                                                   class="input-glass w-14 text-center text-xs font-bold" min="10" max="300">
                                            <span class="text-xs" style="color: var(--color-text-muted);">{{ __('sec') }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <input type="number" :name="'questions[' + qIndex + '][points]'"
                                                   x-model.number="question.points"
                                                   class="input-glass w-16 text-center text-xs font-bold" min="10" max="1000">
                                            <span class="text-xs" style="color: var(--color-text-muted);">{{ __('pts') }}</span>
                                        </div>
                                        <button type="button" @click="removeQuestion(qIndex)" x-show="questions.length > 1"
                                                class="w-7 h-7 rounded-lg flex items-center justify-center text-red-400 hover:bg-red-500/10 hover:text-red-500 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- Question Text --}}
                                <div class="px-4 pt-3">
                                    <input type="text" :name="'questions[' + qIndex + '][text]'" x-model="question.text"
                                           class="input-glass w-full font-medium" placeholder="{{ __('Write question here') }}" required>
                                </div>

                                {{-- Options --}}
                                <div class="p-4 space-y-2">
                                    <p class="text-xs font-medium mb-2" style="color: var(--color-text-muted);">{{ __('Click radio to mark correct answer') }}</p>
                                    <template x-for="(opt, oIndex) in question.options" :key="oIndex">
                                        <div class="flex items-center gap-2 group">
                                            <label class="relative cursor-pointer">
                                                <input type="radio" :name="'q_correct_' + qIndex" :value="opt"
                                                       @change="question.correct = opt"
                                                       :checked="question.correct === opt"
                                                       class="sr-only peer">
                                                <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-500"
                                                     style="border-color: var(--color-border);">
                                                    <div class="w-2 h-2 rounded-full bg-white opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                                </div>
                                            </label>
                                            <span class="text-xs font-extrabold w-5 text-center shrink-0"
                                                  :class="question.correct === opt ? 'text-emerald-500' : ''"
                                                  :style="question.correct !== opt ? 'color: var(--color-text-muted);' : ''"
                                                  x-text="['A','B','C','D','E','F'][oIndex]"></span>
                                            <input type="text" :name="'questions[' + qIndex + '][options][' + oIndex + ']'"
                                                   x-model="question.options[oIndex]"
                                                   class="input-glass flex-1 text-sm" :placeholder="'{{ __('Option') }} ' + (oIndex + 1)" required>
                                            <button type="button" x-show="question.options.length > 2"
                                                    @click="question.options.splice(oIndex, 1)"
                                                    class="opacity-0 group-hover:opacity-100 text-red-400 hover:text-red-500 transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                    </template>

                                    <input type="hidden" :name="'questions[' + qIndex + '][correct]'" :value="question.correct">

                                    <button type="button" @click="question.options.push('')" x-show="question.options.length < 6"
                                            class="text-xs text-primary-500 hover:underline font-bold mt-1 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        {{ __('Add Option') }}
                                    </button>
                                </div>
                            </div>
                        </template>

                        {{-- Add Question Button --}}
                        <button type="button" @click="addQuestion()"
                                class="w-full py-4 rounded-2xl border-2 border-dashed text-sm font-bold transition-all duration-200 hover:border-primary-500 hover:text-primary-500 hover:bg-primary-500/5 flex items-center justify-center gap-2"
                                style="border-color: var(--color-border); color: var(--color-text-muted);">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            {{ __('Add New Question') }}
                        </button>
                    </div>
                    <div class="glass-card-footer flex justify-between">
                        <button type="button" @click="currentStep = 3" class="btn-secondary">← {{ __('Back') }}</button>
                        <button type="submit" class="btn-primary ripple-btn px-8" :disabled="submitting">
                            <span x-show="!submitting" class="flex items-center gap-2">
                                🚀 {{ __('Create Competition') }}
                            </span>
                            <span x-show="submitting" class="flex items-center gap-2">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                                {{ __('Creating...') }}
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function gameCreator() {
    const allLessons = @json($courses->flatMap->lessons->map(fn($l) => ['id' => $l->id, 'course_id' => $l->course_id, 'title' => $l->title])->values());
    const locale = '{{ app()->getLocale() }}';

    return {
        currentStep: 1,
        stepLabels: locale === 'ar'
            ? [__('المعلومات'), __('الكورس'), __('الفرق'), __('الأسئلة')]
            : ['Info', 'Course', 'Teams', 'Questions'],
        courseId: '',
        lessonId: '',
        eligibleCount: 0,
        loading: false,
        submitting: false,
        teamCount: 2,
        teams: [
            { name: locale === 'ar' ? __('الفريق الأحمر') : 'Red Team', color: '#ef4444' },
            { name: locale === 'ar' ? __('الفريق الأزرق') : 'Blue Team', color: '#3b82f6' },
        ],
        questions: [
            { text: '', options: ['', '', '', ''], correct: '', time_limit: 30, points: 100 },
        ],

        get filteredLessons() {
            if (!this.courseId) return [];
            return allLessons.filter(l => l.course_id == this.courseId);
        },

        onCourseChange() {
            this.lessonId = '';
            this.fetchStudents();
        },

        async fetchStudents() {
            if (!this.courseId) { this.eligibleCount = 0; return; }
            this.loading = true;
            try {
                const params = new URLSearchParams({ course_id: this.courseId });
                if (this.lessonId) params.append('min_lesson_id', this.lessonId);
                const res = await fetch(`{{ route('admin.games.eligible-students') }}?${params}`);
                const data = await res.json();
                this.eligibleCount = data.count;
            } catch (e) {
                console.error(e);
            }
            this.loading = false;
        },

        generateTeams() {
            const colors = ['#ef4444','#3b82f6','#10b981','#f59e0b','#8b5cf6','#ec4899','#14b8a6','#f97316','#6366f1','#84cc16'];
            const namesAr = [__('الفريق الأحمر'),__('الفريق الأزرق'),'الفريق الأخضر','الفريق الذهبي','الفريق البنفسجي','الفريق الوردي','الفريق التركواز','الفريق البرتقالي','الفريق النيلي','الفريق الليموني'];
            const namesEn = ['Red Team','Blue Team','Green Team','Gold Team','Purple Team','Pink Team','Teal Team','Orange Team','Indigo Team','Lime Team'];
            const names = locale === 'ar' ? namesAr : namesEn;
            this.teams = [];
            for (let i = 0; i < this.teamCount; i++) {
                this.teams.push({
                    name: names[i] || 'Team ' + (i + 1),
                    color: colors[i] || '#3b82f6',
                });
            }
        },

        addQuestion() {
            this.questions.push({ text: '', options: ['', '', '', ''], correct: '', time_limit: 30, points: 100 });
        },

        removeQuestion(index) {
            this.questions.splice(index, 1);
        },

        submitForm(e) {
            for (let i = 0; i < this.questions.length; i++) {
                if (!this.questions[i].correct) {
                    e.preventDefault();
                    alert('{{ __("Select correct answer for question") }} #' + (i + 1));
                    this.currentStep = 4;
                    return;
                }
            }
            this.submitting = true;
        }
    };
}
</script>
@endsection
