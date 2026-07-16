<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CreateNewUser
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        $email = Str::lower($input['email']);

        $rules = [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
        ];

        if ($this->isLecturerEmail($email)) {
            $rules['desk_contact'] = ['required', 'string', 'max:15'];
            $rules['degree_type'] = ['required', 'in:undergraduate,masters,phd'];
        }

        if ($this->isStudentEmail($email)) {
            $rules['category'] = ['required', 'string', 'max:255'];
        }

        Validator::make($input, $rules)->validate();

        return DB::transaction(function () use ($input, $email): User {
            $role = $this->resolveRole($email);

            $user = User::create([
                'name' => $input['name'],
                'email' => $email,
                'password' => $input['password'],
                'role' => $role,
                'status' => 'active',
            ]);

            if ($role === 'lecturer') {
                $this->createLecturerProfile($user->id, $input);
            }

            if ($role === 'student') {
                $this->createStudentProfile($user->id, $input);
            }

            return $user;
        });
    }

    private function resolveRole(string $email): string
    {
        if ($this->isLecturerEmail($email)) {
            return 'lecturer';
        }

        return 'student';
    }

    private function isLecturerEmail(string $email): bool
    {
        return Str::endsWith(Str::lower($email), '@lecturers.ed');
    }

    private function isStudentEmail(string $email): bool
    {
        return Str::endsWith(Str::lower($email), '@students.ed');
    }

    private function createLecturerProfile(int $userId, array $input): void
    {
        $identifierColumn = Schema::hasColumn('lecturers', 'user_id')
            ? 'user_id'
            : (Schema::hasColumn('lecturers', 'LecturerID') ? 'LecturerID' : null);

        if ($identifierColumn === null) {
            return;
        }

        DB::table('lecturers')->insert([
            $identifierColumn => $userId,
            'contact' => $input['desk_contact'],
            'DegreeType' => $input['degree_type'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createStudentProfile(int $userId, array $input): void
    {
        $identifierColumn = Schema::hasColumn('students', 'user_id')
            ? 'user_id'
            : (Schema::hasColumn('students', 'StudentID') ? 'StudentID' : null);

        if ($identifierColumn === null) {
            return;
        }

        $categoryName = Str::headline(str_replace('_', ' ', $input['category']));

        $categoryId = DB::table('categories')
            ->where('CategoryName', $categoryName)
            ->value('CategoryID');

        if ($categoryId === null) {
            $categoryId = DB::table('categories')->insertGetId([
                'CategoryName' => $categoryName,
                'created_at' => now(),
                'updated_at' => now(),
            ], 'CategoryID');
        }

        DB::table('students')->insert([
            $identifierColumn => $userId,
            'CategoryID' => $categoryId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
