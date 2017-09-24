<?php

use Illuminate\Container\Container;
use LaravelZero\Framework\Contracts\Application as ApplicationContract;

if (! function_exists('app')) {
    /**
     * Get the available container instance.
     *
     * @param  string $make
     *
     * @return mixed|\Laravel\Lumen\Application
     */
    function app($make = null)
    {
        if (is_null($make)) {
            return Container::getInstance();
        }

        return Container::getInstance()
            ->make($make);
    }
}

if (! function_exists('config')) {
    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string $key
     * @param  mixed $default
     *
     * @return mixed
     */
    function config($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('config');
        }

        if (is_array($key)) {
            return app('config')->set($key);
        }

        return app('config')->get($key, $default);
    }
}

if (! function_exists('event')) {
    /**
     * Fire an event and call the listeners.
     *
     * @param  object|string $event
     * @param  mixed $payload
     * @param  bool $halt
     *
     * @return array|null
     */
    function event($event, $payload = [], $halt = false)
    {
        return app('events')->fire($event, $payload, $halt);
    }
}

if (! function_exists('base_path')) {
    /**
     * Get the path to the base of the install.
     *
     * @param  string $path
     *
     * @return string
     */
    function base_path($path = '')
    {
        return app()->basePath().($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

if (! function_exists('confirm')) {
    /**
     * Confirm a question with the user.
     *
     * @param  string $question
     * @param  bool $default
     *
     * @return bool
     */
    function confirm($question, $default = false)
    {
        return app(ApplicationContract::class)
            ->getRunningCommand()
            ->confirm($question, $default);
    }
}

if (! function_exists('ask')) {
    /**
     * Prompt the user for input.
     *
     * @param  string $question
     * @param  string $default
     *
     * @return string
     */
    function ask($question, $default = null)
    {
        return app(ApplicationContract::class)
            ->getRunningCommand()
            ->ask($question, $default);
    }
}

if (! function_exists('anticipate')) {
    /**
     * Prompt the user for input with auto completion.
     *
     * @param  string $question
     * @param  array $choices
     * @param  string $default
     *
     * @return string
     */
    function anticipate($question, array $choices, $default = null)
    {
        return app(ApplicationContract::class)
            ->getRunningCommand()
            ->anticipate($question, $choices, $default);
    }
}

if (! function_exists('askWithCompletion')) {
    /**
     * Prompt the user for input with auto completion.
     *
     * @param  string $question
     * @param  array $choices
     * @param  string $default
     *
     * @return string
     */
    function askWithCompletion($question, array $choices, $default = null)
    {
        return app(ApplicationContract::class)
            ->getRunningCommand()
            ->askWithCompletion($question, $choices, $default);
    }
}

if (! function_exists('secret')) {
    /**
     * Prompt the user for input but hide the answer from the console.
     *
     * @param  string $question
     * @param  bool $fallback
     *
     * @return string
     */
    function secret($question, $fallback = true)
    {
        return app(ApplicationContract::class)
            ->getRunningCommand()
            ->secret($question, $fallback);
    }
}

if (! function_exists('choice')) {
    /**
     * Give the user a single choice from an array of answers.
     *
     * @param  string $question
     * @param  array $choices
     * @param  string $default
     * @param  mixed $attempts
     * @param  bool $multiple
     *
     * @return string
     */
    function choice($question, array $choices, $default = null, $attempts = null, $multiple = null)
    {
        return app(ApplicationContract::class)
            ->getRunningCommand()
            ->choice($question, $choices, $default, $attempts, $multiple);
    }
}

if (! function_exists('table')) {
    /**
     * Format input to textual table.
     *
     * @param  array $headers
     * @param  \Illuminate\Contracts\Support\Arrayable|array $rows
     * @param  string $style
     *
     * @return void
     */
    function table($headers, $rows, $style = 'default')
    {
        app(ApplicationContract::class)
            ->getRunningCommand()
            ->table($headers, $rows, $style);
    }
}

if (! function_exists('info')) {
    /**
     * Write a string as information output.
     *
     * @param  string $string
     * @param  null|int|string $verbosity
     *
     * @return void
     */
    function info($string, $verbosity = null)
    {
        app(ApplicationContract::class)
            ->getRunningCommand()
            ->info($string, $verbosity);
    }
}

if (! function_exists('line')) {
    /**
     * Write a string as standard output.
     *
     * @param  string $string
     * @param  string $style
     * @param  null|int|string $verbosity
     *
     * @return void
     */
    function line($string, $style = null, $verbosity = null)
    {
        app(ApplicationContract::class)
            ->getRunningCommand()
            ->line($string, $style, $verbosity);
    }
}

if (! function_exists('comment')) {
    /**
     * Write a string as comment output.
     *
     * @param  string $string
     * @param  null|int|string $verbosity
     *
     * @return void
     */
    function comment($string, $verbosity = null)
    {
        app(ApplicationContract::class)
            ->getRunningCommand()
            ->comment($string, $verbosity);
    }
}

if (! function_exists('question')) {
    /**
     * Write a string as question output.
     *
     * @param  string $string
     * @param  null|int|string $verbosity
     *
     * @return void
     */
    function question($string, $verbosity = null)
    {
        app(ApplicationContract::class)
            ->getRunningCommand()
            ->question($string, $verbosity);
    }
}

if (! function_exists('error')) {
    /**
     * Write a string as error output.
     *
     * @param  string $string
     * @param  null|int|string $verbosity
     *
     * @return void
     */
    function error($string, $verbosity = null)
    {
        app(ApplicationContract::class)
            ->getRunningCommand()
            ->error($string, $verbosity);
    }
}

if (! function_exists('warn')) {
    /**
     * Write a string as warning output.
     *
     * @param  string $string
     * @param  null|int|string $verbosity
     *
     * @return void
     */
    function warn($string, $verbosity = null)
    {
        app(ApplicationContract::class)
            ->getRunningCommand()
            ->warn($string, $verbosity);
    }
}

if (! function_exists('alert')) {
    /**
     * Write a string in an alert box.
     *
     * @param  string $string
     *
     * @return void
     */
    function alert($string)
    {
        app(ApplicationContract::class)
            ->getRunningCommand()
            ->alert($string);
    }
}

if (! function_exists('notify')) {
    /**
     * Creates a system pop-up notification.
     *
     * @param string $text
     * @param string $body
     * @param string|null $icon
     *
     * @return void
     */
    function notify(string $text, string $body, $icon = null)
    {
        app(ApplicationContract::class)
            ->getRunningCommand()
            ->notify($text, $body, $icon);
    }
}
