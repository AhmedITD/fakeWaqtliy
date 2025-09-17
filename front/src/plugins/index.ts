import type { App } from 'vue'
import piniaPlugin from './pinia'

export default {
  install(app: App) {
    app.use(piniaPlugin)
  }
}
