@props([
    'questionsJson' => '[]',
    'inputName' => 'listening_questions_json',
    'passingScore' => 70,
    'scoreInputName' => 'listening_passing_score',
])

<div
    x-data="listeningBuilder({{ Js::from($questionsJson) }})"
    x-init="init()"
    class="space-y-4"
    dir="ltr">

    <textarea :name="'{{ $inputName }}'" x-ref="jsonOut" class="hidden"></textarea>

    <div class="space-y-3">
        <template x-for="(q, idx) in questions" :key="idx">
            <div class="rounded-2xl p-4 border"
                 style="background:var(--color-surface);border-color:var(--color-border);">
                <div class="flex items-start gap-3">
                    <span class="shrink-0 w-8 h-8 rounded-full text-sm font-black flex items-center justify-center"
                          style="background:var(--color-primary-50);color:var(--color-primary);"
                          x-text="idx + 1"></span>

                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full"
                                  :class="q.type === 'mcq'
                                      ? 'bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300'
                                      : q.type === 'dictation'
                                          ? 'bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300'
                                          : 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300'"
                                  x-text="typeLabel(q.type)"></span>
                            <span class="text-[11px] font-bold uppercase tracking-wide" style="color:var(--color-text-muted);">
                                Question preview
                            </span>
                        </div>

                        <p class="text-base font-extrabold leading-relaxed min-h-[1.75rem]"
                           style="color:var(--color-text);"
                           dir="auto"
                           x-text="questionText(q) || 'No question text yet'"></p>

                        <template x-if="q.type === 'mcq'">
                            <ul class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <template x-for="(opt, oi) in q.options" :key="oi">
                                    <li class="text-sm flex items-center gap-2 rounded-xl px-3 py-2 border"
                                        :style="oi === q.correct_index
                                            ? 'border-color:var(--color-success,#16a34a);color:var(--color-success,#16a34a);font-weight:800;background:rgba(22,163,74,.08);'
                                            : 'border-color:var(--color-border);color:var(--color-text-muted);'">
                                        <span class="font-black" x-text="optionLabel(oi) + '.'"></span>
                                        <span dir="auto" x-text="opt"></span>
                                        <span x-show="oi === q.correct_index">✓</span>
                                    </li>
                                </template>
                            </ul>
                        </template>

                        <template x-if="q.type === 'truefalse'">
                            <p class="text-sm mt-3 font-extrabold"
                               :style="q.correct === 'true' ? 'color:var(--color-success,#16a34a);' : 'color:#dc2626;'"
                               x-text="q.correct === 'true' ? 'Correct answer: True' : 'Correct answer: False'"></p>
                        </template>

                        <template x-if="q.type === 'dictation'">
                            <p class="text-sm mt-3 font-extrabold" style="color:var(--color-success,#16a34a);">
                                Correct answer: <span dir="auto" x-text="q.correct_answer"></span>
                            </p>
                        </template>

                        <p x-show="q.explanation"
                           class="text-xs mt-3 italic"
                           style="color:var(--color-text-muted);"
                           dir="auto"
                           x-text="'Note: ' + q.explanation"></p>
                    </div>

                    <div class="shrink-0 flex gap-2">
                        <button type="button"
                                @click="editQuestion(idx)"
                                class="text-xs px-3 py-2 rounded-xl font-black hover:opacity-80 transition"
                                style="background:var(--color-surface-hover);color:var(--color-text);">
                            Edit
                        </button>
                        <button type="button"
                                @click="removeQuestion(idx)"
                                class="text-xs px-3 py-2 rounded-xl font-black hover:opacity-80 transition"
                                style="background:#fee2e2;color:#dc2626;">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </template>

        <p x-show="questions.length === 0"
           class="text-sm text-center py-6 rounded-2xl border border-dashed"
           style="color:var(--color-text-muted);border-color:var(--color-border);">
            No questions yet. Add MCQ, True/False, or Dictation questions below.
        </p>
    </div>

    <div class="flex flex-wrap gap-2">
        <button type="button"
                @click="openForm('mcq')"
                class="flex items-center gap-2 text-sm font-bold px-4 py-2 rounded-xl border-2 border-dashed border-sky-400 text-sky-600 dark:text-sky-300 hover:bg-sky-50 dark:hover:bg-sky-900/20 transition">
            + MCQ
        </button>
        <button type="button"
                @click="openForm('truefalse')"
                class="flex items-center gap-2 text-sm font-bold px-4 py-2 rounded-xl border-2 border-dashed border-amber-400 text-amber-600 dark:text-amber-300 hover:bg-amber-50 dark:hover:bg-amber-900/20 transition">
            + True / False
        </button>
        <button type="button"
                @click="openForm('dictation')"
                class="flex items-center gap-2 text-sm font-bold px-4 py-2 rounded-xl border-2 border-dashed border-violet-400 text-violet-600 dark:text-violet-300 hover:bg-violet-50 dark:hover:bg-violet-900/20 transition">
            + Dictation
        </button>
    </div>

    <div x-show="showForm"
         x-cloak
         class="rounded-2xl p-5 space-y-4 border-2"
         :class="form.type === 'mcq' ? 'border-sky-300 dark:border-sky-600' : form.type === 'dictation' ? 'border-violet-300 dark:border-violet-600' : 'border-amber-300 dark:border-amber-600'"
         style="background:var(--color-surface-hover);">

        <h5 class="font-extrabold text-sm" style="color:var(--color-text);"
            x-text="(editIdx !== null ? 'Edit ' : 'Add ') + typeLabel(form.type) + ' question'"></h5>

        <div>
            <label class="block text-xs font-semibold mb-1" style="color:var(--color-text);">Question text *</label>
            <input type="text"
                   x-model="form.question"
                   class="input-glass"
                   dir="auto"
                   placeholder="Write the question text here">
        </div>

        <template x-if="form.type === 'mcq'">
            <div class="space-y-2">
                <label class="block text-xs font-semibold" style="color:var(--color-text);">
                    Options. Click the letter button to mark the correct answer.
                </label>
                <template x-for="(opt, oi) in form.options" :key="oi">
                    <div class="flex items-center gap-2">
                        <button type="button"
                                @click="form.correct_index = oi"
                                class="shrink-0 w-8 h-8 rounded-full text-xs font-black border-2 transition"
                                :class="form.correct_index === oi
                                    ? 'border-green-500 bg-green-500 text-white'
                                    : 'border-gray-300 dark:border-gray-600 text-gray-400'"
                                x-text="optionLabel(oi)"></button>
                        <input type="text"
                               x-model="form.options[oi]"
                               class="input-glass flex-1 text-sm"
                               dir="auto"
                               :placeholder="'Option ' + optionLabel(oi)">
                    </div>
                </template>
            </div>
        </template>

        <template x-if="form.type === 'truefalse'">
            <div>
                <label class="block text-xs font-semibold mb-2" style="color:var(--color-text);">Correct answer</label>
                <div class="flex gap-3">
                    <button type="button"
                            @click="form.correct = 'true'"
                            class="flex-1 py-2 rounded-xl text-sm font-black border-2 transition"
                            :class="form.correct === 'true'
                                ? 'border-green-500 bg-green-500 text-white'
                                : 'border-gray-300 dark:border-gray-600 text-gray-500'">
                        True
                    </button>
                    <button type="button"
                            @click="form.correct = 'false'"
                            class="flex-1 py-2 rounded-xl text-sm font-black border-2 transition"
                            :class="form.correct === 'false'
                                ? 'border-red-500 bg-red-500 text-white'
                                : 'border-gray-300 dark:border-gray-600 text-gray-500'">
                        False
                    </button>
                </div>
            </div>
        </template>

        <template x-if="form.type === 'dictation'">
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:var(--color-text);">Correct answer *</label>
                    <input type="text"
                           x-model="form.correct_answer"
                           class="input-glass"
                           dir="auto"
                           placeholder="Example: fifteen or 15">
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:var(--color-text);">Accepted variants</label>
                    <input type="text"
                           x-model="form.accept_variants"
                           class="input-glass"
                           dir="auto"
                           placeholder="Example: 15, fifteen, Fifteen">
                    <p class="text-xs mt-1" style="color:var(--color-text-muted);">Separate variants with commas.</p>
                </div>
            </div>
        </template>

        <div>
            <label class="block text-xs font-semibold mb-1" style="color:var(--color-text);">Explanation</label>
            <input type="text"
                   x-model="form.explanation"
                   class="input-glass"
                   dir="auto"
                   placeholder="Optional note shown after answering">
        </div>

        <div class="flex flex-wrap gap-2 pt-1">
            <button type="button" @click="saveQuestion()" class="btn-primary text-sm px-4 py-2">
                <span x-text="editIdx !== null ? 'Save Edit' : 'Add Question'"></span>
            </button>
            <button type="button" @click="cancelForm()" class="btn-secondary text-sm px-4 py-2">
                Cancel
            </button>
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-4 pt-2 border-t" style="border-color:var(--color-border);">
        <label class="text-sm font-semibold shrink-0" style="color:var(--color-text);">Passing score (%)</label>
        <input type="number"
               name="{{ $scoreInputName }}"
               min="0"
               max="100"
               class="input-glass w-28"
               value="{{ old($scoreInputName, $passingScore) }}">
        <span class="text-xs" style="color:var(--color-text-muted);">
            Questions: <strong x-text="questions.length"></strong>
        </span>
    </div>
