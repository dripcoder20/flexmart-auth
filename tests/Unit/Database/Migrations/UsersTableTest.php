<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Support\Facades\Schema;

class UsersTableTest extends TestCase
{
    use RefreshDatabase;

    /* @test */
    public function testUsersTableExists()
    {
        $this->withoutExceptionHandling();

        $this->assertTrue(Schema::hasTable('users'));
    }

    /* @test */
    public function testUsersColumnsExists()
    {
        $this->withoutExceptionHandling();

        collect([
            'first_name',
            'last_name',
            'middle_name',
            'mobile_number',
            'email',
            'house_number',
            'address',
            'city',
            'province',
            'zip_code',
            'nickname',
            'telephone_number',
            'status',
            'verified',
            'password',
            ])->each(function ($column){
                $this->assertTrue(Schema::hasColumn('users', $column));
            }
        );
    }

    /* @test */
    public function testUsersFirstNameIsRequired()
    {
        $this->expectException(QueryException::class);

        DB::table('users')->insert(array_merge(
            $this->data(),
            [
                'first_name' => null
            ]
        ));

        $this->assertCount(0, User::all());
    }

    /* @test */
    public function testUsersLastNameIsRequired()
    {
        $this->expectException(QueryException::class);

        DB::table('users')->insert(array_merge(
            $this->data(),
            [
                'last_name' => null
            ]
        ));

        $this->assertCount(0, User::all());
    }

    /* @test */
    public function testUsersMobileNumberIsRequired()
    {
        $this->expectException(QueryException::class);

        DB::table('users')->insert(array_merge(
            $this->data(),
            [
                'mobile_number' => null
            ]
        ));

        $this->assertCount(0, User::all());
    }

    /* @test */
    public function testUsersPasswordIsRequired()
    {
        $this->expectException(QueryException::class);

        DB::table('users')->insert(array_merge(
            $this->data(),
            [
                'mobile_number' => null
            ]
        ));

        $this->assertCount(0, User::all());
    }

    /* @test */
    public function testUsersMobileNumberColumnUniqueness()
    {
        $this->expectException(QueryException::class);

        DB::table('users')->insert($this->data());

        DB::table('users')->insert(array_merge(
            $this->data(),
            ['mobile_number' => 1234567890]
        ));

        $this->assertCount(1, User::all());
    }

    /* @test */
    public function testUsersEmailColumnUniqueness()
    {
        $this->expectException(QueryException::class);

        DB::table('users')->insert(array_merge(
            $this->data(),
            [
                'mobile_number' => 1234567890,
                'email' => 'email@test.com'
            ]
        ));

        DB::table('users')->insert(array_merge(
            $this->data(),
            [
                'mobile_number' => 9876543210,
                'email' => 'email@test.com'
            ]
        ));

        $this->assertCount(1, User::all());
    }

    private function data()
    {
        return [
            'first_name' => 'First',
            'last_name' => 'Last',
            'mobile_number' => 1234567890,
            'password' => bcrypt('password@123')
        ];
    }
}
