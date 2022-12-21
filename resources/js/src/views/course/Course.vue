<template>
  <section id="dashboard">
    <course-professor v-if="userRole == 'professor'"/>
    <course-student v-else-if="userRole == 'student'"/>
  </section>
</template>

<script>
import CourseStudent from './student/CourseStudent.vue'
import CourseProfessor from './professor/CourseProfessor.vue'
export default {
  components: {
    CourseStudent,
    CourseProfessor,
  },
  data() {
    return {
      user: JSON.parse(localStorage.getItem('userData')),
    }
  },
  computed: {
    userRole: function(){ return this.user.role.slug },
  }
}
</script>
