<template>
  <b-card no-body>
    <b-card-header class="border-bottom py-1">
      <h6 class="font-weight-bolder mb-0">
        <feather-icon
          icon="CalendarIcon"
          size="18"
          class="mr-50"
        /> Today's Timetable
      </h6>
    </b-card-header>
    <b-card-body class="px-2 pb-2 pt-1">
      <div v-if="schedulesToday.length">
        <div class="timetable-item border py-1" v-for="(schedule, index) in schedulesToday" :key="index">
          <b-row no-gutters>
            <b-col cols="4" style="max-width: 55px;">
              <span class="time bg-danger bg-lighten-1">{{schedule.time}}</span>
            </b-col>
            <b-col cols="8" class="px-1 d-flex align-items-start justify-content-center flex-column">
              <h6 class="font-weight-bolder mb-1">{{schedule.course_name}}</h6>
              <small class="font-weight-bolder text-grey d-block"><font-awesome-icon icon="fa-solid fa-map-marker" /> {{schedule.place}}</small>
            </b-col>
          </b-row>
        </div>
      </div>
      <div class="timetable-item" v-else>
        <p class="m-0">No Lectures Today</p>
      </div>
    </b-card-body>
  </b-card>
</template>
<script>
import axios from 'axios';
import { BRow, BCol, BProgress, BCardText, BCardHeader, BCard, BCardBody, BCardSubTitle, BCardTitle, } from 'bootstrap-vue'
export default {
  components: {
    BRow,
    BCol,
    BProgress,
    BCardText,
    BCardHeader,
    BCard,
    BCardBody,
    BCardSubTitle,
    BCardTitle,
  },
  data() {
    return {
      user: JSON.parse(localStorage.getItem('userData')),
      obj: {},
      schedules: [],
      schedulesToday: [],
      schedulesObj: {},
    }
  },
  created() {
    this.obj['id'] = this.user.id;
    try {
      axios.post('api/schedule/professor', this.obj).then(res => {
        for (const [key, value] of Object.entries(res.data[0])) {
          value['sections'] = [];
          this.schedulesObj[value.course_id] = [];
        }
        for (const [key, value] of Object.entries(res.data[0])) {
          this.schedulesObj[value.course_id].push(value);
        }
        for (const [key, value] of Object.entries(this.schedulesObj)) {
          value.forEach(element => {
            value[0]['sections'].push(element.name);
          });
          this.schedules.push(value[0]);
        }
        let days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        var today = new Date();
        this.schedules.forEach(element => {
          if(element.day == days[today.getDay()]) {
            this.schedulesToday.push(element);
          }
        });
        if(this.schedulesToday.length > 0) {
          this.schedulesToday.sort((a, b) => (a.time < b.time) ? -1 : ((a.time > b.time) ? 1 : 0))
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
<style scoped lang="scss">
.timetable-item {
  border-width: 0px !important;
  &:not(:last-child) {
    border-width: 0px 0px 1px 0px !important;
  }
}
.time {
  display: inline-block;
  color: #fff;
  height: 55px;
  width: 55px;
  border-radius: 50%;
  font-weight: 700;
  text-align: center;
  padding: 14px 0;
  font-size: 9px;
  display: flex;
  justify-content: center;
  align-content: center;
  align-items: center;
}
</style>