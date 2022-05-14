<template>
  <div>
    <h3 class="text-center">Создать пользователя</h3>
    <div class="row">
      <div class="col-md-6">
        <form @submit.prevent="addUser">
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
          <div class="form-group">
            <label>Пароль</label>
            <input type="text" class="form-control" v-model="user.password">
          </div>
          <button type="submit" class="mt-2 btn btn-primary">Создать</button>
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
  methods: {
    addUser() {
      this.axios
          .post('/api/users', this.user)
          .then((response) => {
            this.$router.push({ name: 'users' });
          })
          .catch(err => alert(err.response.data.message))
          .finally(() => this.loading = false)
    }
  }
}
</script>