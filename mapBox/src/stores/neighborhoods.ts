import { defineStore } from 'pinia'
import data from './neighborhoodsData'
import { parseCoordinates, isCoordinateString } from '../utils/mapUtils'
interface State {
  neighborhoodsData: {
    type: string
    features: any[]
  }
  query: string
}
export const useNeighborhoodsStore = defineStore('neighborhoods', {
  state: (): State => (data),
  getters: {
    filteredNeighborhoods(state: any) {
      const query = state.query.toLowerCase()
        if (!query.trim()) {
          return state.neighborhoodsData.features
        }
          return state.neighborhoodsData.features.filter((neighborhood: any) => 
            neighborhood.properties.name?.toLowerCase().includes(query.toLowerCase()) ||
            neighborhood.properties.title?.toLowerCase().includes(query.toLowerCase()) ||
            neighborhood.properties.area?.toLowerCase().includes(query.toLowerCase()) ||
            JSON.stringify(neighborhood.geometry.coordinates) === JSON.stringify(isCoordinateString(query) ? parseCoordinates(query) : query.trim()) //isCoordinateString(query) ? parseCoordinates(query)
        )
    }
  },
  actions: {
    search (query: any){
      this.query = query
    }
  }
})

