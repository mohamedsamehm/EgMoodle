<template>
  <b-card no-body>
    <b-card-header class="border-bottom py-1">
      <h6 class="font-weight-bolder mb-0">
        <font-awesome-icon icon="fa-solid fa-chart-column" /> My Attendance
      </h6>
    </b-card-header>
    <b-card-body class="p-2" v-if="!loading">
      <chartjs-component-bar-chart
        :height="400"
        :data="chartOptions.data"
        :options="chartOptions.options"
      />
    </b-card-body>
    <b-card-body v-else>
      <div class="flex d-flex align-items-center justify-content-center loader-area" style="min-height: 450px;">
        <b-spinner label="Loading..."/>
      </div>
    </b-card-body>
  </b-card>
</template>
<script>
import ChartjsComponentBarChart from './ChartjsComponentBarChart.vue'
import { $themeColors } from '@themeConfig';
import store from '@/store'
import { ref, reactive, onUnmounted } from '@vue/composition-api'
import useAttendance from './useAttendance';
import useAttendanceModule from './useAttendanceModule';
import {
  BSpinner,
  BCardHeader,
  BCard,
  BCardBody,
  BCardSubTitle,
  BCardTitle,
} from 'bootstrap-vue';

export default {
  components: {
		BSpinner,
    ChartjsComponentBarChart,
    BCardHeader,
    BCard,
    BCardBody,
    BCardSubTitle,
    BCardTitle,
  },
  setup(props, { emit }) {
    const USER_APP_STORE_MODULE_NAME = 'attendance'
    // Register module
    if (!store.hasModule(USER_APP_STORE_MODULE_NAME)) store.registerModule(USER_APP_STORE_MODULE_NAME, useAttendanceModule)
    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(USER_APP_STORE_MODULE_NAME)) store.unregisterModule(USER_APP_STORE_MODULE_NAME)
    })
    const chartOptions = ref({});
    chartOptions.value = {
      data: {
        labels: [],
        datasets: [
          {
            data: [],
            backgroundColor: [],
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
                display: true,
              },
              ticks: {
                fontColor: '#6e6b7b',
                fontStyle: 'normal',
                fontSize: '9'
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
                stepSize: 20,
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
        for (const [k, val] of Object.entries(state.attendances)) {   
          if(value['course_id'] == val['course_id']) {
            total++;
            if(val['type'] == 1) {
              presents++;
            }
          }
        }
        if(total !== 0) {          
          chartOptions.value.data.datasets[0].data.push((presents / total) * 100);
          if(((presents / total) * 100) < 50) {
            chartOptions.value.data.datasets[0].backgroundColor.push($themeColors.danger);
          } else {
            chartOptions.value.data.datasets[0].backgroundColor.push($themeColors.primary); 
          }
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
    }
  },
}
</script>