import Vue from 'vue'
import App from './App'

import basics from './pages/basics/home.vue'
Vue.component('basics',basics)

import components from './pages/component/home.vue'
Vue.component('components',components)

import plugin from './pages/plugin/home.vue'
Vue.component('plugin',plugin)

import cuCustom from './colorui/components/cu-custom.vue'
Vue.component('cu-custom',cuCustom)

import websocket from '@/common/websocketStore.js'
 
Vue.prototype.$websocket = websocket;

Vue.config.productionTip = false
Vue.prototype.setData = function (e){
	if(!e){
		console.log('传值为空');
		return;
	}
	if(typeof(e) == "object" && Object.prototype.toString.call(e).toLowerCase() == "[object object]" && !e.length){
		for (var index in e){
		    this[index] = e[index];
		}
	}else{
		console.log('传值不是json对象');
		return;
	}
}

App.mpType = 'app'

const app = new Vue({
    ...App
})
app.$mount()

 



