<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use Illuminate\Container\Container;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\UncompromisedVerifier;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Traits\Conditionable;
use InvalidArgumentException;

class PasswordRule implements Rule, DataAwareRule, ValidatorAwareRule
{
    use Conditionable;

    protected $validator;

    /**
     * The data under validation.
     *
     * @var array
     */
    protected $data;

    /**
     * The minimum size of the password.
     *
     * @var int
     */
    protected $min = 3;

    /**
     * If the password requires at least one uppercase and one lowercase letter.
     *
     * @var bool
     */
    protected $mixedCase = false;

    /**
     * If the password requires at least one letter.
     *
     * @var bool
     */

    /**
     * If the password requires at least one number.
     *
     * @var bool
     */
    protected $numbers = false;

    /**
     * If the password requires at least one symbol.
     *
     * @var bool
     */

    /**
     * If the password should not have been compromised in data leaks.
     *
     * @var bool
     */

    /**
     * The number of times a password can appear in data leaks before being considered compromised.
     *
     * @var int
     */
    protected $compromisedThreshold = 0;

    /**
     * Additional validation rules that should be merged into the default rules during validation.
     *
     * @var array
     */
    protected $customRules = [];

    /**
     * The failure messages, if any.
     *
     * @var array
     */
    protected $messages = [];

    /**
     * The callback that will generate the "default" version of the password rule.
     *
     * @var string|array|callable|null
     */
    public static $defaultCallback;

    public function __construct()
    {
        // $this->min = max((int) $min, 1);
    }

    public function rules($rules)
    {
        $this->customRules = Arr::wrap($rules);

        return $this;
    }

    public function setValidator($validator)
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * Set the data under validation.
     *
     * @param  array  $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Sets the minimum size of the password.
     *
     * @param  int  $size
     * @return $this
     */
    public static function min($size)
    {
        return new static($size);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->messages = [];

        $validator = Validator::make(
            $this->data,
            [$attribute => array_merge(['string', 'min:'.$this->min], $this->customRules)],
            $this->validator->customMessages,
            $this->validator->customAttributes
        )->after(function ($validator) use ($attribute, $value) {
            // if (! is_string($value)) {
            //     return;
            // }

            if ($this->mixedCase && ! preg_match('/(\p{Ll}+.*\p{Lu})|(\p{Lu}+.*\p{Ll})/u', $value)) {
                $validator->errors()->add(
                    $attribute,
                    $this->getErrorMessage('validation.password.mixed')
                );
            }

            if ($this->numbers && ! preg_match('/\pN/u', $value)) {
                $validator->errors()->add(
                    $attribute,
                    $this->getErrorMessage('validation.password.numbers')
                );
            }
        });

        if ($validator->fails()) {
            return $this->fail($validator->messages()->all());
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->messages;
    }

    /**
     * Get the translated password error message.
     *
     * @param  string  $key
     * @return string
     */
    protected function getErrorMessage($key)
    {
        if (($message = $this->validator->getTranslator()->get($key)) !== $key) {
            return $message;
        }

        $messages = [
            'validation.password.mixed' => 'password harus kombinasi huruf besar dan kecil.',
            // 'validation.password.letters' => 'The :attribute must contain at least one letter.',
            // 'validation.password.symbols' => 'The :attribute must contain at least one symbol.',
            'validation.password.numbers' => 'password harus menggunakan angka.',
            // 'validation.password.uncompromised' => 'The given :attribute has appeared in a data leak. Please choose a different :attribute.',
        ];

        return $messages[$key];
    }
    protected function fail($messages)
    {
        $messages = collect(Arr::wrap($messages))->map(function ($message) {
            return $this->validator->getTranslator()->get($message);
        })->all();

        $this->messages = array_merge($this->messages, $messages);

        return false;
    }
    public function mixedCase()
    {
        $this->mixedCase = true;

        return $this;
    }

    public function numbers()
    {
        $this->numbers = true;

        return $this;
    }
}
