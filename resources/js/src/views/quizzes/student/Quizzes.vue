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
        <span v-if="!myQuizzes[data.item.exam_id]">No Attempt</span>
        <span v-else-if="!data.item.buttonColor"><small>Not availabe yet</small></span>
        <span v-else-if="data.item.grade"><strong>{{ data.value }}</strong></span>
        <span v-else>Not graded</span>
      </template>
      <template #cell(opens_at)="data">
        {{data.value | formatNow}}
      </template>
      <template #cell(attempt)="data">
        <b-button size="sm" :variant="'gradient-' + data.item.buttonColor" v-if="data.item.buttonColor" :to="{ name: data.item.route, params: { id: data.item.exam_id } }">
          <span class="text text-capitalize">{{data.item.quizText}}</span>
        </b-button>
        <small v-else>{{data.item.quizText}} <strong>{{data.item.quizTime}}</strong></small>
      </template>
    </b-table>
  </b-card>
</template>

<script>
import Ripple from 'vue-ripple-directive';
import { BTable, BBadge, BCard, BButton } from 'bootstrap-vue'
import axios from 'axios';
import moment from 'moment';
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
      quizzes: {},
      myQuizzes: {},
      tableColumns: [
        { label: '#', key: 'number'},
        { label: 'Name', key: 'title', sortable: true},
        { label: 'Course', key: 'course_name', sortable: true},
        { label: 'Added By', key: 'fullName', sortable: true },
        { label: 'Grade', key: 'grade' },
        { label: 'Opens At', key: 'opens_at' },
        { label: 'Duration', key: 'duration' },
        { label: 'Attempt', key: 'attempt' },
      ],
    }
  },
  created() {
    try {
      axios.get('/api/myquizzes').then(res => {
        res.data[0].forEach(element => {
          this.quizzes[element.exam_id] = element;
        });
        res.data[1].forEach(element => {
          this.myQuizzes[element.exam_id] = element;
        });
        let i = 0;
        for (const [keyQuizzes, valueQuizzes] of Object.entries(this.quizzes)) {
          valueQuizzes['number'] = ++i;
          var returned_endate = moment(moment(valueQuizzes.opens_at)).add(valueQuizzes.duration);
          if(moment(valueQuizzes.opens_at).isAfter(moment().format())) {
            valueQuizzes.quizText = 'Quiz Will Open at:';
            valueQuizzes.quizTime = moment.utc(valueQuizzes.opens_at).format("LLLL");
          } else {
            valueQuizzes.quizEndTime = moment(returned_endate).fromNow();
            const result = this.myQuizzes[valueQuizzes.exam_id];
            if(result) {
              if(moment(returned_endate).isAfter(moment().format())) {
                valueQuizzes.quizText = 'Results will be availabe';
                valueQuizzes.quizTime = valueQuizzes.quizEndTime;
              } else {
                valueQuizzes.quizText = 'View results';
                valueQuizzes.buttonColor = "success";
                valueQuizzes.route = "quiz-results";
              }
            } else {
              if(moment(returned_endate).isAfter(moment().format())) {
                valueQuizzes.quizText = 'Attempt Quiz Now';
                valueQuizzes.buttonColor = "primary";
                valueQuizzes.route = "quiz-submit";
              } else {
                valueQuizzes.quizText = 'Quiz has been Ended from';
                valueQuizzes.quizTime = valueQuizzes.quizEndTime;
              }
            }
          }
          for (const [keymyQuizzes, valuemyQuizzes] of Object.entries(this.myQuizzes)) {
            if(keyQuizzes == keymyQuizzes) {
              valueQuizzes['grade'] = valuemyQuizzes['grade'];
              break;
            }
          }
        }
        for (const [key, value] of Object.entries(this.quizzes)) {
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