<template>
  <div>
    <h3 class="text-center">Создать платеж</h3>
    <div class="row">
      <div class="col-md-6">
        <form @submit.prevent="addPayment">
          <div class="form-group">
            <label>Тип</label>
            <br/>
            <input type="radio" id="one" value="1" v-model="payment.type">
            <label for="one">Списание</label>
            <br/>
            <input type="radio" id="two" value="2" v-model="payment.type">
            <label for="two">Пополнение</label>
          </div>
          <div class="form-group">
            <label>Сумма</label>
            <input type="number" max="99999999" class="form-control" v-model="payment.amount">
          </div>
          <button type="submit" class="mt-2 btn btn-primary">Провести</button>
        </form>
        <button type="submit" @click="addCoinbasePayment" class="mt-2 btn btn-primary">Провести через Coinbase</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      payment: {}
    }
  },
  methods: {
    addPayment() {
      if (this.payment.amount > 99999999) {
        return;
      }
      this.axios
          .post(`/api/users/${this.$route.params.id}/payments`, this.payment)
          .then((response) => {
            this.$router.push({ name: 'payments' });
          })
          .catch(err => alert(err.response.data.message))
          .finally(() => this.loading = false)
    },
    addCoinbasePayment() {
      if (this.payment.amount > 99999999) {
        return;
      }
      this.axios
          .post(`/api/users/${this.$route.params.id}/payments/coinbase`, this.payment)
          .then((response) => {
            this.$router.push({ name: 'payments' });
          })
          .catch(err => alert(err.response.data.message))
          .finally(() => this.loading = false)
    }
  }
}
</script>