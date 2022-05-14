import AllUsers from './components/AllUsers.vue';
import CreateUser from './components/CreateUser.vue';
import EditUser from './components/EditUser.vue';
import UserPayments from './components/UserPayments.vue';
import CreatePayment from './components/CreatePayment.vue';

export const routes = [
    {
        name: 'users',
        path: '/users',
        component: AllUsers
    },
    {
        name: 'create',
        path: '/users/create',
        component: CreateUser
    },
    {
        name: 'edit',
        path: '/users/:id',
        component: EditUser
    },
    {
        name: 'payments',
        path: '/users/:id/payments',
        component: UserPayments
    },
    {
        name: 'payment-create',
        path: '/users/:id/payments/create',
        component: CreatePayment
    },
];