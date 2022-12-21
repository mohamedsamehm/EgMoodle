import Vue from 'vue'
import { ToastPlugin, ModalPlugin } from 'bootstrap-vue'
import VueCompositionAPI from '@vue/composition-api'

import i18n from '@/libs/i18n'
import router from './router'
import moment from 'moment'
import store from './store'
import App from './App.vue'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faCloudArrowUp, faMagnifyingGlass, faAddressCard, faBars, faHouse, faCaretRight, faBookOpen, faAward, faGauge, faUser, faArrowRightFromBracket, faCircleUser, faSpinner, faCode, faChartLine, faTriangleExclamation, faFlag, faCalendarAlt, faMapMarker, faBullhorn, faChartColumn, faGraduationCap, faBorderAll, faFileWord, faFilePdf, faFileLines } from '@fortawesome/free-solid-svg-icons'
import { faBell, faEnvelope, faClock, faPenToSquare, faFolderOpen, faComment, faIdBadge, faCopy } from '@fortawesome/free-regular-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
// Global Components
import './global-components'

// 3rd party plugins
import '@axios'
import '@/libs/acl'
import '@/libs/portal-vue'
import '@/libs/clipboard'
import '@/libs/toastification'
import '@/libs/sweet-alerts'
import '@/libs/vue-select'
import '@/libs/tour'

// BSV Plugin Registration
Vue.use(ToastPlugin)
Vue.use(ModalPlugin)

// Font Awesome
library.add(faCloudArrowUp, faMagnifyingGlass, faAddressCard, faBars, faHouse, faFilePdf, faCaretRight, faBookOpen, faAward, faGauge, faUser, faArrowRightFromBracket, faCircleUser, faSpinner, faCode, faChartLine, faTriangleExclamation, faFlag, faCalendarAlt, faMapMarker, faBullhorn, faBell, faEnvelope, faClock, faPenToSquare, faFolderOpen, faComment, faIdBadge, faCopy, faChartColumn, faGraduationCap, faBorderAll, faFileWord, faFileLines)
Vue.component('font-awesome-icon', FontAwesomeIcon)

// Composition API
Vue.use(VueCompositionAPI)

// Feather font icon - For form-wizard
// * Shall remove it if not using font-icons of feather-icons - For form-wizard
require('@core/assets/fonts/feather/iconfont.css') // For form-wizard

// import core styles
require('@core/scss/core.scss')

// import assets styles
require('@/assets/scss/style.scss')

Vue.config.productionTip = false

Vue.filter('formatDate', function(value) {
  if (value) {
    return moment(String(value)).format("LLLL");
  }
})
Vue.filter('formatNow', function(value) {
  if (value) {
    return moment(String(value)).calendar();
  }
})
Vue.filter('formatNowUTC', function(value) {
  if (value) {
    return moment(String(value)).calendar();
  }
})
Vue.filter('formatDay', function(value) {
  if (value) {
    return moment(String(value)).fromNow(); 
  }
})
Vue.filter('formatTime', function(value) {
  if (value) {
    return moment(String(value), "HH:mm").format("hh:mm A");
  }
})

new Vue({
  router,
  store,
  i18n,
  render: h => h(App),
}).$mount('#app')
