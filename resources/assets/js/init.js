
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

window.VModal = require('vue-js-modal');
window.ModalDialogs = require('vue-modal-dialogs');
window.MessageBox = require('./components/MessageBox.vue');
import BootstrapVue from 'bootstrap-vue';

Vue.use(BootstrapVue);
Vue.use(ModalDialogs);               // No options

Vue.use(VModal.default, { dialog: true });

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('my-modal', require('./components/ModalComponent.vue'));
Vue.component('MessageBox', require('./components/MessageBox.vue'));

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import bModal from 'bootstrap-vue/es/components/modal/modal'
import bModalDirective from 'bootstrap-vue/es/directives/modal/modal'
import bButton from 'bootstrap-vue/es/components/button/button';
import { MultiSelect } from 'vue-search-select'

Vue.component('multi-select', MultiSelect);
Vue.component('b-button', bButton);
Vue.component('b-modal', bModal);
Vue.directive('b-modal', bModalDirective);


