function parseCoordinates(input: string): [number, number] | null 
{
  const coords = input.trim().split(',').map(coord => coord.trim());
  if (coords.length === 2) {
    const lng = parseFloat(coords[0]);
    const lat = parseFloat(coords[1]);
    //basic validation for Baghdad area coordinates
    if (!isNaN(lng) && !isNaN(lat) && 
        lng >= 44.0 && lng <= 45.0 && 
        lat >= 33.0 && lat <= 34.0) {
      return [lng, lat];
    }else{console.log("Invalid Baghdad area coordinates");return null;}
  }
  return null;
}
function isCoordinateString(input: string): boolean 
{
  const coordRegex = /^-?\d+\.?\d*\s*,\s*-?\d+\.?\d*$/;
  return coordRegex.test(input.trim());
}
function sendMessageToIframe(message: [number, number]): void 
{
  if (window.parent) {
    window.parent.postMessage(JSON.stringify(message), import.meta.env.VITE_IFRAME_URL);
    console.log("Message sent to iframe");
  }
}
export { parseCoordinates, isCoordinateString, sendMessageToIframe }