import { useNeighborhoodsStore } from '../stores/neighborhoodsStore'
import { useMapStateStore } from '../stores/mapStateStore'
import { parseCoordinates, isCoordinateString, sendMessageToIframe } from '../utils/mapUtils'
import mapboxgl from 'mapbox-gl'
// Store
const NeighborhoodsStore = useNeighborhoodsStore()
const mapStateStore = useMapStateStore()
function handleSearchButton() 
{
  const searchValue = NeighborhoodsStore.query.trim()
  if (!searchValue) return
  if (isCoordinateString(searchValue)) {
    const coords = parseCoordinates(searchValue)
    if (coords) {
      mapStateStore.flyToCoordinates(coords)
      return
    }
  }
  mapStateStore.flyToCoordinates(NeighborhoodsStore.filteredNeighborhoods[0].coordinates as [number, number])
}
function handel_IframeButton()
{
  if(mapStateStore.xy && mapStateStore.map){
    const coords = parseCoordinates(mapStateStore.xy)
    if(coords){
      sendMessageToIframe(coords)
    }
  }
}
function handleMapClick(e: mapboxgl.MapMouseEvent) 
{
  if (mapStateStore.manuallyAddFlag) {
    const coords: [number, number] = [e.lngLat.lng, e.lngLat.lat]
    mapStateStore.flyToCoordinates(coords)
    mapStateStore.addOrUpdateMarker(coords)
    mapStateStore.setCoordinates(`${e.lngLat.lng},${e.lngLat.lat}`)
  }
}
// export the store getters for safe access
  export {NeighborhoodsStore, mapStateStore}
// export the functions
export {handleMapClick, handleSearchButton, parseCoordinates, isCoordinateString, handel_IframeButton}