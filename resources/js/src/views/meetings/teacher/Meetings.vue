<template>
  <b-card no-body>
    <div class="m-2">
      <b-row>
        <b-col cols="12" md="6" class="d-flex align-items-center justify-content-start mb-1 mb-md-0">
          <label>Show</label>
          <v-select v-model="perPage" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="perPageOptions" :clearable="false" class="per-page-selector d-inline-block mx-50"/>
          <label>entries</label>
        </b-col>

        <!-- Search -->
        <b-col cols="12" md="6">
          <div class="d-flex align-items-center justify-content-end">
            <b-form-input v-model="searchQuery" class="d-inline-block mr-1" placeholder="Search..."/>
            <b-button variant="primary" to="meetings/create">
              <span class="text-nowrap">Add Meeting</span>
            </b-button>
          </div>
        </b-col>
      </b-row>
    </div>
    <div class="flex d-flex align-items-center justify-content-center loader-area" v-if="state.loading" style="min-height: 450px;" >
      <b-spinner label="Loading..."/>
    </div>
    <b-table
      v-else
      class="position-relative"
      :items="state.meetings"
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
          <b-dropdown-item :to="{ name: 'upload-attendance', params: { id: data.item.meeting_id } }">
            <span class="align-middle ml-50">Upload</span>
          </b-dropdown-item>
          <b-dropdown-item :to="{ name: 'view-attendance', params: { id: data.item.meeting_id } }">
            <feather-icon icon="ViewIcon" />
            <span class="align-middle ml-50">View</span>
          </b-dropdown-item>
          <b-dropdown-item :to="{ name: 'meeting-edit', params: { id: data.item.meeting_id } }">
            <feather-icon icon="EditIcon" />
            <span class="align-middle ml-50">Edit</span>
          </b-dropdown-item>

          <b-dropdown-item @click="confirmDelete(data.item.material_id)">
            <feather-icon icon="TrashIcon" />
            <span class="align-middle ml-50">Delete</span>
          </b-dropdown-item>
        </b-dropdown>
      </template>
      <template #cell(teams_url)="data">
        <p class="teams_url m-0">{{data.value}}</p>
      </template>
    </b-table>
    <div class="mx-2 mb-2">
      <b-row>
        <b-col cols="12" sm="6" class="d-flex align-items-center justify-content-center justify-content-sm-start">
          <span class="text-muted">Showing {{currentPage}} to {{perPage}} of {{totalMeetings}} entries</span>
        </b-col>
        <!-- Pagination -->
        <b-col
          cols="12"
          sm="6"
          class="d-flex align-items-center justify-content-center justify-content-sm-end"
        >
          <b-pagination
            v-model="currentPage"
            :total-rows="totalMeetings"
            :per-page="perPage"
            first-number
            last-number
            class="mb-0 mt-1 mt-sm-0"
            prev-class="prev-item"
            next-class="next-item"
          >
            <template #prev-text>
              <feather-icon
                icon="ChevronLeftIcon"
                size="18"
              />
            </template>
            <template #next-text>
              <feather-icon
                icon="ChevronRightIcon"
                size="18"
              />
            </template>
          </b-pagination>

        </b-col>

      </b-row>
    </div>
  </b-card>
</template>

<script>
import Ripple from 'vue-ripple-directive';
import store from '@/store';
import { ref, onUnmounted } from '@vue/composition-api';
import useMeetings from '../useMeetings';
import useMeetingsModule from '../useMeetingsModule';
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
    const USER_APP_STORE_MODULE_NAME = 'meetings'
    // Register module
    if (!store.hasModule(USER_APP_STORE_MODULE_NAME)) store.registerModule(USER_APP_STORE_MODULE_NAME, useMeetingsModule)
    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(USER_APP_STORE_MODULE_NAME)) store.unregisterModule(USER_APP_STORE_MODULE_NAME)
    })

    const loading = ref(false)

    const {
      fetchMeetings,
      tableColumns,
      perPage,
      currentPage,
      totalMeetings,
      dataMeta,
      perPageOptions,
      searchQuery,
      refMeetingsTable,
      refetchData,
      state,	
    } = useMeetings(props, { emit })

		fetchMeetings();

    return {
      fetchMeetings,
      tableColumns,
      perPage,
      currentPage,
      totalMeetings,
      dataMeta,
      perPageOptions,
      refMeetingsTable,
      refetchData,
			state,
      searchQuery,
			loading,
    }
  },
  methods: {
    confirmDelete(meetingId) {
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
          if (!meetingId) return null;
					return store.dispatch('meetings/deleteMeeting', meetingId)
						.then(res => { 
              console.log(res);
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
            text: 'Meeting has been deleted.',
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
.teams_url {
  white-space: nowrap;
  max-width: 600px;
  text-overflow: ellipsis;
  overflow: hidden;
}
@import '~@core/scss/vue/libs/vue-select.scss';
@import '~@core/scss/vue/libs/vue-sweetalert.scss';
</style>
