<?php

namespace Tests\Unit;

use App\Services\Ai\TutorIntentRouter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class TutorIntentRouterTest extends TestCase
{
    public static function goldenQuestions(): array
    {
        $file = __DIR__ . '/../Fixtures/tutor_golden_questions.json';
        $cases = json_decode(file_get_contents($file), true);
        $out = [];
        foreach ($cases as $c) {
            $out[$c['q']] = [$c];
        }

        return $out;
    }

    #[DataProvider('goldenQuestions')]
    public function test_intent_is_identified(array $case): void
    {
        $history = !empty($case['history']) ? [['role' => 'assistant', 'content' => 'Reponse precedente du tuteur.']] : [];
        $route = (new TutorIntentRouter())->classify($case['q'], $history);

        $this->assertSame($case['intent'], $route['intent'], "Intention inattendue pour : {$case['q']}");
        if (isset($case['subtype'])) {
            $this->assertSame($case['subtype'], $route['subtype'], "Sous-type inattendu pour : {$case['q']}");
        }
    }

    public function test_navigation_question_gets_a_short_profile(): void
    {
        $route = (new TutorIntentRouter())->classify('nous sommes sur quel chapitre');

        $this->assertSame('short', $route['length']);
        $this->assertLessThanOrEqual(40, $route['max_words']);
    }

    public function test_argument_gets_a_developed_profile_and_voice_call_caps_it(): void
    {
        $router = new TutorIntentRouter();

        $this->assertSame('developed', $router->classify('démontre que la suite converge')['length']);
        $this->assertLessThanOrEqual(180, $router->classify('démontre que la suite converge', [], 'voice_call')['max_words']);
    }
}

