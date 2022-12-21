<template>
  <b-card no-body>
    <b-table
      class="position-relative"
      :items="items"
      responsive
      :fields="tableColumns"
      primary-key="id"
      show-empty
      empty-text="No matching records found"
    >
      <template #cell(fullName)="data">
        <span v-if="data.item.slug == 'professor'">Prof.{{ data.value }}</span>
        <span v-else-if="data.item.slug == 'engineer'">Eng.{{ data.value }}</span>
      </template>
      <template #cell(grade)="data">
        <b-badge v-if="data.value" variant="success">
          {{ data.value }}
        </b-badge>
        <span v-else>Not Graded</span>
      </template>
      <template #cell(file_path)="data">
        <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" variant="outline-primary" :href="data.value" size="sm" class="text-nowrap">
          <font-awesome-icon icon="fa-solid fa-file-pdf" />
          Download File
        </b-button>
      </template>
      <template #cell(deadline)="data">
        {{data.value | formatNow}}
      </template>
      <template #cell(submit)="data">
        <b-button v-if="data.item.path" v-ripple.400="'rgba(40, 199, 111, 0.15)'" variant="outline-success" size="sm" class="text-nowrap" :to="{ name: 'assignment-submit', params: { id: data.item.assignment_id } }">
          Edit Submission
        </b-button>
        <b-button v-else v-ripple.400="'rgba(40, 199, 111, 0.15)'" variant="outline-success" size="sm" class="text-nowrap" :to="{ name: 'assignment-submit', params: { id: data.item.assignment_id } }">
          Add Submission
        </b-button>
      </template>
    </b-table>
  </b-card>
</template>

<script>
import Ripple from 'vue-ripple-directive';
import { BTable, BBadge, BCard, BButton } from 'bootstrap-vue'
import axios from 'axios';
export default {
  components: {
    BTable,
    BBadge,
    BCard,
    BButton
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      obj: {},
      items: [],
      assignments: {},
      myAssignments: {},
      tableColumns: [
        { label: '#', key: 'number'},
        { label: 'Name', key: 'title', sortable: true},
        { label: 'Course', key: 'course_name', sortable: true},
        { label: 'Added By', key: 'fullName', sortable: true },
        { label: 'Grade', key: 'grade' },
        { label: 'Date', key: 'deadline' },
        { label: 'File', key: 'file_path' },
        { label: 'Submit', key: 'submit' },
      ],
    }
  },
  created() {
    try {
      axios.get('/api/myassignments').then(res => {
        res.data[0].forEach(element => {
          this.assignments[element.assignment_id] = element;
        });
        res.data[1].forEach(element => {
          this.myAssignments[element.assignment_id] = element;
        });
        let i = 0;
        for (const [keyAssignments, valueAssignments] of Object.entries(this.assignments)) {
          valueAssignments['number'] = ++i;
          for (const [keymyAssignments, valuemyAssignments] of Object.entries(this.myAssignments)) {
            if(keyAssignments == keymyAssignments) {
              valueAssignments['path'] = valuemyAssignments['path'];
              valueAssignments['grade'] = valuemyAssignments['grade'];
              break;
            }
          }
        }
        for (const [key, value] of Object.entries(this.assignments)) {
          this.items.push(value);
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