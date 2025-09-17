import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import plugins from './plugins'
import './index.css'
const app = createApp(App)
app.use(plugins)
app.use(router)
app.mount('#app')