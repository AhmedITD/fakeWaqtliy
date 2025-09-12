<template>
  <!-- <div class="relative w-full h-screen flex"> -->
    
    <!-- <div class="w-1/3 h-full overflow-hidden border-r border-black/25 bg-white"> -->
      <!-- <div class="search p-4"> -->
        <!-- Search content will go here -->
      <!-- </div> -->
      <!-- <div class="h-full overflow-auto pb-60"> -->
        <!-- Listings content will go here -->
      <!-- </div> -->
    <!-- </div> -->
    
    <!-- Map Container -->
    <div class="flex h-screen">
      <!-- Search Panel -->
      <div class="w-1/3 bg-white border-r border-gray-300 p-4 overflow-y-auto">
        <div class="mb-4">
          <h2 class="text-xl font-bold mb-2">Search Baghdad</h2>
          <div class="relative">
            <input 
              type="text" 
              v-model="store.query" 
              placeholder="Search neighborhoods or enter coordinates (lng,lat)" 
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <button 
              @click="handleSearchButton"
              class="absolute right-2 top-2 text-gray-500 hover:text-blue-500"
            >
              🔍
            </button>
          </div>
          <div class="mt-2 text-sm text-gray-600">
            <p>💡 Tips:</p>
            <p>• Type neighborhood name (e.g., "Karrada")</p>
            <p>• Enter coordinates (e.g., "44.3856,33.3352")</p>
          </div>
        </div>
        
        <!-- Results -->
        <div class="space-y-2">
          <h3 class="font-semibold text-gray-700">
            {{ store.query ? `Results (${filteredNeighborhoods.length})` : `All Neighborhoods (${store.neighborhoodsData.features.length})` }}
          </h3>
          
          <div 
            v-for="neighborhood in filteredNeighborhoods" 
            :key="neighborhood.properties.name"
            @click="flyToNeighborhood(map, neighborhood)"
            class="p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition-colors"
            :class="{ 'bg-blue-100 border-blue-400': 'aa' === neighborhood.properties.name }"
          >
            <div class="font-semibold text-blue-700">{{ neighborhood.properties.name || neighborhood.properties.title }}</div>
            <div class="text-sm text-gray-600 mt-1">{{ neighborhood.properties.description }}</div>
            <div class="text-xs text-gray-500 mt-1">
              <span class="inline-block mr-3">👥 {{ neighborhood.properties.population }}</span>
              <span class="inline-block">📍 {{ neighborhood.properties.area }}</span>
            </div>
            <div class="text-xs text-gray-400 mt-1">{{ neighborhood.properties.landmarks }}</div>
          </div>
          
          <div v-if="filteredNeighborhoods.length === 0 && store.query" class="text-center py-8 text-gray-500">
            <p>No neighborhoods found</p>
            <p class="text-sm mt-1">Try entering coordinates instead</p>
          </div>
        </div>
      </div>
      <!-- Mode -->
      <div>manually add</div>
      <label class="switch" @click="changeCursor(map)">
        <input type="checkbox" v-model="manuallyAddFlag">
        <span class="slider round"></span>
      </label>
      <!-- Xy mode -->
      <div v-if="!manuallyAddFlag">
        <input @blur="handelCoordinatesInput(map)" type="text" v-model="xy" placeholder="enter the x y location" />
      </div>
      <!-- Add button -->
      <button @click="handelbutton(map)" class="btn btn-primary">Add</button>
      <span>the selected location is :{{ xy }}</span>
      <!-- Map -->
      <div class="w-2/3 relative">
        <div id="map" class="w-full h-full"></div>
      </div>
    </div>
</template>

<script setup lang="ts">
import {onMounted, onUnmounted} from 'vue'
import mapboxgl from 'mapbox-gl'
import 'mapbox-gl/dist/mapbox-gl.css'
//variables
import { map, store, filteredNeighborhoods, xy, currentMousePosition, manuallyAddFlag } from '../../composables/FormMapBox'
//functions
import { flyToNeighborhood, handleSearchButton, handelbutton,changeCursor, flyToCoordinates, handelCoordinatesInput, addOrUpdateMarker } from '../../composables/FormMapBox'
//props
  interface Props {
    center?: [number, number]
    zoom?: number
    style?: string
  }
  const props = withDefaults(defineProps<Props>(), {
    center: () => [44.4, 33.32], // Baghdad coordinates
    zoom: 11, // Closer zoom for city view
    style: 'mapbox://styles/mapbox/streets-v12'
  })
