import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

import Example from '../components/Example.vue'

export default new Router({
  mode: 'history',
  scrollBehavior: () => ({ y: 0 }),
  routes: [
    { path: '/',    component: {template : '<div> This is Homepage </div>'} },
    { path: '/top', component: Example },
    { path: '/foo', component: { template: '<div>foo</div>'} },
    { path: '/bar', component: { template: '<div>bar</div>'} }   
  ]
})