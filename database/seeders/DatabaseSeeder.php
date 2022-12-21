<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(StudentSeeder::class);
        $this->call(ProfessorSeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(LevelsSeeder::class);
        $this->call(SemesterSeeder::class);
        $this->call(SectionSeeder::class);
        $this->call(CoursesSeeder::class);
        $this->call(CourseSectionSeeder::class);
        $this->call(PeriodsSeeder::class);
        $this->call(MaterialsSeeder::class);
        $this->call(MaterialSectionSeeder::class);
        $this->call(MeetingSeeder::class);
        $this->call(StudentAttendancesSeeder::class);
        $this->call(ExamSeeder::class);
        $this->call(QuestionSeeder::class);
        $this->call(OptionSeeder::class);
        $this->call(LectureSeeder::class);
        $this->call(EnrollmentSeeder::class);
        $this->call(SurveyQuestion::class);
    }
}