</div>

<script>
function listeningBuilder(initialJson) {
    return {
        questions: [],
        showForm: false,
        editIdx: null,
        form: {
            type: 'mcq',
            question: '',
            options: ['', '', '', ''],
            correct_index: 0,
            correct: 'true',
            correct_answer: '',
            accept_variants: '',
            explanation: ''
        },

        init() {
            try {
                const parsed = typeof initialJson === 'string' ? JSON.parse(initialJson) : initialJson;
                this.questions = Array.isArray(parsed) ? parsed.map((question) => this.normalizeQuestion(question)) : [];
            } catch (e) {
                this.questions = [];
            }

            this.$watch('questions', () => this.syncJson());
            this.syncJson();
        },

        normalizeQuestion(question) {
            const normalized = { ...question };
            normalized.type = normalized.type || 'mcq';
            normalized.question = this.questionText(normalized);
            normalized.options = Array.isArray(normalized.options) ? normalized.options : ['', '', '', ''];
            normalized.correct_index = Number.isInteger(normalized.correct_index) ? normalized.correct_index : parseInt(normalized.correct_index || 0, 10);
            normalized.correct = normalized.correct || 'true';
            normalized.correct_answer = normalized.correct_answer || '';
            normalized.accept_variants = Array.isArray(normalized.accept_variants) ? normalized.accept_variants : [];
            normalized.explanation = normalized.explanation || '';
            return normalized;
        },

        questionText(question) {
            return String(question?.question || question?.prompt || question?.text || '').trim();
        },

        typeLabel(type) {
            if (type === 'mcq') return 'MCQ';
            if (type === 'dictation') return 'Dictation';
            return 'True / False';
        },

        optionLabel(index) {
            return ['A', 'B', 'C', 'D'][index] || String(index + 1);
        },

        syncJson() {
            if (this.$refs.jsonOut) {
                this.$refs.jsonOut.value = JSON.stringify(this.questions.map((question) => this.normalizeQuestion(question)), null, 2);
            }
        },

        openForm(type) {
            this.editIdx = null;
            this.form = {
                type,
                question: '',
                options: ['', '', '', ''],
                correct_index: 0,
                correct: 'true',
                correct_answer: '',
                accept_variants: '',
                explanation: ''
            };
            this.showForm = true;
        },

        editQuestion(idx) {
            const question = this.normalizeQuestion(this.questions[idx]);
            this.editIdx = idx;
            this.form = {
                type: question.type,
                question: this.questionText(question),
                options: question.type === 'mcq' ? [...question.options, '', '', '', ''].slice(0, 4) : ['', '', '', ''],
                correct_index: question.correct_index ?? 0,
                correct: question.correct ?? 'true',
                correct_answer: question.correct_answer || '',
                accept_variants: Array.isArray(question.accept_variants) ? question.accept_variants.join(', ') : '',
                explanation: question.explanation || ''
            };
            this.showForm = true;
        },

        saveQuestion() {
            if (!this.form.question.trim()) {
                alert('Write the question text first.');
                return;
            }

            let question;
            if (this.form.type === 'mcq') {
                const options = this.form.options.map((option) => option.trim()).filter(Boolean);
                if (options.length < 2) {
                    alert('Add at least two options.');
                    return;
                }

                question = {
                    type: 'mcq',
                    question: this.form.question.trim(),
                    options,
                    correct_index: this.form.correct_index >= options.length ? 0 : this.form.correct_index,
                    explanation: this.form.explanation.trim()
                };
            } else if (this.form.type === 'dictation') {
                if (!this.form.correct_answer.trim()) {
                    alert('Write the correct answer.');
                    return;
                }

                question = {
                    type: 'dictation',
                    question: this.form.question.trim(),
                    correct_answer: this.form.correct_answer.trim(),
                    accept_variants: this.form.accept_variants
                        ? this.form.accept_variants.split(',').map((variant) => variant.trim()).filter(Boolean)
                        : [],
                    explanation: this.form.explanation.trim()
                };
            } else {
                question = {
                    type: 'truefalse',
                    question: this.form.question.trim(),
                    correct: this.form.correct,
                    explanation: this.form.explanation.trim()
                };
            }

            if (this.editIdx !== null) {
                this.questions.splice(this.editIdx, 1, question);
            } else {
                this.questions.push(question);
            }

            this.syncJson();
            this.cancelForm();
        },

        removeQuestion(idx) {
            this.questions.splice(idx, 1);
            this.syncJson();
        },

        cancelForm() {
            this.showForm = false;
            this.editIdx = null;
        }
    };
}
</script>
