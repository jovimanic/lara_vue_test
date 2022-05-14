<template>
  <div>
    <h3 class="text-center">Редактировать пользователя</h3>
    <div class="row">
      <div class="col-md-6">
        <form @submit.prevent="updateUser">
          <div class="form-group">
            <label>Имя</label>
            <input type="text" class="form-control" v-model="user.name">
          </div>
          <div class="form-group">
            <label>Почта</label>
            <input type="text" class="form-control" v-model="user.email">
          </div>
          <div class="form-group">
            <label>Телефон</label>
            <input type="text" class="form-control" v-model="user.phone">
          </div>
<!--          <div class="form-group">-->
<!--            <label>Пароль</label>-->
<!--            <input type="text" class="form-control" v-model="user.password">-->
<!--          </div>-->
          <button type="submit" class="mt-2 btn btn-primary">Сохранить</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: {}
    }
  },
  created() {
    this.axios
        .get(`/api/users/${this.$route.params.id}`)
        .then((res) => {
          this.user = res.data;
        });
  },
  methods: {
    updateUser() {
      this.axios
          .patch(`/api/users/${this.$route.params.id}`, this.user)
          .then((res) => {
            this.$router.push({ name: 'users' });
          })
          .catch(err => alert(err.response.data.message))
    }
  }
}
</script>