<template>
  <b-card no-body>
    <div class="m-2">
      <b-row>
        <!-- Search -->
        <b-col cols="12" md="6" class="ml-auto">
          <div class="d-flex align-items-center justify-content-end">
            <!-- <b-form-input class="d-inline-block mr-1" placeholder="Search..."/> -->
            <b-button variant="primary" to="quizzes/create">
              <span class="text-nowrap">Add Quiz</span>
            </b-button>
          </div>
        </b-col>
      </b-row>
    </div>
    <div class="flex d-flex align-items-center justify-content-center loader-area" v-if="state.loading"  style="min-height: 450px;" >
      <b-spinner label="Loading..."/>
    </div>
    <b-table
      v-else
      class="position-relative"
      :items="state.quizzes"
      responsive
      :fields="tableColumns"
      primary-key="id"
      show-empty
      striped
      empty-text="No matching records found"
    >
      <template #cell(createdDate)="data">
        {{ data.value | formatDay }}
      </template>
      <template #cell(actions)="data">
        <b-dropdown
          variant="link"
          no-caret
          :right="$store.state.appConfig.isRTL"
        >
          <template #button-content>
            <feather-icon
              icon="MoreVerticalIcon"
              size="16"
              class="align-middle text-body"
            />
          </template>
          <b-dropdown-item :to="{ name: 'quiz-grades', params: { id: data.item.exam_id } }">
            <feather-icon icon="EditIcon" />
            <span class="align-middle ml-50">View grades</span>
          </b-dropdown-item>

          <b-dropdown-item :to="{ name: 'quiz-edit', params: { id: data.item.exam_id } }">
            <feather-icon icon="EditIcon" />
            <span class="align-middle ml-50">Edit</span>
          </b-dropdown-item>

          <b-dropdown-item @click="confirmDelete(data.item.material_id)">
            <feather-icon icon="TrashIcon" />
            <span class="align-middle ml-50">Delete</span>
          </b-dropdown-item>
        </b-dropdown>
      </template>
    </b-table>
  </b-card>
</template>

<script>
import Ripple from 'vue-ripple-directive';
import store from '@/store';
import { ref, onUnmounted } from '@vue/composition-api';
import useQuizzes from '../useQuizzes';
import useQuizzesModule from '../useQuizzesModule';
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
import vSelect from 'vue-select';
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
    vSelect
  },
  directives: {
    Ripple,
  },
  setup(props, { emit }) {
    const USER_APP_STORE_MODULE_NAME = 'quizzes'
    // Register module
    if (!store.hasModule(USER_APP_STORE_MODULE_NAME)) store.registerModule(USER_APP_STORE_MODULE_NAME, useQuizzesModule)
    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(USER_APP_STORE_MODULE_NAME)) store.unregisterModule(USER_APP_STORE_MODULE_NAME)
    })

    const loading = ref(false)

    const {
      fetchQuizzes,
      tableColumns,
      state,
      refetchData,
    } = useQuizzes(props, { emit })

		fetchQuizzes();

    return {
      fetchQuizzes,
      tableColumns,
			state,
			loading,
      refetchData,
    }
  },
  methods: {
    confirmDelete(quizId) {
      this.$swal({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        customClass: {
          confirmButton: 'btn btn-primary',
          cancelButton: 'btn btn-outline-danger ml-1',
        },
        buttonsStyling: false,
				showLoaderOnConfirm: true,
        preConfirm() {
          if (!quizId) return null;
					return store.dispatch('quizzes/deleteQuiz', quizId)
						.then(res => { 
							if(res.data.status == 200) {
								return res;
							}
						})
						.catch(error => {
							this.$swal.showValidationMessage(`Request failed:  ${error}`)
						})
        },				
      }).then(result => {
        if (result.value) {
					this.refetchData();
          this.$swal({
            icon: 'success',
            title: 'Deleted!',
            text: 'Quiz has been deleted.',
            customClass: {
              confirmButton: 'btn btn-success',
            },
          })
        }
      })
    },	
  }
}
</script>
<style lang="scss">
@import '~@core/scss/vue/libs/vue-select.scss';
@import '~@core/scss/vue/libs/vue-sweetalert.scss';
</style>
