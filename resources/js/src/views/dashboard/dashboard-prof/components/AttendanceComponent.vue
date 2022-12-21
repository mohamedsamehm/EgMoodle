<template>
  <b-card no-body>
    <b-card-header class="border-bottom py-1">
      <h6 class="font-weight-bolder mb-0">
        <font-awesome-icon icon="fa-solid fa-chart-column" /> Attendance
      </h6>
    </b-card-header>
    <b-card-body class="p-2" v-if="viewChart">
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
import { BSpinner, BCardHeader, BCard, BCardBody, BCardSubTitle, BCardTitle, } from 'bootstrap-vue';
import ChartjsComponentBarChart from './ChartjsComponentBarChart.vue'
import { $themeColors } from '@themeConfig';
import axios from 'axios';
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
  data() {
    return {
      viewChart: false,
      chartOptions: {
        data: {
          labels: [],
          datasets: [{
            label: 'Present',
            data: [],
            backgroundColor: $themeColors.success,
            borderColor: 'transparent',
          }, {
            label: 'Absent',
            data: [],
            backgroundColor: $themeColors.danger,
            borderColor: 'transparent',
          }],
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
            display: true,
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
                  stepSize: 20,
                  min: 0,
                  max: 120,
                  fontColor: '#6e6b7b',
                },
              },
            ],
          },
        },
      },
    }
  },
  created() {
    try {
      axios.get('api/meetings/viewAllAttendance').then(res => {
				if(res.data.status == 200) {
          let total = Object.values(res.data.meetings)[0];
          for (const [key, value] of Object.entries(res.data.meetings)) {
            let presents = 0,
                absents = 0;
            this.chartOptions.data.labels.push(value[0].name);
            value.forEach(element => {
              if(element['type'] == 1) {
                presents++;
              } else {
                absents++;
              }
            });
            this.chartOptions.data.datasets[0].data.push(Math.round((presents / total.length) * 100));
            this.chartOptions.data.datasets[1].data.push(Math.round((absents / total.length) * 100));
          }
          this.viewChart = true;
				} else if(res.data.status == 401) {
          console.log(res.data.status);
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