import type { MapConfig, MapMarker, MapBounds } from './map';

//Props Types
export interface FormMapBoxProps {
  modelValue?: [number, number];  // v-model for coordinates
  markers?: MapMarker[];
  initialCenter?: [number, number];
  initialZoom?: number;
  height?: string;
  width?: string;
  draggable?: boolean;
  clickable?: boolean;
  mapConfig?: Partial<MapConfig>;
  bounds?: MapBounds;
  markerColor?: string;
  markerSize?: number;
}

//Default props
export const DEFAULT_FORM_MAPBOX_PROPS = {
  markers: () => [],
  initialCenter: () => [44.3667, 17.8133] as [number, number],  // Bosnia and Herzegovina
  initialZoom: () => 7,
  height: () => '400px',
  width: () => '100%',
  draggable: () => true,
  clickable: () => true,
  markerColor: () => '#FF0000',
  markerSize: () => 25
} as const;
