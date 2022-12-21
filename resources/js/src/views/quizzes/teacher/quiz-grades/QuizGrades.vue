<template>
  <b-card no-body>
    <div class="flex d-flex align-items-center justify-content-center loader-area" v-if="state.loading" style="min-height: 450px;" >
      <b-spinner label="Loading..."/>
    </div>
    <b-table
      v-else
      class="position-relative"
      :items="state.quizzes"
      responsive
      :fields="tableColumnsMarks"
      primary-key="id"
      show-empty
      striped
      empty-text="No matching records found"
    >
      <template #cell(createdDate)="data">
        {{ data.value | formatDay }}
      </template>
    </b-table>
  </b-card>
</template>

<script>
import Ripple from 'vue-ripple-directive';
import store from '@/store';
import { ref, onUnmounted } from '@vue/composition-api';
import useQuizzes from '../../useQuizzes';
import useQuizzesModule from '../../useQuizzesModule';
import router from '@/router'
import {
  BCard,
  BRow,
  BCol,
	BSpinner,
  BFormInput,
  BButton,
  BTable,
  BDropdown,
  BDropdownItem,
  BPagination,
} from 'bootstrap-vue';
export default {
  components: {
    BCard,
    BRow,
    BCol,
    BSpinner,
    BFormInput,
    BButton,
    BTable,
    BDropdown,
    BDropdownItem,
    BPagination,
  },
  directives: {
    Ripple,
  },
  setup(props, { emit }) {
    console.log(router.currentRoute.params.id);
    const USER_APP_STORE_MODULE_NAME = 'quizzes'
    // Register module
    if (!store.hasModule(USER_APP_STORE_MODULE_NAME)) store.registerModule(USER_APP_STORE_MODULE_NAME, useQuizzesModule)
    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(USER_APP_STORE_MODULE_NAME)) store.unregisterModule(USER_APP_STORE_MODULE_NAME)
    })

    const loading = ref(false)

    const {
      fetchQuizzesMarks,
      tableColumnsMarks,
      state,
    } = useQuizzes(props, { emit })

		fetchQuizzesMarks({ id: router.currentRoute.params.id });

    return {
      fetchQuizzesMarks,
      tableColumnsMarks,
			state,
			loading,
    }
  },
}
</script>
