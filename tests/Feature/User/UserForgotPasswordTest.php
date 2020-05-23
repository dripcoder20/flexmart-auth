<?php

namespace Tests\Feature\User;

use App\Events\UserHasRequestedVerification;
use App\Events\UserResetPasswordHasSucceeded;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserForgotPasswordTest extends TestCase
{
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create([
            'mobile_number' => '+639107654321',
            'created_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::yesterday()->format('Y-m-d H:i:s')
        ]);
        $this->get('forgot');
    }

    /** @test */
    public function it_should_redirect_forgot_password_page_to_mobile_validation_page()
    {
        $this->get('forgot')->assertRedirect('/mobile/validate');
    }

    /** @test */
    public function it_should_have_mobile_validation_session()
    {
        $this->assertTrue(session()->has('mobile:validation'));
        $this->assertEquals('exists:users,mobile_number', session('mobile:validation'));
    }

    /** @test */
    public function it_should_have_redirect_after_session()
    {
        $this->assertTrue(session()->has('redirect_after'));
        $this->assertEquals(url('reset-password'), session('redirect_after'));
    }

    /** @test */
    public function it_should_have_key_prefix_session()
    {
        $this->assertTrue(session()->has('key_prefix'));
        $this->assertEquals('verified-forgot-', session('key_prefix'));
    }

    /** @test */
    public function it_should_redirect_to_verify_page_after_success_validation()
    {
        $this->post('/api/mobile/validate', [
            'mobile_number' => $this->user->mobile_number
        ])->assertSee('mobile/verify');
    }

    /** @test */
    public function it_should_show_reset_password_page()
    {
        $this->get('reset-password')
            ->assertOk()
            ->assertViewIs('reset-password');
    }

    /** @test */
    public function it_should_validate_mobile_number()
    {
        $this->withoutExceptionHandling();
        $this->expectException(ValidationException::class);
        $this->post('/api/mobile/validate', [
                'mobile_number' => 'NOT A NUMBER'
        ]);
    }

    /** @test */
    public function it_should_validate_registered_mobile_number()
    {
        $this->withoutExceptionHandling();
        $this->expectException(ValidationException::class);
        $this->post('/api/mobile/validate', [
                'mobile_number' => '+639101111111'
            ]);
    }

    /** @test */
    public function a_registered_mobile_number_can_request_otp_to_reset_password()
    {
        $this->post('/api/mobile/validate', [
            'mobile_number' => $this->user->mobile_number
        ])->assertSee('/mobile/verify');
    }

    /** @test */
    public function it_will_send_verification_code_via_email_to_registered_user()
    {
        Event::fake();
        $response = $this->post('/api/mobile/validate', [
            'mobile_number' => $this->user->mobile_number
        ]);
        Event::assertDispatched(UserHasRequestedVerification::class, function () use ($response) {
            return $response->getStatusCode() === Response::HTTP_FOUND;
        });
    }

    /** @test */
    public function it_will_resend_verification_code()
    {
        $token = $this->postJson('/api/mobile/validate', [
            'mobile_number' => $this->user->mobile_number
        ])->getOriginalContent()['token'];

        $this->post('api/resend-verification', [
            'token' => $token
        ])->assertStatus(Response::HTTP_ACCEPTED);
    }

    /** @test */
    public function it_will_not_send_email_to_unregistered_email()
    {
        Event::fake();
        $noEmailUser = factory(User::class)->create([
            'email' => ''
        ]);

        $response = $this->post('/api/mobile/validate', [
            'mobile_number' => $noEmailUser->mobile_number
        ]);
        Event::assertNotDispatched(UserHasRequestedVerification::class, function () use ($response) {
            return $response->getStatusCode() === Response::HTTP_FOUND;
        });
    }

    /** @test */
    public function it_should_require_the_password()
    {
        $this->withoutExceptionHandling();
        $this->expectException(ValidationException::class);
        $this->postJson('/api/reset-password', [
            'password' => '',
            'password_confirmation' => ''
        ]);
    }

    /** @test */
    public function it_should_confirm_the_password()
    {
        $this->withoutExceptionHandling();
        $this->expectException(ValidationException::class);
        $this->postJson('/api/reset-password', [
            'password' => 'password123',
            'password_confirmation' => 'passwordMisMatch'
        ]);
    }

    /** @test */
    public function it_can_update_users_password()
    {
        $verifiedUser = $this->verified_user();
        $token = $verifiedUser->getOriginalContent()['confirmation_token'];

        $this->post('api/reset-password', [
            'token' => $token,
            'password' => 'newPassword123.',
            'password_confirmation' => 'newPassword123.'
        ]);
        $user = User::where('mobile_number', $this->user->mobile_number)->first();

        $this->assertEquals(Carbon::yesterday()->format('Y-m-d H:i:s'), $user->created_at);
        $this->assertEquals(Carbon::now()->format('Y-m-d'), $user->updated_at->format('Y-m-d'));
    }

    /** @test */
    public function it_will_respond_on_success()
    {
        $this->withoutExceptionHandling();
        $verifiedUser = $this->verified_user();
        $token = $verifiedUser->getOriginalContent()['confirmation_token'];

        $this->postJson('api/reset-password', [
            'token' => $token,
            'password' => 'newPassword123.',
            'password_confirmation' => 'newPassword123.'
        ])
            ->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /** @test */
    public function it_will_redirect_to_login_after_password_reset_success()
    {
        $verifiedUser = $this->verified_user();
        $token = $verifiedUser->getOriginalContent()['confirmation_token'];

        $this->post('api/reset-password', [
            'token' => $token,
            'password' => 'newPassword123.',
            'password_confirmation' => 'newPassword123.'
        ])
            ->assertRedirect('login');
    }

    /** @test */
    public function it_will_notify_reset_success_via_email()
    {
        Event::fake();
        $verifiedUser = $this->verified_user();
        $token = $verifiedUser->getOriginalContent()['confirmation_token'];

        $response = $this->post('api/reset-password', [
            'token' => $token,
            'password' => 'newPassword123.',
            'password_confirmation' => 'newPassword123.'
        ]);

        Event::assertDispatched(UserResetPasswordHasSucceeded::class, function () use ($response) {
            return $response->getStatusCode() === Response::HTTP_FOUND;
        });
    }

    private function verified_user()
    {
        $identifier = Str::random(32);
        Cache::put('otp-' . $identifier, random_int(100000, 999999), 5);
        Cache::put(session('key_prefix') . $this->user->mobile_number, $identifier, 5);
        $otp = Cache::get('otp-' . $identifier);

        return $verified = $this->postJson('/api/mobile/verify', [
            'code' => $otp,
            'token' => Crypt::encrypt(session('key_prefix') . $this->user->mobile_number)
        ]);
    }
}
