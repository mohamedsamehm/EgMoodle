<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Auth\ForgotPasswordController;

use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\StudentMaterialController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\StudentAssignmentController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudentExamController;
use App\Http\Controllers\StudentAttendanceController;
use App\Http\Controllers\SurveyQuestionController;
use App\Http\Controllers\SurveyController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('guest')->group(function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/usersList', [MessageController::class, 'index']);
    Route::get('/messages', [MessageController::class, 'messages']);
    Route::get('/getChat/{id}', [MessageController::class, 'getChat']);
    Route::post('/sendMessage', [MessageController::class, 'sendMessage']);
    Route::post('/updateType/{id}', [MessageController::class, 'updateType']);
    Route::post('/changeStatus', [MessageController::class, 'changeStatus']);
    Route::post('/clearAll', [MessageController::class, 'destroy']);
    
    Route::post('logout', [LoginController::class, 'logout']);
    
    Route::get('refresh/token', [LoginController::class, 'refreshToken']);
        
    Route::get('user', [UserController::class, 'me']);
    Route::post('profile', [UserController::class, 'updateProfile']);
    Route::post('password', [UserController::class, 'updatePassword']);
    
    Route::get('/enroll', [EnrollmentController::class, 'show']);

    Route::get('/lecture', [LectureController::class, 'show']);

    Route::get('showLectures/{id}', [LectureController::class, 'showLectures']);

    Route::post('/material/show', [StudentMaterialController::class, 'show']);

    Route::post('/material/store', [StudentMaterialController::class, 'store']);

    Route::post('/material/update', [StudentMaterialController::class, 'update']);

    Route::get('/course_material/show_student/{id}', [MaterialController::class, 'show']);

    Route::get('/course_material/show_professor/{id}', [MaterialController::class, 'show_professor']);

    Route::get('myassignment/{id}', [AssignmentController::class, 'show']);

    Route::post('/quiz', [QuestionController::class, 'index']);

    Route::post('/questions', [QuestionController::class, 'show']);

    Route::post('/questions/destroy', [QuestionController::class, 'destroy']);

    Route::post('/questions/store', [QuestionController::class, 'store']);

    Route::post('/quiz/store', [StudentExamController::class, 'store']);

    Route::post('/questions/update', [QuestionController::class, 'update']);

    Route::post('/schedule/professor', [ScheduleController::class, 'professor_schedule']);

    Route::post('/schedule/timetable', [ScheduleController::class, 'student_timetable']);

    Route::post('/schedule/student', [ScheduleController::class, 'student_schedule']);

    Route::get('/myassignments', [AssignmentController::class, 'index']);

    Route::get('/quizzes/professor/', [ExamController::class, 'indexProfessor']);

    Route::get('/quizzes/{id}', [ExamController::class, 'getQuizDetails']);

    Route::get('/quizzes/grades/{id}', [ExamController::class, 'indexProfessorMarks']);

    Route::get('/myquiz/{id}', [ExamController::class, 'getQuizDetailsStudent']);

    Route::get('/myquiz/results/{id}', [ExamController::class, 'getQuizDetailsStudentResults']);

    Route::get('/myquizzes', [ExamController::class, 'indexStudent']);

    Route::post('/quizzes/professor/store', [ExamController::class, 'store']);

    Route::post('/quizzes/professor/update', [ExamController::class, 'update']);

    Route::get('/assignments/professor/', [AssignmentController::class, 'indexProfessor']);

    Route::get('/assignments/professor/all', [AssignmentController::class, 'all']);
    
    Route::get('/assignments/{id}', [AssignmentController::class, 'getAssignmentDetails']);

    Route::post('/assignments/professor/store', [AssignmentController::class, 'store']);

    Route::post('/assignments/professor/update', [AssignmentController::class, 'update']);
    
    Route::post('/meetings/store', [StudentAttendanceController::class, 'store']);

    Route::get('/meetings/viewMyAttendances', [StudentAttendanceController::class, 'viewMyAttendances']);

    Route::get('/meetings/professor/', [MeetingController::class, 'indexProfessor']);

    Route::get('/meetings/viewAttendance/', [MeetingController::class, 'viewAttendance']);
    
    Route::get('/meetings/viewAllAttendance/', [MeetingController::class, 'viewAllAttendance']);
    
    Route::get('/meetings/viewStudentAttendance/', [MeetingController::class, 'viewStudentAttendance']);
    
    Route::get('/meetings/{id}', [MeetingController::class, 'getMeetingDetails']);

    Route::get('/meetings/getAllStudents/{id}', [MeetingController::class, 'getAllStudents']);

    Route::get('/meetings/getAllStudentsForMeetings/{id}', [StudentAttendanceController::class, 'getAllStudentsForMeetings']);

    Route::post('/meetings/professor/store', [MeetingController::class, 'store']);

    Route::post('/meetings/professor/update', [MeetingController::class, 'update']);

    Route::post('/materials/destroy', [MaterialController::class, 'destroy']);

    Route::post('/student_assignments/store', [StudentAssignmentController::class, 'store']);

    Route::post('/student_assignments/update', [StudentAssignmentController::class, 'update']);

    Route::post('/showMyAssignments', [StudentAssignmentController::class, 'show']);

    Route::get('/markAssignments/{id}', [StudentAssignmentController::class, 'showAssignments']);

    Route::post('/saveGrade', [StudentAssignmentController::class, 'saveGrade']);

    Route::get('/survey/questions', [SurveyQuestionController::class, 'index']);

    Route::post('/survey/store', [SurveyController::class, 'store']);

    Route::get('/survey/show/{id}', [SurveyController::class, 'show']);

    // Route::middleware('admin')->group(function () {
    //     Route::group(['prefix' => 'users'], function () {
    //         Route::get('/', [AdminUserController::class, 'getUsers'])->name('admin.users');
    //         Route::get('{id}', [AdminUserController::class, 'getUserDetails'])->name('admin.user.details');
    //         Route::post('save', [AdminUserController::class, 'saveUser'])->name('admin.user.save');
    //         Route::put('{id}', [AdminUserController::class, 'updateUser'])->name('admin.user.update');
    //         // Route::post('/update', [AdminUserController::class, 'updateUser'])->name('admin.user.update');
    //         Route::post('delete', [AdminUserController::class, 'deleteUser'])->name('admin.user.delete');
    //         Route::get('password', [AdminUserController::class, 'generatePassword'])->name('admin.user.password');
    //         Route::post('change-status', [AdminUserController::class, 'changeStatusUser'])->name('admin.user.change.status');
    //     });
    // });
});