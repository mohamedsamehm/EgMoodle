<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MaterialsSeeder extends Seeder
{
  public function run()
  {
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    DB::table('materials')->truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    DB::table('materials')->insert([
      [
        'course_id' => 'CMP538',
        'teams_url' => '',
        'file_name' => '',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'title' => 'Quiz 1',
        'isAssignment' => 0,
        'isExam' => 1,
        'isContent' => 0,
        'week' => 1,
        'user_id' => 1
      ],
      [
        'course_id' => 'CMP461',
        'teams_url' => 'https://teams.live.com/dl/launcher/launcher.html?url=%2F_%23%2Fmeet%2F9433059669669%3Fanon%3Dtrue&type=meet&deeplinkId=879d1690-a87d-4dcb-8d07-ce1d07572b98&directDl=true&msLaunch=true&enableMobilePage=true&suppressPrompt=true',
        'file_name' => '',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'title' => 'Meeting 1',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 1
      ],
      [
        'course_id' => 'CMP461',
        'teams_url' => 'https://teams.live.com/dl/launcher/launcher.html?url=%2F_%23%2Fmeet%2F9433059669669%3Fanon%3Dtrue&type=meet&deeplinkId=879d1690-a87d-4dcb-8d07-ce1d07572b98&directDl=true&msLaunch=true&enableMobilePage=true&suppressPrompt=true',
        'file_name' => '',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'title' => 'Meeting 2',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 1
      ],
      [
        'course_id' => 'CMP461',
        'teams_url' => 'https://teams.live.com/dl/launcher/launcher.html?url=%2F_%23%2Fmeet%2F9433059669669%3Fanon%3Dtrue&type=meet&deeplinkId=879d1690-a87d-4dcb-8d07-ce1d07572b98&directDl=true&msLaunch=true&enableMobilePage=true&suppressPrompt=true',
        'file_name' => '',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'title' => 'Meeting 3',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 1
      ],
      [
        'course_id' => 'CMP461',
        'teams_url' => 'https://teams.live.com/dl/launcher/launcher.html?url=%2F_%23%2Fmeet%2F9433059669669%3Fanon%3Dtrue&type=meet&deeplinkId=879d1690-a87d-4dcb-8d07-ce1d07572b98&directDl=true&msLaunch=true&enableMobilePage=true&suppressPrompt=true',
        'file_name' => '',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'title' => 'Meeting 4',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 1
      ],
      [
        'course_id' => 'CMP461',
        'teams_url' => 'https://teams.live.com/dl/launcher/launcher.html?url=%2F_%23%2Fmeet%2F9433059669669%3Fanon%3Dtrue&type=meet&deeplinkId=879d1690-a87d-4dcb-8d07-ce1d07572b98&directDl=true&msLaunch=true&enableMobilePage=true&suppressPrompt=true',
        'file_name' => '',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'title' => 'Meeting 5',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 1
      ],
      [
        'course_id' => 'CMP524',
        'teams_url' => 'https://teams.live.com/dl/launcher/launcher.html?url=%2F_%23%2Fmeet%2F9433059669669%3Fanon%3Dtrue&type=meet&deeplinkId=879d1690-a87d-4dcb-8d07-ce1d07572b98&directDl=true&msLaunch=true&enableMobilePage=true&suppressPrompt=true',
        'file_name' => '',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'title' => 'Meeting 1',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 1
      ],
      [
        'course_id' => 'CMP524',
        'teams_url' => 'https://teams.live.com/dl/launcher/launcher.html?url=%2F_%23%2Fmeet%2F9433059669669%3Fanon%3Dtrue&type=meet&deeplinkId=879d1690-a87d-4dcb-8d07-ce1d07572b98&directDl=true&msLaunch=true&enableMobilePage=true&suppressPrompt=true',
        'file_name' => '',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'title' => 'Meeting 2',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 1
      ],
      [
        'course_id' => 'CMP524',
        'teams_url' => 'https://teams.live.com/dl/launcher/launcher.html?url=%2F_%23%2Fmeet%2F9433059669669%3Fanon%3Dtrue&type=meet&deeplinkId=879d1690-a87d-4dcb-8d07-ce1d07572b98&directDl=true&msLaunch=true&enableMobilePage=true&suppressPrompt=true',
        'file_name' => '',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'title' => 'Meeting 3',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 1
      ],
      [
        'course_id' => 'CMP524',
        'teams_url' => 'https://teams.live.com/dl/launcher/launcher.html?url=%2F_%23%2Fmeet%2F9433059669669%3Fanon%3Dtrue&type=meet&deeplinkId=879d1690-a87d-4dcb-8d07-ce1d07572b98&directDl=true&msLaunch=true&enableMobilePage=true&suppressPrompt=true',
        'file_name' => '',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'title' => 'Meeting 4',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 1
      ],
      [
        'course_id' => 'CMP524',
        'teams_url' => 'https://teams.live.com/dl/launcher/launcher.html?url=%2F_%23%2Fmeet%2F9433059669669%3Fanon%3Dtrue&type=meet&deeplinkId=879d1690-a87d-4dcb-8d07-ce1d07572b98&directDl=true&msLaunch=true&enableMobilePage=true&suppressPrompt=true',
        'file_name' => '',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'title' => 'Meeting 5',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 1
      ],
      [
        'course_id' => 'CMP538',
        'teams_url' => 'https://teams.live.com/dl/launcher/launcher.html?url=%2F_%23%2Fmeet%2F9433059669669%3Fanon%3Dtrue&type=meet&deeplinkId=879d1690-a87d-4dcb-8d07-ce1d07572b98&directDl=true&msLaunch=true&enableMobilePage=true&suppressPrompt=true',
        'file_name' => '',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'title' => 'Meeting 1',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 1
      ],
      [
        'course_id' => 'CMP538',
        'teams_url' => 'https://teams.live.com/dl/launcher/launcher.html?url=%2F_%23%2Fmeet%2F9433059669669%3Fanon%3Dtrue&type=meet&deeplinkId=879d1690-a87d-4dcb-8d07-ce1d07572b98&directDl=true&msLaunch=true&enableMobilePage=true&suppressPrompt=true',
        'file_name' => '',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'title' => 'Meeting 2',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 1
      ],
      [
        'course_id' => 'CMP538',
        'teams_url' => 'https://teams.live.com/dl/launcher/launcher.html?url=%2F_%23%2Fmeet%2F9433059669669%3Fanon%3Dtrue&type=meet&deeplinkId=879d1690-a87d-4dcb-8d07-ce1d07572b98&directDl=true&msLaunch=true&enableMobilePage=true&suppressPrompt=true',
        'file_name' => '',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'title' => 'Meeting 3',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 1
      ],
      [
        'course_id' => 'CMP538',
        'teams_url' => 'https://teams.live.com/dl/launcher/launcher.html?url=%2F_%23%2Fmeet%2F9433059669669%3Fanon%3Dtrue&type=meet&deeplinkId=879d1690-a87d-4dcb-8d07-ce1d07572b98&directDl=true&msLaunch=true&enableMobilePage=true&suppressPrompt=true',
        'file_name' => '',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'title' => 'Meeting 4',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 1
      ],
      [
        'course_id' => 'CMP538',
        'teams_url' => 'https://teams.live.com/dl/launcher/launcher.html?url=%2F_%23%2Fmeet%2F9433059669669%3Fanon%3Dtrue&type=meet&deeplinkId=879d1690-a87d-4dcb-8d07-ce1d07572b98&directDl=true&msLaunch=true&enableMobilePage=true&suppressPrompt=true',
        'file_name' => '',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'title' => 'Meeting 5',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 1
      ],
      [
        'course_id' => 'P2B',
        'teams_url' => 'https://teams.live.com/dl/launcher/launcher.html?url=%2F_%23%2Fmeet%2F9433059669669%3Fanon%3Dtrue&type=meet&deeplinkId=879d1690-a87d-4dcb-8d07-ce1d07572b98&directDl=true&msLaunch=true&enableMobilePage=true&suppressPrompt=true',
        'file_name' => '',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'title' => 'Meeting 1',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 1
      ],
      [
        'course_id' => 'P2B',
        'teams_url' => 'https://teams.live.com/dl/launcher/launcher.html?url=%2F_%23%2Fmeet%2F9433059669669%3Fanon%3Dtrue&type=meet&deeplinkId=879d1690-a87d-4dcb-8d07-ce1d07572b98&directDl=true&msLaunch=true&enableMobilePage=true&suppressPrompt=true',
        'file_name' => '',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'title' => 'Meeting 2',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 1
      ],
      [
        'course_id' => 'P2B',
        'teams_url' => 'https://teams.live.com/dl/launcher/launcher.html?url=%2F_%23%2Fmeet%2F9433059669669%3Fanon%3Dtrue&type=meet&deeplinkId=879d1690-a87d-4dcb-8d07-ce1d07572b98&directDl=true&msLaunch=true&enableMobilePage=true&suppressPrompt=true',
        'file_name' => '',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'title' => 'Meeting 3',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 1
      ],
      [
        'course_id' => 'P2B',
        'teams_url' => 'https://teams.live.com/dl/launcher/launcher.html?url=%2F_%23%2Fmeet%2F9433059669669%3Fanon%3Dtrue&type=meet&deeplinkId=879d1690-a87d-4dcb-8d07-ce1d07572b98&directDl=true&msLaunch=true&enableMobilePage=true&suppressPrompt=true',
        'file_name' => '',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'title' => 'Meeting 4',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 1
      ],
      [
        'course_id' => 'P2B',
        'teams_url' => 'https://teams.live.com/dl/launcher/launcher.html?url=%2F_%23%2Fmeet%2F9433059669669%3Fanon%3Dtrue&type=meet&deeplinkId=879d1690-a87d-4dcb-8d07-ce1d07572b98&directDl=true&msLaunch=true&enableMobilePage=true&suppressPrompt=true',
        'file_name' => '',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'title' => 'Meeting 5',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 1
      ],
      [
        'course_id' => 'CMP435',
        'teams_url' => 'https://teams.live.com/dl/launcher/launcher.html?url=%2F_%23%2Fmeet%2F9433059669669%3Fanon%3Dtrue&type=meet&deeplinkId=879d1690-a87d-4dcb-8d07-ce1d07572b98&directDl=true&msLaunch=true&enableMobilePage=true&suppressPrompt=true',
        'file_name' => '',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'title' => 'Meeting 1',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 2
      ],
      [
        'course_id' => 'CMP435',
        'teams_url' => 'https://teams.live.com/dl/launcher/launcher.html?url=%2F_%23%2Fmeet%2F9433059669669%3Fanon%3Dtrue&type=meet&deeplinkId=879d1690-a87d-4dcb-8d07-ce1d07572b98&directDl=true&msLaunch=true&enableMobilePage=true&suppressPrompt=true',
        'file_name' => '',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'title' => 'Meeting 2',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 2
      ],
      [
        'course_id' => 'CMP435',
        'teams_url' => 'https://teams.live.com/dl/launcher/launcher.html?url=%2F_%23%2Fmeet%2F9433059669669%3Fanon%3Dtrue&type=meet&deeplinkId=879d1690-a87d-4dcb-8d07-ce1d07572b98&directDl=true&msLaunch=true&enableMobilePage=true&suppressPrompt=true',
        'file_name' => '',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'title' => 'Meeting 3',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 2
      ],
      [
        'course_id' => 'CMP435',
        'teams_url' => 'https://teams.live.com/dl/launcher/launcher.html?url=%2F_%23%2Fmeet%2F9433059669669%3Fanon%3Dtrue&type=meet&deeplinkId=879d1690-a87d-4dcb-8d07-ce1d07572b98&directDl=true&msLaunch=true&enableMobilePage=true&suppressPrompt=true',
        'file_name' => '',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'title' => 'Meeting 4',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 2
      ],
      [
        'course_id' => 'CMP435',
        'teams_url' => 'https://teams.live.com/dl/launcher/launcher.html?url=%2F_%23%2Fmeet%2F9433059669669%3Fanon%3Dtrue&type=meet&deeplinkId=879d1690-a87d-4dcb-8d07-ce1d07572b98&directDl=true&msLaunch=true&enableMobilePage=true&suppressPrompt=true',
        'file_name' => '',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'title' => 'Meeting 5',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 2
      ],
      [
        'course_id' => 'CMP426',
        'teams_url' => 'https://teams.live.com/dl/launcher/launcher.html?url=%2F_%23%2Fmeet%2F9433059669669%3Fanon%3Dtrue&type=meet&deeplinkId=879d1690-a87d-4dcb-8d07-ce1d07572b98&directDl=true&msLaunch=true&enableMobilePage=true&suppressPrompt=true',
        'file_name' => '',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'title' => 'Meeting 1',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 3
      ],
      [
        'course_id' => 'CMP426',
        'teams_url' => 'https://teams.live.com/dl/launcher/launcher.html?url=%2F_%23%2Fmeet%2F9433059669669%3Fanon%3Dtrue&type=meet&deeplinkId=879d1690-a87d-4dcb-8d07-ce1d07572b98&directDl=true&msLaunch=true&enableMobilePage=true&suppressPrompt=true',
        'file_name' => '',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'title' => 'Meeting 2',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 3
      ],
      [
        'course_id' => 'CMP426',
        'teams_url' => 'https://teams.live.com/dl/launcher/launcher.html?url=%2F_%23%2Fmeet%2F9433059669669%3Fanon%3Dtrue&type=meet&deeplinkId=879d1690-a87d-4dcb-8d07-ce1d07572b98&directDl=true&msLaunch=true&enableMobilePage=true&suppressPrompt=true',
        'file_name' => '',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'title' => 'Meeting 3',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 3
      ],
      [
        'course_id' => 'CMP426',
        'teams_url' => 'https://teams.live.com/dl/launcher/launcher.html?url=%2F_%23%2Fmeet%2F9433059669669%3Fanon%3Dtrue&type=meet&deeplinkId=879d1690-a87d-4dcb-8d07-ce1d07572b98&directDl=true&msLaunch=true&enableMobilePage=true&suppressPrompt=true',
        'file_name' => '',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'title' => 'Meeting 4',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 3
      ],
      [
        'course_id' => 'CMP426',
        'teams_url' => 'https://teams.live.com/dl/launcher/launcher.html?url=%2F_%23%2Fmeet%2F9433059669669%3Fanon%3Dtrue&type=meet&deeplinkId=879d1690-a87d-4dcb-8d07-ce1d07572b98&directDl=true&msLaunch=true&enableMobilePage=true&suppressPrompt=true',
        'file_name' => '',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        'title' => 'Meeting 5',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 3
      ],
      [
        'course_id' => 'CMP524',
        'teams_url' => '',
        'file_name' => '/storage/uploads/sim_02_L1_contents_S26.ppt',
        'content' => 'objective lay out and general discussion',
        'title' => 'Lecture 0',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 1
      ],
      [
        'course_id' => 'CMP524',
        'teams_url' => '',
        'file_name' => '/storage/uploads/sim_modeling_bisinessmodel_lect.pptx',
        'content' => 'business model concepts; introduces an introductory level to modeling operations of any system.',
        'title' => 'Lecture 1',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 1,
        'user_id' => 1
      ],
      [
        'course_id' => 'CMP524',
        'teams_url' => '',
        'file_name' => '/storage/uploads/sim_02_L1_contents_S26.ppt',
        'content' => 'verification- validation process',
        'title' => 'Lecture 2',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 2,
        'user_id' => 1
      ],
      [
        'course_id' => 'CMP524',
        'teams_url' => '',
        'file_name' => '/storage/uploads/sim_03_L2_MONTCARLO_S21.pptx',
        'content' => '',
        'title' => 'Lecture 3',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 3,
        'user_id' => 1
      ],
      [
        'course_id' => 'CMP524',
        'teams_url' => '',
        'file_name' => '/storage/uploads/sim_05_L4_prob_part2_S17.ppt',
        'content' => '',
        'title' => 'sim 05 L4 prob part2 S17',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 4,
        'user_id' => 1
      ],
      [
        'course_id' => 'CMP524',
        'teams_url' => '',
        'file_name' => '/storage/uploads/sim_06_L5_rand_No_gen_S26.ppt',
        'content' => '',
        'title' => 'L5',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 5,
        'user_id' => 1
      ],
      [
        'course_id' => 'CMP524',
        'teams_url' => '',
        'file_name' => '/storage/uploads/sim_07_L6_rand_variates_S18.ppt',
        'content' => '',
        'title' => 'sim07-L6',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 6,
        'user_id' => 1
      ],
      [
        'course_id' => 'CMP524',
        'teams_url' => '',
        'file_name' => '/storage/uploads/sim_08_L7_rentcar_queue_S23.ppt',
        'content' => 'rent car case study',
        'title' => 'sim08_L7',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 7,
        'user_id' => 1
      ],
      [
        'course_id' => 'CMP524',
        'teams_url' => '',
        'file_name' => '/storage/uploads/sim_09_L8_que_examples_S16.ppt',
        'content' => 'queueing system',
        'title' => 'sim09_L8',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 8,
        'user_id' => 1
      ],
      [
        'course_id' => 'CMP524',
        'teams_url' => '',
        'file_name' => '/storage/uploads/sim_L10_exc_P1.pptx',
        'content' => 'practical problems',
        'title' => 'modeling_exc',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 9,
        'user_id' => 1
      ],
      [
        'course_id' => 'CMP524',
        'teams_url' => '',
        'file_name' => '/storage/uploads/sim_L11_exc_P2.pptx',
        'content' => 'practical problems 2',
        'title' => 'modeling_exc2',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 10,
        'user_id' => 1
      ],
      [
        'course_id' => 'CMP524',
        'teams_url' => '',
        'file_name' => '/storage/uploads/sim_L12_exc_P3.pptx',
        'content' => 'practical problems 3',
        'title' => 'modeling 3',
        'isAssignment' => 0,
        'isExam' => 0,
        'isContent' => 1,
        'week' => 10,
        'user_id' => 1
      ],
    ]);
  }
}