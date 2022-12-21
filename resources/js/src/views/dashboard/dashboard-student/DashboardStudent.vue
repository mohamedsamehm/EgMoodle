<template>
  <section id="dashboard">
    <b-row>
      <b-col lg="4" sm="6">
        <statistic-card-horizontal
          icon="CodeIcon"
          :statistic="assignmentsCount"
          statistic-title="Assignments"
          infoColor="EditIcon"
          :infoTitle="remainingAssignmentsCount + ' Submission ' + remainingAssignmentsLastDate"
        />
      </b-col>
      <b-col lg="4" sm="6">
        <statistic-card-horizontal
          icon="ActivityIcon"
          :color="(attendanceVal < 70) ? 'danger' : 'success'"
          :statistic="attendanceVal + '%'"
          statistic-title="Attendance"
          infoColor="BarChart2Icon"
          :infoTitle="(attendanceVal < 70) ? 'Below 70%' : 'Average Percentage'"
        />
      </b-col>
      <b-col lg="4" sm="6">
        <statistic-card-horizontal
          icon="FileTextIcon"
          color="info"
          :statistic="quizzesCount"
          statistic-title="Quizzes"
          infoColor="EditIcon"
          :infoTitle="quizzesCount + (quizzesCount > 1 ? ' Quizzes ' : ' Quiz ') + remainingQuizLastDate"
        />
      </b-col>
    </b-row>
    <b-row>
      <b-col lg="8">
        <courses-component />
        <announcements-component />
      </b-col>
      <b-col lg="4">
        <profile-component />
        <attendance-component />
        <timetable-component />
      </b-col>
    </b-row>
  </section>
</template>

<script>
import TimetableComponent from './components/TimetableComponent.vue';
import AnnouncementsComponent from '../components/AnnouncementsComponent.vue';
import AttendanceComponent from './components/AttendanceComponent.vue';
import CoursesComponent from './components/CoursesComponent.vue';
import ProfileComponent from './components/ProfileComponent.vue';
import StatisticCardHorizontal from '@core/components/statistics-cards/StatisticCardHorizontal.vue'
import { BRow, BCol } from 'bootstrap-vue'
import store from '@/store'
import { ref, reactive, onUnmounted } from '@vue/composition-api'
import useAttendance from './components/useAttendance';
import useAttendanceModule from './components/useAttendanceModule';
import axios from 'axios';
import moment from 'moment'
export default {
  components: {
    ProfileComponent,
    TimetableComponent,
    AnnouncementsComponent,
    AttendanceComponent,
    CoursesComponent,
    StatisticCardHorizontal,
    BRow,
    BCol,
  },
  setup(props, { emit }) {
    const USER_APP_STORE_MODULE_NAME = 'attendance'
    // Register module
    if (!store.hasModule(USER_APP_STORE_MODULE_NAME)) store.registerModule(USER_APP_STORE_MODULE_NAME, useAttendanceModule)
    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(USER_APP_STORE_MODULE_NAME)) store.unregisterModule(USER_APP_STORE_MODULE_NAME)
    })
    const {
			state,
      fetchMyAttendances,
      fetchStudentCourses,
    } = useAttendance(props, { emit })
    
		fetchMyAttendances();

    fetchStudentCourses();
    let presents = 0,
    total = 0;
    const attendanceVal = ref(0);
    setTimeout(() => {
      if(state.courses != undefined) {
        for (const [key, value] of Object.entries(state.courses)) {
          presents = 0;
          total = 0;
          for (const [k, val] of Object.entries(state.attendances)) {   
            if(value['course_id'] == val['course_id']) {
              total++;
              if(val['type'] == 1) {
                presents++;
              }
            }
          }
          attendanceVal.value += Math.round(( (presents / total) * 100 ) / state.courses.length);
        }
      }
    }, 1000);
    return {
			state,
      attendanceVal,
      fetchMyAttendances,
      fetchStudentCourses,
    }
  },
  data() {
    return {
      obj: {},
      items: [],
      assignments: {},
      myAssignments: {},
      assignmentsCount: 0,
      remainingAssignmentsCount: 0,
      remainingAssignmentsLastDate: '',
      remainingQuizLastDate: '',
      quizzesCount: 0,
    }
  },
  created() {
    try {
      axios.get('/api/myassignments').then(res => {
        if(res.data[0].length > 0) {
          this.assignmentsCount = res.data[0].length;
          this.remainingAssignmentsCount = this.assignmentsCount - res.data[1].length;
          for (const [key, value] of Object.entries(res.data[0])) {
            if(moment(value['deadline']).isAfter(moment().format())) {
              this.remainingAssignmentsLastDate = moment(value['deadline']).fromNow();
              break;
            } else {
              continue;
            }
          }
          if(this.remainingAssignmentsLastDate == '') {
            this.remainingAssignmentsLastDate = 'was closed from ' + moment(res.data[0][0].deadline).fromNow(); 
          }
        }
      }).catch(err => {
        console.log(err)
      });
    } catch (error) {
      console.log(error);
    }
    
    try {
      axios.get('/api/myquizzes').then(res => {
        this.quizzesCount = res.data[0].length;
        for (const [key, value] of Object.entries(res.data[0])) {
          var returned_endate = moment(moment(value['opens_at'])).add(value['duration']);
          if(moment(value['opens_at']).isAfter(moment().format())) {
            this.remainingQuizLastDate = 'will open ' + moment(returned_endate).fromNow();
            break;
          } else if(moment(returned_endate).isAfter(moment().format())) {
            this.remainingQuizLastDate = 'will close ' + moment(returned_endate).fromNow();
            break;
          } else {
            continue;
          }
        }
        if(this.remainingQuizLastDate == '') {
          var returned_endate = moment(moment(res.data[0][0].opens_at)).add(res.data[0][0].duration);
          this.remainingQuizLastDate = 'was closed from ' + moment(returned_endate).fromNow(); 
        }
      }).catch(err => {
        console.log(err)
      });
    } catch (error) {
      console.log(error);
    }
  }
}
</script>
