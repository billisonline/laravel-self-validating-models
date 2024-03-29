<?php

namespace BYanelli\SelfValidatingModels\Tests;

use BYanelli\SelfValidatingModels\Tests\TestApp\Comment;
use BYanelli\SelfValidatingModels\Tests\TestApp\Post;
use BYanelli\SelfValidatingModels\Tests\TestApp\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SelfValidationTest extends TestCase
{
    public function testSelfValidatesWithTraitAndRules()
    {
        $this->expectException(ValidationException::class);

        $post = new Post;

        $post->title = Str::random(30);

        $post->save();
    }

    public function testSelfValidatesWithTraitAndCustomMessages()
    {
        try {
            $post = new Post;

            $post->title = Str::random(30);

            $post->save();
        } catch (ValidationException $e) {
            $this->assertValidationMessageEquals('The title is too long bro', $e, 'title.0');

            return;
        }

        throw new \Exception;
    }

    public function testSelfValidatesWithTraitAndRulesUsingAlternatePropertyName()
    {
        $this->expectException(ValidationException::class);

        $user = new User();

        $user->email = 'invalid@email@!';

        $user->save();
    }

    public function testSelfValidatesWithTraitAndCustomMessagesUsingAlternatePropertyName()
    {
        try {
            $user = new User();

            $user->email = 'invalid@email@!';

            $user->save();
        } catch (ValidationException $e) {
            $this->assertValidationMessageEquals('The email isn\'t a valid email bro', $e, 'email.0');

            return;
        }

        throw new \Exception;
    }

    public function testValidModelAllowedWithTraitAndRules()
    {
        $post = new Post;

        $post->title = Str::random(20);

        $post->save();

        $this->assertTrue($post->exists);
    }

    public function testSelfValidatesWithValidatorAndRules()
    {
        $this->expectException(ValidationException::class);

        $comment = new Comment;

        $comment->body = Str::random(500);

        $comment->save();
    }

    private function assertValidationMessageEquals(string $expectedMessage, ValidationException $e, string $accessor): void
    {
        $actualMessage = Arr::get($e->validator->getMessageBag()->toArray(), $accessor);

        $this->assertEquals($expectedMessage, $actualMessage);
    }
}
