import { createApp } from 'vue'
import App from '@/App.vue'
import { registerPlugins } from '@core/utils/plugins'

// Styles
import '@core-scss/template/index.scss'
import '@styles/styles.scss'

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
// Create vue app
const app = createApp(App)

app.use(VueSweetalert2);
// Register plugins
registerPlugins(app)

// Mount vue app
app.mount('#app')
