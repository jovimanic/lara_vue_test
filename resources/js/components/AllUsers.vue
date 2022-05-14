<template>
  <div>
    <h2 class="text-center">Пользователи</h2>

    <table class="table">
      <thead>
      <tr>
        <th class="text-center">ID</th>
        <th class="text-center">Имя</th>
        <th class="text-center">Email</th>
        <th class="text-center">Телефон</th>
        <th class="text-center">Действия</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="user in users" :key="user.id">
        <td class="text-center">{{ user.id }}</td>
        <td class="text-center">{{ user.name }}</td>
        <td class="text-center">{{ user.email }}</td>
        <td class="text-center">{{ user.phone }}</td>
        <td class="text-center w-50">
          <div class="btn-group" role="group">
            <router-link :to="{name: 'payments', params: { id: user.id }}" class="btn btn-dark">Платежи</router-link>
            <router-link :to="{name: 'edit', params: { id: user.id }}" class="btn btn-success">Редактировать</router-link>
            <button class="btn btn-danger" @click="deleteProduct(user.id)">Удалить</button>
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
      users: []
    }
  },
  created() {
    this.axios
        .get('/api/users/')
        .then(response => {
          this.users = response.data;
        });
  },
  methods: {
    deleteProduct(id) {
      this.axios
          .delete(`/api/users/${id}`)
          .then(response => {
            let i = this.users.map(data => data.id).indexOf(id);
            this.users.splice(i, 1)
          });
    }
  }
}
</script>