//lifecycles
  onMounted(() => {
    //initialize map
      console.log('MapBox component mounted, initializing map...')
      const accessToken = import.meta.env.VITE_MAPBOX_ACCESS_TOKEN
      if(!accessToken){console.error('MapBox access token is required. Please set VITE_MAPBOX_ACCESS_TOKEN in your .env file.');return;}
      console.log('MapBox access token found, setting up map...')
      mapboxgl.accessToken = accessToken
      //check if container exists
      const container = document.getElementById('map')
      if(!container){console.error('Map container element not found!');return;}
      console.log('Map container found, creating map instance...')
      try {
        map.value = new mapboxgl.Map({
          container: 'map',
          style: props.style,
          center: props.center,
          zoom: props.zoom
        })
        console.log('Map instance created successfully')
      
      } catch (error) {
        console.error('Error creating map instance:', error)
        return
      }
    // @ts-ignore - MapBox GL type complexity issue
    //add map controls
      //add navigation control (zoom in/out buttons)
      map.value.addControl(new mapboxgl.NavigationControl())
      //add fullscreen control
      map.value.addControl(new mapboxgl.FullscreenControl() as any)
      //add geolocate control
      map.value.addControl(
        new mapboxgl.GeolocateControl({
          positionOptions: {
            enableHighAccuracy: true
          },
          trackUserLocation: true,
          showUserHeading: true
        }) as any
    )
    //set the default cursor
      map.value.getCanvas().style.cursor = "url('assets/images/add.png') 24 48, crosshair";
    //map events
      map.value.on('load',()=>{console.log('Map loaded successfully!');})
      map.value.on('error', (e) => {console.error('MapBox error:', e);})
      map.value.on('style.load', () => {console.log('Map style loaded successfully');})
      map.value.on('mousemove', (e) => {currentMousePosition.value = e})
      map.value.on('click', (e) => {
        if (manuallyAddFlag.value) {
          const coords: [number, number] = [e.lngLat.lng, e.lngLat.lat]
          flyToCoordinates(map.value, coords)
          addOrUpdateMarker(map.value, coords)
          xy.value = `${e.lngLat.lng},${e.lngLat.lat}`
          console.log(xy.value)
        }
      })
  })
  onUnmounted(() => {if(map.value){map.value.remove();}})
  //expose map instance for parent components
  defineExpose({getMap: () => map.value})
</script>

<style scoped>
#map {
  width: 100%;
  height: 100%;
  min-height: 500px;
}

:deep(.mapboxgl-map) {
  width: 100% !important;
  height: 100% !important;
}

/* Ensure the map canvas fills the container */
:deep(.mapboxgl-canvas) {
  width: 100% !important;
  height: 100% !important;
}

/* Alternative cursor styles - you can uncomment any of these to try different approaches */

/* Method 2: CSS-only approach with emoji */
/*
:deep(.mapboxgl-canvas) {
  cursor: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 32 32'%3E%3Ctext y='24' font-size='24'%3E📍%3C/text%3E%3C/svg%3E") 16 24, auto;
}
*/

/* Method 3: Simple crosshair with custom styling */
/*
:deep(.mapboxgl-canvas) {
  cursor: crosshair;
}
*/

/* Method 4: Custom marker cursor with different colors */
/*
:deep(.mapboxgl-canvas) {
  cursor: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEyIDJDOC4xMyAyIDUgNS4xMyA1IDlDNSAxNC4yNSAxMiAyMiAxMiAyMkMxMiAyMiAxOSAxNC4yNSAxOSA5QzE5IDUuMTMgMTUuODcgMiAxMiAyWk0xMiAxMS41QzEwLjYyIDExLjUgOS41IDEwLjM4IDkuNSA5QzkuNSA3LjYyIDEwLjYyIDYuNSAxMiA2LjVDMTMuMzggNi41IDE0LjUgNy42MiAxNC41IDlDMTQuNSAxMC4zOCAxMy4zOCAxMS41IDEyIDExLjVaIiBmaWxsPSIjMDA3Q0ZGIi8+Cjwvc3ZnPgo=') 12 24, auto;
}
*/

/* MapBox popup styles - kept as deep selectors since they target MapBox-generated elements */
:deep(.mapboxgl-popup-close-button) {
  display: none;
}

:deep(.mapboxgl-popup-content) {
  font-family: 'Source Sans Pro', 'Helvetica Neue', sans-serif;
  font-size: 15px;
  line-height: 1.47;
  padding: 0;
  width: 198px;
  border-radius: 6px;
  overflow: hidden;
}

:deep(.mapboxgl-popup-content h3) {
  background: #91c949;
  color: #fff;
  margin: 0;
  padding: 10px;
  border-radius: 3px 3px 0 0;
  font-weight: 700;
  margin-top: -15px;
}

:deep(.mapboxgl-popup-content h4) {
  margin: 0;
  padding: 10px;
  font-weight: 400;
}

:deep(.mapboxgl-popup-content div) {
  padding: 10px;
}

:deep(.mapboxgl-popup-anchor-top > .mapboxgl-popup-content) {
  margin-top: 15px;
}

:deep(.mapboxgl-popup-anchor-top > .mapboxgl-popup-tip) {
  border-bottom-color: #91c949;
}

:deep(.popup-image) {
  width: 100%;
  height: 99px;
  object-fit: cover;
  display: block;
  margin: 0;
}

:deep(.popup-content h3) {
  margin: 6px 0 3px 0;
  padding: 0 6px;
  background: transparent;
  color: #333;
  font-size: 14px;
  font-weight: 700;
}

:deep(.popup-content h4) {
  margin: 0 0 6px 0;
  padding: 0 6px;
  color: #666;
  font-size: 12px;
  font-weight: 400;
}

:deep(.popup-content div) {
  padding: 0px 6px;
  font-size: 11px;
  color: #555;
  line-height: 1.3;
}

:deep(.popup-content a) {
  color: #91c949;
  text-decoration: none;
  font-weight: 600;
}

:deep(.popup-content a:hover) {
  color: #7ba83a;
  text-decoration: underline;
}

.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
