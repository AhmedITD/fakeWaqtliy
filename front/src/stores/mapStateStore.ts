import { defineStore } from 'pinia'
import { shallowRef } from 'vue'
import type { mapState } from '@/types/stores'
import mapboxgl from 'mapbox-gl'
export const useMapStateStore = defineStore('mapState', 
{
  state: (): mapState => ({
    map: shallowRef<mapboxgl.Map | null>(null),
    xy: '',
    manuallyAddFlag: true,
    currentMarker: shallowRef<mapboxgl.Marker | null>(null)
  }),
  actions: {
    setMap(mapInstance: mapboxgl.Map) {
      this.map = mapInstance
    },
    setCoordinates(coordinates: string) {
      this.xy = coordinates
    },
    updateMapCursor() {
      if (!this.map) return
      if (this.manuallyAddFlag) {
        this.map.getCanvas().style.cursor = "url('assets/images/add.png') 24 48, crosshair"
      } else {
        this.map.getCanvas().style.cursor = ""
      }
    },
    setMarker(marker: any) {
      if (this.currentMarker) {
        this.currentMarker.remove()
      }
      this.currentMarker = marker
    },
     addOrUpdateMarker(coords: [number, number]) 
    {
      // Remove existing marker from store
      if (this.currentMarker) {
        this.currentMarker.remove()
      }
      // Create new marker at the new location
      if (this.map) {
        const marker = new mapboxgl.Marker({
          color: '#FF0000', // red marker
          draggable: false
        })
        .setLngLat(coords)
        .addTo(this.map as any)
        // Store the marker in the store
        this.setMarker(marker)
      }
    },
    flyToCoordinates(coords: [number, number]) 
    {
      if (this.map && coords) 
        {
          this.map.flyTo({
            center: coords,
            zoom: 15
          })
        }
      }
  }
}
)