<?php

namespace Tests\Unit;

use App\Models\Question;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class QuestionAudioTest extends TestCase
{
    public function test_audio_url_uses_public_disk_and_returns_null_when_file_is_missing(): void
    {
        Storage::fake('public');

        $question = new Question([
            'has_audio' => true,
            'audio_path' => 'quiz-audio/tts-generated/sample.mp3',
        ]);

        $this->assertNull($question->audio_url);

        Storage::disk('public')->put('quiz-audio/tts-generated/sample.mp3', 'audio');

        $this->assertSame(
            Storage::disk('public')->url('quiz-audio/tts-generated/sample.mp3'),
            $question->audio_url
        );
    }

    public function test_tts_text_includes_non_empty_options_only(): void
    {
        $question = new Question([
            'question_text' => 'Choose the correct answer',
            'question_type' => 'multiple_choice',
            'option_a' => 'One',
            'option_b' => 'Two',
            'option_c' => '',
            'option_d' => null,
        ]);

        $ttsText = $question->getTTSText();

        $this->assertStringContainsString('Question. Choose the correct answer.', $ttsText);
        $this->assertStringContainsString('Option A: One.', $ttsText);
        $this->assertStringContainsString('Option B: Two.', $ttsText);
        $this->assertStringNotContainsString('Option C:', $ttsText);
        $this->assertStringNotContainsString('Option D:', $ttsText);
    }
}
