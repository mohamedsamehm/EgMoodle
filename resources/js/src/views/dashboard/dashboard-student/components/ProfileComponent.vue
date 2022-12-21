<template>
  <b-card no-body>
    <b-card-header class="border-bottom py-1">
      <h6 class="font-weight-bolder mb-0">
        <feather-icon
          icon="UserIcon"
          size="18"
          class="mr-50"
        /> My Profile
      </h6>
    </b-card-header>
    <b-card-body class="p-2">
      <b-list-group flush>
        <b-list-group-item>
          <div class="profile-intro pb-1">
            <b-row>
              <b-col cols="4" class="rounded p-0" style="max-width: 100px">
                <b-avatar
                  size="70px"
                  variant="light-primary"
                  :text="userChars"
                />
              </b-col>
              <b-col cols="8" class="py-1">
                <h6 class="font-weight-bolder text-capitalize">{{ username }}</h6>
                <small class="text-grey">Reg#: {{ user.id }}</small>
              </b-col>
            </b-row>
          </div>
        </b-list-group-item>
        <b-list-group-item class="px-0" v-for="course in courses" :key="course.course_id">
          <b-row class="pb-1 px-0 pt-0">
            <b-col cols="6">
              <small class="mt-1 text-grey font-weight-bolder">ROLL NO</small>
              <p class="small-strong">{{course.course_id}}</p>
            </b-col>
            <b-col cols="6">
              <small class="mt-1 text-grey font-weight-bolder">SECTION</small>
              <p class="small-strong">{{course.name}}</p>
            </b-col>
          </b-row>
        </b-list-group-item>
      </b-list-group>
    </b-card-body>
  </b-card>
</template>
<script>
import axios from 'axios';
import { BRow, BAvatar, BCol, BProgress, BCardText, BCardHeader, BCard, BCardBody, BCardSubTitle, BCardTitle, BListGroup, BListGroupItem } from 'bootstrap-vue'
export default {
  components: {
    BListGroup,
    BListGroupItem,
    BRow,
    BAvatar,
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
      courses: [],
      materials: {},
      materialsObj: {},
      materials_readed: {},
      materials_readedObj: {},
      obj: {},
      coursesObj: {},
    }
  },
  created() {
    try {
      axios.get('api/enroll/')
      .then(res => {
        this.courses = res.data[0];
        this.materials = res.data[1];
        this.materials_readed = res.data[2];
        for (const [key, value] of Object.entries(this.materials)) {
          this.materialsObj[value.course_id] = [];
        }
        for (const [key, value] of Object.entries(this.materials)) {
          this.materialsObj[value.course_id].push(value);
        }
        for (const [key, value] of Object.entries(this.materials_readed)) {
          this.materials_readedObj[value.course_id] = [];
        }
        for (const [key, value] of Object.entries(this.materials_readed)) {
          this.materials_readedObj[value.course_id].push(value);
        }
        this.courses.forEach(element => {
          element['percentage'] = 0;
          for (const [key, value] of Object.entries(this.materialsObj)) {
            if(this.materials_readedObj[key] && element.course_id == key) {
              element['percentage'] = (this.materials_readedObj[key].length / value.length) * 100;
              break;
            }
          }
        });
      }).catch(err => {
        console.log(err)
      });
    } catch (error) {
      console.log(error);
    }
  },
  computed: {
    username: function(){ return this.user.fullName;  },
    userChars: function() {return this.username.charAt(0) + '' + this.username.charAt(this.username.indexOf(' ')  + 1)}
  }
}
</script>