<?php

namespace App\Services\Dashboard;

class LogService
{
    public function tailLog(string $filePath, int $lines = 200): array
    {
        if (! file_exists($filePath)) {
            return [];
        }

        $content = @file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($content === false) {
            return [];
        }

        return array_slice($content, -1 * $lines);
    }

    public function parseLogErrors(array $errorLines): array
    {
        $parsedErrors = [];
        $currentError = null;

        foreach ($errorLines as $line) {
            // Check if it's a new error entry (starts with timestamp)
            if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (\w+)\.(\w+): (.+)/', $line, $matches)) {
                // Save previous error if exists
                if ($currentError) {
                    $parsedErrors[] = $currentError;
                }

                // Start new error
                $currentError = [
                    'timestamp' => $matches[1],
                    'environment' => $matches[2],
                    'level' => strtoupper($matches[3]),
                    'message' => $matches[4],
                    'stack_trace' => [],
                    'file_path' => '',
                    'line_number' => '',
                ];

                // Extract file path and line number from message
                if (preg_match('/in (.+):(\d+)/', $matches[4], $fileMatches)) {
                    $currentError['file_path'] = $this->shortenPath($fileMatches[1]);
                    $currentError['line_number'] = $fileMatches[2];
                }
            } elseif ($currentError && (str_starts_with($line, '#') || str_starts_with($line, 'Stack trace:'))) {
                // Add to stack trace
                $currentError['stack_trace'][] = $this->shortenPath($line);
            } elseif ($currentError && ! empty(trim($line))) {
                // Add to message continuation
                $currentError['message'] .= ' '.trim($line);
            }
        }

        // Add the last error
        if ($currentError) {
            $parsedErrors[] = $currentError;
        }

        return array_reverse($parsedErrors); // Most recent first
    }

    private function shortenPath(string $path): string
    {
        $basePath = base_path();

        return str_replace($basePath.DIRECTORY_SEPARATOR, '', $path);
    }
}
