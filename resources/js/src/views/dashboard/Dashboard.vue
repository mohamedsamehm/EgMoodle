<template>
  <section id="dashboard">
    <dashboard-prof v-if="userRole == 'professor'"/>
    <dashboard-student v-else-if="userRole == 'student'"/>
  </section>
</template>

<script>
import DashboardProf from './dashboard-prof/DashboardProf.vue';
import DashboardStudent from './dashboard-student/DashboardStudent.vue';
export default {
  components: {
    DashboardProf,
    DashboardStudent,
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
