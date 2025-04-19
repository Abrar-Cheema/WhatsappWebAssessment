<?php

namespace App\Http\Middleware;

use App\Helpers\TranslateTextHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class Translate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $targetLang = $request->query('lang');

        $response = $next($request);

        if ($targetLang && $targetLang == 'en' && $response instanceof Response && $response->headers->get('Content-Type') === 'application/json') {
            $data = json_decode($response->getContent(), true);
            TranslateTextHelper::setSource('ar')->setTarget('en');
            $translatedData = $this->translateResponseData($data);
            $response->setContent(json_encode($translatedData));
        }

        return $response;
    }

    /**
     * Translate the response data.
     *
     * @param  array  $data
     * @return array
     */
    private function translateResponseData(array $data): array
    {
        foreach ($data as $key => $value) {
            // Skip translation for dates, integers, booleans, and null values
            if (is_array($value)) {
                // Recursively translate nested arrays
                $data[$key] = $this->translateResponseData($value);
            } else {
                // Translate string values
                if ($this->shouldTranslate($key, $value)) {
                    $data[$key] = TranslateTextHelper::translate($value);
                }
            }
        }

        return $data;
    }

    /**
     * Determine if a value should be translated.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return bool
     */
    private function shouldTranslate(string $key, $value): bool
    {
        // Exclude dates, integers, booleans, and null values from translation
        return !is_int($value) && !is_bool($value) && !is_null($value) && !$this->isDate($value);
    }

    /**
     * Check if the given value is a date.
     *
     * @param  mixed  $value
     * @return bool
     */
    private function isDate($value): bool
    {
        return  $value instanceof \DateTime || strtotime($value) !== false;
    }
}
