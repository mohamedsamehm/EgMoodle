<template>
  <div v-if="!loading">
    <b-card no-body>
      <b-card-header>
        <b-card-title></b-card-title>
      </b-card-header>
      <!-- chart -->
      <b-card-body>
        <chartjs-component-bar-chart
          :height="400"
          :data="chartOptions.data"
          :options="chartOptions.options"
        />
      </b-card-body>
    </b-card>
    <b-card no-body>
      <b-card-body>
        <b-table class="position-relative" :items="studentsData" responsive primary-key="id" show-empty striped empty-text="No matching records found" />
      </b-card-body>
    </b-card>
  </div>
  <div class="flex d-flex align-items-center justify-content-center loader-area" style="min-height: 450px;" v-else>
    <b-spinner label="Loading..."/>
  </div>
</template>

<script>
import { ref, reactive, onUnmounted } from '@vue/composition-api'
import store from '@/store'
import ChartjsComponentBarChart from '../componenets/ChartjsComponentBarChart.vue'
import useAttendance from '../useAttendance';
import useAttendanceModule from '../useAttendanceModule';
import { $themeColors } from '@themeConfig'

import {
  BRow,
  BCol,		
  BCard,
  BCardBody,
  BCardFooter,
  BCardHeader,
  BCardTitle,
  BSpinner,
  BTable,
} from 'bootstrap-vue';

export default {
  components: {
		BRow,
		BCol,		
		BCard,
    BCardBody,
    BCardFooter,
    BCardHeader,
    BCardTitle,
		BSpinner,
    ChartjsComponentBarChart,
    BTable,
  },
  setup(props, { emit }) {
    const USER_APP_STORE_MODULE_NAME = 'attendance'
    // Register module
    if (!store.hasModule(USER_APP_STORE_MODULE_NAME)) store.registerModule(USER_APP_STORE_MODULE_NAME, useAttendanceModule)
    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(USER_APP_STORE_MODULE_NAME)) store.unregisterModule(USER_APP_STORE_MODULE_NAME)
    })
    const studentsData = ref([])
    const chartOptions = ref({});
    chartOptions.value = {
      data: {
        labels: [],
        datasets: [
          {
            data: [],
            backgroundColor: '#836AF9',
            borderColor: 'transparent',
          },
        ],
      },
      options: {
        elements: {
          rectangle: {
            borderWidth: 2,
            borderSkipped: 'bottom',
          },
        },
        responsive: true,
        maintainAspectRatio: false,
        responsiveAnimationDuration: 500,
        legend: {
          display: false,
        },
        tooltips: {
          // Updated default tooltip UI
          shadowOffsetX: 1,
          shadowOffsetY: 1,
          shadowBlur: 8,
          shadowColor: 'rgba(0, 0, 0, 0.25)',
          backgroundColor: $themeColors.light,
          titleFontColor: $themeColors.dark,
          bodyFontColor: $themeColors.dark,
        },
        scales: {
          xAxes: [
            {
              display: true,
              gridLines: {
                display: true,
                color: 'rgba(200, 200, 200, 0.2)',
                zeroLineColor: 'rgba(200, 200, 200, 0.2)',
              },
              scaleLabel: {
                display: false,
              },
              ticks: {
                fontColor: '#6e6b7b',
              },
            },
          ],
          yAxes: [
            {
              display: true,
              gridLines: {
                color: 'rgba(200, 200, 200, 0.2)',
                zeroLineColor: 'rgba(200, 200, 200, 0.2)',
              },
              ticks: {
                stepSize: 10,
                min: 0,
                max: 100,
                fontColor: '#6e6b7b',
              },
            },
          ],
        },
      },
    };
    const loading = ref(true)
    const {
			state,
      fetchMyAttendances,
      fetchStudentCourses,
    } = useAttendance(props, { emit })
    
		fetchMyAttendances();

    fetchStudentCourses();

    setTimeout(() => {
      for (const [key, value] of Object.entries(state.courses)) {
        let presents = 0,
        total = 0;
        chartOptions.value.data.labels.push(value['course_name']);
        studentsData.value.push({
          course_name: value['course_name'],
          attended_percentage: '',
        });
        for (const [k, val] of Object.entries(state.attendances)) {   
          if(value['course_id'] == val['course_id']) {
            total++;
            if(val['type'] == 1) {
              presents++;
            }
          }
        }
        if(total !== 0) {          
          studentsData.value[key].attended_percentage = ((presents / total) * 100);
          studentsData.value[key].attended_percentage += '%';
          chartOptions.value.data.datasets[0].data.push((presents / total) * 100);
        } else {
          chartOptions.value.data.datasets[0].data.push(presents);
        }
      }
      loading.value = false;
    }, 1000);
    return {
			state,
      loading,
      fetchMyAttendances,
      fetchStudentCourses,
      chartOptions,
      studentsData,
    }
  },
}
</script>
<style lang="scss">
@import '~@core/scss/vue/libs/vue-select.scss';
@import '~@core/scss/vue/libs/vue-sweetalert.scss';
</style>
