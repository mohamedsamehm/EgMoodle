<template>
  <section id="dashboard">
    <b-row>
      <b-col lg="4" sm="6">
        <statistic-card-horizontal
          icon="CodeIcon"
          :statistic="assignmentsCount"
          statistic-title="Assignments"
        />
      </b-col>
      <b-col lg="4" sm="6">
        <statistic-card-horizontal
          icon="ActivityIcon"
          :color="(attendanceVal < 70) ? 'danger' : 'success'"
          :statistic="attendanceVal + '%'"
          statistic-title="Attendance"
        />
      </b-col>
      <b-col lg="4" sm="6">
        <statistic-card-horizontal
          icon="FileTextIcon"
          :statistic="quizzesCount"
          statistic-title="Quizzes"
          color="info"
        />
      </b-col>
    </b-row>
    <b-row>
      <b-col lg="8">
        <courses-component />
        <announcements-component />
      </b-col>
      <b-col lg="4">
        <attendance-component />
        <timetable-component />
      </b-col>
    </b-row>
  </section>
</template>

<script>
import axios from 'axios';
import TimetableComponent from './components/TimetableComponent.vue';
import AnnouncementsComponent from '../components/AnnouncementsComponent.vue';
import CoursesComponent from './components/CoursesComponent.vue';
import AttendanceComponent from './components/AttendanceComponent.vue';
import StatisticCardHorizontal from '@core/components/statistics-cards/StatisticCardHorizontal.vue'
import { BRow, BCol } from 'bootstrap-vue'
export default {
  components: {
    AttendanceComponent,
    TimetableComponent,
    AnnouncementsComponent,
    CoursesComponent,
    StatisticCardHorizontal,
    BRow,
    BCol,
  },
  data() {
    return {
      attendanceVal: 0,
      assignmentsCount: 0,
      quizzesCount: 0,
    }
  },
  created() {
    try {
      axios.get('api/meetings/viewAllAttendance').then(res => {
        if(res.data.status == 200) {
          let total = Object.values(res.data.meetings)[0];
          let totalCourses = Object.values(res.data.meetings).length;
          for (const [key, value] of Object.entries(res.data.meetings)) {
            let presents = 0;
            value.forEach(element => {
              if(element['type'] == 1) {
                presents++;
              }
            });
            this.attendanceVal += Math.round(( (presents / total.length) * 100 ) / totalCourses);
          }
        } else if(res.data.status == 401) {
          console.log(res.data.status);
        }
      }).catch(err => {
        console.log(err)
      });
    } catch (error) {
      console.log(error);
    }
    try {
      axios.get('/api/assignments/professor/all').then(res => {
        this.assignmentsCount = res.data[0].length;
      }).catch(err => {
        console.log(err)
      });
    } catch (error) {
      console.log(error);
    }
    try {
      axios.get('/api/quizzes/professor/').then(res => {
        let quizzes = [];
        let quizzesObj = {};
        res.data[0].forEach(element => {
          quizzesObj[element.material_id] = [];
        });
        res.data[0].forEach(element => {
          quizzesObj[element.material_id].push(element);
        });
        for (const [key, value] of Object.entries(quizzesObj)) {
          value.forEach(element => {
            element['id'] = element.material_id;
          });
          quizzes.push(value[0]);
        }
        this.quizzesCount = quizzes.length;
      }).catch(err => {
        console.log(err)
      });
    } catch (error) {
      console.log(error);
    }
  }
}
</script>
