<template>
    <datepicker id="date" class="c-input__line" name="project_reception_end" :disabled-dates="disabledDates" :format="DatePickerFormat" :bootstrap-styling="false" :language="ja" placeholder="日付を選択してください" :value="state.date"></datepicker>
</template>

<script>
import Datepicker from 'vuejs-datepicker'
import {ja} from 'vuejs-datepicker/dist/locale'

  export default {
    name: 'app',
    props: ['propdate'],
    data () {
      return {
        DatePickerFormat: 'yyyy-MM-dd',
        ja:ja,
        disabledDates:{
          to: new Date(), //本日以前の日付は選択不可
        },
        state : {
          date: ""
        }
      }
    },
    components: {
      Datepicker
    }
  }
</script>
