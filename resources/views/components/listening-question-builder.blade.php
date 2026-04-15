{{--
    Listening Question Builder Component
    Props:
      $questionsJson  — existing JSON string (or '')
      $inputName      — name of hidden textarea (e.g. 'listening_questions_json')
      $passingScore   — current passing score integer
      $scoreInputName — name of passing score input
--}}
@props([
    'questionsJson'  => '[]',
    'inputName'      => 'listening_questions_json',
    'passingScore'   => 70,
    'scoreInputName' => 'listening_passing_score',
])

<div
    x-data="listeningBuilder({{ Js::from($questionsJson) }})"
    x-init="init()"
    class="space-y-4">

    {{-- Hidden input that carries the JSON on submit --}}
    <textarea :name="'{{ $inputName }}'" x-ref="jsonOut" class="hidden"></textarea>

    {{-- ── Question list ──────────────────────────────────────── --}}
    <div class="space-y-2">
        <template x-for="(q, idx) in questions" :key="idx">
            <div class="flex items-start gap-3 rounded-xl p-3 border"
                 style="background:var(--color-surface);border-color:var(--color-border);">

                {{-- index badge --}}
                <span class="shrink-0 w-6 h-6 rounded-full text-xs font-black flex items-center justify-center mt-0.5"
                      style="background:var(--color-primary-50);color:var(--color-primary);"
                      x-text="idx+1"></span>

                {{-- type badge --}}
                <span class="shrink-0 text-xs font-bold px-2 py-0.5 rounded-full mt-0.5"
                      :class="q.type === 'mcq'
                          ? 'bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300'
                          : q.type === 'dictation'
                              ? 'bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300'
                              : 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300'"
                      x-text="q.type === 'mcq' ? 'MCQ' : q.type === 'dictation' ? 'اكتب' : 'T/F'"></span>

                {{-- question text + answer preview --}}
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold leading-snug" style="color:var(--color-text);" x-text="q.question"></p>

                    {{-- MCQ options preview --}}
                    <template x-if="q.type === 'mcq'">
                        <ul class="mt-1 space-y-0.5">
                            <template x-for="(opt, oi) in q.options" :key="oi">
                                <li class="text-xs flex items-center gap-1.5"
                                    :style="oi === q.correct_index ? 'color:var(--color-success,#16a34a);font-weight:700;' : 'color:var(--color-text-muted);'">
                                    <span x-text="['أ','ب','ج','د'][oi] + '.'"></span>
                                    <span x-text="opt"></span>
                                    <span x-show="oi === q.correct_index">✓</span>
                                </li>
                            </template>
                        </ul>
                    </template>

                    {{-- True/False preview --}}
                    <template x-if="q.type === 'truefalse'">
                        <p class="text-xs mt-1"
                           :style="q.correct === 'true' ? 'color:var(--color-success,#16a34a);font-weight:700;' : 'color:#dc2626;font-weight:700;'"
                           x-text="q.correct === 'true' ? '✓ صح' : '✗ خطأ'"></p>
                    </template>

                    {{-- Dictation preview --}}
                    <template x-if="q.type === 'dictation'">
                        <p class="text-xs mt-1 font-bold" style="color:var(--color-success,#16a34a);"
                           x-text="'✍ ' + q.correct_answer"></p>
                    </template>

                    {{-- explanation --}}
                    <p x-show="q.explanation" class="text-xs mt-1 italic" style="color:var(--color-text-muted);" x-text="'💡 ' + q.explanation"></p>
                </div>

                {{-- actions --}}
                <div class="shrink-0 flex gap-1 mt-0.5">
                    <button type="button" @click="editQuestion(idx)"
                            class="text-xs px-2 py-1 rounded-lg font-bold hover:opacity-80 transition"
                            style="background:var(--color-surface-hover);color:var(--color-text-muted);">✏️</button>
                    <button type="button" @click="removeQuestion(idx)"
                            class="text-xs px-2 py-1 rounded-lg font-bold hover:opacity-80 transition"
                            style="background:#fee2e2;color:#dc2626;">🗑</button>
                </div>
            </div>
        </template>

        <p x-show="questions.length === 0" class="text-xs text-center py-4"
           style="color:var(--color-text-muted);">لا توجد أسئلة بعد — أضف سؤالاً من الأزرار أدناه</p>
    </div>

    {{-- ── Add question buttons ────────────────────────────────── --}}
    <div class="flex flex-wrap gap-2">
        <button type="button" @click="openForm('mcq')"
                class="flex items-center gap-2 text-sm font-bold px-4 py-2 rounded-xl border-2 border-dashed border-sky-400 text-sky-600 dark:text-sky-300 hover:bg-sky-50 dark:hover:bg-sky-900/20 transition">
            + MCQ (اختيار من متعدد)
        </button>
        <button type="button" @click="openForm('truefalse')"
                class="flex items-center gap-2 text-sm font-bold px-4 py-2 rounded-xl border-2 border-dashed border-amber-400 text-amber-600 dark:text-amber-300 hover:bg-amber-50 dark:hover:bg-amber-900/20 transition">
            + صح / خطأ
        </button>
        <button type="button" @click="openForm('dictation')"
                class="flex items-center gap-2 text-sm font-bold px-4 py-2 rounded-xl border-2 border-dashed border-violet-400 text-violet-600 dark:text-violet-300 hover:bg-violet-50 dark:hover:bg-violet-900/20 transition">
            + استمع واكتب
        </button>
    </div>

    {{-- ── Add/Edit form (inline) ──────────────────────────────── --}}
    <div x-show="showForm" x-collapse x-cloak
         class="rounded-xl p-4 space-y-3 border-2"
         :class="form.type === 'mcq' ? 'border-sky-300 dark:border-sky-600' : form.type === 'dictation' ? 'border-violet-300 dark:border-violet-600' : 'border-amber-300 dark:border-amber-600'"
         style="background:var(--color-surface-hover);">

        <h5 class="font-extrabold text-sm" style="color:var(--color-text);"
            x-text="(editIdx !== null ? 'تعديل' : 'إضافة') + ' سؤال ' + (form.type === 'mcq' ? 'MCQ' : form.type === 'dictation' ? 'استمع واكتب' : 'صح/خطأ')"></h5>

        {{-- Question text --}}
        <div>
            <label class="block text-xs font-semibold mb-1" style="color:var(--color-text);">نص السؤال *</label>
            <input type="text" x-model="form.question" class="input-glass" placeholder="اكتب السؤال هنا...">
        </div>

        {{-- MCQ options --}}
        <template x-if="form.type === 'mcq'">
            <div class="space-y-2">
                <label class="block text-xs font-semibold" style="color:var(--color-text);">الخيارات (حدد الصحيح بالضغط على ✓)</label>
                <template x-for="(opt, oi) in form.options" :key="oi">
                    <div class="flex items-center gap-2">
                        <button type="button" @click="form.correct_index = oi"
                                class="shrink-0 w-7 h-7 rounded-full text-xs font-black border-2 transition"
                                :class="form.correct_index === oi
                                    ? 'border-green-500 bg-green-500 text-white'
                                    : 'border-gray-300 dark:border-gray-600 text-gray-400'"
                                x-text="['أ','ب','ج','د'][oi]"></button>
                        <input type="text" x-model="form.options[oi]" class="input-glass flex-1 text-sm"
                               :placeholder="'الخيار ' + ['أ','ب','ج','د'][oi]">
                    </div>
                </template>
            </div>
        </template>

        {{-- True/False selector --}}
        <template x-if="form.type === 'truefalse'">
            <div>
                <label class="block text-xs font-semibold mb-2" style="color:var(--color-text);">الإجابة الصحيحة</label>
                <div class="flex gap-3">
                    <button type="button" @click="form.correct = 'true'"
                            class="flex-1 py-2 rounded-xl text-sm font-black border-2 transition"
                            :class="form.correct === 'true'
                                ? 'border-green-500 bg-green-500 text-white'
                                : 'border-gray-300 dark:border-gray-600 text-gray-500'">
                        ✓ صح
                    </button>
                    <button type="button" @click="form.correct = 'false'"
                            class="flex-1 py-2 rounded-xl text-sm font-black border-2 transition"
                            :class="form.correct === 'false'
                                ? 'border-red-500 bg-red-500 text-white'
                                : 'border-gray-300 dark:border-gray-600 text-gray-500'">
                        ✗ خطأ
                    </button>
                </div>
            </div>
        </template>

        {{-- Dictation fields --}}
        <template x-if="form.type === 'dictation'">
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:var(--color-text);">الإجابة الصحيحة *</label>
                    <input type="text" x-model="form.correct_answer" class="input-glass"
                           placeholder="اكتب الكلمة أو الرقم الصحيح (مثال: fifteen أو 15)">
                    <p class="text-xs mt-1" style="color:var(--color-text-muted);">المقارنة بدون حساسية للحروف الكبيرة والصغيرة</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:var(--color-text);">إجابات بديلة مقبولة (اختياري)</label>
                    <input type="text" x-model="form.accept_variants" class="input-glass"
                           placeholder="مثال: 15, fifteen, Fifteen — افصل بينهم بفاصلة">
                    <p class="text-xs mt-1" style="color:var(--color-text-muted);">لو فيه أكثر من طريقة صح للكتابة</p>
                </div>
            </div>
        </template>

        {{-- Explanation --}}
        <div>
            <label class="block text-xs font-semibold mb-1" style="color:var(--color-text);">الشرح (اختياري)</label>
            <input type="text" x-model="form.explanation" class="input-glass" placeholder="شرح قصير يظهر بعد الإجابة...">
        </div>

        {{-- Form actions --}}
        <div class="flex gap-2 pt-1">
            <button type="button" @click="saveQuestion()"
                    class="btn-primary text-sm px-4 py-2">
                <span x-text="editIdx !== null ? 'حفظ التعديل' : 'إضافة السؤال'"></span>
            </button>
            <button type="button" @click="cancelForm()"
                    class="btn-secondary text-sm px-4 py-2">إلغاء</button>
        </div>
    </div>

    {{-- ── Passing score ────────────────────────────────────────── --}}
    <div class="flex items-center gap-4 pt-2 border-t" style="border-color:var(--color-border);">
        <label class="text-sm font-semibold shrink-0" style="color:var(--color-text);">درجة النجاح (%)</label>
        <input type="number" name="{{ $scoreInputName }}" min="0" max="100" class="input-glass w-28"
               value="{{ old($scoreInputName, $passingScore) }}">
        <span class="text-xs" style="color:var(--color-text-muted);">
            عدد الأسئلة: <strong x-text="questions.length"></strong>
        </span>
    </div>
