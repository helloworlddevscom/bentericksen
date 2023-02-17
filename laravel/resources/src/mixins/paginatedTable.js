import Api from '../services/BaseService'
import {BPagination} from 'bootstrap-vue';
import DatePick from 'vue-date-pick';

export default {
  props: {
    _response: {
      type: Object
    }
  },
  data() {
    return {
      response: {
        current_page: 0,
        from: 0,
        to: 0,
        total: 0,
        per_page: 10,
        last_page: 0,
        data: []
      },
      page: 1,
      business: '',
      contact: '',
      to: '',
      from: '',
      sortBy: 'business.name',
      sortOrder: 'asc'
    }
  },
  components: {
    BPagination,
    DatePick
  },
  watch: {
    page: {
      immediate: true,
      handler() {
        this.getData()
      }
    },
    business() {
      this.resetAndGetData()
    },
    contact() {
      this.resetAndGetData()
    },
    to() {
      this.resetAndGetData()
    },
    from() {
      this.resetAndGetData()  
    }
  },
  computed: {
    url() {
      return ``
    },
    params() {
      const {page, business, contact, from, to, sortBy, sortOrder} = this
      return `page=${page}&business_name=${business}&contact_name=${contact}&from=${from}&to=${to}&sort=${sortBy}&sort_order=${sortOrder}`
    }
  },
  created() {
    if (this._response) {
      Object.assign(this.response, this._response)
    }
  },
  methods: {
    toggleSort(field) {
      if (field === this.sortBy) {
        this.sortOrder = this.sortOrder == 'desc' ? 'asc' : 'desc'
      } else {
        this.sortBy = field
        this.sortOrder = 'asc'
      }
      this.getData()
    },
    resetAndGetData() {
      this.page = 1
      this.getData()
    },
    async getData() {
      Object.assign(this.response, await Api.get(this.url)) 
    }
  }
}