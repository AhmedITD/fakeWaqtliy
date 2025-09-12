import { computed, ref } from "vue"
import { useNeighborhoodsStore } from '../stores/neighborhoods'
import { parseCoordinates, isCoordinateString, sendMessageToIframe } from '../utils/mapUtils'
import mapboxgl from 'mapbox-gl'
const store = useNeighborhoodsStore()
const filteredNeighborhoods = computed(() => store.filteredNeighborhoods)
const map = ref<mapboxgl.Map | null>(null)
const xy = ref<string | null>(null)
const currentMousePosition = ref<any>(null)
const manuallyAddFlag = ref<any>(true)
const currentMarker = ref<any>(null)
const handleSearchButton = () => 
{
  const searchValue = store.query.trim()
  if (!searchValue) return
  if (isCoordinateString(searchValue)) {
    const coords = parseCoordinates(searchValue)
    if (coords) {
      flyToCoordinates(map.value, coords)
      return
    }
  }
  flyToNeighborhood(map.value, filteredNeighborhoods.value[0])
}

function flyToNeighborhood(map: any, currentFeature: any) 
{
  if (map && currentFeature.geometry.type === 'Point') 
    {
    map.flyTo({
      center: currentFeature.geometry.coordinates as [number, number],
      zoom: 15
    });
  }
}

function flyToCoordinates(map: any, coords: [number, number]) 
{
  if (map && coords) 
    {
    map.flyTo({
      center: coords,
      zoom: 15
    })
  }
}
function changeCursor(map: any) 
{
   if (!manuallyAddFlag.value) {
      map.getCanvas().style.cursor = "url('assets/images/add.png') 24 48, crosshair";
      // manuallyAddFlag.value = true
    }else{
      map.getCanvas().style.cursor = "default";
      // manuallyAddFlag.value = false
    }
}
function handelbutton(map: any)
{
  if(xy.value && map){
    const coords = parseCoordinates(xy.value)
    if(coords){
      sendMessageToIframe(coords)
    }
  }
}
function handelCoordinatesInput(map: any)
{
  if(xy.value && map.value){
    const coords = parseCoordinates(xy.value)
    if(coords){
      flyToCoordinates(map, coords)
      addOrUpdateMarker(map, coords)
    }
  }
}

function addOrUpdateMarker(map: any, coords: [number, number]) 
{
  if (currentMarker.value) {
    currentMarker.value.remove()
  }
  //create new marker at the new location
  if (map) {
    currentMarker.value = new mapboxgl.Marker({
      color: '#FF0000', //red marker
      draggable: false
    })
    .setLngLat(coords)
    .addTo(map)
  }
}
//export the variables
export {map, store, filteredNeighborhoods, xy, currentMousePosition, manuallyAddFlag}
//export the functions
export {flyToNeighborhood, flyToCoordinates, changeCursor, handleSearchButton, parseCoordinates, isCoordinateString, handelbutton, handelCoordinatesInput, addOrUpdateMarker}