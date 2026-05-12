<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class AiReviewFile extends Command
{
    protected $signature = 'ai:review {file}';

    protected $description = 'Review Laravel file using local Ollama AI';

    public function handle(): int
    {
        $filePath = base_path($this->argument('file'));

        if (! file_exists($filePath)) {
            $this->error("File not found: {$filePath}");

            return SymfonyCommand::FAILURE;
        }

        $content = file_get_contents($filePath);

        $prompt = <<<PROMPT
You are a senior Laravel backend architect.

Review this Laravel file and provide:
1. Bugs and risks
2. Performance issues
3. Security concerns
4. Laravel best practice improvements
5. Refactoring suggestions

Code:
{$content}
PROMPT;

        $response = Http::timeout(120)
            ->post('http://127.0.0.1:11434/api/generate', [
                'model' => 'qwen2.5-coder:3b',
                'prompt' => $prompt,
                'stream' => false,
            ]);

        if (! $response->successful()) {
            $this->error('Failed to connect to Ollama');

            return SymfonyCommand::FAILURE;
        }

        $result = $response->json();

        $this->line("\n=== AI Review ===\n");
        $this->line($result['response']);

        return SymfonyCommand::SUCCESS;
    }
}
