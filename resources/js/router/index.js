import {createRouter, createWebHistory} from "vue-router";

// middleware
import authMiddleware from '../middleware/auth.js';

// views
import Home from '../pages/Home.vue';

// error
import NotFound from '../pages/NotFound.vue';

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home,
        meta: {
            requiresGuest: true
        },
    },{
        path: '/:pathMatch(.*)*',
        name: 'NotFound',
        component: NotFound
    }
]

const router = createRouter({
    history: createWebHistory(),
    linkExactActiveClass: 'active',
    routes
})

// router.beforeEach(authMiddleware);

export default router