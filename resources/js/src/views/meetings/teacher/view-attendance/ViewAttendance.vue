<template>
  <b-card no-body>
    <div class="flex d-flex align-items-center justify-content-center loader-area" v-if="state.loading" style="min-height: 450px;" >
      <b-spinner label="Loading..."/>
    </div>
    <b-table
      v-else
      class="position-relative"
      :items="state.studentsforMeeting"
      responsive
      :fields="tableColumns"
      primary-key="id"
      show-empty
      striped
      empty-text="No matching records found"
    >
      <template #cell(type)="data">
        <span v-if="data.value == 1">
          <b-badge variant="success">
            Present
          </b-badge>
        </span>
        <span v-else>
          <b-badge variant="danger">
            Absent
          </b-badge>
        </span>
      </template>
    </b-table>
  </b-card>
</template>
<script>
import {
  BSpinner,
  BRow,
  BCol,		
  BCard,
  BCardFooter,
  BCardHeader,
  BCardBody,
  BTable,
  BTh,
  BTd,
  BTr,
  BThead,
  BTbody,
  BBadge
} from 'bootstrap-vue'
import { ref, onUnmounted } from '@vue/composition-api';
import store from '@/store'
import router from '@/router'
import useMeetings from '../../useMeetings';
import useMeetingsModule from '../../useMeetingsModule';

export default {
  components: {
    BSpinner,
		BRow,
		BCol,		
		BCard,
    BCardFooter,
    BCardHeader,
    BCardBody,
    BTable,
    BTh,
    BTd,
    BTr,
    BThead,
    BTbody,
    BBadge
  },
  setup(props, { emit }) {
    const USER_APP_STORE_MODULE_NAME = 'meetings'
    // Register module
    if (!store.hasModule(USER_APP_STORE_MODULE_NAME)) store.registerModule(USER_APP_STORE_MODULE_NAME, useMeetingsModule)
    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(USER_APP_STORE_MODULE_NAME)) store.unregisterModule(USER_APP_STORE_MODULE_NAME)
    })
    const tableColumns = [
      { label: '#', align: 'start', sortable: false, key: 'number',},
      { label: 'Name', key: 'fullName'},
      { label: 'Email', key: 'email'},
      { label: 'Status', key: 'type' },
    ];
    const loading = ref(false)
    const {
			state,
      fetchStudentsForMeetings,
    } = useMeetings(props, { emit })
    
		fetchStudentsForMeetings({ id: router.currentRoute.params.id });

    return {
			state,
      tableColumns,
      fetchStudentsForMeetings,
			loading,
    }
  },
}
</script>