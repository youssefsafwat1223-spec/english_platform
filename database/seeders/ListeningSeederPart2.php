<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourseLevel;
use App\Models\ListeningExercise;

class ListeningSeederPart2 extends Seeder
{
    public function run(): void
    {
        $courseId = 6;
        $data = [
            'أنواع الأفعال' => [
                ['type' => 'mcq', 'prompt' => '"She SEEMS tired." — type of SEEMS?', 'options' => ['Action', 'Modal', 'Linking', 'Auxiliary'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"He CAN swim very fast." — type of CAN?', 'options' => ['Action', 'Modal', 'Linking', 'Regular'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"They ARE playing outside." — type of ARE?', 'options' => ['Main', 'Modal', 'Linking', 'Auxiliary'], 'correct_index' => 3],
                ['type' => 'mcq', 'prompt' => '"I RUN five kilometres." — type of RUN?', 'options' => ['Linking', 'Aux', 'Action', 'Modal'], 'correct_index' => 2],
                ['type' => 'dictation', 'prompt' => '"She ___ (modal) speak..." — write the verb', 'correct_answer' => 'can', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"The food ___ (linking) delicious." — write the verb', 'correct_answer' => 'tastes', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"He ___ (auxiliary) finished." — write the verb', 'correct_answer' => 'has', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"They ___ (action) to the park." — write the verb', 'correct_answer' => 'walked', 'accept_variants' => []],
            ],
            'فعل الكينونة' => [
                ['type' => 'mcq', 'prompt' => '"___ is my favourite hobby." — hear: Swimming', 'options' => ['Swim', 'Swam', 'Swimming', 'To swim'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"She enjoys ___." — hear: reading', 'options' => ['read', 'reads', 'to read', 'reading'], 'correct_index' => 3],
                ['type' => 'mcq', 'prompt' => '"___ too much is unhealthy." — hear: Eating', 'options' => ['Eat', 'Eating', 'Eaten', 'To eat'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"He is good at ___." — hear: painting', 'options' => ['paint', 'to paint', 'painted', 'painting'], 'correct_index' => 3],
                ['type' => 'dictation', 'prompt' => '"___ every day improves..." — write gerund', 'correct_answer' => 'Practising', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"She is afraid of ___." — write gerund', 'correct_answer' => 'flying', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"___ notes helps you remember." — write gerund', 'correct_answer' => 'Taking', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"They stopped ___." — write gerund', 'correct_answer' => 'arguing', 'accept_variants' => []],
            ],
            'المفعول به' => [
                ['type' => 'mcq', 'prompt' => '"She called ___." — object pronoun', 'options' => ['I', 'my', 'me', 'mine'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"Give ___ the book, please." — object pronoun', 'options' => ['I', 'me', 'my', 'mine'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"The teacher helped ___." — object for "we"', 'options' => ['we', 'our', 'ours', 'us'], 'correct_index' => 3],
                ['type' => 'mcq', 'prompt' => '"I can see ___ from here." — object for "they"', 'options' => ['their', 'theirs', 'them', 'they'], 'correct_index' => 2],
                ['type' => 'dictation', 'prompt' => '"He gave ___ a gift." — write pronoun', 'correct_answer' => 'her', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"Can you help ___?" — write pronoun', 'correct_answer' => 'me', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"She saw ___ at the mall." — write pronoun', 'correct_answer' => 'him', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"They invited ___ to the party." — write pronoun', 'correct_answer' => 'us', 'accept_variants' => []],
            ],
            'الصفات' => [
                ['type' => 'mcq', 'prompt' => '"The ___ cat sat..." — hear: fluffy', 'options' => ['quickly', 'fluffy', 'run', 'very'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"She has ___ eyes." — hear: beautiful', 'options' => ['beautifully', 'beauty', 'beautiful', 'beautify'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"It was a ___ day." — hear: cold', 'options' => ['coldly', 'cold', 'colder', 'coldness'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"He is a ___ student." — hear: hardworking', 'options' => ['harder', 'hardwork', 'hardworking', 'hardworked'], 'correct_index' => 2],
                ['type' => 'dictation', 'prompt' => '"The ___ dog barked loudly." — write adjective', 'correct_answer' => 'angry', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"She wore a ___ dress." — write adjective', 'correct_answer' => 'red', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"It was a ___ movie." — write adjective', 'correct_answer' => 'boring', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"He lives in a ___ house." — write adjective', 'correct_answer' => 'large', 'accept_variants' => []],
            ],
            'الظروف' => [
                ['type' => 'mcq', 'prompt' => '"She sings ___." — hear: beautifully', 'options' => ['beautiful', 'beauty', 'beautifully', 'beautify'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"He arrived ___." — hear: late', 'options' => ['lately', 'later', 'late', 'latest'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"They ___ go to the gym." — hear: often', 'options' => ['often', 'much', 'very', 'since'], 'correct_index' => 0],
                ['type' => 'mcq', 'prompt' => '"She spoke ___ loudly." — hear: too', 'options' => ['so', 'very', 'too', 'quite'], 'correct_index' => 2],
                ['type' => 'dictation', 'prompt' => '"He runs ___." — write adverb', 'correct_answer' => 'quickly', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"She ___ forgets her keys." — write adverb', 'correct_answer' => 'always', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"They arrived ___." — write adverb', 'correct_answer' => 'early', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"He speaks English ___." — write adverb', 'correct_answer' => 'fluently', 'accept_variants' => []],
            ],
            'الاسم المنسوب' => [
                ['type' => 'mcq', 'prompt' => '"A ___ driver." — hear: bus', 'options' => ['buses', 'bus', 'busing', 'bused'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"A ___ room." — hear: classroom', 'options' => ['room', 'class', 'classroom', 'a'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"A ___ bag." — hear: school', 'options' => ['schools', 'schooling', 'school', 'schooled'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"A ___ table." — hear: kitchen', 'options' => ['kitchens', 'kitchening', 'kitchen', 'kitchened'], 'correct_index' => 2],
                ['type' => 'dictation', 'prompt' => '"A ___ door." — write attributive noun', 'correct_answer' => 'car', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"A ___ station." — write attributive noun', 'correct_answer' => 'police', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"A ___ match." — write attributive noun', 'correct_answer' => 'football', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"A ___ book." — write attributive noun', 'correct_answer' => 'story', 'accept_variants' => []],
            ],
            'البادئة واللاحقة' => [
                ['type' => 'mcq', 'prompt' => '"UN + happy = ___"', 'options' => ['unhappily', 'unhappy', 'unhappiness', 'unhappier'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"care + LESS = ___"', 'options' => ['careful', 'careless', 'caring', 'cared'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"RE + write = ___"', 'options' => ['writing', 'writer', 'rewrite', 'written'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"teach + ER = ___"', 'options' => ['teaching', 'teachable', 'teacher', 'taught'], 'correct_index' => 2],
                ['type' => 'dictation', 'prompt' => '"DIS + agree = ___" — write complete word', 'correct_answer' => 'disagree', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"help + FUL = ___" — write complete word', 'correct_answer' => 'helpful', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"IM + possible = ___" — write complete word', 'correct_answer' => 'impossible', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"kind + NESS = ___" — write complete word', 'correct_answer' => 'kindness', 'accept_variants' => []],
            ],
            'الملكية' => [
                ['type' => 'mcq', 'prompt' => '"This is ___ book." — hear: Sara\'s', 'options' => ['Saras', 'Sara\'s', 'Sara', 'Saras\''], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"The ___ car is red." — hear: children\'s', 'options' => ['childrens', 'children', 'children\'s', 'childrens\''], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"This is ___ pen." — hear: my', 'options' => ['me', 'mine', 'my', 'I'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"The house is ___." — hear: theirs', 'options' => ['them', 'their', 'they', 'theirs'], 'correct_index' => 3],
                ['type' => 'dictation', 'prompt' => '"This is ___ laptop." — write possessive', 'correct_answer' => 'Ahmed\'s', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"The ___ names are on the list." — write possessive', 'correct_answer' => 'students\'', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"This bag is ___." — write possessive pronoun', 'correct_answer' => 'hers', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"It is ___ responsibility." — write possessive adj', 'correct_answer' => 'our', 'accept_variants' => []],
            ],
            'حروف الجر' => [
                ['type' => 'mcq', 'prompt' => '"The cat is ___ the box." — hear: under', 'options' => ['on', 'in', 'under', 'behind'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"I will meet you ___ Monday." — hear: on', 'options' => ['in', 'at', 'on', 'by'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"She lives ___ London." — hear: in', 'options' => ['at', 'in', 'on', 'to'], 'correct_index' => 1],
                ['type' => 'mcq', 'prompt' => '"The meeting starts ___ 9 o\'clock." — hear: at', 'options' => ['in', 'on', 'at', 'by'], 'correct_index' => 2],
                ['type' => 'dictation', 'prompt' => '"The book is ___ the table." — write preposition', 'correct_answer' => 'on', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"I was born ___ 1995." — write preposition', 'correct_answer' => 'in', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"She walked ___ the bridge." — write preposition', 'correct_answer' => 'across', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"He ran ___ the street." — write preposition', 'correct_answer' => 'along', 'accept_variants' => []],
            ],
            'الأسماء الإشارية' => [
                ['type' => 'mcq', 'prompt' => '"___ is my pen." — close singular', 'options' => ['Those', 'These', 'That', 'This'], 'correct_index' => 3],
                ['type' => 'mcq', 'prompt' => '"___ are my books." — far plural', 'options' => ['This', 'That', 'Those', 'These'], 'correct_index' => 2],
                ['type' => 'mcq', 'prompt' => '"___ students over there..." — far plural', 'options' => ['This', 'These', 'That', 'Those'], 'correct_index' => 3],
                ['type' => 'mcq', 'prompt' => '"___ was a great movie!" — past event', 'options' => ['These', 'Those', 'That', 'This'], 'correct_index' => 2],
                ['type' => 'dictation', 'prompt' => '"___ is my house." — close singular', 'correct_answer' => 'This', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"___ are expensive shoes!" — close plural', 'correct_answer' => 'These', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"___ building over there is tall." — far singular', 'correct_answer' => 'That', 'accept_variants' => []],
                ['type' => 'dictation', 'prompt' => '"___ were the best days." — far plural past', 'correct_answer' => 'Those', 'accept_variants' => []],
            ]
        ];

        $count = 0;
        foreach ($data as $searchKey => $questions) {
            $level = CourseLevel::where('course_id', $courseId)
                ->where('title', 'LIKE', "%{$searchKey}%")
                ->first();

            if ($level) {
                ListeningExercise::updateOrCreate(
                    ['course_level_id' => $level->id],
                    [
                        'title' => $level->title . ' — Listening',
                        'script_ar' => 'تمرين استماع',
                        'questions_json' => $questions,
                        'passing_score' => 70,
                    ]
                );
                $level->update(['has_listening_exercise' => true]);
                $this->command->info("✅ Added listening to: " . $level->title);
                $count++;
            } else {
                $this->command->warn("⚠ Not found: {$searchKey}");
            }
        }
        $this->command->info("🎉 Part 2 Done! Processed {$count} levels.");
    }
}
