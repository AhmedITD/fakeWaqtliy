import { defineStore } from 'pinia'
import data from './neighborhoodsData'
import type { NeighborhoodsState } from '@/types/stores'
export const useNeighborhoodsStore = defineStore('neighborhoods', {
  state: (): NeighborhoodsState => (data),
  getters: {
    filteredNeighborhoods(state: NeighborhoodsState) 
    {
      const query = state.query.toLowerCase().trim()
      if (!query) {
        return state.Data
      }      
      return state.Data.filter((neighborhood: NeighborhoodsState['Data'][number]) => {
        if ( neighborhood.name?.toLowerCase().includes(query) ) {
          return true
        }
        return false
      })
    }
  },
  actions: {
    search (query: string)
    {
      this.query = query
    }
  }
})