</div>

<script>
function listeningBuilder(initialJson) {
    return {
        questions: [],
        showForm: false,
        editIdx: null,
        form: { type: 'mcq', question: '', options: ['','','',''], correct_index: 0, correct: 'true', correct_answer: '', accept_variants: '', explanation: '' },

        init() {
            try {
                const parsed = typeof initialJson === 'string' ? JSON.parse(initialJson) : initialJson;
                this.questions = Array.isArray(parsed) ? parsed : [];
            } catch(e) { this.questions = []; }
            this.$watch('questions', () => this.syncJson());
            this.syncJson();
        },

        syncJson() {
            if (this.$refs.jsonOut) {
                this.$refs.jsonOut.value = JSON.stringify(this.questions, null, 2);
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
            const q = this.questions[idx];
            this.editIdx = idx;
            this.form = {
                type: q.type,
                question: q.question || '',
                options: q.type === 'mcq' ? [...(q.options || ['','','',''])] : ['','','',''],
                correct_index: q.correct_index ?? 0,
                correct: q.correct ?? 'true',
                correct_answer: q.correct_answer || '',
                accept_variants: q.accept_variants ? q.accept_variants.join(', ') : '',
                explanation: q.explanation || ''
            };
            this.showForm = true;
        },

        saveQuestion() {
            if (!this.form.question.trim()) {
                alert('اكتب نص السؤال أولاً');
                return;
            }
            if (this.form.type === 'mcq') {
                if (this.form.options.filter(o => o.trim()).length < 2) {
                    alert('أضف خيارين على الأقل');
                    return;
                }
            }
            if (this.form.type === 'dictation' && !this.form.correct_answer.trim()) {
                alert('اكتب الإجابة الصحيحة');
                return;
            }

            let q;
            if (this.form.type === 'mcq') {
                q = {
                    type: 'mcq',
                    question: this.form.question.trim(),
                    options: this.form.options.map(o => o.trim()).filter(o => o),
                    correct_index: this.form.correct_index,
                    explanation: this.form.explanation.trim()
                };
                if (q.correct_index >= q.options.length) q.correct_index = 0;
            } else if (this.form.type === 'dictation') {
                const variants = this.form.accept_variants
                    ? this.form.accept_variants.split(',').map(v => v.trim()).filter(v => v)
                    : [];
                q = {
                    type: 'dictation',
                    question: this.form.question.trim(),
                    correct_answer: this.form.correct_answer.trim(),
                    accept_variants: variants,
                    explanation: this.form.explanation.trim()
                };
            } else {
                q = {
                    type: 'truefalse',
                    question: this.form.question.trim(),
                    correct: this.form.correct,
                    explanation: this.form.explanation.trim()
                };
            }

            if (this.editIdx !== null) {
                this.questions.splice(this.editIdx, 1, q);
            } else {
                this.questions.push(q);
            }

            this.cancelForm();
        },

        removeQuestion(idx) {
            this.questions.splice(idx, 1);
        },

        cancelForm() {
            this.showForm = false;
            this.editIdx = null;
        }
    };
}
</script>
