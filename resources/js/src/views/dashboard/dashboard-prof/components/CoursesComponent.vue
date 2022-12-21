<template>
  <b-card no-body>
    <b-card-header class="border-bottom py-1">
      <h6 class="font-weight-bolder mb-0">
        <feather-icon
          icon="BookOpenIcon"
          size="18"
          class="mr-50"
        /> Courses
      </h6>
      <b-button-group>
        <b-button v-ripple.400="'rgba(40, 199, 111, 0.15)'" class="mr-1" @click="changeGrid" :variant="isGrid ? 'flat-success p-0' : 'flat-dark p-0'">
          <font-awesome-icon icon="fa-solid fa-border-all" size="lg" />
        </b-button>
        <b-button v-ripple.400="'rgba(40, 199, 111, 0.15)'" @click="changeList" :variant="isList ? 'flat-success p-0' : 'flat-dark p-0'">
          <font-awesome-icon icon="fa-solid fa-bars" size="lg" />
        </b-button>
      </b-button-group>
    </b-card-header>
    <b-card-body class="p-2" :class="(isList ? 'p-0' : '')">
      <b-row :class="(isList ? 'flex-column' : '')">
        <b-col :class="(isList ? 'col-12' : 'col-12 col-lg-4 mb-2')" v-for="course in courses" :key="course.course_id">
          <b-card class="mb-0" no-body :class="(isList ? 'border-bottom rounded-0' : 'border')">
            <b-button :to="{ path: '/course/' + course.course_id}"  style="all: unset !important;cursor: pointer !important;">
              <b-img
                fluid
                v-if="isGrid"
                :src="require('@/assets/images/courses/' + course.image)"
                alt="Course image"
                class="card-img-top"
              />
              <b-card-body :class="(isList ? 'px-0 py-1' : '')" >
                <b-card-sub-title class="mb-1 mt-0"><span class="text-grey">{{course.course_id}}</span><span v-for="section in course.sections" :key="section" class="text-grey"> | {{section}}</span></b-card-sub-title>
                <b-card-title class="mb-1"><h5 class="m-0">{{course.course_name}}</h5></b-card-title>
              </b-card-body>
            </b-button>
          </b-card>
        </b-col>
      </b-row>
    </b-card-body>
  </b-card>
</template>
<script>
import axios from 'axios';
import Ripple from 'vue-ripple-directive'
import { BImg, BRow, BButtonGroup, BButton, BCol, BProgress, BCardText, BCardHeader, BCard, BCardBody, BCardSubTitle, BCardTitle, } from 'bootstrap-vue'
export default {
  components: {
    BImg,
    BRow,
    BButtonGroup,
    BButton,
    BCol,
    BProgress,
    BCardText,
    BCardHeader,
    BCard,
    BCardBody,
    BCardSubTitle,
    BCardTitle,
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      user: JSON.parse(localStorage.getItem('userData')),
      courses: [],
      materials: {},
      materialsObj: {},
      obj: {},
      coursesObj: {},
      isGrid: true,
      isList: false,
    }
  },
  methods: {
    changeGrid() {
      this.isGrid = true;
      this.isList = false;
    },
    changeList() {
      this.isList = true;
      this.isGrid = false;
    },
  },
  created() {
    try {
        axios.get('/api/lecture/')
        .then(res => {
          this.materials = res.data[1];
          for (const [key, value] of Object.entries(res.data[0])) {
            value['sections'] = [];
            this.coursesObj[value.course_id] = [];
          }
          for (const [key, value] of Object.entries(res.data[0])) {
            this.coursesObj[value.course_id].push(value);
          }
          for (const [key, value] of Object.entries(this.coursesObj)) {
            value.forEach(element => {
              value[0]['sections'].push(element.section_name);
            });
            this.courses.push(value[0]);
          }
          for (const [key, value] of Object.entries(this.materials)) {
            this.materialsObj[value.course_id] = [];
          }
          for (const [key, value] of Object.entries(this.materials)) {
            this.materialsObj[value.course_id].push(value);
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
.card {
  img {
    max-height: 150px;
    width: 100%;
    object-fit: cover;
  }
}
.small-strong {
  font-size: 13px;
  font-weight: 600;
}
</style>