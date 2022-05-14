<template>
  <div>
    <h2 class="text-center">Платежи</h2>

                <router-link :to="{name: 'payment-create', params: { id: this.$route.params.id }}" class="btn btn-success">Создать</router-link>

    <table class="table">
      <thead>
      <tr>
        <th class="text-center">ID</th>
        <th class="text-center">ID пользователя</th>
        <th class="text-center">Тип</th>
        <th class="text-center">Сумма</th>
        <th class="text-center">Действия</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="payment in payments" :key="payment.id">
        <td class="text-center">{{ payment.id }}</td>
        <td class="text-center">{{ payment.user_id }}</td>
        <td class="text-center">{{ payment.type.text }}</td>
        <td class="text-center">{{ payment.amount }}</td>
        <td class="text-center">
          <div class="btn-group" role="group">
            <button class="btn btn-danger" @click="deletePayment(payment.id)">Удалить</button>
          </div>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      payments: []
    }
  },
  created() {
    this.axios
        .get(`/api/users/${this.$route.params.id}/payments`)
        .then(response => {
          this.payments = response.data;
        });
  },
  methods: {
    deletePayment(paymentId) {
      this.axios
          .delete(`/api/users/${this.$route.params.id}/payments/${paymentId}`)
          .then(response => {
            let i = this.payments.map(data => data.id).indexOf(paymentId);
            this.payments.splice(i, 1)
          });
    }
  }
}
</script>