<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Assessment;
use App\Models\Assignment;
use App\Models\ClassMembership;
use App\Models\ClassSubject;
use App\Models\Country;
use App\Models\EducationLevel;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SchoolAndClassSeeder extends Seeder
{
    /**
     * Run the database seeds for schools, classes, memberships, and initial assignments.
     */
    public function run(): void
    {
        $country = Country::where('code', 'TG')->first();
        $academicYear = AcademicYear::where('is_current', true)->first();
        $level3e = EducationLevel::where('code', '3E')->first();
        $maths = Subject::where('code', 'MATHS')->first();
        $teacher = User::where('email', 'prof.maths@kamehub.tg')->first();
        $assessment = Assessment::where('title', 'like', '%Thalès%')->first();

        if (!$country || !$academicYear || !$level3e || !$maths || !$teacher || !$assessment) {
            return;
        }

        // 1. Établissement de référence : Lycée de Tokoin (Lomé)
        $school = School::firstOrCreate(
            ['name' => 'Lycée de Tokoin (Lomé)', 'country_id' => $country->id],
            [
                'school_type' => 'public',
                'city' => 'Lomé',
                'status' => 'active',
            ]
        );

        // 2. Classe de 3e A
        $class3eA = SchoolClass::firstOrCreate(
            ['school_id' => $school->id, 'label' => 'Classe de 3e A', 'academic_year_id' => $academicYear->id],
            [
                'education_level_id' => $level3e->id,
                'series_id' => null,
                'created_by' => $teacher->id,
            ]
        );

        // 3. Attribution de la matière Mathématiques à l'enseignant
        ClassSubject::firstOrCreate(
            ['class_id' => $class3eA->id, 'subject_id' => $maths->id],
            [
                'teacher_user_id' => $teacher->id,
            ]
        );

        // 4. Élève Koffi Mensah
        $studentKoffi = User::firstOrCreate(
            ['email' => 'koffi.student@monkayier.tg'],
            [
                'password_hash' => bcrypt('StudentPass2026!'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $studentKoffi->assignRole('student');

        UserProfile::firstOrCreate(
            ['user_id' => $studentKoffi->id],
            [
                'first_name' => 'Koffi',
                'last_name' => 'Mensah',
            ]
        );

        // Inscription de Koffi en 3e A
        ClassMembership::firstOrCreate(
            ['class_id' => $class3eA->id, 'user_id' => $studentKoffi->id, 'membership_role' => 'student'],
            [
                'status' => 'active',
                'joined_at' => now(),
            ]
        );

        // 5. Devoir assigné à la 3e A
        Assignment::firstOrCreate(
            ['class_id' => $class3eA->id, 'title' => 'Devoir Surveillé n°1 : Théorème de Thalès & Applications'],
            [
                'teacher_user_id' => $teacher->id,
                'assessment_id' => $assessment->id,
                'due_at' => now()->addDays(7),
                'status' => 'published',
            ]
        );
    }
}
