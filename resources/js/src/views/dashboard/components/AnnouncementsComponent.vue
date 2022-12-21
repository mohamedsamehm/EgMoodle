<template>
  <b-card no-body>
    <b-card-header class="border-bottom py-1">
      <h6 class="font-weight-bolder mb-0">
        <font-awesome-icon icon="fa-solid fa-bullhorn" size="sm" class="mr-50"/> Announcements
      </h6>
    </b-card-header>
    <b-card-body class="p-2">
      <div class="announcement-item pr-1 pb-1 border-bottom">
        <h5 class="mb-1 text-right" dir="rtl">التربية العسكرية.<span class="new bg-danger text-white">جديد</span></h5>
        <small class="text-grey mb-50 d-block font-weight-bolder text-right" dir="rtl"><font-awesome-icon icon="fa-regular fa-clock" /> {{today}}</small>
        <p class="mb-1 text-secondary text-right">على جميع طلاب المستوي الثالث سرعة التسجيل في دورة التربية العسكرية حرصا من الاكاديمية على عدم حدوث معوقات عند التخرج</p>
      </div>
    </b-card-body>
  </b-card>
</template>
<script>
import { BCardHeader, BCard, BCardBody, BCardSubTitle, BCardTitle, } from 'bootstrap-vue'
export default {
  components: {
    BCardHeader,
    BCard,
    BCardBody,
    BCardSubTitle,
    BCardTitle,
  },
  data() {
    return {
      today: new Intl.DateTimeFormat('ar-EG-u-nu-latn', {day: 'numeric', month: 'long',weekday: 'long',year : 'numeric'}).format(Date.now())
    }
  }
}
</script>
<style scoped lang="scss">
.announcement-item {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  .new {
    display: inline-block;
    margin-right: 10px;
    font-size: 11px;
    padding: 5px 10px;
    border-radius: 5px;
  }
  h5 {
    font-weight: 600;
    font-size: 16px;
    line-height: 2;
    margin-bottom: 16px;
  }
  p {
    margin-top: 16px;
    font-size: 16px;
    line-height: 2 !important;
  }
}
</style>